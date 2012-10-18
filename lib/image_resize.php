<?php

// imagecopyresampled() with alpha channel support.

function image_resize($src, $width, $height){

    $img = imagecreatetruecolor($width, $height);

    imagecolortransparent($img, imagecolorallocatealpha($img, 0, 0, 0, 127));

    imagealphablending($img, false);
    imagesavealpha($img, true);

    imagecopyresampled($img, $src, 0, 0, 0, 0, $width, $height, imagesx($src), imagesy($src));

    imagealphablending($img, true);

    return $img;
}
