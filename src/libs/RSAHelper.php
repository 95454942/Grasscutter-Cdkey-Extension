<?php
function RSAdecrypt($content,$privateKey)
{
    // 加载RSA私钥
    $privateKey = openssl_pkey_get_private($privateKey);
    // 解密数据
    $decryptedData = '';
    openssl_private_decrypt(base64_decode($content), $decryptedData, $privateKey);
    
    // 输出解密后的数据
    return $decryptedData;
}

?>