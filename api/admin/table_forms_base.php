<?php

class HFormTable {

    var $sql;
    var $columns;
    var $edit_link_base;
    var $delete_link_base;
    var $add_link_base;
    var $add_link_base2;
    var $token;
    var $filter;
    var $sortfilter;
    var $count_on_page;
    var $page;
    var $sort_template;
    var $max_count;
    var $base_link;
    var $one_folder;
    var $folder_icon;
    var $edit_icon;
    var $delete_icon;
    var $add_folder_icon;
    var $edit_folder_icon;
    var $add_icon;
    var $sort_up_icon;
    var $sort_down_icon;
    var $hide_group_action;
    var $show_folders;
    var $show_code;
    var $show_count_list;
    var $folder_link;
    var $short_links;

    var $can_add;
    var $can_edit;
    var $can_delete;
    
    function SetPaginator($count, $page) {
        $this->count_on_page = $count;
        $this->page = $page;
        $this->max_count = $this->sql->GetCount();
        $this->Reload();
    }

    function DrawPaginator($template = "", $real_count) {
        if ($this->count_on_page > 0)
            IncludeComponent("system\page_navigator", $template, Array(
                "count" => $this->count_on_page,
                "page" => $this->page,
                "max_count" => $real_count,
                "base_link" => $this->base_link
            ));
    }

    function HFormTable($values) {
        $this->count_on_page = 0;
        $this->page = 0;
        if (!isset($values['filter']))
            $values['filter'] = 1;
        if (!isset($values['short_links']))
            $values['short_links'] = 1;
        if (!isset($values['edit_link']))
            $values['edit_link'] = "#";
        if (!isset($values['delete_link']))
            $values['delete_link'] = "#";
        if (!isset($values['add_link']))
            $values['add_link'] = "";
        if (!isset($values['add_link2']))
            $values['add_link2'] = "";
        if (!isset($values['show_code']))
            $values['show_code'] = true;
        if (!isset($values['show_folders']))
            $values['show_folders'] = true;
        if (!isset($values['hide_group_action']))
            $values['hide_group_action'] = false;
        if (!isset($values['folder_link']))
            $values['folder_link'] = true;

        if (!isset($values['can_add']))
            $values['can_add'] = true;
        if (!isset($values['can_edit']))
            $values['can_edit'] = true;
        if (!isset($values['can_delete']))
            $values['can_delete'] = true;
        $this->can_add = $values['can_add'];
        $this->can_edit = $values['can_edit'];
        $this->can_delete = $values['can_delete'];

        if ($values['filter'] == "")
            $values['filter'] = "1";

        if (is_array($values['filter']))
            if (count($values['filter']) == 0)
                $values['filter'] = "1";

        $table = $values['table'];
        $filter = $values['filter'];


        $this->folder_link = $values['folder_link'];
        $this->hide_group_action = $values['hide_group_action'];
        $this->edit_link_base = $values['edit_link'];
        $this->delete_link_base = $values['delete_link'];
        $this->add_link_base = $values['add_link'];
        $this->add_link_base2 = $values['add_link2'];
        $this->short_links=$values['short_links'];

        $this->base_link = "";
        $this->token = "table" . $table;
        $this->sql = new HolySQL($table);
        $this->filter = $filter;


        $this->PrepareFilter();

        $this->sql->Select($filter, $this->sortfilter);
    }

    function PrepareFilter() {
        if (!isset($_GET['sort_by']))
            $_GET['sort_by'] = "sort";
        if (!isset($_GET['sort_direct']))
            $_GET['sort_direct'] = "ASC";

        $this->sortfilter = " `" . $_GET['sort_by'] . "` " . $_GET['sort_direct'];
    }

