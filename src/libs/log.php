<?php

class Log
{
    public $log_path;
    public $log_file;
    public function __construct($path,$logfile = "product.log") {
        $this->log_path = $path;
        $this->log_file = $logfile;
        if ($this->log_path[strlen($this->log_path) - 1] != '/')
        {
            $this->log_path = $this->log_path . '/';
        }
        $this->log_file = $this->log_path . $this->log_file;
    }
    function Debug($content)
    {
        file_put_contents($this->log_file,"[" . date("Y-m-d H:i:s") . "] [DEBUG] $content\n",FILE_APPEND|LOCK_EX);
        
    }
    function Info($content)
    {
        file_put_contents($this->log_file,"[" . date("Y-m-d H:i:s") . "] [INFO ] $content\n",FILE_APPEND|LOCK_EX);

    }
    function War($content)
    {
        file_put_contents($this->log_file,"[" . date("Y-m-d H:i:s") . "] [WARN] $content\n",FILE_APPEND|LOCK_EX);
        
    }
    function Error($content)
    {
        file_put_contents($this->log_file,"[" . date("Y-m-d H:i:s") . "] [ERROR] $content\n",FILE_APPEND|LOCK_EX);
        
    }
    function Fatal($content)
    {
        file_put_contents($this->log_file,"[" . date("Y-m-d H:i:s") . "] [FATAL] $content\n",FILE_APPEND);
        
    }
}
