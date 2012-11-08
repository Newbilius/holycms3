<?php
require_once("../engine.php");

global $_global_bread;
$_global_bread[] = Array("Импорт структуры из XML");

if (isset($_POST['go'])) {
  if ($_POST['xml_file']['error']==0){
      $data=PrepareXMLFromUrl($_POST['xml_file']['tmp_name']);
      //@todo функцию документировать
      $data=recursive_iconv("utf-8","windows-1251",$data);
      preprint($data);
  }
};
?>

<form method=post enctype="multipart/form-data">
    <input type=hidden name=go value=1>
    Выберите группу блоков:<br>

    <br>
    Файл импорта:<br>

    <input type=file name=xml_file>

    <input type=submit value="Начать импорт" class="btn">
</form>