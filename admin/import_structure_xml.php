<?php
require_once("../engine.php");

global $_global_bread;
$_global_bread[] = Array("������ ��������� �� XML");


$groups = new DBlockGroup();
$groups->GetList();
$groups_list = $groups->GetFullList();

if (isset($_POST['go'])) {
  if ($_POST['xml_file']['error']==0){
      $data=PrepareXMLFromUrl($_POST['xml_file']['tmp_name']);
      //@todo ������� ���������������
      $data=recursive_iconv("utf-8","windows-1251",$data);
      preprint($_POST);
      preprint($data);
  }
};
?>

<form method=post enctype="multipart/form-data">
    <input type=hidden name=go value=1>
    �������� ������ ������:<br>
    <select name="group_in_id">
        <?
        foreach ($groups_list as $_group){
            ?>
        <option value="<?=$_group['name']?>"><?=$_group['caption']?> [<?=$_group['name']?>]</option>
        <?
        }
        ?>
    </select>
    <br>
    ���� �������:<br>

    <input type=file name=xml_file>

    <input type=submit value="������ ������" class="btn">
</form>