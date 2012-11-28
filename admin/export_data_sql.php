<?php
require_once("../engine.php");

global $_global_bread;
$_global_bread[] = Array("Экспорт данных в SQL");

//получить список групп датаблоков
$groups = new DBlockGroup();
$groups->GetList();
$groups_list = $groups->GetFullList();

$fields = new DBlockFields();

$blocks = new DBlock;

if (isset($_POST['go'])) {
    if (count($_POST['block']) == 0) {
        ?>
        <div class="alert alert-error fade in" style="max-width:400px;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            Не выбран ни один блок данных, нечего экспортировать.
        </div>
        <?
    } else {
        if (!file_exists($_SERVER['DOCUMENT_ROOT'] . "/upload/"))
            mkdir($_SERVER['DOCUMENT_ROOT'] . "/upload/");
        if (!file_exists($_SERVER['DOCUMENT_ROOT'] . "/upload/export/"))
            mkdir($_SERVER['DOCUMENT_ROOT'] . "/upload/export/");

        $fname = '/upload/export/blocks_' . date("d.m.Y_H_i_s") . '.sql';
        $fname_full = $_SERVER['DOCUMENT_ROOT'] . $fname;

        backup_database_tables($_POST['block'], false, $fname_full,true);
        ?>
        <div class="alert alert-success fade in" style="max-width:400px;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            Файл экспорта сохранен. <a href="<?= $fname ?>">Скачать файл.</a>
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
            $check_name="check_".$_group['name'];
            ?>
            <li style="list-style:none;">
                <label class="checkbox">
                    <input onclick="var checkbox = $(this).is(':checked');$('.<?=$check_name?>').attr('checked',checkbox);" type="checkbox" class="<?=$check_name?>"><? echo $_group['caption'] ?><br>
                </label>
                <ul>
                    <?
                    $blocks->GetListByGroup($_group['id']);
                    $block_list = $blocks->GetFullList();
                    foreach ($block_list as $_block) {
                        ?>
                        <label class="checkbox">
                            <input class="<?=$check_name?>" type="checkbox" name=block[] value="<? echo $_block['name'] ?>"><? echo $_block['caption'] ?><br>
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