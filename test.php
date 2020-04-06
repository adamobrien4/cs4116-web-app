<?php

$url = "https://api.adorable.io/avatars/300/abott@adorable.png";

$file_loc = "./picture.jpg";

if(file_put_contents($file_loc, file_get_contents($url))) { 
    echo "File downloaded successfully"; 
} else { 
    echo "File downloading failed."; 
}


?>