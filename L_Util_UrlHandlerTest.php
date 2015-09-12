<?php
/**
 * @file: L_Util_UrlHandlerTest
 * @author: qufu
 * @date: 2015/08/17
 */
class L_Util_UrlHandlerTest extends DefensorTestCase {
    /**
     * @test
     * @param void
     * @return void
     */
    public function testIsUrl1() {
        printf("\n 方法：%-20s 场景：%s\n", __FUNCTION__, '判断是否url：是');
        $data = 'http://www.baidu.com';
        $testData = 1;
        $data = L_Util_UrlHandler::isUrl($data);
        $this->assertEquals($data, $testData);
    }
    
    /**
     * @test
     * @param void
     * @return void
     */
    public function testIsUrl2() {
        printf("\n 方法：%-20s 场景：%s\n", __FUNCTION__, '判断是否url：是');
        $data = 'https://www.baidu.com';
        $testData = 1;
        $data = L_Util_UrlHandler::isUrl($data);
        $this->assertEquals($data, $testData);
    }
    
    /**
     * @test
     * @param void
     * @return void
     */
    public function testIsUrl3() {
        printf("\n 方法：%-20s 场景：%s\n", __FUNCTION__, '判断是否url：否');
        $data = 'www.baidu.com';
        $testData = 0;
        $data = L_Util_UrlHandler::isUrl($data);
        $this->assertEquals($data, $testData);
    }
    
    /**
     * @test
     * @param void
     * @return void
     */
    public function testIsUrl4() {
        printf("\n 方法：%-20s 场景：%s\n", __FUNCTION__, '判断是否url：否');
        $data = 'baidu.com';
        $testData = 0;
        $data = L_Util_UrlHandler::isUrl($data);
        $this->assertEquals($data, $testData);
    }
    
    /**
     * @test
     * @param void
     * @return void
     */
    public function testIsUrl5() {
        printf("\n 方法：%-20s 场景：%s\n", __FUNCTION__, '判断是否url：否');
        $data = 'com';
        $testData = 0;
        $data = L_Util_UrlHandler::isUrl($data);
        $this->assertEquals($data, $testData);
    }
    
    /**
     * @test
     * @param void
     * @return void
     */
    public function testIsUrl6() {
        printf("\n 方法：%-20s 场景：%s\n", __FUNCTION__, '判断是否url：否');
        $data = 'http://.com';
        $testData = 0;
        $data = L_Util_UrlHandler::isUrl($data);
        $this->assertEquals($data, $testData);
    }
    
    /**
     * @test
     * @param void
     * @return void
     */
    public function testIsUrl7() {
        printf("\n 方法：%-20s 场景：%s\n", __FUNCTION__, '判断是否url：否');
        $data = '';
        $testData = '-';
        $data = L_Util_UrlHandler::isUrl($data);
        $this->assertEquals($data, $testData);
    }
    
    /**
     * @test
     * @param void
     * @return void
     */
    public function testUrlNormalizaton1() {
        printf("\n 方法：%-20s 场景：%s\n", __FUNCTION__, 'url归一化：成功');
        $data = 'http://www.baidu.com';
        $testData = 'http://www.baidu.com';
        $data = L_Util_UrlHandler::urlNormalization($data);
        $this->assertEquals($data, $testData);
    }
    
    /**
     * @test
     * @param void
     * @return void
     */
    public function testUrlNormalizaton2() {
        printf("\n 方法：%-20s 场景：%s\n", __FUNCTION__, 'url归一化：成功');
        $data = 'www.baidu.com';
        $testData = 'http://www.baidu.com';
        $data = L_Util_UrlHandler::urlNormalization($data);
        $this->assertEquals($data, $testData);
    }
    
    /**
     * @test
     * @param void
     * @return void
     */
    public function testUrlNormalizaton3() {
        printf("\n 方法：%-20s 场景：%s\n", __FUNCTION__, 'url归一化：成功');
        $data = 'baidu.com';
        $testData = 'http://www.baidu.com';
        $data = L_Util_UrlHandler::urlNormalization($data);
        $this->assertEquals($data, $testData);
    }
    
    /**
     * @test
     * @param void
     * @return void
     */
    public function testUrlNormalizaton4() {
        printf("\n 方法：%-20s 场景：%s\n", __FUNCTION__, 'url归一化：失败');
        $data = '.com';
        $testData = '-';
        $data = L_Util_UrlHandler::urlNormalization($data);
        $this->assertEquals($data, $testData);
    }
    
    /**
     * @test
     * @param void
     * @return void
     */
    public function testUrlNormalizaton5() {
        printf("\n 方法：%-20s 场景：%s\n", __FUNCTION__, 'url归一化：失败');
        $data = '';
        $testData = '-';
        $data = L_Util_UrlHandler::urlNormalization($data);
        $this->assertEquals($data, $testData);
    }
    
    /**
     * @test
     * @param void
     * @return void
     */
    public function testGetIpByUrl1() {
        printf("\n 方法：%-20s 场景：%s\n", __FUNCTION__, '获取ip信息：成功');
        $data = 'www.hd315.gov.cn';
        $testData = '211.101.228.185';
        $data = L_Util_UrlHandler::getIpByUrl($data);
        $this->assertEquals($data, $testData);
    }
    
    /**
     * @test
     * @param void
     * @return void
     */
    public function testGetIpByUrl2() {
        printf("\n 方法：%-20s 场景：%s\n", __FUNCTION__, '获取ip信息：成功');
        $data = 'hd315.gov.cn';
        $testData = '211.101.228.185';
        $data = L_Util_UrlHandler::getIpByUrl($data);
        $this->assertEquals($data, $testData);
    }
    
