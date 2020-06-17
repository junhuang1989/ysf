<?php

require_once './vendor/autoload.php';

use Ysf\SDKConfig;
use Ysf\AcpService;

class Test {
    /**
     * 云闪付支付
     */
    public function ysfpay()
    {
        $orderInfo = array (
            'title' => '商品:白衣天使礼包',
            'number' => 'G200513145226cga2808617K5Fzd',
            'price' => 0.01,
            'description' => '商品:白衣天使礼包',
            'department' => '3',
        );



        $frontUrl = '192.168.1.80/Api/Paymentengineer/ysfjumpUrl'; //前台通知地址
        $backUrl = '192.168.1.80/Api/Paymentengineer/yunshanfu_async';
        $formHTML = $this->buildYsfForm($orderInfo, $frontUrl, $backUrl);
        die($formHTML);
    }



    /**
     * 云闪付支付表单
     */
    private function buildYsfForm($orderInfo, $frontUrl, $backUrl)
    {
        header('Content-type:text/html;charset=utf-8');

        $params = array(
            //以下信息非特殊情况不需要改动
            'version' => SDKConfig::getSDKConfig()->version,                 //版本号
            'encoding' => 'utf-8',                  //编码方式
            'txnType' => '01',                      //交易类型
            'txnSubType' => '01',                  //交易子类
            'bizType' => '000201',                  //业务类型
            'frontUrl' => $frontUrl,  //前台通知地址
            'backUrl' => $backUrl,      //后台通知地址
            'signMethod' => SDKConfig::getSDKConfig()->signMethod,                  //签名方法
            'channelType' => '08',                  //渠道类型，07-PC，08-手机
            'accessType' => '0',                  //接入类型
            'currencyCode' => '156',              //交易币种，境内商户固定156

            //TODO 以下信息需要填写
            'merId' => SDKConfig::getSDKConfig()->merId,        //商户代码，请改自己的测试商户号，此处默认取demo演示页面传递的参数
            'orderId' => $orderInfo['number'],    //商户订单号，8-32位数字字母，不能含“-”或“_”，此处默认取demo演示页面传递的参数，可以自行定制规则
            'txnTime' => date("YmdHis"),    //订单发送时间，格式为YYYYMMDDhhmmss，取北京时间，此处默认取demo演示页面传递的参数
            'txnAmt' => $orderInfo['price'] * 100,    //交易金额，单位分，此处默认取demo演示页面传递的参数

            'payTimeout' => date('YmdHis', strtotime('+15 minutes')),

            'riskRateInfo' => '{commodityName=' . $orderInfo['title'] . '}',
            'reqReserved' => $orderInfo['title'],
        );

        AcpService::sign($params);

        $uri = SDKConfig::getSDKConfig()->frontTransUrl;

        $html_form = AcpService::createAutoFormHtml($params, $uri);
        return $html_form;
    }
}

$t = new Test();
$t->ysfpay();