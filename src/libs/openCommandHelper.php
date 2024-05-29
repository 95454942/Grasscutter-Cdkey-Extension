<?php

class OpenCommandHelper{
    public $url = "";
    public $consoleToken = "";
    public $action = "";
    public $curlLib;
    public $serverList = array();
    public $logger;
    function __construct($ocf,$curl)
    {
        $this->url = $ocf[1];
        $this->consoleToken = $ocf[0];
        $this->curlLib = $curl;

    }
    function isMultiServer()
    {
        $this->action = "runmode";
        $response = $this->exec();
        if ($response->data)
        {
            $this->action = "server";
            $this->serverList = $this->exec()->data;
            return true;
        }
        else
        {
            return false;
        }
        
    }
    // {
    //     "token": "Gd&d@$QA2FecE8gu",
    //     "action": "server",
    //     "server": "da852682-8a0a-448a-adaf-fa078a964fe8",
    //     "data": "give 223 x100 @10000001"
    // }
    function exec($server = "",$data = "")
    {
        $request = json_encode(
            array(
                "token"=>$this->consoleToken,
                "action"=>$this->action,
                "server"=>$server,
                "data"=>$data
            )
        );
        $this->curlLib->post(
            $this->url,
            $request
        );
        if ($this->curlLib->error) {
            $this->logger->Error(json_encode($this->curlLib->errorMessage . "\n"));
            $this->logger->Error(json_encode($this->curlLib->diagnose()));
        } else {
            $this->logger->Info(json_encode($this->curlLib->response) . "\n");
            
        }

        switch ($this->curlLib->response->retcode) {
            case 200:
                return $this->curlLib->response;
            default:
                throw new ErrorException("服务器响应失败 请检查你的配置");
        }
    }
}