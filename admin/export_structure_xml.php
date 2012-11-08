<?php
require_once("../engine.php");

global $_global_bread;
$_global_bread[] = Array("Экспорт структуры в XML");

//получить список групп датаблоков
$groups = new DBlockGroup();
$groups->GetList();
$groups_list = $groups->GetFullList();

$blocks = new DBlock;

if (isset($_POST['go'])) {
    if (count($_POST['block']) == 0) {
        ?>
<div class="alert alert-error fade in" style="max-width:400px;">
<button type="button" class="close" data-dismiss="alert">&times;</button>
Не выбран ни один блок данных, нечего экспортировать.
</div>
<?
    }
};
?>
<form method="post">
    <input type="hidden" name="go" value="1">
    <ul>
<?
foreach ($groups_list as $_group) {
    ?>
            <li>
            <? echo $_group['caption'] ?>
                <ul>
                <?
                $blocks->GetListByGroup($_group['id']);
                $block_list = $blocks->GetFullList();
                foreach ($block_list as $_block) {
                    ?>
                        <label class="checkbox">
                            <input type="checkbox" name=block[] value="<? echo $_block['id'] ?>"><? echo $_block['caption'] ?><br>
                        </label>

        <?
    };
    ?>
                </ul>
            </li>
    <?
}
//получить список датаблоков в группе
?>
    </ul>
    <input name=submit type=submit value="Запустить экспорт" style="width:40%;HEIGHT:28px;" class="btn btn-success">
</form>