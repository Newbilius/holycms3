<?php

if (!defined('HCMS'))
    die();

if (file_exists($full_template_path))
    include($full_template_path);
else
    SystemAlert("�� ������ ������ <b>" . $full_template_path . "</b>");
?>