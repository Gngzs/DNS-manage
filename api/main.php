<?php
//error_reporting(0);
require_once("basic.php");
require_once("server/tencent.php");
require_once("server/ali.php");
require_once("server/cf.php");

//查询服务商列表
function getServerList($offset = 0, $limit = 10)
{
    $sql1 = "SELECT * FROM list_server LIMIT " . $offset . "," . $limit;
    $sql2 = "SELECT COUNT(*) FROM list_server;";
    return array(executeQuery(($sql1)), executeQuery(($sql2)));
}
//检查与服务商的连接
function checkServerStatus($server, $secretid, $secretkey)
{
    switch ($server) {
        case "腾讯云":
            $res = tencent\checkServerStatus($secretid, $secretkey);
            break;
        case "阿里云":
            $client = ali\api::Init($secretid, $secretkey,);
            $res = ali\api::DescribeDomains($client);
            break;
        case "Cloudflare":
            $res = cf\Checktoken($secretid, $secretkey);
            break;
    }
    return $res;
}
//编辑服务商
function editServer($id, $servername, $server, $secretid, $secretkey)
{
    $sql = "UPDATE `list_server` SET `servername` = '" . $servername . "', `server` = '" . $server . "', `secretid` = '" . $secretid . "', `secretkey` = '" . $secretkey . "' WHERE `list_server`.`id` = " . $id;
    return executeQuery($sql);
}

//删除服务商
function delServer($id)
{
    $sql = "DELETE FROM list_server WHERE `list_server`.`id` = " . $id;
    return executeQuery($sql);
}

//启用/禁用服务商
function ableServer($id, $now_able)
{
    if ($now_able == "1") {
        $sql = "UPDATE `list_server` SET `enable` = '0' WHERE `list_server`.`id` = " . $id;
    } elseif ($now_able == "0") {
        $sql = "UPDATE `list_server` SET `enable` = '1' WHERE `list_server`.`id` = " . $id;
    }
    return executeQuery($sql);
}

//添加服务商
function addServer($name, $server, $secretid, $secretkey)
{
    $sql = "INSERT INTO `list_server` (`id`, `servername`, `server`, `secretid`, `secretkey`, `enable`) VALUES (NULL, '" . $name . "', '" . $server . "', '" . $secretid . "', '" . $secretkey . "', '1');";
    return array("status" => "success", "message" => executeQuery($sql));
}

//查询域名列表
function getDomainList($offset = 0, $limit = 10)
{
    $sql1 = "SELECT * FROM list_domain LIMIT " . $offset . "," . $limit;
    $sql2 = "SELECT COUNT(*) FROM list_domain;";
    $res1 = executeQuery($sql1);
    array_walk($res1, function (&$item) {
        $domainData = getDnsRecordsNumber($item["domain"], $item["servername"], $item["location"], $item["zoneid"]);
        if ($domainData[0]) {
            $item["recNumber"] = $domainData[1];
            $item["status"] = 1;
        } else {
            $item["recNumber"] = "？";
            $item["status"] = 0;
            $item["error_m"] = $domainData[1];
        }
        if ($item["location"] == "Cloudflare") {
            //
        } else {
            $item["zoneid"] = "仅Cloudflare";
        }
    });
    return array($res1, executeQuery(($sql2)));
}

//查询解析条数以及域名连接
function getDnsRecordsNumber($domain, $servername, $location, $zoneid)
{
    $sql = "SELECT * FROM `list_server` WHERE `servername` = '" . $servername . "'";
    $sqlres = executeQuery($sql);
    $secretid = $sqlres[0]["secretid"];
    $secretkey = $sqlres[0]["secretkey"];
    switch ($location) {
        case "阿里云":
            $client = ali\api::Init($secretid, $secretkey,);
            $res = ali\api::DescribeDomainRecords($client, $domain, 0, 10);
            //return $res['body']['TotalCount'];
            if ($res['status'] == 'success') {
                return array(true, $res['message']['body']['TotalCount']);
                break;
            }
            return array(false, $res['message']);
            break;
        case "腾讯云":
            $res = tencent\checkDomain($secretid, $secretkey, $domain);
            if ($res['status'] == 'success') {
                return array(true, $res['message']['DomainInfo']['RecordCount'] - 2);
                break;
            }
            return array(false, $res['message']);
            break;
        case "Cloudflare":
            $res = cf\checkDomain($secretid, $secretkey, $zoneid);
            if ($res['status'] == 'success') {
                return array(true, $res['message']['result_info']['total_count']);
                break;
            }
            return array(false, $res['message']);
            break;
    }
}

