<?php
    //error_reporting(E_ALL);

	if(($_GET['type'] == 'json') || ($_GET['content-type'] == 'xml')){
		$content_type = $_GET['content-type'];
	}
	else{
		$content_type = 'json';
	}

    header('Content-type: application/'.$content_type.'; charset=utf-8');

    $whitelist = array(
        'nve',
        'yr',
        'met'
    );

    if(in_array(explode('.', parse_url($_GET['url'])['host'])[1], $whitelist)){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $_GET['url']);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Accept: application/'.$content_type,
            'Accept-Language: nb-no,nb;q=0.9,no-no;q=0.8,no;q=0.6,nn-no;q=0.5,nn;q=0.4,en-us;q=0.3,en;q=0.1',
            'Accept-Encoding: gzip,deflate,sdch'
        ));

        curl_exec($ch);
        
        if(curl_errno($ch)){
            echo 'Curl error: ' . curl_error($ch);
        }
        curl_close($ch);
    };
        
?>