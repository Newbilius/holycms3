<?php

class HFormEdit {

    var $sql;
    var $columns;
    var $data;
    var $token;
    var $result;
    var $filter;
    var $e;
    var $return_link;

    function DrawErrors() {
        foreach ($this->e as $error) {
            ?>
            <span style="color:red;">
                <?= $error ?>
            </span><BR>
            <?
        };
    }

    function GO() {
        if (!isset($_POST['name']))
            $_POST['name'] = "";
        if ($_POST['name'] == "")
            if (isset($_POST['caption']))
                $_POST['name'] = strtolower(rus2translit($_POST['caption']));

        if (isset($_POST['token']))
            if ($_POST['token'] == $this->token) {
                foreach ($this->columns as $item) {
                    if ((!isset($_POST[$item['name']])) && ($item['required']))
                        $this->e[] = "Поле <b>[" . $item['caption'] . "]</b> обязательно для заполнения!";
                    if (isset($_POST[$item['name']]))
                        if ($_POST[$item['name']] == "")
                            if ($item['required'])
                                if ($item['type'] == "pass") {
                                    unset($_POST[$item['name']]);
                                }else
                                    $this->e[] = "Поле <b>[" . $item['caption'] . "]</b> обязательно для заполнения!";
                };
                if (count($this->e) > 0)
                    $this->DrawErrors();
                else {
                    $this->result = true;
                    return $this->result;
                };
            };
    }

    function Reload() {
        unset($this->data);
        $this->data = $this->sql->SelectOnce($this->filter);
    }

    function DrawBefore() {
        $meta = false;
        $show_add_top_buttons=false;
        $show_add_folder_buttons=false;
        if ($this->data['folder'])
                {
                    $show_add_top_buttons=true;
                    $show_add_folder_buttons=true;
                };
        foreach ($this->columns as $item)
            if (isset($item['meta']))
                if ($item['meta'])
                {
                    $meta = true;
                    $show_add_top_buttons=true;
                };
        ?>
        <form method=post enctype="multipart/form-data">
            <input type=hidden name=token value=<?= $this->token ?>>
        <?
        if ($show_add_top_buttons) {
            ?>
                <ul class="tabs tabs_item nav nav-pills" style="padding-left:0px;">
                    <li class="active">
                        <a rel=tab1 href="#">Основные свойства</a>
                    </li>
                   <?if ($meta){?>
                    <li class="">
                        <a rel=tab2 href="#">Мета-тэги</a>
                    </li>
                    <?};?>
                    <?if ($show_add_folder_buttons){?>
                    <li class="">
                        <a href="elements_list.php?dblock=<?echo $_GET['dblock']?>&parent=<?echo $_GET['id']?>">Содержимое папки</a>
                    </li>                    
                    <?};?>
                </ul>
                <HR>
            <?
        };
        ?>
            <table width=90% border=0 class="item_table">
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
        <?
    }

    function DrawAfter() {
        ?>
                <tr>
                    <td colspan=3>
                        <input name=submit type=submit value="Сохранить" style="float:left;height:26px;width:30%;margin-right:10px;" class="btn btn-success">
                        <input name=submit type=submit value="Применить" style="height:26px;width:30%;margin-right:10px;" class="btn btn-primary">
                        <input name=submit onclick="window.location='<?= $this->return_link ?>';" type=button value="Отменить" style="height:26px;width:30%;margin-right:10px;" class="btn btn-warning">
                    </td>
                </tr>
            </table>
        </form>
        <?
    }

    function HFormEdit($values) {
        $table = $values['table'];
        $id_or_name = $values['id'];

        $this->token = "edit" . $id_or_name;
        $this->sql = new HolySQL($table);

        if (is_numeric($id_or_name))
            $filter = "id=" . $id_or_name;
        else
            $filter = "name='" . $id_or_name . "'";

        $this->filter = $filter;

        $this->data = $this->sql->SelectOnce($filter);

        $this->result = false;
    }

    function Draw() {
        $this->DrawBefore();
        foreach ($this->columns as $item) {
            ?>
            <tr <? if (isset($item['meta'])) if ($item['meta']) { ?>class="meta_tr"<? } else { ?>class="no_meta_tr"<? }; ?> <? if (!$item['visible']) { ?>style="display:none;"<? } ?>>
                <td valign=top>
                    <b><?= str_replace(" ", "&nbsp;", $item['caption']) ?></b>
                </td>
                <td valign=top>
            <?
            $path = $_SERVER['DOCUMENT_ROOT'] . "/site/forms/" . $item['type'] . ".php";
            $path0 = $_SERVER['DOCUMENT_ROOT'] . "/engine/api/forms/" . $item['type'] . ".php";
            if (!file_exists($path)) {
                $path = $path0;
            };
            if (!file_exists($path)) {
                $item['type'] = "text";
                $path = $_SERVER['DOCUMENT_ROOT'] . "/engine/api/forms/" . $item['type'] . ".php";
            };
            include_once($path);

            $name = "CForm_" . $item['type'];

            $obj = new $name;
            $obj->SetBlock($_GET['dblock']);
            if ($item['multiple']) {
                global $_global_counter;
                $many_items = explode(";", $this->data[$item['name']]);
                $tmp_counter = -1;

                foreach ($many_items as $mi)
                    if ($mi != "") {
                        $tmp_counter++;

                        $mi_tmp[$item['name']] = str_replace("[&&&&&&]", ";", $mi);
                        echo "<div style='border:0px solid red;width:100%;'>";
                        $obj->Edit($item['name'], $mi_tmp, $item['add_values'], $item['multiple']);
                        echo "</div>";
                        ?>
                                <div style="border:0px solid green;width:100%;">
                                    Порядок вывода: <input name=<?= $item['name'] ?>[SORT][<?= $tmp_counter ?>] value=100>
                                </div>
                                <?
                            };
                        $mi_tmp[$item['name']] = "";
                        /* #adddiv_ */
                        ?>
                        <div id=hiddden_<?= $item['name'] ?> style="display:none;">

                        <?
                        $item['add_values'].=";hidden_add";
                        $obj->Edit($item['name'], $mi_tmp, $item['add_values'], $item['multiple']);
                        ?>
                            <div style="border:0px solid green;">
                                Порядок вывода: <input name=<?= $item['name'] ?>[SORT][] value=100>
                            </div>
                        </div>
                        <div id=adddiv_<?= $item['name'] ?>><a class="btn btn-primary" onclick="AddElementDiv('#hiddden_<?= $item['name'] ?>','#hiddden_<?= $item['name'] ?>','<?= $item['name'] ?>');">Добавить ещё</a></div>
                        <?
                    }
                    else
                        $obj->Edit($item['name'], $this->data, $item['add_values'], $item['multiple']);
                    ?>
            </tr>
                        <?
                    };
                    $this->DrawAfter();
                }

                function Add($values) {
                    $name = $values['name'];
                    $caption = $values['caption'];
                    $type = $values['type'];
                    if (!isset($values['add_values']))
                        $values['add_values'] = "";
                    if (!isset($values['multiple']))
                        $values['multiple'] = 0;
                    if (!isset($values['visible']))
                        $values['visible'] = 1;
                    $add_values = $values['add_values'];
                    if (!isset($values['required']))
                        $values['required'] = 0;
                    if (!isset($values['meta']))
                        $values['meta'] = 0;
                    $this->columns[] = array("name" => $name, "caption" => $caption, "type" => $type, "add_values" => $add_values, 'required' => $values['required'], 'multiple' => $values['multiple'], 'visible' => $values['visible'], 'meta' => $values['meta']);
                }

            }
            ?>