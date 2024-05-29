<?php
class CdkeyHelper
{
    public $cdkey = "";
    private $db;
    private $t_cdkey = "cdkeys";
    private $t_history = "cdkey_history";
    function __construct($db,$tConfig = false)
    {
        $this->db = $db;
        if ($tConfig && count($tConfig) == 2)
        {
            $this->t_cdkey = $tConfig[0];
            $this->t_history = $tConfig[1];
        }
    }
    function Add($cdkey,$maxCount,$items = null,$valid = 1,$note = null)
    {
        $stmt = $this->db->prepare("INSERT INTO `$this->t_cdkey` (`cdkey`, `max_usage_count`, `items`, `is_valid`, `note`) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sisis",$cdkey,$maxCount,$items,$valid,$note);
        if ($stmt->execute())
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    function Delete()
    {
        
    }
    function GenerateCdkey($Count = 1,$length = 12)
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $cdkeys = array();
        $cdkey = '';
        for ($q = 0;$q <$Count;$q++)
        {
            $cdkey = '';
            for ($i = 0; $i < $length; $i++) {
                $cdkey .= $characters[rand(0, $charactersLength - 1)];
            }
            array_push($cdkeys,$cdkey);
        }

        return $cdkeys;
    }
    function View($cdkey)
    {
        $stmt = $this->db->prepare("SELECT * from `$this->t_cdkey` WHERE `cdkey` = ?");
        $stmt->bind_param("s", $cdkey);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
function Usage($cdkey, $uid, $note = null)
{
    if ($this->Verify($cdkey, $uid)) {
        $clientIP = $this->GetCLientIP();
        $stmt = $this->db->prepare("INSERT INTO `$this->t_history` (`id`, `cdkey`, `ip`, `uid`, `note`) VALUES (NULL, ?, ?, ?, ?)");
        $stmt->bind_param("ssis", $cdkey, $clientIP, $uid, $note);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}
    function Verify($cdkey, $uid)
    {
        $stmt = $this->db->prepare("SELECT cdkey, uid from `$this->t_history` WHERE `cdkey` = ? AND `uid` = ?");
        $stmt->bind_param("si", $cdkey, $uid);
        $stmt->execute();
        if ($stmt->get_result()->num_rows >= 1) {
            return false;
        }
        $stmt = $this->db->prepare("SELECT cdkey, uid from `$this->t_history` WHERE `cdkey` = ?");
        $stmt->bind_param("s", $cdkey);
        $stmt->execute();
        $USAGE_COUNT = $stmt->get_result()->num_rows;
        $stmt = $this->db->prepare("SELECT * from `$this->t_cdkey` WHERE `cdkey` = ? AND `is_valid` = 1 OR `expire_time` != 0 AND `expire_time` > current_timestamp");
        $stmt->bind_param("s", $cdkey);
        $stmt->execute();
        $result = $stmt->get_result();
        $R = $result->fetch_assoc();
        if ($R == null)
        {
            return false;
        }
        if ($R['max_usage_count'] <= $USAGE_COUNT && $R['max_usage_count'] != 0)
        {
            return false;
        }
        else {
            return true;
        }
    }
    function GetCLientIP(){ 
        $ip=false; 
        if(!empty($_SERVER['HTTP_CLIENT_IP'])){ 
            $ip=$_SERVER['HTTP_CLIENT_IP']; 
        }
        if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){ 
            $ips=explode (', ', $_SERVER['HTTP_X_FORWARDED_FOR']); 
            if($ip){ array_unshift($ips, $ip); $ip=FALSE; }
            for ($i=0; $i < count($ips); $i++){
                if(!eregi ('^(10│172.16│192.168).', $ips[$i])){
                    $ip=$ips[$i];
                    break;
                }
            }
        }
        return ($ip ? $ip : $_SERVER['REMOTE_ADDR']); 
    }
}