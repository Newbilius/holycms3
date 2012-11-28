<?php

function GetImageResizeCache($fname, $w, $h, $add = "", $rect = 0, $url_only = false,$now=false) {
    $pic = new HolyImg($fname);
    if ($rect)
        $pic->ResizeSquare($w);
    else
        $pic->Resize(Array("width" => $w, "height" => $h));
    $url = $pic->GetURL();
    if ($url_only)
        return $url;
    else
        $pic->DrawHref(Array(
            'draw_inner' => $add,
            'now' => $now,
        ));
}

function GetImageResizeCacheAndLink($link, $fname, $w, $h, $add = "", $add2 = "", $add3 = "", $rect = 0) {
    if ($link == "")
        $link = $fname;
    ?>
    <a href="<?= $link ?>" <?= $add2 ?>><?= GetImageResizeCache($fname, $w, $h, $add, $rect) ?><?= $add3 ?></a>
    <?
}

;

function DrawWater($file_name, $fName, $percent = 50) {
    if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $fName))
        return $file_name;

    $pic = new HolyImg($file_name);
    $pic->AddWaterMark($fName, Array(
        "transparent" => $percent,
    ));
    return $pic->GetURL();
}

;

function imagealphamask3(&$picture, $mask) {

    // Get sizes and set up new picture
    $xSize = imagesx($mask[0]);
    $ySize = imagesy($mask[0]);

    $xSize0 = imagesx($picture);
    $ySize0 = imagesy($picture);

    $newPicture = imagecreatetruecolor($xSize, $ySize);
    imagesavealpha($newPicture, true);
    imagefill($newPicture, 0, 0, imagecolorallocatealpha($newPicture, 0, 0, 0, 127));


    $dimg = imagecreatetruecolor($xSize, $ySize);
    imagecopyresampled($dimg, $picture, 0, 0, 0, 0, $xSize, $ySize, $xSize0, $ySize0);
    imagedestroy($picture);
    $picture = $dimg;

    // Perform pixel-based alpha map application
    for ($x = 0; $x < $xSize; $x++) {
        for ($y = 0; $y < $ySize; $y++) {
            $alpha = imagecolorsforindex($mask[0], imagecolorat($mask[0], $x, $y));
            $alpha = 127 - floor($alpha['red'] / 2);
            $color = imagecolorsforindex($picture, imagecolorat($picture, $x, $y));
            $color2 = imagecolorsforindex($mask[0], imagecolorat($mask[0], $x, $y));
            $color3 = imagecolorsforindex($mask[1], imagecolorat($mask[1], $x, $y));
            if (($color2['red'] == 255) && ($color2['green'] == 255) && ($color2['blue'] == 255))
                imagesetpixel($newPicture, $x, $y, imagecolorallocatealpha($newPicture, $color['red'], $color['green'], $color['blue'], $alpha));
            else
                imagesetpixel($newPicture, $x, $y, imagecolorallocatealpha($mask[1], $color3['red'], $color3['green'], $color3['blue'], $alpha));
        }
    }

    // Copy back to original picture
    imagedestroy($picture);
    $picture = $newPicture;
}

function imagealphamask(&$picture, $mask) {

    // Get sizes and set up new picture
    $xSize = imagesx($mask);
    $ySize = imagesy($mask);

    $xSize0 = imagesx($picture);
    $ySize0 = imagesy($picture);

    $newPicture = imagecreatetruecolor($xSize, $ySize);
    imagesavealpha($newPicture, true);
    imagefill($newPicture, 0, 0, imagecolorallocatealpha($newPicture, 0, 0, 0, 127));


    $dimg = imagecreatetruecolor($xSize, $ySize);
    imagecopyresampled($dimg, $picture, 0, 0, 0, 0, $xSize, $ySize, $xSize0, $ySize0);
    imagedestroy($picture);
    $picture = $dimg;

    // Perform pixel-based alpha map application
    for ($x = 0; $x < $xSize; $x++) {
        for ($y = 0; $y < $ySize; $y++) {
            $alpha = imagecolorsforindex($mask, imagecolorat($mask, $x, $y));
            $alpha = 127 - floor($alpha['red'] / 2);
            $color = imagecolorsforindex($picture, imagecolorat($picture, $x, $y));
            imagesetpixel($newPicture, $x, $y, imagecolorallocatealpha($newPicture, $color['red'], $color['green'], $color['blue'], $alpha));
        }
    }

    // Copy back to original picture
    imagedestroy($picture);
    $picture = $newPicture;
}

