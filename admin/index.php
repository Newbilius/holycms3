<?php

require_once("../engine.php");

if (isset($_GET['path']))
    if ($_GET['path'] == "adm") {
        header('Location: /engine/admin/');
        exit;
    };
//preprint($_SERVER);
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