//查询解析记录列表
function getDomainDnsList($domain, $location, $servername, $offset, $limit, $zoneid)
{
    $sql = "SELECT * FROM `list_server` WHERE `servername` = '" . $servername . "'";
    $sqlres = executeQuery($sql);
    $secretid = $sqlres[0]["secretid"];
    $secretkey = $sqlres[0]["secretkey"];
    switch ($location) {
        case "腾讯云":
            $res = tencent\getDnsList($secretid, $secretkey, $domain, $offset, $limit);
            if ($res["status"] == "success") {
                array_walk($res["message"]["RecordList"], function (&$item) use ($domain) {
                    $item['Domain'] = $domain;
                });
                return array("status" => "success", "total" => $res["message"]["RecordCountInfo"]["TotalCount"], "rows" => $res["message"]["RecordList"]);
                break;
            }
            return $res;
            break;
        case "阿里云":
            $client = ali\api::Init($secretid, $secretkey,);
            $res = ali\api::DescribeDomainRecords($client, $domain, $offset, $limit);
            if ($res["status"] == "success") {
                array_walk($res['message']['body']['DomainRecords']['Record'], function (&$item) use ($domain) {
                    $item['Name'] = $item['RR'];
                    $item['UpdatedOn'] = @date('Y-m-d H:i:s', $item['UpdateTimestamp'] / 1000);
                    $item['Domain'] = $domain;
                    $replaceline = ['默认', '移动', '联通', '电信', '境外'];
                    $searchline = ['default', 'mobile', 'unicom', 'telecom', 'oversea'];
                    $item['Line'] = str_replace($searchline, $replaceline, $item['Line']);
                });
                return array("status" => "success", "total" => $res['message']['body']['TotalCount'], "rows" => $res['message']['body']['DomainRecords']['Record']);
                unset($item);
                break;
            }
            return $res;
            break;
        case "Cloudflare":
            $res = cf\checkDomain($secretid, $secretkey, $zoneid);
            if ($res['status'] == 'success') {
                array_walk($res['message']['result'], function (&$item) use ($domain) {
                    $item['Name'] = str_replace("." . $item['zone_name'], "", $item['name']);
                    $item['Type'] = $item['type'];
                    $item['Value'] = $item['content'];
                    $item['TTL'] = $item['ttl'];
                    $item['UpdatedOn'] = $item['modified_on'];
                    $item['RecordId'] = $item['id'];
                    $item['Domain'] = $domain;
                    $item['Remark'] = $item['comment'];
                });
                return array("status" => "success", "rows" => $res['message']['result'], "total" => $res['message']['result_info']['total_count']);
                break;
            }
            return array("status" => "server_error", "message" => $res['message']);
            break;
    }
}

//查询所有服务商名称
function getServerName()
{
    $sql = "SELECT servername, id FROM list_server";
    return executeQuery($sql);
}

//添加域名
function addDomain($domain, $serverid, $zoneid = "")
{
    $sql = "SELECT * FROM `list_server` WHERE `id` = '" . $serverid . "'";
    $sqlres = executeQuery($sql);
    $location = $sqlres[0]["server"];
    $servername = $sqlres[0]['servername'];
    $connect = getDnsRecordsNumber($domain, $servername, $location, $zoneid);
    if ($connect[0]) {
        $sql1 = "INSERT INTO `list_domain` (`id`, `domain`, `servername`, `location`, `zoneid`) VALUES (NULL, '" . $domain . "', '" . $servername . "', '" . $location . "', '" . $zoneid . "');";
        return array("status" => "success", "message" => executeQuery($sql1));
    } else {
        return array("status" => "error", "message" => $connect[1]);
    }
}

//删除域名
function delDomain($id)
{
    $sql = "DELETE FROM list_domain WHERE `list_domain`.`id` = " . $id;
    return executeQuery($sql);
}

