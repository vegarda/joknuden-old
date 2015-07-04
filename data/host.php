<?php
    $host = '127.0.0.1';
    $whitelist = array(
        'localhost',
        'vegard.me',
		'yr.vegard.me'
    );

    if(in_array($_SERVER['HTTP_HOST'], $whitelist)){
        $host = 'joknuden.no';
    };

?>