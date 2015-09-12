<?php
/**
 * L_Util_UrlHandler 
 * url处理逻辑: 供工商数据处理等使用
 * @copyright Copyright (c) 2015 Baidu.com, Inc. All Rights Reserved
 * @author 曲福<qufu@baidu.com> 
 */
class L_Util_UrlHandler {
    // 操作失败,返回'-'
    const CONST_HANDLE_FAILED = '-';
   
    // 包含www
    const CONST_WITH_WWW = 'www';

    // 包含http://
    const CONST_WITH_HTTP = 'http://';

    // 包含http://www.
    const CONST_WITH_HTTP_AND_WWW = 'http://www.';

    // 获取相应ip注册地域信息的新浪api的url
    const CONST_SINAAPI_URL = 'http://int.dpool.sina.com.cn/iplookup/iplookup.php';
    
    // 检查是否URL的正则
    const PATTERN_ISURL = "/^(?:https?:\\/\\/)[\\w-]++(?:\\.[\\w-]++)++.*\$/i";
    
    // 检查是否domain的正则
    const PATTERN_ISDOMAIN = '/^([\\w|\-|\\.]*\\.(com.(cn|hk)|ac|ah|asia|biz|bj|cc|cn|com|co|cq|fj|gd|gov|gs|gx|gz|ha|hb|he|hi|hk|hl|hn|host|info|jl|js|jx|la|ln|me|mo|mobi|name|net|nm|nx|org|press|pw|qh|ren|sc|sd|sh|sn|sx|tel|tj|tm|top|tv|tw|us|wang|website|xj|xyz|xz|yn|zj))/i';
    
    // 检查字段是否为www|com顶级域名等
    const PATTERN_ISTOPDOMAIN = "/^(www|com|cn|hk|ac|ah|asia|biz|bj|cc|cn|com|co|cq|fj|gd|gov|gs|gx|gz|ha|hb|he|hi|hk|hl|hn|host|info|jl|js|jx|la|ln|me|mo|mobi|name|net|nm|nx|org|press|pw|qh|ren|sc|sd|sh|sn|sx|tel|tj|tm|top|tv|tw|us|wang|website|xj|xyz|xz|yn|zj)\$/i";
    
    /**
     * 检查输入是否是url
     * @param string $strUrl 
     * @return integer  是否url(对应1,0)
     */
    public static function isUrl($strUrl) {
        if (empty($strUrl)) {
            return self::CONST_HANDLE_FAILED;
        }
        return preg_match(self::PATTERN_ISURL, $strUrl);
    }
    
    /**
     * 对输入进行格式归一化成url格式 
     * @param string $strUrl
     * @return string 处理过的url,无法处理则'-'
     */
    public static function urlNormalization($strUrl) {
        if (empty($strUrl)) {
            return self::CONST_HANDLE_FAILED;
        }
        if (self::isUrl($strUrl)) {
            return $strUrl;
        }
        if (self::CONST_WITH_WWW == substr($strUrl, 0, 3)) {
            $strRet = self::CONST_WITH_HTTP . $strUrl;
        } else {
            $strRet = self::CONST_WITH_HTTP_AND_WWW . $strUrl;
        }
        return self::isUrl($strRet) ? $strRet : self::CONST_HANDLE_FAILED;
    }
    
    /**
     * 根据输入url获取ip信息
     * @param string $strUrl
     * @return string ip信息,无法获取则'-'
     */
    public static function getIpByUrl($strUrl) {
        if (empty($strUrl)) {
            return self::CONST_HANDLE_FAILED;
        }
        $strUrl = self::urlNormalization($strUrl);
        if ($strUrl == self::CONST_HANDLE_FAILED) {
            return self::CONST_HANDLE_FAILED;
        }
        $arrUrl = parse_url($strUrl);
        $strIp = gethostbyname($arrUrl['host']);
        if ($strIp != $arrUrl['host']) {
            $strRet = $strIp;
        }
        return $strRet ? $strRet : self::CONST_HANDLE_FAILED;
    }
    
    /**
     * 根据输入url获取ip的地域信息
     * @param string $strUrl
     * @return string ip的地域信息,无法获取则返回"-\t-\t-"
     */
    public static function getAddrByUrl($strUrl) {
        if (empty($strUrl)) {
            return self::CONST_HANDLE_FAILED;
        }
        $strIp = self::getIpByUrl($strUrl);
        if ($strIp != self::CONST_HANDLE_FAILED) {
            $jsonResult = L_Http::get(self::CONST_SINAAPI_URL, array('format' => 'json', 'ip' => $strIp));
            $arrResult = json_decode($jsonResult, true);
        }
        $arrRet['country'] = $arrResult['country'] ? $arrResult['country'] : self::CONST_HANDLE_FAILED;
        $arrRet['province'] = $arrResult['province'] ? $arrResult['province'] : self::CONST_HANDLE_FAILED;
        $arrRet['city'] = $arrResult['city'] ? $arrResult['city'] : self::CONST_HANDLE_FAILED;
        return $arrRet['country'] . "\t" . $arrRet['province'] . "\t" . $arrRet['city'];
    }
    
    /**
     * 根据输入url获取域名
     * @param string $strUrl
     * @return string 域名信息,无法获取则'-'
     */
    public static function getDomainByUrl($strUrl) {
        if (empty($strUrl)) {
            return self::CONST_HANDLE_FAILED;
        }
        if (self::isUrl($strUrl)) {
            $arrUrl = parse_url($strUrl);
            $strRet = $arrUrl['host'];
            return $strRet ? $strRet : self::CONST_HANDLE_FAILED;
        } 
        if (preg_match(self::PATTERN_ISDOMAIN, $strUrl, $arrOutMatch)) {
            $strRet = $arrOutMatch[1];
        }
        return $strRet ? $strRet : self::CONST_HANDLE_FAILED;
    }
    
    /**
     * 判断输入url的域名等级
     * @param string $strUrl
     * @return integer 域名等级数,例如：
     *              -  识别失败
     *              1  一级域名，如：www.baidu.com
     *              2  二级域名，如：tieba.baidu.com
     *              ......
     */
    public static function getDomainLevelByUrl($strUrl) {
        if (empty($strUrl)) {
            return self::CONST_HANDLE_FAILED;
        }
        $strDomain = self::getDomainByUrl($strUrl);
        $strRet = 0;
        if (self::CONST_HANDLE_FAILED == $strDomain) {
            return self::CONST_HANDLE_FAILED;
        }
        $arrDomain = preg_split('/\\./', $strDomain);
        foreach ($arrDomain as $item) {
            if (!preg_match(self::PATTERN_ISTOPDOMAIN, $item)) {
                $strRet++;
            }
        }
        return $strRet;
    }
}

