<?php

class HFormAdd {

    var $sql;
    var $columns;
    var $data;
    var $token;
    var $e;
    var $result;
    var $return_link;

    function DrawBefore() {
        $meta = false;
        //preprint($this->columns);
        $show_add_top_buttons=false;
        foreach ($this->columns as $item)
            if (isset($item['meta']))
                if ($item['meta'])
                {
                    $meta = true;
                    $show_add_top_buttons=true;
                    
                    };
        ?>
        <form method=post enctype="multipart/form-data" onSubmit="return DeleteTmp();">
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
                </ul>
                <HR>
                <?
            };
            ?>
            <form method=post enctype="multipart/form-data" onSubmit="return DeleteTmp();">
                <input type=hidden name=token value=<?= $this->token ?>>
                <table width=90% border=0>
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
                            <input type=submit value="Добавить" style="float:left;height:26px;width:40%;margin-right:20px;" class="btn btn-success">
                            <input onclick="window.location='<?= $this->return_link ?>';" type=button value="Отменить" style="float:left;height:26px;width:40%;" class="btn btn-warning">
                        </td>
                    </tr>
                </table>
        <?
    }

    function DrawErrors() {
        foreach ($this->e as $error) {
            ?>
                    <span style="color:red;">
                    <?= $error ?>
                    </span><BR>
                    <?
                };
            }

            function HFormAdd($values) {
                $table = $values['table'];

                $this->token = "add";
                $this->sql = new HolySQL($table);
                $this->result = false;
            }

            function GO() {
                if (!isset($_POST['name']))
                    $_POST['name'] = "";
                if ($_POST['name'] == "")
                    if (isset($_POST['caption']))
                        $_POST['name'] = strtolower(rus2translit($_POST['caption']));
                foreach ($this->columns as $item) {
                    if ($item['multiple'])
                        if (isset($_POST[$item['name']])) {
                            $tmp_res = $_POST[$item['name']];
                            $_POST[$item['name']] = $_POST[$item['name']][0];
                        };

                    if ((!isset($_POST[$item['name']])) && ($item['required']))
                        $this->e[] = "Поле <b>[" . $item['caption'] . "]</b> обязательно для заполнения!";
                    else
                    if (isset($_POST[$item['name']]))
                        if ($_POST[$item['name']] == "")
                            if ($item['required'])
                                $this->e[] = "Поле <b>[" . $item['caption'] . "]</b> обязательно для заполнения!";

                    if ($item['multiple'])
                        if (isset($_POST[$item['name']]))
                            $_POST[$item['name']] = $tmp_res;
                };
                if (count($this->e) > 0)
                    $this->DrawErrors();
                else
                    $this->result = true;
            }

            function Draw() {
                if (isset($_POST['token']))
                    $this->GO();
                $this->DrawBefore();
                foreach ($this->columns as $item) {
                    ?>
                    <tr <? if (isset($item['meta'])) if ($item['meta']) { ?>class="meta_tr"<? } else { ?>class="no_meta_tr"<? }; ?> <? if (!$item['visible']) { ?>style="display:none;"<? } ?>>
                        <td valign=top>
                            <b><?= str_replace(" ", "&nbsp;", $item['caption']) ?></b>
                        </td>
                        <td valign=top>
                    <?
                    $path = FOLDER_ROOT . "/site/forms/" . $item['type'] . ".php";
                    $path0 = FOLDER_ROOT . "/engine/api/forms/" . $item['type'] . ".php";
                    if (!file_exists($path)) {
                        $path = $path0;
                    };
                    if (!file_exists($path)) {
                        $item['type'] = "text";
                        $path = FOLDER_ROOT . "/engine/api/forms/" . $item['type'] . ".php";
                    };
                    include_once($path);
                    $name = "CForm_" . $item['type'];
                    $obj = new $name;
                    $item['add_values'].=";hidden_add";
                    $obj->Add($item['name'], $item['add_values'], $item['multiple']);
                    if ($item['multiple']) {
                        ?>
                                <div style="border:0px solid green;width:100%;">
                                    Порядок вывода: <input name=<?= $item['name'] ?>[SORT][] value=100>
                                </div>
                                <?
                            };
                            ?>

                            <?
                            if ($item['multiple']) {
                                ?>
                                <div class="delete_before_add" id=hiddden_<?= $item['name'] ?> style="display:none;">
                                <?
                                $obj->Add($item['name'], $item['add_values'], $item['multiple']);
                                ?>
                                    <div style="border:0px solid green;width:100%;;">
                                        Порядок вывода: <input name=<?= $item['name'] ?>[SORT][] value=100>
                                    </div>
                                </div>
                                <BR>
                                <div id=adddiv_<?= $item['name'] ?>><a class="btn btn-primary" onclick="AddElementDiv('#hiddden_<?= $item['name'] ?>','#adddiv_<?= $item['name'] ?>','<?= $item['name'] ?>');">Добавить ещё</a></div>
                                <?
                            };
                            ?>
                    </tr>
                                <?
                            };
                            $this->DrawAfter();
                            return $this->result;
                        }

                        function Add($values) {
                            $name = $values['name'];
                            $caption = $values['caption'];
                            $type = $values['type'];
                            if (!isset($values['add_values']))
                                $values['add_values'] = "";
                            if (!isset($values['required']))
                                $values['required'] = 0;
                            if (!isset($values['multiple']))
                                $values['multiple'] = 0;
                            if (!isset($values['visible']))
                                $values['visible'] = 1;
                            if (!isset($values['meta']))
                                $values['meta'] = 0;
                            $add_values = $values['add_values'];
                            $this->columns[] = array("name" => $name, "caption" => $caption, "type" => $type, "add_values" => $add_values, 'required' => $values['required'], 'multiple' => $values['multiple'], 'visible' => $values['visible'], 'meta' => $values['meta']);
                        }

                    }
                    ?>