<?php

$srcPath = 'sample.jpg';
$textPath = 'text-layer.png';

$dstWidth = 960;
$dstHeight = 640;

//new image
$img = imagecreatetruecolor($dstWidth, $dstHeight);
$bgImg = imagecreatefromjpeg($srcPath);
$textImg = imagecreatefrompng($textPath);


//colors
$colorBg = imagecolorallocate($img, 255, 255, 255);
$colorRibbon = imagecolorallocatealpha($img, 255, 120, 120, 50);


//fill bg
imagefilledrectangle($img, 0, 0, $dstWidth, $dstHeight, $colorBg);

imagecopyresized($img, $bgImg, 0, 0, 0, 0, 640, 640, 640, 640);
imagecopyresized($img, $textImg, 0, 0, 0, 0, $dstWidth, $dstHeight, $dstWidth, $dstHeight);


//send photo to user
header('Content-type: image/png');
imagepng($img);