function GetImageResizeCacheAndMask($fname, $mask, $w, $h, $add = "") {
    if (strpos($fname, "http") !== false)
        $complete_link = $fname;
    $new_name = $fname;
    $parts = pathinfo($fname);

    $new_name = "/upload/resize_cache/" . $parts['filename'] . "_" . $w . "_" . $h . "_masked_" . str_replace("/", "_", $mask) . ".png";

    if (file_exists($_SERVER['DOCUMENT_ROOT'] . $new_name))
        $complete_link = $new_name;
    else {


        $icon = new cache_resize_icons($w, $h, 0);

        if ($icon->getIcon($fname, $new_name))
            $complete_link = $new_name;
        else
            $complete_link = $fname;


        // Load source and mask
        $source = imagecreatefrompng($_SERVER['DOCUMENT_ROOT'] . $new_name);
        $mask = imagecreatefrompng($_SERVER['DOCUMENT_ROOT'] . $mask);
// Apply mask to source
        imagealphamask($source, $mask);
// Output
//imagepng( $source );
        unlink($_SERVER['DOCUMENT_ROOT'] . $new_name);
        if (!file_exists($_SERVER['DOCUMENT_ROOT'] . "/upload/resize_cache/"))
            mkdir($_SERVER['DOCUMENT_ROOT'] . "/upload/resize_cache/");
        imagepng($source, $_SERVER['DOCUMENT_ROOT'] . $new_name);
    };
    ?>
    <img src="<?= $complete_link ?>" <?= $add ?>>
    <?
}

function GetImageResizeCacheAndMask2($fname, $mask, $w, $h, $add = "") {
    if (strpos($fname, "http") !== false)
        $complete_link = $fname;
    $new_name = $fname;
    $parts = pathinfo($fname);

    $new_name = "/upload/resize_cache/" . $parts['filename'] . "_" . $w . "_" . $h . "_masked_" . str_replace("/", "_", $mask[0]) . ".png";

    if (file_exists($_SERVER['DOCUMENT_ROOT'] . $new_name))
        $complete_link = $new_name;
    else {


        $icon = new cache_resize_icons($w, $h, 0);

        if ($icon->getIcon($fname, $new_name))
            $complete_link = $new_name;
        else
            $complete_link = $fname;


        // Load source and mask
        $source = imagecreatefrompng($_SERVER['DOCUMENT_ROOT'] . $new_name);
        $mask1 = imagecreatefrompng($_SERVER['DOCUMENT_ROOT'] . $mask[0]);
        $mask2 = imagecreatefrompng($_SERVER['DOCUMENT_ROOT'] . $mask[1]);
// Apply mask to source
        imagealphamask($source, $mask1);
        imagepng($source, $_SERVER['DOCUMENT_ROOT'] . $new_name);

        imagedestroy($source);
        $source = imagecreatefrompng($_SERVER['DOCUMENT_ROOT'] . $new_name);
        imagealphamask($source, $mask2);
// Output
//imagepng( $source );
        unlink($_SERVER['DOCUMENT_ROOT'] . $new_name);
        if (!file_exists($_SERVER['DOCUMENT_ROOT'] . "/upload/resize_cache/"))
            mkdir($_SERVER['DOCUMENT_ROOT'] . "/upload/resize_cache/");
        imagepng($source, $_SERVER['DOCUMENT_ROOT'] . $new_name);
    };
    ?>
    <img src="<?= $complete_link ?>" <?= $add ?>>
    <?
}

function GetImageResizeCacheAndMask3($fname, $mask, $w, $h, $add = "") {
    if (strpos($fname, "http") !== false)
        $complete_link = $fname;
    $new_name = $fname;
    $parts = pathinfo($fname);

    $new_name = "/upload/resize_cache/" . $parts['filename'] . "_" . $w . "_" . $h . "_masked_" . str_replace("/", "_", $mask[0] . $mask[1]) . ".png";

    if (file_exists($_SERVER['DOCUMENT_ROOT'] . $new_name))
        $complete_link = $new_name;
    else {


        $icon = new cache_resize_icons($w, $h, 0);

        if ($icon->getIcon($fname, $new_name))
            $complete_link = $new_name;
        else
            $complete_link = $fname;


        // Load source and mask
        $source = imagecreatefrompng($_SERVER['DOCUMENT_ROOT'] . $new_name);
        $mask[0] = imagecreatefrompng($_SERVER['DOCUMENT_ROOT'] . $mask[0]);
        $mask[1] = imagecreatefrompng($_SERVER['DOCUMENT_ROOT'] . $mask[1]);
// Apply mask to source
        imagealphamask3($source, $mask);
// Output
//imagepng( $source );
        unlink($_SERVER['DOCUMENT_ROOT'] . $new_name);
        if (!file_exists($_SERVER['DOCUMENT_ROOT'] . "/upload/resize_cache/"))
            mkdir($_SERVER['DOCUMENT_ROOT'] . "/upload/resize_cache/");
        imagepng($source, $_SERVER['DOCUMENT_ROOT'] . $new_name);
    };
    ?>
    <img src="<?= $complete_link ?>" <?= $add ?>>
    <?
}

