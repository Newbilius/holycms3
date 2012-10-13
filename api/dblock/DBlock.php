<?php

/**
 *  ласс дл€ работы с блоками данных/
 */
class DBlock extends DBaseClass {

    var $group;  //дл€ работы с группами

    /**
     *  онструктор
     */

    function DBlock() {
        $this->sql = new HolySQL("system_data_block");
        $this->sql2 = new HolySQL("system_data_block");
        $this->group = new DBlockGroup();
    }

    /**
     * ¬озвращает список блоков в выбранной группе
     * 
     * @param string/int $group  <p>выбранна€ группа</p>
     * @param string/array $filter=Array()  <p>[не об€зательное] фильтр блоков</p>
     * @param string $sort = "sort ASC"  <p>[не об€зательное] пор€док сортировки блоков</p>
     */
    function GetListByGroup($group, $filter = Array(), $sort = "sort ASC") {
        if (is_numeric($group))
            $filter["bgroup"] = $group;
        else
            $filter["bgroup"] = $this->group->GetIDByName($group);

        $this->sql->Select($filter, $sort);
    }

    /**
     * ¬озвращает список блоков в выбранной группе
     * 
     * @param array $values  <p>данные дл€ создани:</p>
     * <br><b>fav</b> - bool - [не об€зательное] отображать блок в списке любимых
     * <br><b>hide_folders</b> - bool - [не об€зательное] скрыть папки
     * <br><b>name</b> - string - код
     * <br><b>caption</b> - string - им€
     * <br><b>group</b> - string - группа блока
     * <br><b>hide_folders</b> - true/false - [не об€зательное] скрыть возможность создани€ папок
     * <br><b>hide_folders2</b> - true/false - [не об€зательное] скрыть возможность создани€ папок выше первого уровн€ вложенности
     * <br><b>hide_code</b> - true/false - [не об€зательное] скрыть возможность указывать код
     */
    function Create($values) {
        $name = $values['name'];
        $caption = $values['caption'];
        $group = $values['group'];
        if (!isset($values['sort']))
            $values['sort'] = 0;
        $sort = $values['sort'];
        if (intval($sort) == 0)
            $sort = $this->sql->GetSortAuto();

        if (!isset($values['fav']))
            $values['fav'] = 0;
        $fav = $values['fav'];
        if (!isset($values['hide_folders']))
            $values['hide_folders'] = 0;
        $hide_folders = $values['hide_folders'];
        if (!isset($values['hide_folders2']))
            $values['hide_folders2'] = 0;
        $hide_folders2 = $values['hide_folders2'];
        if (!isset($values['hide_code']))
            $values['hide_code'] = 0;
        $hide_code = $values['hide_code'];

        if (!is_numeric($group))
            $group = $this->group->GetIDByName($group);
        $this->sql->Insert(Array(
            "caption" => $caption,
            "name" => $name,
            "sort" => $sort,
            "bgroup" => $group,
            "fav" => $fav,
            "hide_folders" => $hide_folders,
            "hide_folders2" => $hide_folders2,
            "hide_code" => $hide_code,
                )
        );

        //создаЄм таблицу!

        $query = "CREATE TABLE `" . $name . "` (`id` INT NOT NULL AUTO_INCREMENT ,`name` TEXT CHARACTER SET cp1251 COLLATE cp1251_general_ci NOT NULL ,`caption` TEXT CHARACTER SET cp1251 COLLATE cp1251_general_ci NOT NULL ,`sort` INT NOT NULL ,`parent` INT NOT NULL ,`folder` INT NOT NULL ,PRIMARY KEY ( `id` ) );";
        $this->sql->Query($query);
    }

    /**
     * ѕереместить блок $name в группу $group
     * 
     * @param string/id $name  <p>код/id блока</p>
     * @param string/int $group  <p>группа дл€ перемещени€</p>
     */
    function Move($name, $group) {
        if (is_numeric($name))
            $name = $this->group->GetNameByID($name);

        if (!is_numeric($group))
            $group = $this->group->GetIDByName($group);

        $this->sql->Update(Array("name" => $name), Array("bgroup" => $group));
    }

    /**
     * ”далить блок $name
     * 
     * @param string $name  <p>код блока</p>
     */
    function Delete($name) {
        $this->sql->Query("DROP TABLE " . $name . "");
        $this->sql->Delete("name='" . $name . "'");
    }

    /**
     * »зменить данные блока (данные нужно указывать целиком!)
     * 
     * @param string/int $id  <p>код/id блока</p>
     * @param array $values  <p>данные блока</p>
     */
    function Update($id, $values) {

        if (is_numeric($id)) {
            if (intval($id) != 0)
                $val2 = $this->sql2->SelectOne("id=" . $id);
        } else {
            $val2 = $this->sql2->SelectOne("name='" . $id . "'");
        }

        //переименовывание блока
        if ($val2['name'] != $values["name"]) {
            $this->sql2->Query("RENAME TABLE " . $val2['name'] . " TO " . $values["name"]);
        };


        if (is_numeric($id)) {
            if (intval($id) != 0)
                $this->sql->Update(Array("id" => $id), $values);
        } else {
            $this->sql->Update(Array("name" => $id), $values);
        }
    }

}

;
?>