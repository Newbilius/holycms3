<?php

class HElementForm extends HFormTable {

    function HElementForm($values) {
        //$this->sql->debug=true;
        if (!isset($values['filter']))
            $values['filter'] = 1;
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
        if (!isset($values['show_count_list']))
            $values['show_count_list'] = false;
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
        
        $this->folder_link_add="";
        global $force_filter;
        if ($force_filter) {
            $_force_filter_name = $_GET['force_filter_name'];
            $_force_filter_value = $_GET['force_filter_value'];
            $this->folder_link_add = "&force_filter_name=" . $_force_filter_name . "&force_filter_value=" . $_force_filter_value;
        };
        
        if ($values['filter'] == "")
            $values['filter'] = "1";
        else
        if (is_array($values['filter'])) {
            foreach ($values['filter'] as $i => $n)
                if (($n == "") && (!is_numeric($n)))
                    unset($values['filter'][$i]);
        };

        if (is_array($values['filter']))
            if (count($values['filter']) == 0)
                $values['filter'] = "1";

        $table = $values['table'];
        $filter = $values['filter'];

        if (is_array($filter))
        foreach ($filter as $_filt_id=>$_filter){
            if (is_numeric($_filter)){
                $filter[$_filt_id]=Array($_filt_id,"=",$_filter);
            }else{
                $filter[$_filt_id]=Array($_filt_id,"LIKE","%".$_filter."%");
            };
        };
        
        $this->folder_link = $values['folder_link'];
        $this->show_count_list = $values['show_count_list'];
        $this->hide_group_action = $values['hide_group_action'];
        $this->edit_link_base = $values['edit_link'];
        $this->delete_link_base = $values['delete_link'];
        $this->add_link_base = $values['add_link'];
        $this->add_link_base2 = $values['add_link2'];

        $this->show_folders = $values['show_folders'];
        $this->show_code = $values['show_code'];

        $this->token = "table" . $table;
        $this->sql = new HolySQL($table);
        
        $this->filter = $filter;
        $this->PrepareFilter();

        //$add_sort="folder DESC,";
        //if ($table=="pages")
        $add_sort = "";
//$this->sql->debug=true;

        $this->sql->Select($filter, $add_sort . $this->sortfilter);
    }