function GetImageResizeCacheAndMask4($fname, $mask, $w, $h, $add = "") {
    if (strpos($fname, "http") !== false)
        $complete_link = $fname;
    $new_name = $fname;
    $parts = pathinfo($fname);

    $new_name = "/upload/resize_cache/" . $parts['filename'] . "_" . $w . "_" . $h . "_masked_" . str_replace("/", "_", $mask[0] . $mask[1]) . ".png";

    if (file_exists($_SERVER['DOCUMENT_ROOT'] . $new_name))
        $complete_link = $new_name;
    else {


        $icon = new cache_resize_icons($w, $h, 0);

        if ($icon->getIcon($fname, $new_name))
            $complete_link = $new_name;
        else
            $complete_link = $fname;


        // Load source and mask
//$source = imagecreatefrompng( $_SERVER['DOCUMENT_ROOT'].$new_name );
//$mask[0] = imagecreatefrompng( $_SERVER['DOCUMENT_ROOT'].$mask[0] );
//$mask[1] = imagecreatefrompng( $_SERVER['DOCUMENT_ROOT'].$mask[1] );
// Apply mask to source
        if (!file_exists($_SERVER['DOCUMENT_ROOT'] . "/upload/resize_cache/"))
            mkdir($_SERVER['DOCUMENT_ROOT'] . "/upload/resize_cache/");
        $first = new Imagick($_SERVER['DOCUMENT_ROOT'] . $mask[0]);
        $second = new Imagick($_SERVER['DOCUMENT_ROOT'] . $new_name);
        $first->compositeImage($second, imagick::COMPOSITE_ATOP, 0, 0);
        $first->writeImage($_SERVER['DOCUMENT_ROOT'] . $new_name);

        $first2 = new Imagick($_SERVER['DOCUMENT_ROOT'] . $mask[1]);
        $second2 = new Imagick($_SERVER['DOCUMENT_ROOT'] . $new_name);
        $first2->compositeImage($second2, imagick::COMPOSITE_DEFAULT, 0, 0);
        $first2->writeImage($_SERVER['DOCUMENT_ROOT'] . $new_name);

        if (!file_exists($_SERVER['DOCUMENT_ROOT'] . "/upload/resize_cache/"))
            mkdir($_SERVER['DOCUMENT_ROOT'] . "/upload/resize_cache/");
//imagepng( $source,$_SERVER['DOCUMENT_ROOT'].$new_name );
    };
    ?>
    <img src="<?= $complete_link ?>" <?= $add ?>>
    <?
}

//"имя переменной","значение","максимальное число значений"
function AddToCookieArray($name, $value, $max_count = -1) {
    if (isset($_COOKIE[$name]))
        $cur_array = explode(";", $_COOKIE[$name]); else
        $cur_array = Array();

    if (!in_array($value, $cur_array))
        $cur_array[] = $value;

    if ($max_count != -1)
        if (count($cur_array) > $max_count) {
            reset($cur_array);
            $first = key($cur_array);
            unset($cur_array[$first]);
        };
    $_COOKIE[$name] = implode(";", $cur_array);
    setcookie($name, $_COOKIE[$name], time() + 90000, "/");
}

function ResizeImageLink($fname, $w, $h) {
    return "/engine/resize.php?w=" . $w . "&h=" . $h . "&fname=" . $fname;
}

;

function GetImageResize($path, $width, $height, $add = "") {
    ?>
    <img src="/engine/resize.php?w=<?= $width ?>&h=<?= $height ?>&fname=<?= $path ?>" <?= $add ?>>
    <?
}
?>