<?php

class HTypeTable extends HFormTable {

    function GO() {
        if (isset($_POST['token']))
            if ($_POST['token'] == $this->token) {
                return true;
            }
    }

    function HTypeTable($values) {
        parent::__construct($values);
    }

    function DrawTableHeader() {
        ?>
        <script>
            function addTableRow(jQtable){
                var new_line;
                new_line=$('.last_id').html();
                var t_size=$(".tableform tr").size()-2;
                new_line=new_line.replace(new RegExp("[[]]",'g'),"["+t_size+"]");
                new_line=new_line.replace(new RegExp(">Add<",'g'),"><");
                $('.tableform tbody').append("<tr>"+new_line+"</tr>");
            }


            function addTableRow_meta(jQtable)
            {
                addTableRow_spec(jQtable,"caption2","Заголовок","short_text",0,1,1,1);
                addTableRow_spec(jQtable,"description","Description","wysiwyg_text",0,1,1,1);
                addTableRow_spec(jQtable,"keywords","Ключевые слова","wysiwyg_text",0,1,1,1);
            };

            function addTableRow_spec(jQtable,code_value,caption_value,type_value,multi,folder,not_in_list,meta){
                var new_line;
                new_line=$('.last_id').html();
                var t_size=$(".tableform tr").size()-2;
                	
                /*
                var code_value="code";			//код свойства
                var caption_value="капшн";		//заголовок
                var type_value="wysiwyg_html";	//тип
                var multi=true;					//множественное
                var folder=true;				//и папка тоже
                var not_in_list=true;			//не в списке
                var meta=true;					//meta
                 */
                new_line=new_line.replace(new RegExp('name="name[[]]" value=""','g'),'name="name[]" value="'+code_value+'"');
                new_line=new_line.replace(new RegExp('name="caption[[]]" value=""','g'),'name="caption[]" value="'+caption_value+'"');
                new_line=new_line.replace(new RegExp(type_value,'g'),type_value+'" selected ');
                if (multi)
                    new_line=new_line.replace(new RegExp('name="multiple[[]]"','g'),'name="multiple[]" checked');
                if (folder)
                    new_line=new_line.replace(new RegExp('name="owner_type[[]]"','g'),'name="owner_type[]" checked');
                if (not_in_list)
                    new_line=new_line.replace(new RegExp('name="not_list[[]]"','g'),'name="not_list[]" checked');
                if (meta)
                    new_line=new_line.replace(new RegExp('name="meta[[]]"','g'),'name="meta[]" checked');
                new_line=new_line.replace(new RegExp("[[]]",'g'),"["+t_size+"]");
                new_line=new_line.replace(new RegExp(">Add<",'g'),"><");
                	
                	
                //alert(new_line);
                $('.tableform tbody').append("<tr>"+new_line+"</tr>");
            }
        </script>
        <form method=post>
            <input type=hidden name=token value=<?= $this->token ?>>
            
            <input onclick="addTableRow('#tableform')" value="Добавить строчку" style="float:left;height:22px;width:15%;margin-right:10px;margin-bottom:10px;" class="btn btn-success">
            <? if ($this->short_links){?>
            <input onclick="addTableRow_meta('#tableform')" value="Добавить meta-тэги" style="float:left;height:22px;width:20%;margin-right:10px;margin-bottom:10px;" class="btn  btn-info">
            <? global $_standard_element_fields; ?>
            <div class="btn-group" style="float:left;margin-right:10px;margin-bottom:10px;">
                <button data-toggle="dropdown" style="height:32px;" class="btn dropdown-toggle">Добавить стандартное поле&nbsp;<span class="caret"></span></button>
                <ul class="dropdown-menu">
                    <?
                    foreach ($_standard_element_fields as $_stander_e_f) {
                        ?>
                        <li id="find2_sort" name="find2_sort">
                            <a href="javascript:return 0;" onclick='addTableRow_spec("#tableform","<?= $_stander_e_f['code'] ?>","<?= $_stander_e_f['caption'] ?>","<?= $_stander_e_f['type'] ?>",<?= intval($_stander_e_f['multi']) ?>,<?= intval($_stander_e_f['folder']) ?>,<?= intval($_stander_e_f['not_in_list']) ?>,<?= intval($_stander_e_f['meta']) ?>)'>
                                <?= $_stander_e_f['caption'] ?>
                            </a>
                        </li>  
                    <? }
                    ?>

                </ul>
            </div>
            <?};?>
            <?
            parent::DrawTableHeader();
        }

        function DrawTable() {
            $counter = -1;
            $count = 1;
            while ($item = $this->sql->GetNext()) {
                $counter++;
                $count++;
                ?>
                <tr <? if ($count % 2 == 0) { ?>class="odd"<? }; ?> >
                    <td><input  type=hidden checked name=id[] value="<?= $item['id'] ?>">
                        <input class="first_checkbox" type=checkbox name=delete_id[] value="<?= $item['id'] ?>">
                    </td>
                    <?
                    if ($this->columns)
                        foreach ($this->columns as $column) {
                            $path = FOLDER_ROOT . "/site/forms/" . $column['type'] . ".php";
                            $path0 = FOLDER_ROOT . "/engine/api/forms/" . $column['type'] . ".php";
                            if (!file_exists($path)) {
                                $path = $path0;
                            };
                            if (!file_exists($path)) {
                                $column['type'] = "text";
                                $path = FOLDER_ROOT . "/engine/api/forms/" . $column['type'] . ".php";
                            };
                            include_once($path);
                            $name = "CForm_" . $column['type'];
                            $obj = new $name;
                            ?>
                            <td align=center><?
                    $obj->EditView($column['name'], $item, $column['add_values'], $counter);
                            ?></td>
                            <?
                        };
                    ?>
                    <? if ($this->edit_link_base != "#") {
                        $edit = $this->CreateLink($this->edit_link_base, $item);
                        ?><td><a href=<?= $edit ?>>Изменить</a></td><? }; ?>
                    <? if ($this->delete_link_base != "#") {
                        $del = $this->CreateLink($this->delete_link_base, $item);
                        ?><td><a onclick='return(window.confirm("Вы уверены, что хотите удалить?"))' href=<?= $del ?>>Удалить</a></td><? }; ?>
                </tr>
                <?
            };
        }

        function DrawTableFooter() {
            ?>

            <tr class=last_id style="display:none;">
                <td><input type=hidden name=id[] value=-1></td>
                <?
                if ($this->columns)
                    foreach ($this->columns as $column) {
                        $path = FOLDER_ROOT . "/site/forms/" . $column['type'] . ".php";
                        $path0 = FOLDER_ROOT . "/engine/api/forms/" . $column['type'] . ".php";
                        if (!file_exists($path)) {
                            $path = $path0;
                        };
                        if (!file_exists($path)) {
                            $column['type'] = "text";
                            $path = FOLDER_ROOT . "/engine/api/forms/" . $column['type'] . ".php";
                        };
                        include_once($path);
                        $name = "CForm_" . $column['type'];
                        $obj = new $name;
                        ?>
                        <td align=center><?
                $obj->EditView($column['name'], Array(), $column['add_values'], "");
                        ?></td><?
            };
        ?>
            </tr>
        </tbody>
        </table>
        <?
        parent::DrawTableFooter();
        ?>

        <BR><input type=submit value="Сохранить" style="float:left;height:26px;width:50%;" class="btn">
        </form>
        <?
    }

}
?>