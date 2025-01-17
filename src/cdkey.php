<?php
require __DIR__ . '/libs/vendor/autoload.php';
include_once __DIR__ . '/libs/mailSender.php';
include_once __DIR__ . '/libs/log.php';
include_once __DIR__ . '/libs/cdkeyHelper.php';
include_once __DIR__ . '/libs/RSAHelper.php';
include_once __DIR__ . '/libs/openCommandHelper.php';
include_once __DIR__ . '/actions.php';
include_once __DIR__ . '/configs.php';

use Curl\Curl;

$curl = new Curl();
$logger = new Log("logs");
header("Content-Type: application/json");
// 自定义错误处理函数
function customErrorHandler($errorNumber, $errorMessage, $errorFile, $errorLine) {
    global $logger;
    $logger->Fatal("[$errorNumber] $errorMessage in $errorFile on line $errorLine");
    $logger->Fatal("堆栈信息:" . json_encode(debug_backtrace()));
    onError();

}

// 自定义异常处理函数
function customExceptionHandler($exception) {
    global $logger;
    $logger->Error($exception->getMessage());
    onError();
}

function onError()
{
    die( "{\"retcode\":-2003,\"message\":\"服务器内部错误\"}");
}

// 设置全局错误处理程序
set_error_handler("customErrorHandler");

// 设置全局异常处理程序
set_exception_handler("customExceptionHandler");

$cdkeyHelper = new CdkeyHelper(new mysqli(DB_HOST,DB_USER,DB_PASSWD,DB_NAME));
//$cdkeyHelper->Add("TESTCDKEY",1145,json_encode(array("101 9999 1","102 9999 1","103 9999 1")));


if (array_key_exists("action",$_GET))
{
    ActionHandler($_GET['action'],$_GET,$cdkeyHelper);
}
$authkey = $_GET['authkey'];
$cdkey = $_GET['cdkey'];
$authkey = json_decode(RSAdecrypt($authkey,file_get_contents("private_key.pem")));
$logger->info(json_encode($authkey));
$uid = $authkey->uid;
$nickname = $authkey->nickname;
//$level = $authkey->level;
$targetServer = $_GET['server'];

$mailSender = new GcMailSender($curl,$ocf);
$och = new OpenCommandHelper($ocf,$curl);
$och->logger = $logger;
$multiServer = $och->isMultiServer();

if ($multiServer && ($targetServer == null)) //如果处于多服务器模式并未设置目标服务器
{
    $mailSender->mTargetServer = array_search("Game_main",(array)$och->serverList);
}
else if($multiServer && ($targetServer != null))
{
    $mailSender->mTargetServer = array_search($targetServer,(array)$och->serverList);
}
$msg = "MSG_NOT_INITIALIZED";
$code = -2003;
if ($authkey === null)
{
    $msg = "系统错误";
}
else {
    if (!$cdkeyHelper->Usage($cdkey,$uid))
    {
        $msg = "兑换码无效或者已经被使用";
    }
    else
    {
        $mailSender->mTarget = $uid;
        $mailSender->mTitle = "兑换码奖励";
        if ($cdkeyHelper->View($cdkey)['items'] != null)
        {$mailSender->mItem = true;}
        //$msg = $cdkeyHelper->View("yuanshen")['items'];
        $mailSender->mItems = json_decode($cdkeyHelper->View($cdkey)['items']);
        $mailSender->mContent = "兑换码奖励,请查收\n被使用的兑换码: <color=#00FF00>$cdkey</color>";
        $mailSender->Send();
        $msg = "兑换成功";
        $code = 0;
    }

}

die( "{\"retcode\":$code,\"message\":\"$msg\"}");

