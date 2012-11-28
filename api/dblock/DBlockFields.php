<?php

/**
 * Класс для работы со свойствами блоков.
 */
class DBlockFields extends DBaseClass {

    var $datab;
    var $typeb;
    var $sql2;

    /**
     * Конструктор.
     */
    function DBlockFields() {
        $this->sql = new HolySQL("system_data_block_fields");
        $this->sql2 = new HolySQL("system_data_block_fields");
        $this->datab = new DBlock();
        $this->typeb = new DBlockTypes();
    }

    /**
     * Возвращает список свойств по выбранному блоку
     * 
     * @param string/int dblock  <p>выбранный блок</p>
     * @param string/array $filter=Array()  <p>[не обязательное] фильтр свойств</p>
     * @param string $sort = "sort ASC"  <p>[не обязательное] порядок сортировки свойств</p>
     */
    function GetListByBlock($dblock, $filter = Array(), $sort = "sort ASC") {
        if (is_numeric($dblock))
            $filter['data_block'] = $dblock;
        else
            $filter['data_block'] = $this->datab->GetIDByName($dblock);
        $this->sql->Select($filter, $sort);
    }

    /**
     * Изменить данные свойства (данные нужно указывать целиком!)
     * 
     * @param string $id  <p>код свойства</p>
     * @param array $values  <p>данные свойства</p>
     */
    function Update($id, $values) {
        //@fix поправить для id-шников
        if (is_numeric($values['data_block'])) {
            
        } else {
            $values['data_block'] = $this->datab->GetIDByName($values['data_block']);

            //проверить, не изменился ли код, если да - то всё будет сложнее
            $type_info = $this->sql2->SelectOne("id=" . $id);
            if (isset($type_info['name']))
                if (isset($values['name']))
                    if ($type_info['name'] != $values['name']) {
                        //нужно переименовать столбец, а потом уже всё остальное
                        $type = $this->typeb->GetByID($values['type']);

                        $type = $type['basetype'];
                        $block_name = $this->datab->GetByID($values['data_block']);

                        $block_name = $block_name['name'];

                        $sql2 = new HolySQL($block_name);
                        $sql2->RenameColumn($type_info['name'], $values['name'], $type);
                    };
        }
        parent::Update($id, $values);
    }

    /**
     * Создает новое свойство
     * 
     * @param array $values  <p>данные свойства:</p>
     * <br><b>caption</b> - string - заголовок
     * <br><b>name</b> - string - код
     * <br><b>data_block</b> - string - name блока
     * <br><b>type</b> - string - name типа
     * <br><b>sort</b> - int - порядок сортировки
     * <br><b>required</b> - bool - обязательное ли поле, по умолчанию false
     * <br><b>multiple</b> - bool - множественное,false (0 нет, 1 да)
     * <br><b>owner_type</b> - int - владелец (0 - элементы,1 - папки и элементы)
     */
    
    function Create($values = Array()) {
        $block_name = $values['data_block'];
        $values['data_block'] = $this->datab->GetIDByName($values['data_block']);
        
        //@fix поправить работу с id
        if (is_numeric($values['type'])) {
            
        }
        else
            $values['type'] = $this->typeb->GetIDByName($values['type']);

        if (!isset($values['sort']))
            $values['sort'] = $this->sql->GetSortAuto();
        if ($values['sort'] == 0)
            $values['sort'] = $this->sql->GetSortAuto();
        if (!isset($values['required']))
            $values['required'] = 0;
        if (!isset($values['multiple']))
            $values['multiple'] = 0;
        if (!isset($values['dgroup']))
            $values['dgroup'] = 0;
        if (!isset($values['owner_type']))
            $values['owner_type'] = 0;
        if (!isset($values['not_element']))
            $values['not_element'] = 0;

        $this->sql->Insert($values);


        if ($values['multiple'] == 1)
            $type_info['basetype'] = "TEXT";
        elseif ($values['multiple'] == 2)
            $type_info['basetype'] = "INT";
        else
            $type_info = $this->typeb->GetByID($values['type']);
        $sql2 = new HolySQL($block_name);
        $sql2->AddColumn($values['name'], $type_info['basetype']);
    }

    /**
     * Удаляет свойство
     * 
     * @param int/string $name <p>ID/код удаляемого свойства</p>
     */
    function Delete($name) {
        if (is_numeric($name))
            $name_name = $this->GetNameByID($name);
        else {
            $name_name = $name;
            $name = $this->GetIDByName($name);
        };
        $fields = $this->sql->SelectOnce("id='" . $name . "'");
        if (isset($fields['data_block']))
            if ($fields['data_block']) {
                $bname = $this->datab->GetNameByID($fields['data_block']);
                $sql2 = new HolySQL($bname);
                $sql2->DeleteColumn($name_name);
                $this->sql->Delete("id='" . $name . "'");
            };
    }

}

;
?>