<?
$user_ifno_holy = $H_USER->GetInfo();
if (!$user_ifno_holy['block_control'])
    die("������������ ����");

global $_global_bread;
$_global_bread[] = Array("����� ������", "/engine/admin/group_list.php");

if (isset($_GET['delete'])) {
    $gr = new DBlockGroup();
    $gr->Delete($_GET['delete']);
    ?>
    <span style="color:green;">
        ������� <?= $_GET['delete'] ?> �����
    </span>
    <?
};

if (!isset($_GET['page']))
    $_GET['page'] = 1;
if (!isset($_GET['filter']))
    $_GET['filter'] = Array();
$table = new HFormTable(Array("table" => "system_data_block_group",
            "edit_link" => "blocks_list.php?group=#ID#",
            "add_link" => "group_add.php",
            "delete_link" => "group_list.php?delete=#ID#"));


if (isset($_GET['parent']))
    $table->delete_link_base.="&parent=" . $_GET['parent'];
$table->Add(Array("name" => "id", "caption" => "id", "type" => "label"));
$table->Add(Array("name" => "caption", "caption" => "��������", "type" => "short_text"));
$table->Add(Array("name" => "name", "caption" => "���", "type" => "short_text"));
$table->Add(Array("name" => "sort", "caption" => "����������", "type" => "sort"));

//@todot ����� ��� "����� ������ ������"
$table->Draw();
?>&nbsp;
<script>
    table="system_data_block_group";
</script>