<?php
require __DIR__ . '/libs/vendor/autoload.php';
include_once __DIR__ . '/libs/mailSender.php';
include_once __DIR__ . '/libs/log.php';
include_once __DIR__ . '/libs/cdkeyHelper.php';
use Curl\Curl;

$curl = new Curl();
$logger = new Log("logs");
$cdkeyHelper = new CdkeyHelper(new mysqli("localhost","gc_cdkey","a2DtiimwENhBeDAW","gc_cdkey"));
//$cdkeyHelper->Add("TESTCDKEY",1145,json_encode(array("101 9999 1","102 9999 1","103 9999 1")));

header("Content-Type: application/json");
$mailSender = new GcMailSender($curl,array("LLnWdfEVlC2HRv7iVYFK1p+au54q/5ZO","http://ps.gitdl.cn:4433/opencommand/api"));
$msg = "MSG_NOT_INITIALIZED";
$code = -2003;
$data = explode("CDKEYEND",$_GET['cdkey']);
if (count($data) != 2)
{
    $msg = "兑换码格式错误";
}
else {
    $cdkey = $data[0];
    $uid = $data[1];
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
        $mailSender->mItems = json_decode($cdkeyHelper->View($cdkey)['items']);
        $mailSender->mContent = "兑换码奖励,请查收\n被使用的兑换码: <color=#00FF00>$cdkey</color>";
        $mailSender->Send();
        $msg = "兑换成功";
        $code = 0;
    }

}

die( "{\"retcode\":$code,\"message\":\"$msg\"}");

# {"token":"LLnWdfEVlC2HRv7iVYFK1p+au54q/5ZO","action":"command","data":"sendMail 10001 |/sendMail 测试 |/sendMail sssssssss |/sendMail GrasscutterTools |/sendMail 111 9999 1 |/sendMail finish @10004"}