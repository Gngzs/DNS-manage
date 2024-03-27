<?php

// This file is auto-generated, don't edit it. Thanks.
namespace ali;

require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

use AlibabaCloud\SDK\Alidns\V20150109\Alidns;
use AlibabaCloud\Tea\Console\Console;
use AlibabaCloud\Tea\Tea;
use AlibabaCloud\Tea\Utils\Utils;
use \Exception;
use AlibabaCloud\Tea\Exception\TeaError;
use AlibabaCloud\Darabonba\Env\Env;

use Darabonba\OpenApi\Models\Config;
use AlibabaCloud\SDK\Alidns\V20150109\Models\DescribeDomainsRequest;
use AlibabaCloud\SDK\Alidns\V20150109\Models\AddDomainRequest;
use AlibabaCloud\SDK\Alidns\V20150109\Models\DescribeDomainRecordsRequest;
use AlibabaCloud\SDK\Alidns\V20150109\Models\DescribeRecordLogsRequest;
use AlibabaCloud\SDK\Alidns\V20150109\Models\DescribeDomainRecordInfoRequest;
use AlibabaCloud\SDK\Alidns\V20150109\Models\DescribeDomainInfoRequest;
use AlibabaCloud\SDK\Alidns\V20150109\Models\AddDomainRecordRequest;
use AlibabaCloud\SDK\Alidns\V20150109\Models\UpdateDomainRecordRequest;
use AlibabaCloud\SDK\Alidns\V20150109\Models\SetDomainRecordStatusRequest;
use AlibabaCloud\SDK\Alidns\V20150109\Models\DeleteDomainRecordRequest;
use AlibabaCloud\SDK\Alidns\V20150109\Models\DescribeDomainGroupsRequest;
use AlibabaCloud\SDK\Alidns\V20150109\Models\AddDomainGroupRequest;
use AlibabaCloud\SDK\Alidns\V20150109\Models\UpdateDomainGroupRequest;
use AlibabaCloud\SDK\Alidns\V20150109\Models\DeleteDomainGroupRequest;


use AlibabaCloud\Tea\Utils\Utils\RuntimeOptions;

/**
 * Author: 东南dnf
 * Powered by Aliyun Darabonba Language :)
 */
class api
{

    /**
     * Init 初始化客户端
     * @param string $accessKeyId
     * @param string $accessKeySecret
     * @param string $regionId
     * @return Alidns Client
     */
    public static function Init($accessKeyId, $accessKeySecret, $endpoint="dns.aliyuncs.com")
    {
        $config = new Config([]);
        // 传AccessKey ID入config
        $config->accessKeyId = $accessKeyId;
        // 传AccessKey Secret入config
        $config->accessKeySecret = $accessKeySecret;
        $config->endpoint = $endpoint;
        return new Alidns($config);
    }

    /**
     * DescribeDomains  查询账户下域名
     * @param Alidns $client      客户端
     * @return void
     */
    public static function DescribeDomains($client)
    {
        $req = new DescribeDomainsRequest([]);
        $runtime = new RuntimeOptions([]);
        try {
            $resp = $client->describeDomainsWithOptions($req,$runtime);
            return json_decode(Utils::toJSONString(Tea::merge($resp)));
        } catch (Exception $error) {
            if (!($error instanceof TeaError)) {
                $error = new TeaError([], $error->getMessage(), $error->getCode(), $error);
            }

            return array("status"=>"server_error" ,"message"=>$error->message);
        }
    }

    /**
     * AddDomain  阿里云云解析添加域名
     * @param Alidns $client      客户端
     * @param string $domainName  域名名称
     * @return void
     */
    public static function AddDomain($client, $domainName)
    {
        $req = new AddDomainRequest([]);
        $req->domainName = $domainName;
        try {
            $resp = $client->addDomain($req);
            return Utils::toJSONString(Tea::merge($resp));
        } catch (Exception $error) {
            if (!($error instanceof TeaError)) {
                $error = new TeaError([], $error->getMessage(), $error->getCode(), $error);
            }
            return $error->message;
        }
    }

    /**
     * DescribeDomainRecords 查询域名解析记录
     * @param Alidns $client          客户端 
     * @param string $domainName      域名名称
     * @return void
     */
    public static function DescribeDomainRecords($client, $domainName)
    {
        $req = new DescribeDomainRecordsRequest([]);
        $req->domainName = $domainName;
        try {
            $resp = $client->describeDomainRecords($req);
            return Utils::toJSONString(Tea::merge($resp));
        } catch (Exception $error) {
            if (!($error instanceof TeaError)) {
                $error = new TeaError([], $error->getMessage(), $error->getCode(), $error);
            }
            return $error->message;
        }
    }

