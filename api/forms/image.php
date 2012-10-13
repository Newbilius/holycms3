<?
$_global_counter = Array();
$_image_counter = Array();

class CForm_image extends CForm_Text {

    function View($name, $data, $add, $multiple = false) {
        if ($data[$name] != "") {
            ?>
            <input type="hidden" name=<?= $name ?>_OLD value="<?= $data[$name] ?>" />
            <? GetImageResizeCache($data[$name], 100, 100) ?><BR>
        <? }
        ?>
        <?
    }

    function Add($name, $add, $multiple = false) {
        ?>
        <div class="inner_foto">
            <ul class="tabs tabs2 nav nav-pills" style="padding-left:0px;">
                <li class="fileimg1_button_li active" style="cursor:pointer;">
                    <a class="fileimg1_button">Загрузить файл</a>
                </li>
                <li style="cursor:pointer;" class="fileimg2_button_li"><a class="fileimg2_button">Выбрать файл</a></li>
            </ul>

            <input class="fileimg1" type="file" name=<?= $name ?><? if ($multiple) { ?>[]<? }; ?> />
            <input class="fileimg2" type="text" name=<?= $name ?>_MANUAL<? if ($multiple) { ?>[]<? }; ?> value="" /><a class="btn file_img_select fileimg2" style="margin-top:-0px;">Выбрать файл</a>			
        </div>
        <?
    }

    function Edit($name, $data, $add, $multiple = false) {
        ?>

        <div class="inner_foto">
            <ul class="tabs tabs2 nav nav-pills" style="padding-left:0px;">
                <li class="fileimg1_button_li active" style="cursor:pointer;">
                    <a class="fileimg1_button">Загрузить файл</a>
                </li>
                <li style="cursor:pointer;" class="fileimg2_button_li"><a class="fileimg2_button">Выбрать файл</a></li>
            </ul>
            <?
            global $_global_counter;
            if ($data[$name] != "") {
                if (!isset($_global_counter[$name]))
                    $_global_counter[$name] = -1;
                if ($multiple)
                    $_global_counter[$name]++;
                ?>
                <input type="hidden" name=<?= $name ?>_OLD<? if ($multiple) { ?>[<?= $_global_counter[$name] ?>]<? }; ?> value="<?= $data[$name] ?>" />

                <? GetImageResizeCache($data[$name], 200, 200) ?><BR>
            <? }
            ?>

            <input class="fileimg2" type="text" name=<?= $name ?>_MANUAL<? if ($multiple) { ?>[<?
            if ($data
                    [$name] != "")
                echo $_global_counter[$name]
                    ?>]<? }; ?> value="" />
            <a class="btn file_img_select fileimg2" style="margin-top:-0px;">Выбрать файл</a>

            <input class="fileimg1" type="file" name=<?= $name ?><? if ($multiple) { ?>[<? if ($data[$name] != "") echo $_global_counter[$name] ?>]<? }; ?> />
            <? if ($data[$name] != "") { ?>
                <BR><a href='<?= $data[$name] ?>' target=_new>Полный размер</a><BR>
                <input type="checkbox" name=<?= $name ?>_DELETE<? if ($multiple) { ?>[<?= $_global_counter[$name] ?>]<? }; ?> value="<?= $data[$name] ?>" /> Удалить<BR><BR>
            <? } ?>
        </div>
        <?
    }

