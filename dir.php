<?php	

$day    = date("Y-m-d");
//$dir    = '/var/www/html/timelapse/'.$day;
$dir    = $_SERVER['DOCUMENT_ROOT'].'/timelapse/'.$day;
#$images = scandir($dir, 1);
#$images = array_slice($images,1);
$images = glob($dir .'/*.{jpeg,jpg}', GLOB_BRACE);

foreach ($images as $image){
	$image = basename($image);
	echo '
			<a id="webcam" class="fancybox hidden" rel="webcam" title="View from Joknuden" href="'.$REQUEST_URI.'/timelapse/'.$day.'/'.$image.'" alt="">'.$image.'</a>';
}

?>
