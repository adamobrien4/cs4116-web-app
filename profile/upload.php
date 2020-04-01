<?php

session_start();

$file = $_FILES['file'];

$ext = strtolower(explode(".", $file['name'])[1]);

if($ext == "jpeg") {
    $ext = "jpg";
}
$filename = $_SESSION['user_id'] . "." . $ext;
$new_location = "../assets/uploads/" . $_SESSION['user_id'] . ".jpg";

$location = "../assets/uploads/" . $filename;

$uploadOk = 1;

// Valid extensions
$valid_extensions = array("jpg", "jpeg", "png");
if (!in_array($ext, $valid_extensions)) {
    $uploadOk = 0;
}

if ($uploadOk == 0) {
    echo "failure";
} else {
    /* Upload file */
    if (move_uploaded_file($file['tmp_name'], $location)) {

        // Turn image into jpg
        if ($ext == "png") {
            $image = imagecreatefrompng($location);
            $bg = imagecreatetruecolor(imagesx($image), imagesy($image));
            imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));
            imagealphablending($bg, TRUE);
            imagecopy($bg, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
            imagedestroy($image);
            $quality = 90; // 0 = worst / smaller file, 100 = better / bigger file 
            imagejpeg($bg, $new_location, $quality);
            imagedestroy($bg);

            unlink($location);
        }

        echo $new_location;
    } else {
        echo "failure";
    }
}
