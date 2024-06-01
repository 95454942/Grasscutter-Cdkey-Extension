<?php

function ActionHandler($action,array $args,$Handler)
{
switch ($action) {
    case 'gencdkey':
        GenerateCdkeys($Handler,$args['count']);
        die(json_encode(array("msg"=>"ok","retcode"=>0)));
    
    default:
        die(json_encode(array("msg"=>"action_invalid","retcode"=>-1)));

}
}


function GenerateCdkeys(CdkeyHelper $cdkeyHelper,$Count)
{
    $cdkeys = $cdkeyHelper->GenerateCdkey($Count);
    foreach ($cdkeys as $cdkey) {
        $cdkeyHelper->Add($cdkey,0,json_encode(array("102 120000 1","201 6480 1","1073 1 90")));
    }
}