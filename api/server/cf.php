<?php

namespace cf;

require_once("basic.php");
require_once("main.php");

define("cfurl","https://api.cloudflare.com/client/v4");

//检查apitoken
function Checktoken($token){
    $url = cfurl."/user/tokens/verify";
    $header = array(
        'Authorization: Bearer '.$token
    );
    $res = json_decode(CurlRequest($url,"GET",null,$header),true);
    if($res['success']==false){
        return array('status'=> 'server_error','message'=> $res['errors'][0]["error_chain"][0]["message"]);
    }
    return array("status"=> "success","message"=> $res);
}