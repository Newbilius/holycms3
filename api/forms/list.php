<?

class CForm_list extends CForm_Text {

    function View($name, $data, $add, $multiple = false) {
        $add_datas = explode(";", $add);
        $spec_sql = new HolySQL($add_datas[0]);
        $spec_sql->Select();
        if (!isset($data[$name]))
            $data[$name] = "";
        ?>
        <? while ($ndata = $spec_sql->GetNext()) { ?>
            <?
            if ($data[$name] == $ndata[$add_datas[1]]) {
                echo $ndata[$add_datas[2]];
                ?>
                [<?= $data[$name] ?>]
                <?
            }
        }
    }

    function EditView($name, $data, $add, $counter) {
        $add_datas = explode(";", $add);
        //PrePrint($add_datas);
        //0 таблица
        //1 значащее поле
        //2 поле отображаемое
        $spec_sql = new HolySQL($add_datas[0]);
        $spec_sql->Select();
        if (!isset($data[$name]))
            $data[$name] = "";
        ?>
        <select class=type_select name=<?= $name ?>[<?= $counter ?>] style="width:101%">
            <?
            while ($ndata = $spec_sql->GetNext()) {
                ?>
                <option <? if (isset($add_datas[3])) { ?>name="<?= $ndata[$add_datas[3]] ?>"<? }; ?> <? if ($data[$name] == $ndata[$add_datas[1]]) { ?>selected<? }; ?> value="<?= $ndata[$add_datas[1]] ?>"><?= $ndata[$add_datas[2]] ?></option>
            <? }; ?>
        </select>

        <?
    }

    function Edit($name, $data, $add, $multiple = false) {
        $add_datas = explode(";", $add);
        //PrePrint($add_datas);
        //0 таблица
        //1 значащее поле
        //2 поле отображаемое
        $spec_sql = new HolySQL($add_datas[0]);
        $spec_sql->Select();
        ?>
        <?
        while ($ndata = $spec_sql->GetNext()) {
            if (!isset($ndata['parent']))
                $ndata['parent'] = 0;
            $result_list[] = $ndata;
        };
        ?>       
        <select name=<?= $name ?><? if ($multiple) { ?>[]<? }; ?> style="width:101%">
            <option value="">[нет значения]</option>
            <?
            $this->PrintListItems(&$result_list, 0, &$data, &$add_datas, "", $name);
            ?>
        </select>

        <?
    }

    function PrintListItems($result_list, $root, $data, $add_datas, $text_add, $name) {
        if ($root != 0)
            $text_add.="&nbsp;&nbsp;&nbsp;&nbsp;";
        foreach ($result_list as $list_item) {

            if (!isset($list_item['parent']))
                $list_item['parent'] = 0;
            if ($list_item['parent'] == $root) {
                /* <? if ($data[$name] == $ndata[$add_datas[1]]) { ?>selected<? }; ?> */
                ?>
                <option  <? if ($data[$name] == $list_item[$add_datas[1]]) { ?>selected<? }; ?> value="<?= $list_item[$add_datas[1]] ?>"><?= $text_add . $list_item[$add_datas[2]] ?></option>
                <?
                $this->PrintListItems(&$result_list, $list_item['id'], $data, &$add_datas, $text_add, $name);
            };
        };
    }

    function Add($name, $add, $multiple = false) {
        $add_datas = explode(";", $add);
        //PrePrint($add_datas);
        //0 таблица
        //1 значащее поле
        //2 поле отображаемое
        $spec_sql = new HolySQL($add_datas[0]);
        $spec_sql->Select();
        ?>
        <?
        while ($ndata = $spec_sql->GetNext()) {
            if (!isset($ndata['parent']))
                $ndata['parent'] = 0;
            $result_list[] = $ndata;
        };
        ?>    
        <select name=<?= $name ?><? if ($multiple) { ?>[]<? }; ?> style="width:101%">
            <option value="">[нет значения]</option>
            <?
            $this->PrintListItems(&$result_list, 0, &$data, &$add_datas, "", $name);
            ?>
        </select>

        <?
    }

    function Filter($name, $add = "") {
        if (!isset($_GET['filter'][$name]))
            $_GET['filter'][$name] = "";
        $add_datas = explode(";", $add);
        //PrePrint($add_datas);
        //0 таблица
        //1 значащее поле
        //2 поле отображаемое
        $spec_sql = new HolySQL($add_datas[0]);
        $spec_sql->Select();
        ?>
        <select  name=filter[<?= $name ?>] style="width:95%">
            <option <? if ($_GET['filter'][$name] == "") { ?>selected<? }; ?> value=''>[любое]</option>
            <? while ($ndata = $spec_sql->GetNext()) { ?>
                <option <? if ($_GET['filter'][$name] == $ndata[$add_datas[1]]) { ?>selected<? }; ?> value="<?= $ndata[$add_datas[1]] ?>"><?= $ndata[$add_datas[2]] ?></option>
            <? }; ?>
        </select>

        <?
    }

}
?>