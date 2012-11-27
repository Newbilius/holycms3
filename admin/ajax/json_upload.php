<?php

require_once($_SERVER['DOCUMENT_ROOT'] . "/engine/engine.php");
global $H_USER;
$user_info_holy = $H_USER->GetInfo();
if ($H_USER->GetID()) {
    $tmp_file = end($_FILES);
    if ($tmp_file['error'][0] == 0) {

        $name = $tmp_file['name'][0];
        $tmp_name = $tmp_file['tmp_name'][0];
        $size = $tmp_file['size'][0];

        $exp = explode(".", $name);
        $extens = end($exp);
        $file_name = MD5(time() . $name . $tmp_name . rand()) . "." . $extens;

        $fold1 = substr($file_name, 0, 2);
        $fold2 = substr($file_name, 2, 2);

        if (!file_exists(FOLDER_UPLOAD))
            mkdir(FOLDER_UPLOAD);
        if (!file_exists(FOLDER_IMAGE))
            mkdir(FOLDER_IMAGE);
        if (!file_exists(FOLDER_IMAGE . $fold1))
            mkdir(FOLDER_IMAGE . $fold1);
        if (!file_exists(FOLDER_IMAGE . $fold1 . "/" . $fold2))
            mkdir(FOLDER_IMAGE . $fold1 . "/" . $fold2);

        $file_name = URI_IMAGE . $fold1 . "/" . $fold2 . "/" . $file_name;

        rename($tmp_name, $_SERVER['DOCUMENT_ROOT'] . $file_name);

        $full_name = $_SERVER['DOCUMENT_ROOT'] . $file_name;

        $new_image['name'] = $name;
        $new_image['size'] = $size;
        $new_image['url'] = $file_name;
        $resize_img = new HolyImg($full_name);
        $resize_img->Resize(Array("width" => 80, "height" => 80));
        $new_image['thumbnail_url'] = "http://" . $_SERVER['HTTP_HOST'] . $resize_img->GetURL(true);
        $new_image['delete_url'] = "/engine/admin/ajax/json_delete.php?delete_path=" . $file_name;
        $new_image['delete_type'] = "GET";
        $new_image['type'] = GetMIME($full_name);

        $new_image_all[] = $new_image;

        header('Vary: Accept');
        header('Content-type: application/json');
        echo json_encode($new_image_all);
    };
}
?>