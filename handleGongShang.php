<?php
/**
 * @file: 工商数据处理脚本
 * @author：qufu@baidu.com
 * @date: 2015/08/10
 *
 */
// 启动ap，以自动加载L_Util_UserHandler类
require_once dirname(__FILE__) . '/../../start.php';

/**
 * GongShangHandler
 * 工商数据使用到的处理逻辑类
 */
class GongShangHandler {
    // 处理失败，返回'-'
    const CONST_HANDLE_FAILED = '-';
    
    // 识别主体地域信息的目标城市
    const CONST_IS_TARGET_CITY = '北京市';
    
    // 识别主题地域信息的其他城市
    const CONST_ISNOT_TARGET_CITY = '外埠';
    
    // 切割copyRight的正则
    const PATTERN_SPLIT_COPYRIGHT = '/[ ,|]/';
    
    // 切割strTagTitle的正则
    const PATTERN_SPLIT_TAGTITLE = '/[: _,\\-\\[\\]\\|]/';
    
    // 检查是否为括号包含数据的正则
    const PATTERN_IS_INBRACKET = '/(?:\\()(.*)(?:\\))/';
    
    // 检查是否包含网站名的正则
    const PATTERN_IS_WEBSITENAME = "/(公司|集团|平台|中心|网城|网|营|班|部|店|医院|教育)\$/";
    
    // 检查是否为无效网站名的正则
    const PATTERN_ISNOT_WEBSITENAME = '/(阻断页|首页|建设中|网站访问报错|ICONST_IS7|续费|404|Home)/';
    
    // 检查是否包含备案信息的正则
    const PATTERN_COPYRIGHT = '/京网文|京ICP|京icp|新出网证|京公网|京公安|京卫网|资格证|许可证|经营性网站备案信息|网络110报警服务|12321垃圾信息举报中心|网络文化经营单位|协会|认证|举报中心|安全联盟|诚信联盟|文网文|辟谣平台|公约|专利|资格|机构|诚信网站|可信网站|信用|网络警察|网警|商务部/';
    
    // 判断主体地域逻辑用到的城市列表
    public static $arrCity = array();
    
    // 直辖市
    public static $arrMunicipalities = array(
        '北京市',
        '上海市',
        '重庆市',
        '天津市',
    );
    
    /**
     * 从TagTitle字段提取网站名字
     * @param string $strTagTitle 原始数据
     * @return string 提取出的网站名字，无法提取则返回'-'
     */
    public static function getWebSiteNameByTagTitle($strTagTitle) {
        if (empty($strTagTitle)) {
            return self::CONST_HANDLE_FAILED;
        }
        $strTmpTagTitle = explode('Title:', trim($strTagTitle));
        $strTagTitle = $strTmpTagTitle[1];
        $arrDetail = preg_split(self::PATTERN_SPLIT_TAGTITLE, $strTagTitle);
        if (1 == count($arrDetail)) {
            return $arrDetail[0];
        }
        if (preg_match(self::PATTERN_IS_WEBSITENAME, $arrDetail[0])) {
            $strRet = $arrDetail[0];
        }
        if (preg_match(self::PATTERN_IS_WEBSITENAME, $arrDetail[count($arrDetail) - 1])) {
            $strRet = $arrDetail[count($arrDetail) - 1];
        }
        if (!$strRet || preg_match(self::PATTERN_ISNOT_WEBSITENAME, $strRet)) {
            $strRet = self::CONST_HANDLE_FAILED;
        }
        return $strRet;
    }
    
    /**
     * 从CopyrightText字段获取资质信息
     * @param string $strCopyRight 原始数据
     * @return string 提取的资质信息，无法提取则返回'-'
     */
    public static function getLicenseByCopyRight($strCopyRight) {
        if (empty($strCopyRight)) {
            return self::CONST_HANDLE_FAILED;
        }
        $strTmpCopyright = explode('Text:', trim($strCopyRight));
        $strCopyRight = $strTmpCopyright[1];
        $arrDetail = preg_split(self::PATTERN_SPLIT_COPYRIGHT, $strCopyRight);
        $intFlag = 0;
        $intCount = count($arrDetail);
        for ($i = 0; $i < $intCount; $i++) {
            if (preg_match(self::PATTERN_COPYRIGHT, $arrDetail[$i])) {
                $intFlag = 1;
                $strRet = $arrDetail[$i] . ';';
            }
        }
        return (0 == $intFlag) ? self::CONST_HANDLE_FAILED : $strRet;
    }
    
    /**
     * 获取cityArr
     * @return array 各省的城市信息
     */
    public static function getCityArr() {
        if (self::$arrCity) {
            return self::$arrCity;
        } 
        AP_Loader::import(PHPLIB_PATH . '/GongShang/cityArr.php');
        foreach ($countryArr as $province) {
            $pName = $province['province_name'];
            if (!is_array($province['city'])) {
                continue;
            }
            foreach ($province['city'] as $city) {
                if (in_array($pName, self::$arrMunicipalities)) {
                    self::$arrCity[$pName][0] = $pName;
                    continue;
                }
                self::$arrCity[$pName][] = $city['city_name'];
            }
        }
        return self::$arrCity;
    }
    
