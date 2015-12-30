<?php	

$day    = date("Y-m-d");
$dir    = $_SERVER['DOCUMENT_ROOT'].'/timelapse/'.$day;
$images = glob($dir .'/*.{jpeg,jpg}', GLOB_BRACE);
rsort($images);

$first = basename($images[0]);
$images = array_slice($images, 1);
echo '<a class="webcam-link lightbox" rel="webcam" data-lightbox="webcam" title="View from Joknuden @ '.substr($first, 0 , -4).'" href="'.$REQUEST_URI.'timelapse/'.$day.'/'.$first.'" alt=""><img src="/timelapse/'.$day.'/'.$first.'" class="webcam-image"/></a>';

foreach ($images as $image){
	$image = basename($image);
	echo '
			<a class="lightbox hidden" data-lightbox="webcam" 	rel="webcam" title="View from Joknuden @ '.substr($image, 0 , -4).'" href="'.$REQUEST_URI.'timelapse/'.$day.'/'.$image.'" alt=""></a>';
}

?>
