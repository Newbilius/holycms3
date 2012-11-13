<?php
require_once("../engine.php");

global $_global_bread;
$_global_bread[] = Array("������ ��������� �� XML");

$groups = new DBlockGroup();
$groups->GetList();
$groups_list = $groups->GetFullList();

if (isset($_POST['go'])) {
    if ($_POST['xml_file']['error'] == 0) {
        $data = PrepareXMLFromUrl($_POST['xml_file']['tmp_name']);
        //@todo ������� ���������������
        $data = recursive_iconv("utf-8", "windows-1251", $data);

        foreach ($data as $block_name => $block_list) {
            $new_block = new DBlock();
            echo "������ ���� " . $block_name . "<BR>";
            $new_block_values = array();
            foreach ($block_list as $block_fields_code => $block_fields)
                if ($block_fields_code != "fields") {
                    $new_block_values[$block_fields_code] = $block_fields;
                };
            $new_block_values['group'] = $_POST['group_in_id'];
            unset($new_block_values['bgroup']);
            $new_block->Create($new_block_values);
            //$new_block_id=$new_block->sql->last_id;
            //������� ��������
            foreach ($block_list["fields"] as $_field) {
                $new_field_values = array();
                $NewField = new DBlockFields();
                foreach ($_field as $_field_p_name => $_field_p_value)
                    $new_field_values[$_field_p_name] = $_field_p_value;
                unset($new_field_values['id']);
                $new_field_values['data_block'] = $block_name;

                $NewField->Create($new_field_values);
                echo "������ �������� " . $new_field_values['name'] . " ����� " . $block_name . "<BR>";
            };
            echo "<BR>";
        };
        ?>
        <HR>
        <?
    }
};
?>

<form method=post enctype="multipart/form-data">
    <input type=hidden name=go value=1>
    �������� ������ ������:<br>
    <select name="group_in_id">
<?
foreach ($groups_list as $_group) {
    ?>
            <option value="<?= $_group['id'] ?>"><?= $_group['caption'] ?> [<?= $_group['name'] ?>]</option>
            <?
        }
        ?>
    </select>
    <br>
    ���� �������:<br>

    <input type=file name=xml_file>

    <input type=submit value="������ ������" class="btn">
</form>