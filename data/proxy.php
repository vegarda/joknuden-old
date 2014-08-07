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
        if (curl_exec($ch) === FALSE) {
            die("Curl failed: " . curl_error($ch));
        }
        curl_close($ch);    
    };
?>