<?
$_global_bread[] = Array("Настройки сайта", "/engine/admin/options.php");
$types = new DBlockElement("options_class");
$values = new DBlockElement("options");

$types->GetList();
while ($tmp = $types->GetNext())
    $arTypes[$tmp['id']] = $tmp;

$values->GetList("parent=0 AND folder=0");
while ($tmp = $values->GetNext())
    $arValues[$tmp['id']] = $tmp;

//подготовка данных
if (isset($_POST['form_go']))
    if ($_POST['form_go'] == 512) {
        //preprint($_FILES);
        //preprint($_POST);
        foreach ($arValues as $val) {
            $id = $val['id'];
            unset($val['id']);

            if (isset($_POST[$id]))
                if (is_array($_POST[$id]))
                    if (isset($_POST[$id]['error'])) {
                        if ($_POST[$id]['error'] == 0) {
                            $old_name = $_POST[$id]['name'];
                            $exp = explode(".", $old_name);
                            $extens = end($exp);
                            $file_name = MD5(time() . $old_name . rand()) . "." . $extens;

                            move_uploaded_file($_POST[$id]['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . "/upload/" . $file_name);
                            $val['hvalue'] = $file_name;
                        };
                    };

            if (!isset($_POST[$id]))
                $_POST[$id] = "";

            if (!is_array($_POST[$id])) {
                $val['hvalue'] = $_POST[$id];
            };
            $values->Update($id, $val, true);
        };
        unset($arValues);
        $values->GetList("parent=0 AND folder=0");
        while ($tmp = $values->GetNext())
            $arValues[$tmp['id']] = $tmp;
    };
?>
<BR>
<form method=post enctype="multipart/form-data">
    <input type=submit value="Сохранить" style="width:100%;HEIGHT:35PX;" class="btn">
    <BR>
    <input type=hidden name="form_go" value="512">
    <table width=100% border=0 cellpadding=0 cellspacing=0 id=tableform name=tableform class=tableform>
        <tr>
            <td>
                Свойство
            </td>
            <td>
                Значение
            </td>
        </tr>
<?
//вывод значений
$odd = 3;
foreach ($arValues as $val) {
    $odd++;
    if ($val['options_class'] == "")
        $val['options_class'] = 1;
    ?>
            <tr <? if ($odd % 2 == 0) { ?>class="odd"<? }; ?>>
                <td width=200 valign=top style="padding-top:5px;padding-left:5px;"><?= $val['caption'] ?> [<?= $val['name'] ?>]</td>
                <td>
            <?
            switch ($arTypes[$val['options_class']]['name']) {
                case "text2":
                    ?>
                            <textarea name="<?= $val['id'] ?>" style="width:90%;height:190px;"><?= $val['hvalue'] ?></textarea>
                            <?
                            break;

                        case "file":
                            ?>

                            <input name="<?= $val['id'] ?>" type=file>
                            <? if ($val['hvalue']) { ?>
                            <br><a href="/upload/<?= $val['hvalue'] ?>"><? echo $val['hvalue']?></a><br>
            <? }; ?>
                            <?
                            break;
                        
                        case "pic":
                            ?>

                            <input name="<?= $val['id'] ?>" type=file><BR>
                            <? if ($val['hvalue']) { ?>
                                <img src="/upload/<?= $val['hvalue'] ?>">
            <? }; ?>
                            <?
                            break;
                        case "wis":
                            ?>
                            <textarea class="wisiwig" id="s<?= $val['id'] ?>" name="<?= $val['id'] ?>" style="width:90%;height:190px;"><?= $val['hvalue'] ?></textarea>
            <?
            break;


        case "check":
            ?>

                            <input type=checkbox name="<?= $val['id'] ?>" <? if ($val['hvalue']) { ?>checked<? }; ?>>

            <? break;

        default:
            ?>
                            <input style="width:90%;" type=text value="<?= $val['hvalue'] ?>" name="<?= $val['id'] ?>">
                            <?
                            break;
                    };
                    ?>
                </td>
            </tr>
            <?
        };
        ?>
    </table>
    <br><input type=submit value="Сохранить" style="width:100%;HEIGHT:35PX;" class="btn">
</form>
<? include_once FOLDER_ENGINE."api/forms/wysiwyg_html.php";?>
<? CForm_wysiwyg_html::HTML("");?>