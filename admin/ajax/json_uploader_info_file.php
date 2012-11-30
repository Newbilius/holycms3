<?php

require_once($_SERVER['DOCUMENT_ROOT'] . "/engine/engine.php");
global $H_USER;
$user_info_holy = $H_USER->GetInfo();
if ($H_USER->GetID()) {
    $src = new DBlockElement($_GET['dblock']);
    $element = $src->GetByID($_GET['id']);
    $images = explode(";", $element[$_GET['field']]);
    $images_array = array();
    foreach ($images as $_image) 
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . $_image))
        {
        $tmp_name = explode("/", $_image);
        $new_image['delete_url'] = "/engine/admin/ajax/json_delete.php?delete_path=" . $_image;
        $new_image['name'] = end($tmp_name);
        $new_image['size'] = filesize($_SERVER['DOCUMENT_ROOT'] . $_image);
        $new_image['url'] = $_image;
        $new_image['delete_type'] = "GET";
        $images_array[] = $new_image;
    }
    header('Vary: Accept');
    header('Content-type: application/json');
    echo json_encode($images_array);
}
?>