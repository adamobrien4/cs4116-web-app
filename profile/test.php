<?php

$location = "../assets/uploads/13.png";
$new_location = "../assets/uploads/13.jpg";

$image = imagecreatefrompng($location);
$bg = imagecreatetruecolor(imagesx($image), imagesy($image));
imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));
imagealphablending($bg, TRUE);
imagecopy($bg, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
imagedestroy($image);
$quality = 85; // 0 = worst / smaller file, 100 = better / bigger file 
imagejpeg($bg, $new_location, $quality);
imagedestroy($bg);
