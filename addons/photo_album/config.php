<?
global $_top_menu;
$_top_menu[] = Array(
    "caption" => "-",
    "parent" => "utilits",
);

$_top_menu[] = Array(
    "url" => "/engine/admin/photo_add.php",
    "caption" => "Загрузка фото в альбом",
    "admin_right" => false,
    "parent" => "utilits",
);
?>