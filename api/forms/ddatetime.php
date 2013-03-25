<?

class CForm_ddatetime extends CForm_Text {

    function HTML($name) {
        ?>
        <script>
            $().ready(function() {
                //$('#<?= $name ?>').datepicker({format:'yy-mm-dd',today:true,close:true,effect:'slide',theme:'simple'});

                $('#<?= $name ?>').attachDatepicker({ dayNamesMin: ["Пн", "Вт", "Ср", "Чт", "Пт", "Сб", "Вск"],dateFormat:'yy-mm-dd',monthNames: ["Январь","Февраль","Март","Апрель","Май","Июнь","Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь"] });

            })
        </script>
        <?
    }

    function Add($name, $add, $multiple = false) {
        if (!isset($_POST[$name][0]))
            $_POST[$name][0] = date("Y-m-d");
        if (isset($_POST[$name]))
            if ($_POST[$name][0] == "")
                $_POST[$name][0] = date("Y-m-d");
        if (!isset($_POST[$name][1]))
            $_POST[$name][1] = date("H");
        if (!isset($_POST[$name][2]))
            $_POST[$name][2] = date("i");
        if (!isset($_POST[$name][3]))
            $_POST[$name][3] = date("s");
        $this->HTML($name);

        if ($_POST[$name][0] == "0000-00-00")
            $data_POST[$name][0] = date("Y-m-d");
        ?>
        <input id=<?= $name ?> name=<?= $name ?>[0] value="<?= $_POST[$name][0] ?>" style="float:left;">
        <select id=<?= $name ?> name=<?= $name ?>[1] style="float:left;">
            <?
            for ($i = 0; $i <= 23; $i++) {
                ?>
                <option value=<?= $i ?> <? if ($i == $_POST[$name][1]) { ?>selected<? }; ?>>
                    <?= DateTimePrefix($i) ?>
                </option>
                <?
            };
            ?>
        </select>
        <select id=<?= $name ?> name=<?= $name ?>[2] style="float:left;">
            <?
            for ($i = 0; $i <= 59; $i++) {
                ?>
                <option value=<?= $i ?> <? if ($i == $_POST[$name][2]) { ?>selected<? }; ?>>
                    <?= DateTimePrefix($i) ?>
                </option>
                <?
            };
            ?>
        </select>
        <select id=<?= $name ?> name=<?= $name ?>[3] style="float:left;">
            <?
            for ($i = 0; $i <= 59; $i++) {
                ?>
                <option value=<?= $i ?> <? if ($i == $_POST[$name][3]) { ?>selected<? }; ?>>
                    <?= DateTimePrefix($i) ?>
                </option>
                <?
            };
            ?>
        </select>
        <?
    }

    function Edit($name, $data, $add, $multiple = false) {
        $this->HTML($name);
        $base_date = explode(" ", $data[$name]);
        $base_time = explode(":", $base_date[1]);

        if ($base_date[0] == "0000-00-00")
            $base_date[0] = date("Y-m-d");
        ?>
        <input id=<?= $name ?> name=<?= $name ?>[0] value="<?= $base_date[0] ?>" style="float:left;">
        <select id=<?= $name ?> name=<?= $name ?>[1] style="float:left;">
            <?
            for ($i = 0; $i <= 23; $i++) {
                ?>
                <option value=<?= $i ?> <? if ($i == $base_time[0]) { ?>selected<? }; ?>>
                    <?= DateTimePrefix($i) ?>
                </option>
                <?
            };
            ?>
        </select>
        <select id=<?= $name ?> name=<?= $name ?>[2] style="float:left;">
            <?
            for ($i = 0; $i <= 59; $i++) {
                ?>
                <option value=<?= $i ?> <? if ($i == $base_time[1]) { ?>selected<? }; ?>>
                    <?= DateTimePrefix($i) ?>
                </option>
                <?
            };
            ?>
        </select>
        <select id=<?= $name ?> name=<?= $name ?>[3] style="float:left;">
            <?
            for ($i = 0; $i <= 59; $i++) {
                ?>
                <option value=<?= $i ?> <? if ($i == $base_time[2]) { ?>selected<? }; ?>>
                    <?= DateTimePrefix($i) ?>
                </option>
                <?
            };
            ?>
        </select>
        <?
    }

    function AfterEdit($name, $value, $add) {
        if (!is_array($value))
            return $value;
        return $value[0] . " " . $value[1] . ":" . $value[2] . ":" . $value[3];
    }

    function BeforeAdd($name, $value, $add) {
        if (!is_array($value))
            return $value;
        return $value[0] . " " . $value[1] . ":" . $value[2] . ":" . $value[3];
    }

    function Filter($name) {
        if (!isset($_GET['filter'][$name]))
            $_GET['filter'][$name] = "";
        $this->HTML($name);
        ?>
        <input id=<?= $name ?> name=filter[<?= $name ?>] value="<?= $_GET['filter'][$name]; ?>" style="width:90%">
        <?
    }

}
?>