    /**
     * @test
     * @param void
     * @return void
     */
    public function testGetIpByUrl3() {
        printf("\n 方法：%-20s 场景：%s\n", __FUNCTION__, '获取ip信息：失败');
        $data = '.com';
        $testData = '-';
        $data = L_Util_UrlHandler::getIpByUrl($data);
        $this->assertEquals($data, $testData);
    }
    
    /**
     * @test
     * @param void
     * @return void
     */
    public function testGetIpByUrl4() {
        printf("\n 方法：%-20s 场景：%s\n", __FUNCTION__, '获取ip信息：失败');
        $data = '';
        $testData = '-';
        $data = L_Util_UrlHandler::getIpByUrl($data);
        $this->assertEquals($data, $testData);
    }
    
    /**
     * @test
     * @param void
     * @return void
     */
    public function testGetAddrByUrl1() {
        printf("\n 方法：%-20s 场景：%s\n", __FUNCTION__, '获取ip地域信息：成功');
        $data = 'hd315.gov.cn';
        $testData = "中国\t北京\t北京";
        $data = L_Util_UrlHandler::getAddrByUrl($data);
        $this->assertEquals($data, $testData);
    }
    
    /**
     * @test
     * @param void
     * @return void
     */
    public function testGetAddrByUrl2() {
        printf("\n 方法：%-20s 场景：%s\n", __FUNCTION__, '获取ip地域信息：失败');
        $data = '.com';
        $testData = "-\t-\t-";
        $data = L_Util_UrlHandler::getAddrByUrl($data);
        $this->assertEquals($data, $testData);
    }
    
    /**
     * @test
     * @param void
     * @return void
     */
    public function testGetAddrByUrl3() {
        printf("\n 方法：%-20s 场景：%s\n", __FUNCTION__, '获取ip地域信息：失败');
        $data = '';
        $testData = "-";
        $data = L_Util_UrlHandler::getAddrByUrl($data);
        $this->assertEquals($data, $testData);
    }
    
    /**
     * @test
     * @param void
     * @return void
     */
    public function testGetDomainByUrl1() {
        printf("\n 方法：%-20s 场景：%s\n", __FUNCTION__, '获取域名信息：成功');
        $data = 'http://www.hd315.gov.cn';
        $testData = 'www.hd315.gov.cn';
        $data = L_Util_UrlHandler::getDomainByUrl($data);
        $this->assertEquals($data, $testData);
    }
    
    /**
     * @test
     * @param void
     * @return void
     */
    public function testGetDomainByUrl2() {
        printf("\n 方法：%-20s 场景：%s\n", __FUNCTION__, '获取域名信息：成功');
        $data = 'www.hd315.gov.cn/hello';
        $testData = 'www.hd315.gov.cn';
        $data = L_Util_UrlHandler::getDomainByUrl($data);
        $this->assertEquals($data, $testData);
    }
    
    /**
     * @test
     * @param void
     * @return void
     */
    public function testGetDomainByUrl3() {
        printf("\n 方法：%-20s 场景：%s\n", __FUNCTION__, '获取域名信息：失败');
        $data = '.huoxing/hello';
        $testData = '-';
        $data = L_Util_UrlHandler::getDomainByUrl($data);
        $this->assertEquals($data, $testData);
    }
    
    /**
     * @test
     * @param void
     * @return void
     */
    public function testGetDomainByUrl4() {
        printf("\n 方法：%-20s 场景：%s\n", __FUNCTION__, '获取域名信息：失败');
        $data = '';
        $testData = '-';
        $data = L_Util_UrlHandler::getDomainByUrl($data);
        $this->assertEquals($data, $testData);
    }
    
    /**
     * @test
     * @param void
     * @return void
     */
    public function testGetDomainLevelByUrl1() {
        printf("\n 方法：%-20s 场景：%s\n", __FUNCTION__, '获取域名信息：成功');
        $data = 'http://www.baidu.com';
        $testData = 1;
        $data = L_Util_UrlHandler::getDomainLevelByUrl($data);
        $this->assertEquals($data, $testData);
    }
    
    /**
     * @test
     * @param void
     * @return void
     */
    public function testGetDomainLevelByUrl2() {
        printf("\n 方法：%-20s 场景：%s\n", __FUNCTION__, '获取域名信息：成功');
        $data = 'http://tieba.baidu.com';
        $testData = 2;
        $data = L_Util_UrlHandler::getDomainLevelByUrl($data);
        $this->assertEquals($data, $testData);
    }
    
    /**
     * @test
     * @param void
     * @return void
     */
    public function testGetDomainLevelByUrl3() {
        printf("\n 方法：%-20s 场景：%s\n", __FUNCTION__, '获取域名信息：失败');
        $data = 'http://www.com.cn';
        $testData = 0;
        $data = L_Util_UrlHandler::getDomainLevelByUrl($data);
        $this->assertEquals($data, $testData);
    }
    
    /**
     * @test
     * @param void
     * @return void
     */
    public function testGetDomainLevelByUrl4() {
        printf("\n 方法：%-20s 场景：%s\n", __FUNCTION__, '获取域名信息：失败');
        $data = '.huoxing/hello';
        $testData = '-';
        $data = L_Util_UrlHandler::getDomainLevelByUrl($data);
        $this->assertEquals($data, $testData);
    }
    
    /**
     * @test
     * @param void
     * @return void
     */
    public function testGetDomainLevelByUrl5() {
        printf("\n 方法：%-20s 场景：%s\n", __FUNCTION__, '获取域名信息：失败');
        $data = '';
        $testData = '-';
        $data = L_Util_UrlHandler::getDomainLevelByUrl($data);
        $this->assertEquals($data, $testData);
    }
}

