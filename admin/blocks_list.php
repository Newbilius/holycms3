<?
$user_ifno_holy = $H_USER->GetInfo();
if (!$user_ifno_holy['block_control'])
    die("недостаточно прав");

if (!isset($_GET['group']))
    die("не выбрана группа блоков");

global $_global_bread;
$_global_bread[] = Array("Блоки данных", "/engine/admin/group_list.php");
$block = new DBlockGroup();
$tmp = $block->GetByID($_GET['group']);
$_global_bread[] = Array($tmp['caption'], "/engine/admin/blocks_list.php?group=" . $_GET['group']);

if (isset($_GET['delete'])) {
    $gr = new DBlock();

    $gr->Delete($_GET['delete']);
    ?>
    <span style="color:green;">
        Элемент <?= $_GET['delete'] ?> удалён
    </span>
    <?
};



$form = new HFormEdit(Array('table' => "system_data_block_group", 'id' => $_GET['group']));
$form->return_link = "group_list.php";

$form->Add(Array("name" => "caption", "caption" => "Название", "type" => "short_text", 'required' => true));
$form->Add(Array("name" => "name", "caption" => "Код", "type" => "read_only", 'required' => true));
$form->Add(Array("name" => "sort", "caption" => "Сортировка", "type" => "sort", 'required' => false));

if ($form->GO()) {
    $returns_now = false;
    if ($_POST['submit'] == "Сохранить")
        $returns_now = true;
    unset($_POST['submit']);



    $block->Update($_GET['group'], Array(
        "caption" => $_POST["caption"],
        "name" => $_POST["name"],
        "sort" => $_POST["sort"],
    ));
    $form->Reload();
    ?>
    <? if ($returns_now) { ?>
        <script>$(document).ready(function () {
            window.location='<?= $form->return_link ?>'
        });
        </script>
    <? }; ?>
    <?
};

$form->Draw();
?>
<HR>
<?
if (!isset($_GET['page']))
    $_GET['page'] = 1;
if (!isset($_GET['filter']))
    $_GET['filter'] = Array();

if (isset($_GET['group']))
    $_GET['filter']['bgroup'] = $_GET['group'];

$table = new HFormTable(Array("table" => "system_data_block",
            "filter" => $_GET['filter'],
            "page" => $_GET['page'],
            "edit_link" => "block_edit.php?group=" . $_GET['group'] . "&dblock=#NAME#",
            "add_link" => "block_add.php?group=" . $_GET['group'],
            "delete_link" => "?group=" . $_GET['group'] . "&delete=#NAME#"));
if (isset($_GET['parent']))
    $table->delete_link_base.="&parent=" . $_GET['parent'];
$table->Add(Array("name" => "id", "caption" => "id", "type" => "label"));
$table->Add(Array("name" => "caption", "caption" => "Название", "type" => "short_text"));
$table->Add(Array("name" => "name", "caption" => "Код", "type" => "short_text"));
$table->Add(Array("name" => "sort", "caption" => "Сортировка", "type" => "sort"));


$table->Draw();
?>&nbsp;
<script>
    table="system_data_block";
</script>