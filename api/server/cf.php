<?php

namespace cf;

require_once("basic.php");
require_once("main.php");

define("cfurl", "https://api.cloudflare.com/client/v4");

//检查apitoken
function Checktoken($email, $key)
{
    $url = cfurl . "/accounts";
    $header = array(
        'X-Auth-Email:' . $email,
        'X-Auth-Key:' . $key
    );
    $res = json_decode(CurlRequest($url, "GET", null, $header), true);
    if ($res['success'] == false) {
        return array('status' => 'server_error', 'message' => $res['errors'][0]["message"]);
    } else {
        return array("status" => "success", "message" => $res);
    }
}

//获取域名概览信息&解析记录列表
function checkDomain($email, $key, $zoneid)
{
    $url = cfurl . "/zones/" . $zoneid . "/dns_records";
    $header = array(
        'X-Auth-Email:' . $email,
        'X-Auth-Key:' . $key
    );
    $res = json_decode(CurlRequest($url, "GET", null, $header), true);
    if ($res['success'] == false) {
        return array('status' => 'server_error', 'message' => $res['errors'][0]["message"]);
    } else {
        return array("status" => "success", "message" => $res);
    }
}

//更新域名解析记录
function updateDomainRecord($email, $key, $zoneid, $hostRecord, $value, $recordType, $recordId, $domain, $remark, $proxied, $ttl)
{
    $url = cfurl . "/zones/" . $zoneid . "/dns_records/" . $recordId;
    $header = array(
        'X-Auth-Email:' . $email,
        'X-Auth-Key:' . $key
    );
    $data = json_encode(array(
        "content" => $value,
        "name" => $hostRecord . "." . $domain,
        "proxied" => filter_var($proxied, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
        "type" => $recordType,
        "comment" => $remark,
        "ttl" => (int)$ttl
    ));
    $res = json_decode(CurlRequest($url, "PUT", $data, $header), true);
    if ($res['success'] == false) {
        return array('status' => 'server_error', 'message' => $res['errors'][0]["message"]);
    } else {
        return array("status" => "success", "message" => $res);
    }
}

//新增解析记录
function creatRecord($email, $key, $zoneid, $hostRecord, $value, $recordType, $domain, $remark, $proxied, $ttl)
{
    $url = cfurl . "/zones/" . $zoneid . "/dns_records/";
    $header = array(
        'X-Auth-Email:' . $email,
        'X-Auth-Key:' . $key
    );
    $data = json_encode(array(
        "content" => $value,
        "name" => $hostRecord . "." . $domain,
        "proxied" => filter_var($proxied, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
        "type" => $recordType,
        "comment" => $remark,
        "ttl" => (int)$ttl
    ));
    $res = json_decode(CurlRequest($url, "POST", $data, $header), true);
    if ($res['success'] == false) {
        return array('status' => 'server_error', 'message' => $res['errors'][0]["message"]);
    } else {
        return array("status" => "success", "message" => $res);
    }
}

//删除域名解析记录
function deletRecord($email, $key, $zoneid, $recordId)
{
    $url = cfurl . "/zones/" . $zoneid . "/dns_records/" . $recordId;
    $header = array(
        'X-Auth-Email:' . $email,
        'X-Auth-Key:' . $key
    );
    $res = json_decode(CurlRequest($url, "DELETE", null, $header), true);
    if ($res['success'] == false) {
        return array('status' => 'server_error', 'message' => $res['errors'][0]["message"]);
    } else {
        return array("status" => "success", "message" => $res);
    }
}
