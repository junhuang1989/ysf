<?php

require_once './vendor/autoload.php';

use Ysf\SDKConfig;
use Ysf\AcpService;

class Test {
    /**
     * ������֧��
     */
    public function ysfpay()
    {
        $orderInfo = array (
            'title' => '��Ʒ:������ʹ���',
            'number' => 'G200513145226cga2808617K5Fzd',
            'price' => 0.01,
            'description' => '��Ʒ:������ʹ���',
            'department' => '3',
        );



        $frontUrl = '192.168.1.80/Api/Paymentengineer/ysfjumpUrl'; //ǰ̨֪ͨ��ַ
        $backUrl = '192.168.1.80/Api/Paymentengineer/yunshanfu_async';
        $formHTML = $this->buildYsfForm($orderInfo, $frontUrl, $backUrl);
        die($formHTML);
    }



    /**
     * ������֧����
     */
    private function buildYsfForm($orderInfo, $frontUrl, $backUrl)
    {
        header('Content-type:text/html;charset=utf-8');

        $params = array(
            //������Ϣ�������������Ҫ�Ķ�
            'version' => SDKConfig::getSDKConfig()->version,                 //�汾��
            'encoding' => 'utf-8',                  //���뷽ʽ
            'txnType' => '01',                      //��������
            'txnSubType' => '01',                  //��������
            'bizType' => '000201',                  //ҵ������
            'frontUrl' => $frontUrl,  //ǰ̨֪ͨ��ַ
            'backUrl' => $backUrl,      //��̨֪ͨ��ַ
            'signMethod' => SDKConfig::getSDKConfig()->signMethod,                  //ǩ������
            'channelType' => '08',                  //�������ͣ�07-PC��08-�ֻ�
            'accessType' => '0',                  //��������
            'currencyCode' => '156',              //���ױ��֣������̻��̶�156

            //TODO ������Ϣ��Ҫ��д
            'merId' => SDKConfig::getSDKConfig()->merId,        //�̻����룬����Լ��Ĳ����̻��ţ��˴�Ĭ��ȡdemo��ʾҳ�洫�ݵĲ���
            'orderId' => $orderInfo['number'],    //�̻������ţ�8-32λ������ĸ�����ܺ���-����_�����˴�Ĭ��ȡdemo��ʾҳ�洫�ݵĲ������������ж��ƹ���
            'txnTime' => date("YmdHis"),    //��������ʱ�䣬��ʽΪYYYYMMDDhhmmss��ȡ����ʱ�䣬�˴�Ĭ��ȡdemo��ʾҳ�洫�ݵĲ���
            'txnAmt' => $orderInfo['price'] * 100,    //���׽���λ�֣��˴�Ĭ��ȡdemo��ʾҳ�洫�ݵĲ���

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