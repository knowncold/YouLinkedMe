<?php

function echo_server_log($log){
	file_put_contents("log.txt", $log, FILE_APPEND);
}

define ( "TOKEN", "czdiy" );
$right = "开灯";

function checkSignature() {
	$signature = $_GET ['signature'];
	$nonce = $_GET ['nonce'];
	$timestamp = $_GET ['timestamp'];
	$tmpArr = array ($nonce, $timestamp, TOKEN );
	sort ( $tmpArr );
	
	$tmpStr = implode ( $tmpArr );
	$tmpStr = sha1 ( $tmpStr );
	if ($tmpStr == $signature) {
		return true;
	}
	return false;
}
if(false == checkSignature()) {
	exit(0);
}
$echostr = $_GET ['echostr'];
if($echostr) {
	echo $echostr;
	exit(0);
}
	function getPostData() {
			$data = $GLOBALS['HTTP_RAW_POST_DATA'];
				return	$data;
	}
$PostData = getPostData();

if(!$PostData){
	echo_server_log("wrong input! PostData is NULL");
	echo "wrong input!";
	exit(0);
}
$xmlObj = simplexml_load_string($PostData, 'SimpleXMLElement', LIBXML_NOCDATA);
if(!$xmlObj) {
	echo_server_log("wrong input! xmlObj is NULL\n");
	echo "wrong input!";
	exit(0);
}
$fromUserName = $xmlObj->FromUserName;
$toUserName = $xmlObj->ToUserName;
$msgType = $xmlObj->MsgType;

if('text' != $msgType) {
	$retMsg = '只支持文本消息';	
}else{
	$content = $xmlObj->Content;
	if ($content == $right) {
		$retMsg = "正确";
    }else{
    	$retMsg = $content;
    }

}
$retTmp = "<xml>
		<ToUserName><![CDATA[%s]]></ToUserName>
		<FromUserName><![CDATA[%s]]></FromUserName>
		<CreateTime>%s</CreateTime>
		<MsgType><![CDATA[text]]></MsgType>
		<Content><![CDATA[%s]]></Content>
		<FuncFlag>0</FuncFlag>
		</xml>";
$resultStr = sprintf($retTmp, $fromUserName, $toUserName, time(), $retMsg);
echo $resultStr

?>
