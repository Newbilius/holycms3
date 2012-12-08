<?php

require_once(realpath(str_replace("\\","/",dirname(dirname(dirname(__FILE__)))."/engine.php")));
global $H_USER;
$user_info_holy = $H_USER->GetInfo();
if ($H_USER->GetID()) {
    $tmp_file = $_POST['json_json_multiple_foto'][0];

    if ($tmp_file['error'] == 0) {
        $el = new DBlockElement($_photo_id);
        $el->Add(Array(
            "foto" => $tmp_file,
            "name" => MD5($tmp_file['name'] . time()),
            "caption" => $tmp_file['name'],
            "parent" => $_POST['parent']
        ));
        $added_element = $el->GetByID($el->sql->last_id);
        $_image = $added_element['foto'];
        $tmp_name = explode("/", $_image);
        $new_image['delete_url'] = "/engine/admin/ajax/json_delete.php?delete_path=" . $_image;
        $new_image['name'] = end($tmp_name);
        $new_image['size'] = filesize(FOLDER_ROOT . $_image);
        $new_image['url'] = $_image;
        $resize_img = new HolyImg(FOLDER_ROOT . $_image);
        $resize_img->Resize(Array("width" => 80, "height" => 80));
        $new_image['thumbnail_url'] = $resize_img->GetURL(true);
        $new_image['delete_type'] = "GET";

        header('Vary: Accept');
        header('Content-type: application/json');
        echo json_encode(Array(0=>$new_image));
    };
}
?>