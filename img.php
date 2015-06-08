<?php

//if(!$_POST) {
//	header('Location: ./');
//	die;
//}

function downloadMap($coords) {
	$mapUrl = 'http://maps.googleapis.com/maps/api/staticmap?center='.$coords.'&zoom=6&scale=false&size=600x300&maptype=roadmap&format=png&visual_refresh=true';

	$data = file_get_contents($mapUrl);
	$mapFilename = 'tmp/'.md5(rand()).'.png';
	file_put_contents($mapFilename, $data);

	return $mapFilename;
}

function imagealphamask( &$picture, $mask ) {
	// Get sizes and set up new picture
	$xSize = imagesx( $picture );
	$ySize = imagesy( $picture );
	$newPicture = imagecreatetruecolor( $xSize, $ySize );
	imagesavealpha( $newPicture, true );
	imagefill( $newPicture, 0, 0, imagecolorallocatealpha( $newPicture, 0, 0, 0, 127 ) );

	// Resize mask if necessary
	if( $xSize != imagesx( $mask ) || $ySize != imagesy( $mask ) ) {
		$tempPic = imagecreatetruecolor( $xSize, $ySize );
		imagecopyresampled( $tempPic, $mask, 0, 0, 0, 0, $xSize, $ySize, imagesx( $mask ), imagesy( $mask ) );
		imagedestroy( $mask );
		$mask = $tempPic;
	}

	// Perform pixel-based alpha map application
	for( $x = 0; $x < $xSize; $x++ ) {
		for( $y = 0; $y < $ySize; $y++ ) {
			$alpha = imagecolorsforindex( $mask, imagecolorat( $mask, $x, $y ) );
			$alpha = 127 - floor( $alpha[ 'red' ] / 2 );
			$color = imagecolorsforindex( $picture, imagecolorat( $picture, $x, $y ) );
			imagesetpixel( $newPicture, $x, $y, imagecolorallocatealpha( $newPicture, $color[ 'red' ], $color[ 'green' ], $color[ 'blue' ], $alpha ) );
		}
	}

	// Copy back to original picture
	imagedestroy( $picture );
	$picture = $newPicture;
}


$srcPath = false;
if(isset($_FILES['file']) && $_FILES['file']['size'] != 0) {

	$srcExtension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
	$srcPath = 'tmp/'.md5(rand()).'.'.$srcExtension;

	move_uploaded_file($_FILES['file']['tmp_name'], $srcPath);
} else {
	$srcPath = 'sample.jpg';
}


//


require 'GDText/Box.php';


$headerText = isset($_POST['day']) ? 'Dzień '.$_POST['day'] : 'Dzień 1';
$routeText = isset($_POST['route']) ? 'Trasa: '.$_POST['route'] : '';
$descriptionText = isset($_POST['description']) ? $_POST['description'] : '';

$textPath = 'text-layer.png';
$textMaskPath = 'text-layer-mask.png';
$mapPath = downloadMap('49.826613099999996,19.043443099999998');

$dstWidth = 1100;
$dstHeight = 640;

//calculate width/height
list($srcWidth, $srcHeight) = getimagesize($srcPath);


//new image
$img = imagecreatetruecolor($dstWidth, $dstHeight);
$mapLayer = imagecreatetruecolor($dstWidth, $dstHeight);
$maskLayerImg = imagecreatefrompng('text-layer-mask.png');

$bgImg = imagecreatefromjpeg($srcPath);
$textImg = imagecreatefrompng($textPath);
$mapImg = imagecreatefrompng($mapPath);


//alpha
imagesavealpha($mapLayer, true);
$colorTransparent = imagecolorallocatealpha($mapLayer, 255, 255, 255, 0);
imagefill($mapLayer, 0, 0, $colorTransparent);

//colors
$colorBg = imagecolorallocate($img, 255, 255, 255);
$colorBorder = imagecolorallocate($img, 0, 0, 0);
$colorHeader = imagecolorallocate($img, 80, 148, 206);
$colorRoute = imagecolorallocate($img, 50, 50, 50);
$colorText = imagecolorallocate($img, 30, 30, 30);


//fill bg
imagefilledrectangle($img, 0, 0, $dstWidth, $dstHeight, $colorBg);

imagecopyresized($img, $bgImg, 0, 0, 0, 0, 640, 640, $srcWidth, $srcHeight);
imagecopyresized($img, $textImg, 0, 0, 0, 0, $dstWidth, $dstHeight, $dstWidth, $dstHeight);

imagecopyresized($mapLayer, $mapImg, $dstWidth - 550, $dstHeight - 200, 0, 50, 600, 300, 600, 300);
imagealphamask($mapLayer, $maskLayerImg);

imagecopyresized($img, $mapLayer, 0, 0, 0, 0, $dstWidth, $dstHeight, $dstWidth, $dstHeight);


//header text
$box = new Box($img);
$box->setFontFace(__DIR__.'/fonts/Amatic-Bold.ttf');
$box->setFontColor($colorHeader);
$box->setFontSize(80);
$box->setLineHeight(1);
$box->setBox(580, 70, 480, 50);
$box->setTextAlign('center', 'center');
$box->draw($headerText);

//route box
$box = new Box($img);
$box->setFontFace(__DIR__.'/fonts/Lato-Medium.ttf');
$box->setFontColor($colorRoute);
$box->setFontSize(21);
$box->setBox(600, 140, 460, 100);
$box->setTextAlign('center', 'center');
$box->draw($routeText);

//description box
$box = new Box($img);
$box->setFontFace(__DIR__.'/fonts/Lato-Light.ttf');
$box->setFontColor($colorText);
$box->setFontSize(18);
$box->setLineHeight(1.4);
$box->setBox(640, 260, 420, 300);
$box->setTextAlign('right', 'top');
$box->draw($descriptionText);


//todo:remove
imagerectangle($img, 0, 0, $dstWidth - 1, $dstHeight - 1, $colorBorder);

//send photo to user
header('Content-type: image/png');
imagepng($img);