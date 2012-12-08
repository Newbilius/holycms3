<?php

require_once("../engine.php");

global $force_filter;

$force_filter=false;

if ((isset($_GET['force_filter_name'])) && (isset($_GET['force_filter_value']))){
    $force_filter="&force_filter_name=".$_GET['force_filter_name']."&force_filter_value=".$_GET['force_filter_value'];
}

if (isset($_GET['path']))
    if ($_GET['path'] == "adm") {
        header('Location: /engine/admin/');
        exit;
    };
if (isset($_SERVER['REDIRECT_URL']))
    if (($_SERVER['REDIRECT_URL'] == "/adm") || ($_SERVER['REDIRECT_URL'] == "/engine/admin/")|| ($_SERVER['REDIRECT_URL'] == "/adm/")) {
        header('Location: /engine/admin/');
        exit;
    };

global $H_USER;


if ($H_USER->GetID() == 0) {
    IncludeComponent("system\auth_form", "default");
    if ($H_USER->GetID() != 0)
        IncludeComponent("system\administration_panel", "default");
    else
        "";
}
else
    IncludeComponent("system\administration_panel", "default");
?>