    function BeforeAdd($name, $value, $add) {
        $file_name = "";
        $add = explode(";", $add);
        $add = end($add);
        if (isset($_POST[$name . '_MANUAL'][$add]))
            if ($_POST[$name . '_MANUAL'][$add])
                if (file_exists($_SERVER['DOCUMENT_ROOT'] . $_POST[$name . '_MANUAL'][$add]))
                    $value = PrepareFile($_SERVER['DOCUMENT_ROOT'] . $_POST[$name . "_MANUAL"][$add], true);

        if (isset($_POST[$name . '_MANUAL']))
            if ($_POST[$name . '_MANUAL'])
                if ($_POST[$name . '_MANUAL'] != "")
                    if (file_exists($_SERVER['DOCUMENT_ROOT'] . $_POST[$name . '_MANUAL']))
                        $value = PrepareFile($_SERVER['DOCUMENT_ROOT'] . $_POST[$name . "_MANUAL"], true);

        if (isset($value['error']))
            if ($value['error'] == 0)
                if ($value['tmp_name'] != "") {
                    $exp = explode(".", $value['name']);
                    $extens = end($exp);
                    $file_name = MD5(time() . $value['name'] . rand()) . "." . $extens;


                    $fold1 = substr($file_name, 0, 2);
                    $fold2 = substr($file_name, 2, 2);

                    if (!file_exists(FOLDER_UPLOAD))
                        mkdir(FOLDER_UPLOAD);
                    if (!file_exists(FOLDER_IMAGE))
                        mkdir(FOLDER_IMAGE);
                    if (!file_exists(FOLDER_IMAGE . $fold1))
                        mkdir(FOLDER_IMAGE . $fold1);
                    if (!file_exists(FOLDER_IMAGE . $fold1 . "/" . $fold2))
                        mkdir(FOLDER_IMAGE . $fold1 . "/" . $fold2);

                    $file_name = URI_IMAGE . $fold1 . "/" . $fold2 . "/" . $file_name;


                    if (isset($value['move']))
                        rename($value['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $file_name);
                    else
                    if (isset($value['copy']))
                        copy($value['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $file_name);
                    else
                        move_uploaded_file($value['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $file_name);
                };
        return $file_name;
    }

    function AfterEdit($name, $value, $add, $multiple = false) {
        global $_global_counter;
        global $_image_counter;

        $file_name = "";
        if ($multiple) {
            if (!isset($_image_counter[$name]))
                $_image_counter[$name] = -1;
            if ($multiple)
                $_image_counter[$name]++;
            if (isset($_POST[$name . "_OLD"][$_image_counter[$name]]))
                $file_name = $_POST[$name . "_OLD"][$_image_counter[$name]];
        }
        else {
            if (isset($_POST[$name . "_OLD"]))
                $file_name = $_POST[$name . "_OLD"];
        };

        if (isset($value))
            if (isset($value['error']))
                if ($value['error'] == 0)
                    if ($value['tmp_name'] != "") {
                        $exp = explode(".", $value['name']);
                        $extens = end($exp);
                        $file_name = MD5(time() . $value['name'] . rand()) . "." . $extens;

                        $fold1 = substr($file_name, 0, 2);
                        $fold2 = substr($file_name, 2, 2);

                        if (!file_exists(FOLDER_UPLOAD))
                            mkdir(FOLDER_UPLOAD);
                        if (!file_exists(FOLDER_IMAGE))
                            mkdir(FOLDER_IMAGE);
                        if (!file_exists(FOLDER_IMAGE . $fold1))
                            mkdir(FOLDER_IMAGE . $fold1);
                        if (!file_exists(FOLDER_IMAGE . $fold1 . "/" . $fold2))
                            mkdir(FOLDER_IMAGE . $fold1 . "/" . $fold2);

                        $file_name = URI_IMAGE . $fold1 . "/" . $fold2 . "/" . $file_name;

                        if (isset($value['move']))
                            rename($value['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $file_name);
                        else
                        if (isset($value['copy']))
                            copy($value['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $file_name);
                        else
                            move_uploaded_file($value['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $file_name);
                    };
        if ($multiple) {
            if (isset($_POST[$name . "_DELETE"][$_image_counter[$name]])) {
                $file_name = "";
                if ($_POST[$name . "_OLD"][$_image_counter[$name]] != "")
                    @unlink($_SERVER['DOCUMENT_ROOT'] . $_POST[$name . "_OLD"][$_image_counter[$name]]);
            };


            //preprint($_POST[$name."_MANUAL"]);
            if (isset($_POST[$name . "_MANUAL"][$_image_counter[$name]]))
                if (($_POST[$name . "_MANUAL"][$_image_counter[$name]]))
                    if (file_exists($_SERVER['DOCUMENT_ROOT'] . $_POST[$name . "_MANUAL"][$_image_counter[$name]])) {
                        $file_name = $_POST[$name . "_MANUAL"][$_image_counter[$name]];
                        $exp = explode(".", $file_name);
                        $extens = end($exp);
                        $newname = MD5(time() . $_POST[$name . "_MANUAL"][$_image_counter[$name]] . rand()) . "." . $extens;

                        $fold1 = substr($newname, 0, 2);
                        $fold2 = substr($newname, 2, 2);

                        if (!file_exists(FOLDER_UPLOAD))
                            mkdir(FOLDER_UPLOAD);
                        if (!file_exists(FOLDER_IMAGE))
                            mkdir(FOLDER_IMAGE);
                        if (!file_exists(FOLDER_IMAGE . $fold1))
                            mkdir(FOLDER_IMAGE . $fold1);
                        if (!file_exists(FOLDER_IMAGE . $fold1 . "/" . $fold2))
                            mkdir(FOLDER_IMAGE . $fold1 . "/" . $fold2);

                        $newname = URI_IMAGE . $fold1 . "/" . $fold2 . "/" . $newname;

                        copy($_SERVER['DOCUMENT_ROOT'] . $file_name, $_SERVER['DOCUMENT_ROOT'] . $newname);
                        $file_name = $newname;
                    };
        }
        else {
            if (isset($_POST[$name . "_DELETE"])) {
                $file_name = "";
                if ($_POST[$name . "_OLD"] != "")
                    @unlink($_SERVER['DOCUMENT_ROOT'] . $_POST[$name . "_OLD"]);
            };
            if (isset($_POST[$name . "_MANUAL"]))
                if ($_POST[$name . "_MANUAL"])
                    if (file_exists($_SERVER['DOCUMENT_ROOT'] . $_POST[$name . "_MANUAL"])) {
                        $file_name = $_POST[$name . "_MANUAL"];
                        $exp = explode(".", $file_name);
                        $extens = end($exp);
                        $newname = MD5(time() . $_POST[$name . "_MANUAL"] . rand()) . "." . $extens;

                        $fold1 = substr($newname, 0, 2);
                        $fold2 = substr($newname, 2, 2);

                        if (!file_exists(FOLDER_UPLOAD))
                            mkdir(FOLDER_UPLOAD);
                        if (!file_exists(FOLDER_IMAGE))
                            mkdir(FOLDER_IMAGE);
                        if (!file_exists(FOLDER_IMAGE . $fold1))
                            mkdir(FOLDER_IMAGE . $fold1);
                        if (!file_exists(FOLDER_IMAGE . $fold1 . "/" . $fold2))
                            mkdir(FOLDER_IMAGE . $fold1 . "/" . $fold2);

                        $newname = URI_IMAGE . $fold1 . "/" . $fold2 . "/" . $newname;

                        copy($_SERVER['DOCUMENT_ROOT'] . $file_name, $_SERVER['DOCUMENT_ROOT'] . $newname);
                        $file_name = $newname;
                    };
        };

        return $file_name;
    }

    function Filter($name) {
        
    }

}
?>