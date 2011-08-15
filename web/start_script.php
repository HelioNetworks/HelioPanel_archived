<?php

/**
 * Start the script in the background, then drop the connection.
 */
function start_script($url){
    
    $parsed = parse_url($url);
    
    if (empty($parsed['port'])) $parsed['port'] = 80;
  
    $fp = fsockopen($parsed['host'],$parsed['port']);
    
    if (!$fp) return false;
    
    $out = "GET ${parsed['path']}?${parsed['query']} HTTP/1.1\r\n";
    $out.= "Host: ${parsed['host']}:${parsed['port']}\r\n";
    $out.= "Connection: close\r\n\r\n";
    
    fwrite($fp, $out);
    fclose($fp);

    return true;
}