    function DrawTableBefore() {
        parent::DrawTableBefore();
        $path = FOLDER_ROOT . "/engine/api/forms/text.php";
        include_once($path);
        ?>
        <form method=get style="margin:0px;">
        <?
        if (!isset($_GET['parent']))
            $_GET['parent'] = 0;
        ?>
            <input type=hidden name=dblock value="<?= $_GET['dblock'] ?>">
            <input type=hidden name=parent value="<?= $_GET['parent'] ?>">
<?
        global $force_filter;
        if ($force_filter) {
            $_force_filter_name = $_GET['force_filter_name'];
            $_force_filter_value = $_GET['force_filter_value'];
?>
            <input type=hidden name=force_filter_name value="<?= $_GET['force_filter_name'] ?>">
            <input type=hidden name=force_filter_value value="<?= $_GET['force_filter_value'] ?>">
            <?
        };
        ?>
            <table>
                <tr valign=top>
                    <td valign=top>
                        <table class="item_filter table table-bordered table-condensed">
                            <tr>
                                <th align=center><center>Название поля</center></th>
                            <th align=center><center>Фильтр по полю</center></th>
                            <th align=center><center>-</center></th>
                </tr>
                        <?
                        if ($this->columns)
                            foreach ($this->columns as $item) {
                                ?>
                        <tr name=find1_<?= $item['name'] ?> id=find1_<?= $item['name'] ?> style="display:none;">
                            <td><?= $item['caption'] ?></td>
                            <td><?
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
                $obj = new $name();

                $obj->Filter($item['name'], $item['add_values']);

                $find_forme_array[] = array($item['caption'], $item['name']);
                                ?></td>
                            <td>
                                <a href=# onclick="find_form_hide('<?= $item['name'] ?>')">Скрыть</a>
                            </td>
                        </tr>
                            <?
                        };
                    ?>
                <tr>
                    <td colspan=3 align=center><center>
                    <input type=submit value=Фильтровать class="btn" style="margin-bottom:10px;">
        <?
        if (!isset($_GET['parent']))
            $_GET['parent'] = 0;
        ?>
                    <input type=hidden name=parent value=-1 >
                    <button class="btn" style="margin-bottom:10px;" onclick="window.location='?dblock=<?= $_GET['dblock'] ?>&parent=<?= $_GET['parent'] ?><?=$this->folder_link_add?>';return false;">Сбросить</button>
                </center></td>
                </tr>
            </table>
        </td>
        <td valign=top>
            <script>
                function find_form_show(code)
            {
                var name1="find1_"+code;
                var name2="find2_"+code;
                var name3="find3_"+code;
                $("#"+name1).show();
                $("#"+name2).hide();
                $("#"+name3).hide();
            };
            function find_form_hide(code)
            {
                var name2="find1_"+code;
                var name1="find2_"+code;
                $("#"+name1).show();
                $("#"+name2).find("input").val("");
                $("#"+name2).find("select").val("");
                $("#"+name2).hide();
            };

            </script>	
            <div class="btn-group">
                <button class="btn dropdown-toggle" data-toggle="dropdown">Скрытые поля фильтра <span class="caret"></span></button>
                <ul class="dropdown-menu">
                    <?
                    foreach ($find_forme_array as $fa) {
                        ?>
            <?
            if (isset($_GET['filter'][$fa[1]]))
                if ($_GET['filter'][$fa[1]] != "") {
                    ?>
                                <script>
                                find_form_show('<?= $fa[1] ?>');
                                </script>
                    <?
                };
            ?>
                        <li name=find2_<?= $fa['1'] ?> id=find2_<?= $fa['1'] ?>>
                            <a onclick="find_form_show('<?= $fa['1'] ?>')" href=#>

                        <?= $fa['0'] ?>
                            </a></li>
            <?
        }
        ?>

                </ul>
            </div>

            <script type="text/javascript">

            $(document).ready(function() {
                $('#hiddenhref').click(function() {
                    $.fancybox($("#hiddenform").html().replace(/find2_/g,"find3_"));
                });


            });
            </script>

        </td>
        </tr>
        </table>

        </form>
        <?
    }

    function DrawTableFooter() {
        parent::DrawTableFooter();
        $this->sql->Select("folder=1");
        while ($data = $this->sql->GetNext())
            $folders[] = $data;
        $ccount = $this->sql->GetCount();
        ?>
        <? if ((!$this->hide_group_action) &&($this->can_delete OR $this->can_edit)) { ?>
            <script>
            function SecondList()
            {
                if ($("#what_to_do").attr("value")=="del")
                    $(".where_move").hide(); else $(".where_move").show();
            };
            </script>
            <BR>
            <div>
                Действия:
                <select onchange="SecondList()" name=what_to_do id=what_to_do>
                    <?if ($this->can_delete){?><option value=del>Удалить</option><?};?>
            <? if ($ccount) if ($this->can_edit){ ?><option value=move>Переместить</option> <? }; ?>
                </select>

                <span class="where_move" style="display:none;">куда</span>
                <select name=where_move class="where_move" style="display:none;">
                    <option value=0>[ корень ]</option>
                    <?

                    function DrawFolderTree($parent, $array_of, $name_add = "") {
                        //получаем список папок
                        foreach ($array_of as $data)
                            if ($data['parent'] == $parent) {
                                ?>
                                <option value=<?= $data['id'] ?>><?= $name_add . $data['caption'] ?></option>
                                <?
                                DrawFolderTree($data['id'], $array_of, $name_add . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
                            };
                    }

;
                    DrawFolderTree(0, $folders);
                    ?>
                </select>
                <input type=submit value="ОК" class="btn" >
            </div>
        <? }; ?>

        <?
        if ($this->count_on_page > 0) {
            $this->DrawPaginator($this->sort_template, $this->max_count);
        };
    }

}
?>