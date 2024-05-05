<?php
class GcMailSender
{
    public $mContent = "MAIL_CONTENT";
    public $mTitle = "MAIL_TITLE";
    public $mTarget = "10001";
    public $mSender = "GameMaster";
    public $mItem = false;
    public $mItems = array("101 9999 1");
    //public $opencommandData = "";
    public $openCommandConfig = array();
    private $curlLib ;
    public $openCommandUrl; 
    private $cHeader = "/sendMail";
    private $eEndTag = "finish";
    function __construct($curl,$sConfig)
    {
        $this->curlLib = $curl;
        // $this->mTitle = $title;
        // $this->mContent = $content;
        // $this->mTarget = $UID;
        // $this->mSender = $senderName;
        $this->openCommandUrl = $sConfig[1];
        $this->openCommandConfig = array(
            "token"=>$sConfig[0],
            "action"=>"command",
            "data"=>"ping"
        );
    }
    //sendMail 10001 |/sendMail 测试 |/sendMail sssssssss |/sendMail GrasscutterTools |/sendMail 111 9999 1 |/sendMail finish @10004"}
    function Build()
    {
        $mailContent = "";
        $space = " ";
        $F = "|";
        $mailItems = "";
        if ($this->mItem && $this->mItems != null)
        {
            foreach ($this->mItems as $item) {
                $mailItems .= $space . $this->cHeader . $space . $item . $F;
            }
        }
        $mailContent = $this->cHeader . $space . $this->mTarget . $F . $this->cHeader . $space . $this->mTitle . $F . $this->cHeader . $space . $this->mContent
        . $F . $this->cHeader . $space . $this->mSender  . $F . $space . $mailItems . $this->cHeader . $space . $this->eEndTag;
        return $mailContent;

    }
    function Send()
    {
        $logger = new Log("logs");
        $this->openCommandConfig['data'] = $this->Build();
        
        $this->curlLib->post($this->openCommandUrl,json_encode($this->openCommandConfig));

        if ($this->curlLib->error) {
            $logger->Error(json_encode($this->curlLib->errorMessage . "\n"));
            $logger->Error(json_encode($this->curlLib->diagnose()));
        } else {
            $logger->Info(json_encode($this->curlLib->response) . "\n");
            
        }
    }
    private function ResetConfig()
    {

    }

}

