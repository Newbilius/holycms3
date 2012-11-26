<?php

require_once($_SERVER['DOCUMENT_ROOT'] . "/engine/engine.php");
global $H_USER;
$user_info_holy = $H_USER->GetInfo();
if ($H_USER->GetID()) {
    $src = new DBlockElement($_GET['dblock']);
    $element = $src->GetByID($_GET['id']);
    $images = explode(";", $element[$_GET['field']]);
    $images_array = array();
    foreach ($images as $_image) {
        $tmp_name = explode("/", $_image);
        $new_image['name'] = end($tmp_name);
        $new_image['size'] = filesize($_SERVER['DOCUMENT_ROOT'] . "/upload/" . $_image);
        $new_image['url'] = "/upload/" . $_image;
        $resize_img = new HolyImg($_SERVER['DOCUMENT_ROOT'] . "/upload/" . $_image);
        $resize_img->Resize(Array("width" => 80, "height" => 80));
        $new_image['thumbnail_url'] = "http://" . $_SERVER['HTTP_HOST'] . $resize_img->GetURL(true);
        $new_image['delete_url'] = "/admin/ajax/json_delete.php?path=" . $_image;
        $new_image['delete_type'] = "GET";
        $images_array[] = $new_image;
    }
    header('Vary: Accept');
    header('Content-type: application/json');
    echo json_encode($images_array);
}
?>