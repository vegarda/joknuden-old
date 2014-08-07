<?php
    header('Content-type: application/json; charset=utf-8');
    $whitelist = array(
        'nve',
        'yr',
        'met'
    );
    if(in_array(explode('.', parse_url($_GET['url'])['host'])[1], $whitelist)){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $_GET['url']);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Accept: application/json',
            'Accept-Language: nb-no,nb;q=0.9,no-no;q=0.8,no;q=0.6,nn-no;q=0.5,nn;q=0.4,en-us;q=0.3,en;q=0.1',
            'Accept-Encoding: gzip,deflate,sdch'
        ));
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);

        /* try to follow redirects */
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        /* timeout after the specified number of seconds. assuming that this script runs
        on a server, 20 seconds should be plenty of time to verify a valid URL.  */
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $response = curl_exec($ch);
        
        if(curl_errno($ch)){
            echo 'Curl error: ' . curl_error($ch);
        }
        
        if($response === false){
            echo 'Curl error: ' . curl_error($ch);
        }
        
        curl_close($ch);
        
        echo $response;
        
    };
?>