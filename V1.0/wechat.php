<?php

//错误日志
function echo_server_log($log){
	file_put_contents("log.txt", $log, FILE_APPEND);
}

//定义TOKEN
define ( "TOKEN", "ulink" );

//验证微信公众平台签名
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

//接入时验证接口
$echostr = $_GET ['echostr'];
if($echostr) {
	echo $echostr;
	exit(0);
}

//获取POST数据
function getPostData() {
	$data = $GLOBALS['HTTP_RAW_POST_DATA'];
	return	$data;
}
$PostData = getPostData();

//验错
if(!$PostData){
	echo_server_log("wrong input! PostData is NULL");
	echo "wrong input!";
	exit(0);
}

//装入XML
$xmlObj = simplexml_load_string($PostData, 'SimpleXMLElement', LIBXML_NOCDATA);

//验错
if(!$xmlObj) {
	echo_server_log("wrong input! xmlObj is NULL\n");
	echo "wrong input!";
	exit(0);
}

//准备XML
$fromUserName = $xmlObj->FromUserName;
$toUserName = $xmlObj->ToUserName;
$msgType = $xmlObj->MsgType;


if('text' != $msgType) {		//初步判断
	$retMsg = '只支持文本消息';
}else{
	$content = $xmlObj->Content;
	if ($content == "开灯") {			//比对命令
		file_put_contents("store.txt", "11");//更改文件值
		$retMsg = "成功";
    }else if ($content == "关灯") {
    	file_put_contents("store.txt", "00");
    	$retMsg = "成功";
    }

}

//装备XML
$retTmp = "<xml>
		<ToUserName><![CDATA[%s]]></ToUserName>
		<FromUserName><![CDATA[%s]]></FromUserName>
		<CreateTime>%s</CreateTime>
		<MsgType><![CDATA[text]]></MsgType>
		<Content><![CDATA[%s]]></Content>
		<FuncFlag>0</FuncFlag>
		</xml>";
$resultStr = sprintf($retTmp, $fromUserName, $toUserName, time(), $retMsg);

//反馈
echo $resultStr;
?>
