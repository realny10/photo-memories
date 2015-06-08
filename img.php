<?php

if(!$_POST) {
	header('Location: ./');
	die;
}


require 'GDText/Box.php';

$srcPath = 'sample.jpg';
$textPath = 'text-layer.png';

$headerText = isset($_POST['day']) ? 'Dzień '.$_POST['day'] : 'Dzień 1';
$routeText = isset($_POST['route']) ? 'Trasa: '.$_POST['route'] : '';
$descriptionText = isset($_POST['description']) ? $_POST['description'] : '';

$dstWidth = 1100;
$dstHeight = 640;

//new image
$img = imagecreatetruecolor($dstWidth, $dstHeight);
$bgImg = imagecreatefromjpeg($srcPath);
$textImg = imagecreatefrompng($textPath);


//colors
$colorBg = imagecolorallocate($img, 255, 255, 255);
$colorBorder = imagecolorallocate($img, 0, 0, 0);
$colorHeader = imagecolorallocate($img, 80, 148, 206);
$colorRoute = imagecolorallocate($img, 50, 50, 50);
$colorText = imagecolorallocate($img, 30, 30, 30);


//fill bg
imagefilledrectangle($img, 0, 0, $dstWidth, $dstHeight, $colorBg);

imagecopyresized($img, $bgImg, 0, 0, 0, 0, 640, 640, 640, 640);
imagecopyresized($img, $textImg, 0, 0, 0, 0, $dstWidth, $dstHeight, $dstWidth, $dstHeight);


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


//send photo to user
header('Content-type: image/png');
imagepng($img);