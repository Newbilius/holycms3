<?

class CForm_list_max extends CForm_text {

    function Edit($name, $data, $add, $multiple = false) {
        $add_datas = explode(";", $add);
        $item_tags=explode(";",$data[$name]);
        //0 таблица
        //1 значащее поле
        //2 поле отображаемое
        $spec_sql = new HolySQL($add_datas[0]);
        $spec_sql->Select();
        while ($ndata = $spec_sql->GetNext()) {
            if (!isset($ndata['parent']))
                $ndata['parent'] = 0;
            $result_list[] = $ndata;
        };
        ?>  
        <select data-placeholder="Введите название..." style="width:100%;" multiple class="chosen" name="<?= $name ?>[]" id="<?= $name ?>">
            <?
            foreach ($result_list as $_tag) {
                $selected = "";
                if (in_array($_tag[$add_datas[1]], $item_tags))
                    $_selected = "selected";
                else
                    $_selected = "";
                ?>
                <option <? echo $_selected; ?> value="<?= $_tag[$add_datas[1]] ?>"><?= $_tag[$add_datas[2]] ?></a>
                    <?
                };
                ?>
        </select>
        <?
    }

    function BeforeAdd($name, $value, $add) {
        $new_value = "";
        foreach ($value as $_value)
            if ($_value) {
                if ($new_value!="")
                    $new_value.=";";
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
                    $new_value.=";";
                $new_value.=$_value;
            }
        return $new_value;
    }

    function Add($name, $add, $multiple = false) {
        $add_datas = explode(";", $add);
        //0 таблица
        //1 значащее поле
        //2 поле отображаемое
        $spec_sql = new HolySQL($add_datas[0]);
        $spec_sql->Select();
        while ($ndata = $spec_sql->GetNext()) {
            if (!isset($ndata['parent']))
                $ndata['parent'] = 0;
            $result_list[] = $ndata;
        };
        ?>  
        <select data-placeholder="Введите название..." style="width:100%;" multiple class="chosen" name="<?= $name ?>[]" id="<?= $name ?>">
            <?
            foreach ($result_list as $_tag) {
                ?>
                <option value="<?= $_tag[$add_datas[1]] ?>"><?= $_tag[$add_datas[2]] ?></a>
                    <?
                };
                ?>
        </select>
        <?
    }

}
?>