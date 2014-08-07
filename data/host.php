<?php
    $host = '127.0.0.1';
    $whitelist = array(
        '127.0.0.1',
        '::1'
    );

    if(in_array($_SERVER['REMOTE_ADDR'], $whitelist)){
        //$host = 'joknuden.no';
        $host = '192.168.10.10';
    };

?>