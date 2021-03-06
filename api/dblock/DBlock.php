<?php

//кэши для того, что бы постоянно не дергать одно и то же
//в пределах однного скрипта
$_full_blocks_array=array();
/**
 * Класс для работы с блоками данных/
 */
class DBlock extends DBaseClass {

    var $group;  //для работы с группами

    /**
     * Конструктор
     */

    function GetIDByName($name) {
        $dat = $this->GetByID($name);
        if (!isset($dat['id']))
            $dat['id'] = false;
        return $dat['id'];
    }
    
    function GetByID($id = 0, $sort = "sort ASC") {
        global $_full_blocks_array;
        if (isset($_full_blocks_array[$id]))
            return $_full_blocks_array[$id];
        else
            parent::GetByID ($id,$sort);
    }
    
    function DBlock() {
        global $_full_blocks_array;
        $this->sql = new HolySQL("system_data_block");
        $this->sql2 = new HolySQL("system_data_block");
        $this->group = new DBlockGroup();
        
        if (count($_full_blocks_array)==0){
            $this->sql->Select();
            while ($_tmp_block=$this->sql->GetNext()){
                $_full_blocks_array[$_tmp_block['id']]=$_tmp_block;
                $_full_blocks_array[$_tmp_block['name']]=$_tmp_block;
            }
        }
    }

    /**
     * Возвращает список блоков в выбранной группе
     * 
     * @param string/int $group  <p>выбранная группа</p>
     * @param string/array $filter=Array()  <p>[не обязательное] фильтр блоков</p>
     * @param string $sort = "sort ASC"  <p>[не обязательное] порядок сортировки блоков</p>
     */
    function GetListByGroup($group, $filter = Array(), $sort = "sort ASC") {
        if (is_numeric($group))
            $filter["bgroup"] = $group;
        else
            $filter["bgroup"] = $this->group->GetIDByName($group);

        $this->sql->Select($filter, $sort);
    }

    /**
     * Создает новый блок
     * 
     * @param array $values  <p>данные для создани:</p>
     * <br><b>fav</b> - bool - [не обязательное] отображать блок в списке любимых
     * <br><b>hide_folders</b> - bool - [не обязательное] скрыть папки
     * <br><b>name</b> - string - код
     * <br><b>caption</b> - string - имя
     * <br><b>group</b> - string - группа блока
     * <br><b>hide_folders</b> - true/false - [не обязательное] скрыть возможность создания папок
     * <br><b>hide_folders2</b> - true/false - [не обязательное] скрыть возможность создания папок выше первого уровня вложенности
     * <br><b>hide_code</b> - true/false - [не обязательное] скрыть возможность указывать код
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
        
        if (!isset($values['childs']))
            $values['childs'] = "";        
        $childs=$values['childs'];
        if (!isset($values['bind_cache']))
            $values['bind_cache']="";
        $bind_cache=$values['bind_cache'];
		
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
            "childs"=>$childs,
			"bind_cache"=>$bind_cache,
                )
        );

        //создаём таблицу!
        global $_CONFIG;
        $query = "CREATE TABLE `" . $name . "` (`id` INT NOT NULL AUTO_INCREMENT ,`name` TEXT CHARACTER SET ".$_CONFIG['CODEPAGE']." COLLATE ".$_CONFIG['COLLATE']." NOT NULL ,`caption` TEXT CHARACTER SET ".$_CONFIG['CODEPAGE']." COLLATE ".$_CONFIG['COLLATE']." NOT NULL ,`sort` INT NOT NULL ,`parent` INT NOT NULL ,`folder` INT NOT NULL ,PRIMARY KEY ( `id` ) );";
        $this->sql->Query($query);
    }

    /**
     * Переместить блок $name в группу $group
     * 
     * @param string/id $name  <p>код/id блока</p>
     * @param string/int $group  <p>группа для перемещения</p>
     */
    function Move($name, $group) {
        if (is_numeric($name))
            $name = $this->group->GetNameByID($name);

        if (!is_numeric($group))
            $group = $this->group->GetIDByName($group);

        $this->sql->Update(Array("name" => $name), Array("bgroup" => $group));
    }

    /**
     * Удалить блок $name
     * 
     * @param string $name  <p>код блока</p>
     */
    function Delete($name) {
        $this->sql->Query("DROP TABLE " . $name . "");
        $this->sql->Delete("name='" . $name . "'");
        global $_full_blocks_array;
        unset($_full_blocks_array);
    }

    /**
     * Изменить данные блока (данные нужно указывать целиком!)
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
        global $_full_blocks_array;
        unset($_full_blocks_array);
    }

}

;
?>