//更新域名解析记录
function updateRecord($domain, $hostRecord, $recordType, $lineType, $recordValue, $weight, $ttl, $remark, $status, $proxy, $recordid, $zoneid)
{
    $sql = "SELECT * FROM `list_domain` WHERE `domain` = '" . $domain . "'";
    $sqlres = executeQuery($sql);
    $servername = $sqlres[0]['servername'];
    $location = $sqlres[0]['location'];
    $sql2 = "SELECT * FROM `list_server` WHERE `servername` = '" . $servername . "'";
    $sqlres2 = executeQuery($sql2);
    $secretid = $sqlres2[0]["secretid"];
    $secretkey = $sqlres2[0]["secretkey"];
    switch ($location) {
        case "腾讯云":
            $res = tencent\modifyRecord($secretid, $secretkey, $domain, $recordType, $lineType, $recordValue, $recordid, $hostRecord, $weight, $ttl, $remark, $status);
            return $res;
            break;
        case "阿里云":
            $client = ali\api::Init($secretid, $secretkey,);
            $searchline = ['默认', '移动', '联通', '电信', '境外'];
            $replaceline = ['default', 'mobile', 'unicom', 'telecom', 'oversea'];
            $lineType = str_replace($searchline, $replaceline, $lineType);
            $res = ali\api::UpdateDomainRecord($client, $recordid, $hostRecord, $recordType, $recordValue, $ttl, $lineType);
            if ($res['status'] == "success") {
                $client = ali\api::Init($secretid, $secretkey,);
                $resremark = ali\api::UpdateDomainRecordRemark($client, $recordid, $remark);
                $resstatus = ali\api::SetDomainRecordStatus($client, $recordid, $status);
                return $res;
                break;
            } elseif ($res['status'] == "server_error" and $res["code"] == "DomainRecordDuplicate") {
                $client = ali\api::Init($secretid, $secretkey,);
                $resremark = ali\api::UpdateDomainRecordRemark($client, $recordid, $remark);
                $resstatus = ali\api::SetDomainRecordStatus($client, $recordid, $status);
                return array("status" => "success");
                break;
            } else {
                return $res;
                break;
            }
        case "Cloudflare":
            $res = cf\updateDomainRecord($secretid, $secretkey, $zoneid, $hostRecord, $recordValue, $recordType, $recordid, $domain, $remark, $proxy, $ttl);
            return $res;
            break;
    }
}

//添加域名解析记录
function creatRecord($domain, $hostRecord, $recordType, $lineType, $recordValue, $weight, $ttl, $remark, $status, $proxy, $zoneid)
{
    $sql = "SELECT * FROM `list_domain` WHERE `domain` = '" . $domain . "'";
    $sqlres = executeQuery($sql);
    $servername = $sqlres[0]['servername'];
    $location = $sqlres[0]['location'];
    $sql2 = "SELECT * FROM `list_server` WHERE `servername` = '" . $servername . "'";
    $sqlres2 = executeQuery($sql2);
    $secretid = $sqlres2[0]["secretid"];
    $secretkey = $sqlres2[0]["secretkey"];
    switch ($location) {
        case "腾讯云":
            $res = tencent\CreateRecord($secretid, $secretkey, $domain, $recordType, $lineType, $recordValue, $hostRecord, $weight, $ttl, $remark, $status);
            return $res;
            break;
        case "阿里云":
            $client = ali\api::Init($secretid, $secretkey,);
            $searchline = ['默认', '移动', '联通', '电信', '境外'];
            $replaceline = ['default', 'mobile', 'unicom', 'telecom', 'oversea'];
            $lineType = str_replace($searchline, $replaceline, $lineType);
            if (!$ttl) {
                $ttl = 600;
            }
            $res = ali\api::AddDomainRecord($client, $domain, $hostRecord, $recordType, $recordValue, $ttl, $lineType);
            if ($res['status'] == "success") {
                $recordid = $res['message']['body']['RecordId'];
                $client = ali\api::Init($secretid, $secretkey,);
                $resremark = ali\api::UpdateDomainRecordRemark($client, $recordid, $remark);
                $resstatus = ali\api::SetDomainRecordStatus($client, $recordid, $status);
                return $res;
                break;
            } else {
                return $res;
                break;
            }
        case "Cloudflare":
            $res = cf\creatRecord($secretid, $secretkey, $zoneid, $hostRecord, $recordValue, $recordType, $domain, $remark, $proxy, $ttl);
            return $res;
            break;
    }
}

//删除域名解析
function deletRecord($domain, $recordId, $zoneid)
{
    $sql = "SELECT * FROM `list_domain` WHERE `domain` = '" . $domain . "'";
    $sqlres = executeQuery($sql);
    $servername = $sqlres[0]['servername'];
    $location = $sqlres[0]['location'];
    $sql2 = "SELECT * FROM `list_server` WHERE `servername` = '" . $servername . "'";
    $sqlres2 = executeQuery($sql2);
    $secretid = $sqlres2[0]["secretid"];
    $secretkey = $sqlres2[0]["secretkey"];
    switch ($location) {
        case "腾讯云":
            $res = tencent\DeletRecord($secretid, $secretkey, $domain, $recordId);
            return $res;
            break;
        case "阿里云":
            $client = ali\api::Init($secretid, $secretkey,);
            $res = ali\api::DeleteDomainRecord($client, $recordId);
            return $res;
            break;
        case "Cloudflare":
            $res = cf\deletRecord($secretid, $secretkey, $zoneid, $recordId);
            return $res;
            break;
    }
}
