<?php

namespace tencent;

require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

use TencentCloud\Common\Credential;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Profile\HttpProfile;
use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentCloud\Dnspod\V20210323\DnspodClient;
use TencentCloud\Dnspod\V20210323\Models\DescribeUserDetailRequest;

//查询账户信息（检查连接状态）
function checkServerStatus($id, $key)
{
    try {
        // 实例化一个认证对象，入参需要传入腾讯云账户 SecretId 和 SecretKey，此处还需注意密钥对的保密
        // 代码泄露可能会导致 SecretId 和 SecretKey 泄露，并威胁账号下所有资源的安全性。以下代码示例仅供参考，建议采用更安全的方式来使用密钥，请参见：https://cloud.tencent.com/document/product/1278/85305
        // 密钥可前往官网控制台 https://console.cloud.tencent.com/cam/capi 进行获取
        $cred = new Credential($id, $key);
        // 实例化一个http选项，可选的，没有特殊需求可以跳过
        $httpProfile = new HttpProfile();
        $httpProfile->setEndpoint("dnspod.tencentcloudapi.com");

        // 实例化一个client选项，可选的，没有特殊需求可以跳过
        $clientProfile = new ClientProfile();
        $clientProfile->setHttpProfile($httpProfile);
        // 实例化要请求产品的client对象,clientProfile是可选的
        $client = new DnspodClient($cred, "", $clientProfile);

        // 实例化一个请求对象,每个接口都会对应一个request对象
        $req = new DescribeUserDetailRequest();

        $params = array();
        $req->fromJsonString(json_encode($params));

        // 返回的resp是一个DescribeUserDetailResponse的实例，与请求对象对应
        $resp = $client->DescribeUserDetail($req);

        // 输出json格式的字符串回包
        return json_decode($resp->toJsonString());
    } catch (TencentCloudSDKException $e) {
        return array("status"=>"server_error","message"=>$e->getMessage());
    }
}