    /**
     * 从主体名字获取地域信息
     * @param string $strZhuTi
     * @return string 获取地域信息，'北京'or'外埠'，无法获取则返回'-'
     */
    public static function getDiYuByZhuTi($strZhuTi) {
        if (empty($strZhuTi)) {
            return self::CONST_HANDLE_FAILED;
        }
        // 获取城市列表
        $arrCity = self::getCityArr();
        
        // 取包含地域信息的字符，包括主题字符串头两个字和()里的字
        $strAddr = mb_substr($strZhuTi, 0, 2, 'utf-8');
        preg_match_all(self::PATTERN_IS_INBRACKET, $strZhuTi, $arrResult);
        $strAddr2 = $arrResult[1][0];
        
        foreach ($arrCity as $province => $citys) {
            if (strstr($province, $strAddr) || '' != $strAddr2 && strstr($province, $strAddr2)) {
                return (self::CONST_IS_TARGET_CITY == $province) ? self::CONST_IS_TARGET_CITY : self::CONST_ISNOT_TARGET_CITY;
            }
            foreach ($citys as $city) {
                if (strstr($city, $strAddr) || '' != $strAddr2 && strstr($city, $strAddr2)) {
                    return (self::CONST_IS_TARGET_CITY == $province) ? self::CONST_IS_TARGET_CITY : self::CONST_ISNOT_TARGET_CITY;
                }
            }
        }
        return self::CONST_HANDLE_FAILED;
    }
}

// 脚本接收到用户不合法输入时显示的提示
function usage() {
    echo "*********************************************************************************************************\n";
    echo "usage : php handleGongShang.php -F filename [ other actions]\n";
    echo "example: \n";
    echo "php handleGongShang.php -F fname -i 13 -t 15 //get first colomn of website from tagtitle on 15th colomn of fname;\n";
    echo "                                     //get second colomn of ip from url on 13rd colomn of fname;\n";
    echo "                                     //get other colomns from the same colomns of fname.\n";
    echo "actions:\n";
    echo "    -n get the name of website from tagtitle\n";
    echo "    -l get the license information from copyright\n";
    echo "    -L get the level of domain from url\n";
    echo "    -i get the ip from url\n";
    echo "    -a get the address from url\n";
    echo "    -d get the domain from url\n";
    echo "    -D get the diyu from zhuti\n";
    echo "*********************************************************************************************************\n";
}

// 处理用户参数,如有不合法，提示用户
$arrOptions = getopt('F:n:l:L:i:a:d:D:');
if (!$arrOptions['F'] || 2 > count($arrOptions)) {
    usage();
    exit;
}

// 读入文件，如果文件不存在或打不开，提示用户
if (!file_exists($arrOptions['F'])) {
    exit("\nfile not exist\n");
}
$fp = fopen($arrOptions['F'], 'r');
if (!$fp) {
    exit("\ncant open target file\n");
}

// 用户输入参数与被调用类的关系
$arrClass = array(
    'n' => 'GongShangHandler',
    'l' => 'GongShangHandler',
    'L' => 'L_Util_UrlHandler',
    'i' => 'L_Util_UrlHandler',
    'a' => 'L_Util_UrlHandler',
    'd' => 'L_Util_UrlHandler',
    'D' => 'GongShangHandler',
);

// 用户输入参数与被调用函数关系
$arrFunction = array(
    'n' => 'getWebSiteNameByTagTitle',
    'l' => 'getLicenseByCopyRight',
    'L' => 'getDomainLevelByUrl',
    'i' => 'getIpByUrl',
    'a' => 'getAddrByUrl',
    'd' => 'getDomainByUrl',
    'D' => 'getDiYuByZhuTi',
);

// 开始对文件中各行数据进行循环处理
while (!feof($fp)) {
    // 获取数据中各字段
    $strLine = trim(fgets($fp));
    if (empty($strLine)) {
        continue;
    }
    $arrLine = explode("\t", $strLine);
    
    // 根据用户参数依次调用相关类中封装的处理逻辑
    $strRet = '';
    foreach ($arrOptions as $key => $value) {
        // 用户参数F，为指定文件名，不需调用处理逻辑
        if ('F' == $key) {
            continue;
        }
        
        // 用户参数D，为判断主体地域，要清洗数据
        $data = ('D' != $key) ? $arrLine[$value - 1] : L_Util_Query::normalizationData($arrLine[$value - 1]);
        
        // 根据用户参数，调用对应类的相应处理逻辑
        $strRet = call_user_func(array($arrClass[$key], $arrFunction[$key]), $data) . "\t" . $strRet;
    }
    echo $strRet . $strLine . "\n";
}

// 关闭文件
fclose($fp);

