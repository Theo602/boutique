<?php

require_once('../config/init.php');

$code = bin2hex(random_bytes(3));
$captcha_code  = substr($code, 0, 6);
$_SESSION['captcha_code'] = $captcha_code;


$image = imagecreatetruecolor(120, 36);

$background = imagecolorallocate($image, 0, 0, 0);

$forground = imagecolorallocate($image, 255, 255, 255);

imagefill($image, 0, 0, $background);
imagestring($image, 5, 34, 10,  $captcha_code, $forground);

header("Cache-Control: no-cache, must-revalidate");

header('Content-type: image/png');

imagepng($image);
imagedestroy($image);
