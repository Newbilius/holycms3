<?php
require_once("../engine.php");

global $_global_bread;
$_global_bread[] = Array("������ ������ �� SQL");

$groups = new DBlockGroup();
$groups->GetList();
$groups_list = $groups->GetFullList();

if (isset($_POST['go'])) {
    if ($_POST['xml_file']['error'] == 0) {
        ImportSQL($_POST['xml_file']['tmp_name'],Array("utf-8","windows-1251"));
        ?>
        <div class="alert alert-success fade in" style="max-width:400px;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            ���� ������� (�������) ������������.
        </div>
        <?
    }
};
?>

<form method=post enctype="multipart/form-data">
    <input type=hidden name=go value=1>
    ���� �������:<br>

    <input type=file name=xml_file>

    <input type=submit value="������ ������" class="btn">
</form>