    function DrawTableHeader() {
        $this->folder_icon = '<img src=img/folder.png align=left style="padding-right:4px;">';
        $this->edit_icon = '<img src=img/edit.png align=left >';
        $this->delete_icon = '<img src=img/delete.png align=left >';

        $this->add_icon = '<img src=img/add.png align=left>';
        $this->add_folder_icon = '<img src=img/add_folder.png align=left>';
        $this->edit_folder_icon = '<img src=img/folder_edit.png align=left>';

        $this->sort_up_icon = '<img src=img/sort_up.png>';
        $this->sort_down_icon = '<img src=img/sort_down.png>';
        if (($this->add_link_base2 != "") || ($this->show_count_list)) {
            if (!isset($_GET['page_count']))
                $_GET['page_count'] = 10;
            $sort_array = Array(10, 20, 50, 100, 200, 500, 999999);
            ?>
            <div>
            </form><form method=get>
                Число элементов: <select name=page_count id=page_count>
                    <?
                    foreach ($sort_array as $sa) {
                        ?>
                        <option <? if ($sa == $_GET['page_count']) { ?>selected<? }; ?> value=<?= $sa ?>><?= $sa ?></option>
                        <?
                    };
                    ?>
                </select>
                <? if (isset($_GET['sort_by'])) { ?><input type=hidden name=sort_by value=<?= $_GET['sort_by'] ?>><? }; ?>
                <? if (isset($_GET['sort_direct'])) { ?><input type=hidden name=sort_direct value=<?= $_GET['sort_direct'] ?>><? }; ?>
                <input type=hidden name=dblock value=<?= $_GET['dblock'] ?>>
                <input type=submit value=сменить class="btn" >
            </form>
            <form method=post>
            </div>
            <?
        };

        if ($this->can_add)
        if ($this->add_link_base != "") {
            ?>
            <a class="btn btn-success ajax" style="float:left;" href="<?= $this->add_link_base ?>">Добавить</a>
            <?
        };

        if ($this->can_add)
        if ($this->add_link_base2 != "")
            if ($this->show_folders) {
                ?>
                <a class="btn btn-info ajax" style="margin-left:15px;float:left;" href="<?= $this->add_link_base2 ?>">Добавить папку</a>
                <?
            };
        ?>

        <?
        if ($this->add_link_base2 != "")
            if (isset($_GET['parent']))
                if ($_GET['parent'] > 0) {
                    $one_folder = $this->sql->SelectOne("id=" . $_GET['parent']);
                    $this->one_folder = $one_folder;
                    $this->Reload();
                    ?>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn btn-primary ajax" style="margin-left:15px;float:left;" href="folder_edit.php?dblock=<?= $_GET['dblock'] ?>&parent=<?= $this->one_folder['parent'] ?>&id=<?= $_GET['parent'] ?>">Отредактировать папку</a>
                    <?
                };
        ?>
        <BR><BR>
        <table width=100% border=1px cellpadding=0 cellspacing=0 id=tableform name=tableform class=tableform>
            <thead>
                <tr>
                    <? if (!$this->hide_group_action) { ?>
                        <td>
                            <input id=global_check name=global_check type=checkbox onclick="
                                            if ($('#global_check').attr('checked'))
                                                $('.first_checkbox').attr('checked','checked');
                                        else
                                            $('.first_checkbox').removeAttr('checked');">

                        </td><? }; ?>
                    <?
                    if ($this->columns)
                        foreach ($this->columns as $item) {
                            $ASC = "ASC";
                            if ($_GET['sort_by'] == $item['name'])
                                if ($_GET['sort_direct'] == "ASC")
                                    $ASC = "DESC"; else
                                    $ASC = "ASC";
                            $ASC0 = $ASC;
                            if (isset($_GET['parent']))
                                $ASC.="&parent=" . $_GET['parent'];
                            if (!isset($_GET['page_count']))
                                $_GET['page_count'] = 10;
                            ?>
                            <td align=center>
                                <? if (isset($_GET['dblock'])) {
                                    if ($_GET['sort_by'] == $item['name']) {
                                        ?><b><? }; ?>
                                        <a href=?dblock=<?= $_GET['dblock'] ?>&sort_by=<?= $item['name'] ?>&sort_direct=<?= $ASC ?>&page_count=<?= $_GET['page_count'] ?>>
                                        <? }; ?>
                                    <?= $item['caption'] ?>
                                    </a>
                                    <?
                                    if (isset($_GET['dblock'])) {
                                        if ($_GET['sort_by'] == $item['name']) {
                                            if ($ASC0 == "ASC")
                                                echo $this->sort_down_icon; else
                                                echo $this->sort_up_icon;
                                        }
                                    };
                                    ?>
                            <? if (isset($_GET['dblock'])) if ($_GET['sort_by'] == $item['name']) { ?></b><? } ?>
                            </td>
                            <?
                        };
                    ?>
                    <? if ($this->edit_link_base != "#") if ($this->can_edit){ ?><td>&nbsp;</td><? } ?>
        <? if ($this->delete_link_base != "#") if ($this->can_delete){ ?><td>&nbsp;</td><? } ?>
                </tr>
            </thead>
            <tbody>
                <?
            }

