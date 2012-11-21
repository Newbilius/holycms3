<?php
require_once("../engine.php");

if (!$H_USER->IsAdmin())
SystemAlertFatal("Недостаточно прав.");

global $_global_bread;
$_global_bread[] = Array("Настройки доступа");

//получить список
$users_groups_rs = new DBlockElement("system_user_groups");
$users_groups_rs->GetList();
$users_groups = $users_groups_rs->GetFullList();

if (isset($_POST['go'])) {
    foreach ($users_groups as $ug_save){
        $update_values=array();
        $update_values['read']=$_POST[$ug_save['name']]['read'];
        $update_values['add']=$_POST[$ug_save['name']]['add'];
        $update_values['edit']=$_POST[$ug_save['name']]['edit'];
        $update_values['delete']=$_POST[$ug_save['name']]['delete'];
        
        if (!isset($update_values['read'])) $update_values['read']="";
        if (!isset($update_values['add'])) $update_values['add']="";
        if (!isset($update_values['edit'])) $update_values['edit']="";
        if (!isset($update_values['delete'])) $update_values['delete']="";
        
        //$users_groups_rs->sql->debug=true;
        $users_groups_rs->Update($ug_save['id'], $update_values,false);
    };
    $users_groups_rs->GetList();
    $users_groups = $users_groups_rs->GetFullList();
    //preprint($_POST);
    //preprint($users_groups);
    /*
     * <input onclick="var checkbox = $(this).is(':checked');$('.<?=$check_name?>').attr('checked',checkbox);" type="checkbox" class="<?=$check_name?>">
     */
};

$full_user_groups_access=Array();
foreach ($users_groups as $_ug)
{
    $full_user_groups_access[$_ug['name']]['read']=explode(";",$_ug['read']);
    $full_user_groups_access[$_ug['name']]['add']=explode(";",$_ug['add']);
    $full_user_groups_access[$_ug['name']]['edit']=explode(";",$_ug['edit']);
    $full_user_groups_access[$_ug['name']]['delete']=explode(";",$_ug['delete']);
};

//получить список групп датаблоков
$groups = new DBlockGroup();
$groups->GetList();
$groups_list = $groups->GetFullList();

$fields = new DBlockFields();

$blocks = new DBlock;

$cols_count = 1;
$groups_count = count($users_groups);
?>
<table class="table table-striped table-bordered table-hover table-condensed">
    <thead>
        <tr>
            <th rowspan="2">Название</th>
            <?
            foreach ($users_groups as $_u_group) {
                $cols_count+=4;
                ?>
                <th colspan="4" style="text-align:center;"><center><a style="cursor:pointer" onclick="$('.group_<?=$_u_group['name']?>').attr('checked', '1')" href=#><?= $_u_group['caption'] ?></a> <a style="cursor:pointer" onclick="$('.group_<?=$_u_group['name']?>').removeAttr('checked')" href="#">[&ndash;]</a></center></th>
            <? }; ?>
        </tr>
        <tr>
            <?
            $top_types=Array("read","add","edit","delete");
            ?>
            <?
            foreach ($users_groups as $_u_group) {
                foreach ($top_types as $_top_types){
                ?>
                <th><a style="cursor:pointer" onclick="$('.group_<?=$_u_group['name']?>.type_<?=$_top_types?>').attr('checked', '1')" href="#"><?=$_top_types?></a> <a style="cursor:pointer" onclick="$('.group_<?=$_u_group['name']?>.type_<?=$_top_types?>').removeAttr('checked')" href="#">[&ndash;]</a></th>
            <? };}; ?>            
        </tr>
    </thead>
    <tbody>
    <form method="post">
        <input type="hidden" name="go" value="1">
        <?
        foreach ($groups_list as $_group) {
            $check_name = "check_" . $_group['name'];
            ?>
            <tr>
                <td colspan="<? echo $cols_count ?>">
                    <? echo $_group['caption'] ?>
                </td>
            </tr>
            <? //preprint($users_groups);?>
            <?
            $blocks->GetListByGroup($_group['id']);
            $block_list = $blocks->GetFullList();
            foreach ($block_list as $_block) {
                ?>
                <tr>
                    <td>
                        <a style="cursor:pointer" onclick="$('.block_<?=$_block['name']?>').attr('checked', '1');return false;" href=#><? echo $_block['caption'] ?></a>
                        <a onclick="$('.block_<?=$_block['name']?>').removeAttr('checked')" href="#">[&ndash;]</a>
                    </td>
                    <? foreach ($users_groups as $_ug) { ?>
                        <td style="text-align:center;">
                            <label class="checkbox">
                                <input class="block_<?=$_block['name']?> group_<?=$_ug['name']?> type_read" <?if (in_array($_block['name'], $full_user_groups_access[$_ug['name']]['read'])) echo "checked";?> type="checkbox" name="<?= $_ug['name'] ?>[read][]" value="<?= $_block['name'] ?>">
                            </label>
                        </td>
                        <td style="text-align:center;">
                            <label class="checkbox">
                                <input class="block_<?=$_block['name']?> group_<?=$_ug['name']?> type_add" <?if (in_array($_block['name'], $full_user_groups_access[$_ug['name']]['add'])) echo "checked";?> type="checkbox" name="<?= $_ug['name'] ?>[add][]" value="<?= $_block['name'] ?>">
                            </label>
                        </td>
                        <td style="text-align:center;">
                            <label class="checkbox">
                                <input class="block_<?=$_block['name']?> group_<?=$_ug['name']?> type_edit" <?if (in_array($_block['name'], $full_user_groups_access[$_ug['name']]['edit'])) echo "checked";?> type="checkbox" name="<?= $_ug['name'] ?>[edit][]" value="<?= $_block['name'] ?>">
                            </label>
                        </td>
                        <td style="text-align:center;">
                            <label class="checkbox">
                                <input class="block_<?=$_block['name']?> group_<?=$_ug['name']?> type_delete" <?if (in_array($_block['name'], $full_user_groups_access[$_ug['name']]['delete'])) echo "checked";?> type="checkbox" name="<?= $_ug['name'] ?>[delete][]" value="<?= $_block['name'] ?>">
                            </label>
                        </td>
                    <? }; ?>
                </tr>
                <?
            };
            ?>
            <?
        }
//получить список датаблоков в группе
        ?>
        </tbody>
</table>
<input name=submit type=submit value="Сохранить" style="width:40%;HEIGHT:28px;" class="btn btn-success">
</form>