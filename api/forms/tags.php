<?

class CForm_tags extends CForm_text {

    function Edit($name, $data, $add, $multiple = false) {
        $add = explode(";", $add);
        $tags = new HolyTags($add[0], $add[1]);
        $tags_list = $tags->GetTagsList();
        $item_tags = $tags->GetListSimple($data);
        ?>
        <select data-placeholder="Выберите теги..." style="width:100%;" multiple class="chosen" name="<?= $name ?>[]" id="<?= $name ?>">
            <?
            foreach ($tags_list as $_tag_name => $_tag) {
                $selected = "";
                if (in_array($_tag_name, $item_tags))
                    $_selected = "selected";
                else
                    $_selected = "";
                ?>
                <option <? echo $_selected; ?> value="<?= $_tag_name ?>"><?= $_tag_name ?></a>
                    <?
                };
                ?>
        </select><br>
        Добавить тэг:<input id="add_tag_<?= $name ?>" name="add_tag_<?= $name ?>" value=""><a onclick="return add_tag_click_<?=$name?>();" href="" class="btn btn-small">Добавить</a>
        <br><br>
				<style>
		.chzn-container
{
width:100% !important;
}
</style>
        <?
        $this->_html_add($name);
    }

    function BeforeAdd($name, $value, $add) {
        $new_value = "";
        foreach ($value as $_value)
            if ($_value) {
                if ($new_value!="")
                    $new_value.=",";
                $new_value.=$_value;
            }
        return $new_value;
    }
    
    function AfterEdit($name, $value, $add, $multiple = false) {
        $new_value = "";
        if (is_array($value))
        foreach ($value as $_value)
            if ($_value) {
                if ($new_value!="")
                    $new_value.=",";
                $new_value.=$_value;
            }
        return $new_value;
    }

    protected function _html_add($name){
        ?>
        <script>
            function add_tag_click_<?=$name?>(){
                var val=$('#add_tag_<?= $name ?>').val();
                if (val!=""){
                        $('#<?= $name ?>')
                            .append($("<option></option>")
                            .attr("value",val)
                            .attr("selected",true)
                            .text(val)); 
                    $("#<?= $name ?>").trigger("liszt:updated");
                    $('#add_tag_<?= $name ?>').val("");
                };
                return false;
            }
        </script>
        <?
    }
    
    function Add($name, $add, $multiple = false) {
        $add = explode(";", $add);
        $tags = new HolyTags($add[0], $add[1]);
        $tags_list = $tags->GetTagsList();
        ?>
        <select data-placeholder="Выберите теги..." style="width:100%;" multiple class="chosen" name="<?= $name ?>[]" id="<?= $name ?>">
            <?
            foreach ($tags_list as $_tag_name => $_tag) {
                ?>
                <option value="<?= $_tag_name ?>"><?= $_tag_name ?></a>
                    <?
                };
                ?>
        </select><br>
        Добавить тэг:<input id="add_tag_<?= $name ?>" name="add_tag_<?= $name ?>" value=""><a onclick="return add_tag_click_<?=$name?>();" href="" class="btn btn-small">Добавить</a>
        <br><br>
		<style>
		.chzn-container
{
width:100% !important;
}
</style>
        <?
        $this->_html_add($name);
    }

}
?>