    /**
     * DescribeRecordLogs  查询域名解析记录日志
     * @param Alidns $client         客户端
     * @param string $domainName     域名名称
     * @return void
     */
    public static function DescribeRecordLogs($client, $domainName)
    {
        $req = new DescribeRecordLogsRequest([]);
        $req->domainName = $domainName;
        try {
            $resp = $client->describeRecordLogs($req);
            return Utils::toJSONString(Tea::merge($resp));
        } catch (Exception $error) {
            return $error;
            if (!($error instanceof TeaError)) {
                $error = new TeaError([], $error->getMessage(), $error->getCode(), $error);
            }
            //return $error;
        }
    }

    /**
     * DescribeDomainRecordByRecordId 查询域名解析记录信息
     * @param Alidns $client         客户端
     * @param string $recordId
     * @return void
     */
    public static function DescribeDomainRecordByRecordId($client, $recordId)
    {
        $req = new DescribeDomainRecordInfoRequest([]);
        $req->recordId = $recordId;
        try {
            $resp = $client->describeDomainRecordInfo($req);
            return Utils::toJSONString(Tea::merge($resp));
        } catch (Exception $error) {
            if (!($error instanceof TeaError)) {
                $error = new TeaError([], $error->getMessage(), $error->getCode(), $error);
            }
            return $error->message;
        }
    }

    /**
     * DescribeDomainInfo  查询域名信息
     * @param Alidns $client         客户端
     * @param string $domainName     域名名称
     * @return void
     */
    public static function DescribeDomainInfo($client, $domainName)
    {
        $req = new DescribeDomainInfoRequest([]);
        $req->domainName = $domainName;
        try {
            $resp = $client->describeDomainInfo($req);
            return Utils::toJSONString(Tea::merge($resp));
        } catch (Exception $error) {
            if (!($error instanceof TeaError)) {
                $error = new TeaError([], $error->getMessage(), $error->getCode(), $error);
            }
            return $error->message;
        }
    }

    /**
     * AddDomainRecord  添加域名解析记录
     * @param Alidns $client            客户端
     * @param string $domainName        域名名称
     * @param string $RR                主机记录
     * @param string $recordType              记录类型(A/NS/MX/TXT/CNAME/SRV/AAAA/CAA/REDIRECT_URL/FORWARD_URL)
     * @param string $Value
     * @return void
     */
    public static function AddDomainRecord($client, $domainName, $RR, $recordType, $Value)
    {
        $req = new AddDomainRecordRequest([]);
        $req->domainName = $domainName;
        $req->RR = $RR;
        $req->type = $recordType;
        $req->value = $Value;
        try {
            $resp = $client->addDomainRecord($req);
            return Utils::toJSONString(Tea::merge($resp));
        } catch (Exception $error) {
            if (!($error instanceof TeaError)) {
                $error = new TeaError([], $error->getMessage(), $error->getCode(), $error);
            }
            return $error->message;
        }
    }

    /**
     * UpdateDomainRecord  更新域名解析记录
     * @param Alidns $client          客户端
     * @param string $recordId        解析记录ID
     * @param string $RR              主机记录
     * @param string $recordType            记录类型(A/NS/MX/TXT/CNAME/SRV/AAAA/CAA/REDIRECT_URL/FORWARD_URL)
     * @param string $Value
     * @return void
     */
    public static function UpdateDomainRecord($client, $recordId, $RR, $recordType, $Value)
    {
        $req = new UpdateDomainRecordRequest([]);
        $req->recordId = $recordId;
        $req->RR = $RR;
        $req->type = $recordType;
        $req->value = $Value;
        try {
            $resp = $client->updateDomainRecord($req);
            return Utils::toJSONString(Tea::merge($resp));
        } catch (Exception $error) {
            if (!($error instanceof TeaError)) {
                $error = new TeaError([], $error->getMessage(), $error->getCode(), $error);
            }
            return $error->message;
        }
    }

    /**
     * SetDomainRecordStatus  设置域名解析状态
     * @param Alidns $client      客户端
     * @param string $recordId    解析记录ID
     * @param string $status      解析状态(ENABLE/DISABLE)
     * @return void
     */
    public static function SetDomainRecordStatus($client, $recordId, $status)
    {
        $req = new SetDomainRecordStatusRequest([]);
        $req->recordId = $recordId;
        $req->status = $status;
        try {
            $resp = $client->setDomainRecordStatus($req);
            return Utils::toJSONString(Tea::merge($resp));
        } catch (Exception $error) {
            if (!($error instanceof TeaError)) {
                $error = new TeaError([], $error->getMessage(), $error->getCode(), $error);
            }
            return $error->message;
        }
    }