            function DrawTableFooter() {
                ?>
            </tbody>
        </table>
        <?
    }

    function CreateLink($shab, $data) {
        foreach ($data as $i => $d) {
            $var = "#" . strtoupper($i) . "#";
            $shab = str_replace($var, $d, $shab);
        };
        return $shab;
    }

    function DrawTableBefore() {
        
    }

    function Draw() {

        $this->DrawTableBefore();
        ?>

        <form method=post>
            <?
            $this->DrawTableHeader();
            $this->DrawTable();
            $this->DrawTableFooter();
            ?>
        </form>
        <?
    }

    function Load() {
        unset($this->data);
        $this->PrepareFilter();
        $this->data = $this->sql->Select($this->filter, $this->sortfilter);
    }

    function Reload() {
        unset($this->data);
        $this->PrepareFilter();
        if ($this->count_on_page != 0)
            $count_on_page = $this->count_on_page;
        if ($this->page != 0)
            $page = $this->page;
        //$add_sort="folder DESC,";
        $add_sort = "";
        if ($this->sql->table == "pages")
            $add_sort = "";
        $this->data = $this->sql->Select($this->filter, $add_sort . $this->sortfilter, "*", $count_on_page, $page);
    }

    function DrawTable() {
        $count = 1;
        if (!isset($_GET['parent']))
            $_GET['parent'] = 0; //@todo ох неправильно это, ох неправильно

        if ($_GET['parent'] > 0) {
            $one_folder = $this->sql->SelectOne("id=" . $_GET['parent']);
            $this->one_folder = $one_folder;
            $this->Reload();
            ?>
            <tr>
                <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
                <td colspan="<?= count($this->columns) - 3 ?>"><!--[П1]--> <?= $this->folder_icon ?><a class=ajax onclick="jQuery('#f1').jstree('deselect_all');jQuery('#f1').jstree('select_node', '#tree_block_<?= $_GET['dblock'] ?>_element_<?= $one_folder['parent'] ?>'); " href=?dblock=<?= $_GET['dblock'] ?>&parent=<?= $one_folder['parent'] ?>>&nbsp;...папка выше...&nbsp;</a></td>
                <td>&nbsp;</td><td>&nbsp;</td>
            </tr>

            <?
        };
        while ($item = $this->sql->GetNext()) {
            $count++;
            $edit_java1 = 'onclick="window.location=';
            $edit_java2 = '"';
            ?>
            <tr name="tr_<?= $count ?>" id="tr_<?= $count ?>" <? if ($count % 2 == 0) { ?>class="odd"<? }; ?> onMouseOver="$('#tr_<?= $count ?>').addClass('selected_tr');" onMouseOut="$('#tr_<?= $count ?>').removeClass('selected_tr');">
            <? if (!$this->hide_group_action) { ?>
                    <td>        
                        <input class="first_checkbox" type=checkbox name=id[] value="<?= $item['id'] ?>">

                    </td>
                <? }; ?>
                <?
                if ($this->columns)
                    foreach ($this->columns as $column) {
                        //if ((in_array($column['name'],Array("id","name","sort","caption"))) || ($item['folder']))
                        $path = $_SERVER['DOCUMENT_ROOT'] . "/site/forms/" . $column['type'] . ".php";
                        $path0 = $_SERVER['DOCUMENT_ROOT'] . "/engine/api/forms/" . $column['type'] . ".php";
                        if (!file_exists($path)) {
                            $path = $path0;
                        };
                        if (!file_exists($path)) {
                            $column['type'] = "text";
                            $path = $_SERVER['DOCUMENT_ROOT'] . "/engine/api/forms/" . $column['type'] . ".php";
                        };
                        include_once($path);
                        $name = "CForm_" . $column['type'];
                        $obj = new $name();
                        if (!isset($item['folder']))
                            $item['folder'] = 0;

                        if (($item['folder']) && ($this->folder_link))
                            $edit_java = $edit_java1 . "'folder_edit.php?dblock=" . $_GET['dblock'] . "&parent=" . $item['parent'] . "&id=" . $item['id'] . "'" . $edit_java2;
                        elseif ($this->edit_link_base != "#")
                            $edit_java = $edit_java1 . "'" . ($this->CreateLink($this->edit_link_base, $item)) . "'" . $edit_java2;
                        else
                            $edit_java = "";
                        ?>
                        <td <? if ($column['name'] == "sort") { ?> class="dragHandle" style="cursor: move;"<? }; ?> <? if ($column['name'] != "sort") { ?><?= $edit_java ?><? }; ?> <? if ((5 == 6) && ($item['folder']) && ($column['name'] == "caption")) { ?> colspan="<?= count($this->columns) - 3 ?>" <? } ?>><?
                        ?>
                            <? if ($column['name'] == "sort") { ?><img src=/engine/js/updown2.gif> <? }; ?>
                                <? if (($item['folder']) && ($column['name'] == "caption")) { ?>
                                     <!--[П2]--><?= $this->folder_icon ?><a class=ajax onclick="jQuery('#f1').jstree('deselect_all');jQuery('#f1').jstree('select_node', '#tree_block_<?= $_GET['dblock'] ?>_element_<?= $item['id'] ?>'); " href="?dblock=<?= $_GET['dblock'] ?>&parent=<?= $item['id'] ?>"><?
                    };
                    if ($column['multiple']) {
                                    ?>
                                    [множ]
                                    <?
                                } else
                                    $obj->View($column['name'], $item, $column['add_values'], $column['multiple']);
                                ?>

                            <? if (($item['folder']) && ($column['name'] == "caption")) { ?>
                                </a>
                        <? }; ?>
                        </td>
                        <?
                    };
                ?>
                <?
                if (($item['folder']) && ($this->folder_link)) 
                    if ($this->can_edit){
                    ?>
                    <td><a href=folder_edit.php?dblock=<?= $_GET['dblock'] ?>&parent=<?= $item['parent'] ?>&id=<?= $item['id'] ?>><?= $this->edit_icon ?></a></td>
                    <?
                } else
                if ($this->edit_link_base != "#") if ($this->can_edit){
                    $edit = $this->CreateLink($this->edit_link_base, $item);
                    ?><td><a href=<?= $edit ?>><?= $this->edit_icon ?></a></td><? }; ?>
            <? if ($this->delete_link_base != "#") 
                if ($this->can_delete)
                {
                $del = $this->CreateLink($this->delete_link_base, $item);
                ?><td><a onclick='return(window.confirm("Вы уверены, что хотите удалить?"))' href=<?= $del ?>><?= $this->delete_icon ?></a></td><? }; ?>
            </tr>
            <?
        };
    }

    function Add($values) {
        $name = $values['name'];
        $caption = $values['caption'];
        $type = $values['type'];
        if (!isset($values['add_values']))
            $values['add_values'] = "";
        if (!isset($values['multiple']))
            $values['multiple'] = 0;
        $add_values = $values['add_values'];

        $this->columns[] = array("name" => $name, "caption" => $caption, "type" => $type, "add_values" => $add_values, "multiple" => $values['multiple']);
    }

}
?>