    /**
     * DeleteDomainRecord  删除域名解析记录
     * @param Alidns $client         客户端
     * @param string $recordId       解析记录ID
     * @return void
     */
    public static function DeleteDomainRecord($client, $recordId)
    {
        $req = new DeleteDomainRecordRequest([]);
        $req->recordId = $recordId;
        try {
            $resp = $client->deleteDomainRecord($req);
            return Utils::toJSONString(Tea::merge($resp));
        } catch (Exception $error) {
            if (!($error instanceof TeaError)) {
                $error = new TeaError([], $error->getMessage(), $error->getCode(), $error);
            }
            return $error->message;
        }
    }

    /**
     * DescribeDomainGroups  查询域名组
     * @param Alidns $client 客户端
     * @return void
     */
    public static function DescribeDomainGroups($client)
    {
        $req = new DescribeDomainGroupsRequest([]);
        try {
            $resp = $client->describeDomainGroups($req);
            return Utils::toJSONString(Tea::merge($resp));
        } catch (Exception $error) {
            if (!($error instanceof TeaError)) {
                $error = new TeaError([], $error->getMessage(), $error->getCode(), $error);
            }
            return $error->message;
        }
    }

    /**
     * AddDomainGroup  添加域名组
     * @param Alidns $client      客户端
     * @param string $groupName   域名组名
     * @return void
     */
    public static function AddDomainGroup($client, $groupName)
    {
        $req = new AddDomainGroupRequest([]);
        $req->groupName = $groupName;
        try {
            $resp = $client->addDomainGroup($req);
            return Utils::toJSONString(Tea::merge($resp));
        } catch (Exception $error) {
            if (!($error instanceof TeaError)) {
                $error = new TeaError([], $error->getMessage(), $error->getCode(), $error);
            }
            return $error->message;
        }
    }

    /**
     * UpdateDomainGroup  更新域名组名称
     * @param Alidns $client         客户端
     * @param string $groupId        解析组ID
     * @param string $groupName      新域名组名称
     * @return void
     */
    public static function UpdateDomainGroup($client, $groupId, $groupName)
    {
        $req = new UpdateDomainGroupRequest([]);
        $req->groupId = $groupId;
        $req->groupName = $groupName;
        try {
            $resp = $client->updateDomainGroup($req);
            return Utils::toJSONString(Tea::merge($resp));
        } catch (Exception $error) {
            if (!($error instanceof TeaError)) {
                $error = new TeaError([], $error->getMessage(), $error->getCode(), $error);
            }
            return $error->message;
        }
    }

    /**
     * DeleteDomainGroup  删除域名组
     * @param Alidns $client      客户端
     * @param string $groupId     域名组ID
     * @return void
     */
    public static function DeleteDomainGroup($client, $groupId)
    {
        $req = new DeleteDomainGroupRequest([]);
        $req->groupId = $groupId;
        try {
            $resp = $client->deleteDomainGroup($req);
            return Utils::toJSONString(Tea::merge($resp));
        } catch (Exception $error) {
            if (!($error instanceof TeaError)) {
                $error = new TeaError([], $error->getMessage(), $error->getCode(), $error);
            }
            return $error->message;
        }
    }

    /**
     * @param string[] $args
     * @return void
     */
    public static function main($args)
    {
        $regionId = @$args[7];
        $domainName = @$args[0];
        $RR = @$args[1];
        $recordType = @$args[2];
        $value = @$args[3];
        $recordId = @$args[4];
        $groupName = @$args[5];
        $groupId = @$args[6];
        // 0.初始化客户端
        $client = self::Init(Env::getEnv("ACCESS_KEY_ID"), Env::getEnv("ACCESS_KEY_SECRET"), $regionId);
        // 1.查询账户下域名
        self::DescribeDomains($client);
        // 2.阿里云云解析添加域名
        self::AddDomain($client, $domainName);
        // 3.查询域名解析记录
        self::DescribeDomainRecords($client, $domainName);
        // 4.查询域名记录日志
        self::DescribeRecordLogs($client, $domainName);
        // 5.通过RecordId查询域名解析记录
        self::DescribeDomainRecordByRecordId($client, $recordId);
        // 6.查询域名信息
        self::DescribeDomainInfo($client, $domainName);
        // 7.添加域名解析记录
        self::AddDomainRecord($client, $domainName, $RR, $recordType, $value);
        // 8.更新域名解析记录
        self::UpdateDomainRecord($client, $recordId, $RR, $recordType, $value);
        // 9.设置域名解析状态
        self::SetDomainRecordStatus($client, $recordId, "ENABLE");
        // 10.删除域名解析记录
        self::DeleteDomainRecord($client, $recordId);
        // 11.查询域名组
        self::DescribeDomainGroups($client);
        // 12.添加域名组
        self::AddDomainGroup($client, $groupName);
        // 13.更新域名组名称
        self::UpdateDomainGroup($client, $groupId, $groupName);
        // 14.删除域名组
        self::DeleteDomainGroup($client, $groupId);
    }
}

