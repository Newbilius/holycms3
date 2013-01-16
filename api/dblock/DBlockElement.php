<?php

/**
 * Операции над элементами.
 */
class DBlockElement extends DBaseClass {

    public $fieldsb;
    public $typesb;
    public $table;
    public $datab;
    public $table_name;

    /**
     * @param string/array <p>таблица (код!) или массив,в котором есть ключ ['table']</p>
     * @return DBlockElement
     */
    function Delete($id) {
        $_func_name1="DBlockBeforeDelete_".$this->table_name;
        $_func_name2="DBlockBeforeDelete";
        $_func_name3="DBlockAfterDelete_".$this->table_name;
        $_func_name4="DBlockAfterDelete";

        $_before_data=NULL;
        
        if ((function_exists($_func_name1)) ||
                (function_exists($_func_name2)) ||
                (function_exists($_func_name3)) ||
                (function_exists($_func_name4))){
            $_before_data = GetElement($this->table_name);
        }
        
        
        if (function_exists($_func_name1)) {
            $_before_data = $_func_name1($_before_data);
        };
        if (function_exists($_func_name2)) {
            if ($_before_data !== NULL) {
                $_before_data = $_func_name2($_before_data);
            };
        };
        
        if ($_before_data!==NULL)
            parent::Delete($id);
        
        if (function_exists($_func_name3)) {
            if ($_before_data !== NULL) {
                $_before_data = $_func_name1($_before_data);
            };
        };
        if (function_exists($_func_name4)) {
            if ($_before_data !== NULL) {
                $_before_data = $_func_name2($_before_data);
            };
        };
    }    
    
    /**
     * @param string/array <p>таблица (код!) или массив,в котором есть ключ ['table']</p>
     * @return DBlockElement
     */
    function DBlockElement($values) {
        parent::__construct();
        $this->datab = new DBlock();

        if (!is_array($values)) {
            $tmp = $values;
            unset($values);
            $values['table'] = $tmp;
        };
        $this->sql = new HolySQL($values['table']);
        //получить список подходящих полей
        $this->typesb = new DBlockTypes();
        $this->fieldsb = new DBlockFields();
        $this->fieldsb->GetListByBlock($values['table']);

        $this->table = $this->datab->GetIDByName($values['table']);
    }

    /**
     * 
     * Получает список вложенных по элементу по выбранному блоку данных.
     * 
     * @param integer $id <p>id элемента текущего блока данных</p>
     * @param string $block <p>имя блока</p>
     */
    function GetElementChilds($id,$block){
        $block_data_src=new DBlock();
        $block_data=$block_data_src->GetByID($this->table);
        $childs_data=explode("/",$block_data['childs']);
        foreach ($childs_data as &$child_data){
            $child_data=explode(";",$child_data);
            $child_data_new[$child_data[0]]=$child_data;
        };
        $tmp_item=$this->GetByID($id);
        $filter[$child_data_new[$block][2]]=$tmp_item[$child_data_new[$block][1]];
        $tmp_res=new DBlockElement($block);
        $tmp_res->GetList($filter);
        $items=$tmp_res->GetFullList();
        return $items;
    }
    
    /**
     * Обновляет один элемент
     * 
     * @param string/int $ID <p>ID или код элемента</p>
     * @param array $values <p>массив значений</p>
     * @param arrray $not_only_selected=true <p>[optional] при false обновляет только выбранные свойства, при true - забивает не указанные пустыми значениями и 0 для int/false</p>
     */
    function Update($ID, $values, $not_only_selected = true) {
        $insert_values = Array();
        $_func_name1="DBlockBeforeUpdate_".$this->table_name;
        $_func_name2="DBlockBeforeUpdate";
        $_func_name3="DBlockAfterUpdate_".$this->table_name;
        $_func_name4="DBlockAfterUpdate";
        
        $_before_data=NULL;
        
        if ((function_exists($_func_name1)) ||
                (function_exists($_func_name2)) ||
                (function_exists($_func_name3)) ||
                (function_exists($_func_name4))){
            $_before_data = GetElement($this->table_name, $ID);
        }
        
        if (function_exists($_func_name1)) {
            $values = $_func_name1($_before_data, $values);
        };
        if (function_exists($_func_name2)) {
            if ($values !== NULL) {
                $values = $_func_name2($_before_data, $values);
            };
        };
        if ($values !== NULL){
        //пройтись по полям
        $this->fieldsb->GetListByBlock($this->table);
        while ($field = $this->fieldsb->GetNext())
            if ((isset($values[$field['name']])) || ($not_only_selected)) {
                $FIRST = 0;
                $field['type_int'] = $field['type'];
                if (is_numeric($field['type_int'])) {
                    $field['type_name'] = $this->typesb->GetNameByID($field['type']);
                }
                else
                    $field['type_name'] = $field['type']; {
                    $path = FOLDER_ROOT . "/site/forms/" . $this->typesb->GetNameByID($field['type']) . ".php";
                    $path0 = FOLDER_ROOT . "/engine/api/forms/" . $this->typesb->GetNameByID($field['type']) . ".php";
                    if (!file_exists($path)) {
                        $path = $path0;
                    };
                    if (!file_exists($path)) {
                        $field['type'] = "text";
                        $path = FOLDER_ROOT . "/engine/api/forms/text.php";
                    };
                    include_once($path);
                    if (is_numeric($field['type']))
                        $name = "CForm_" . $this->typesb->GetNameByID($field['type']);
                    else
                        $name = "CForm_" . $field['type'];
                    $obj = new $name;

                    $not_insert = false;
                    if ($field['multiple'])
                        if (isset($values[$field['name']][0]))
                            $old_type_name_bug = $values[$field['name']];

                    if ($field['multiple']) {

                        unset($tmp_sort_of_multi_item);
                        $spec_counter = -1;

                        $insert_values[$field['name']] = "";

                        if (isset($values[$field['name']]))
                            if ($values[$field['name']])
                                foreach ($values[$field['name']] as $num_of_many => $vvv)
                                    if ($num_of_many !== "SORT")
                                        if ($vvv != "") {

                                            $spec_counter++;
                                            $FIRST++;
                                            if ($FIRST >= 2)
                                                $insert_values[$field['name']].=";";

                                            //@fix некрасиво
                                            @$not_insert = $obj->NeedInsert($vvv);

                                            if (!$not_insert)
                                                if ($vvv) {
                                                    if (isset($values[$field['name']]['SORT'][$spec_counter]))
                                                        $NUM_OF_MULTI = $values[$field['name']]['SORT'][$spec_counter];
                                                    else
                                                        $NUM_OF_MULTI = 100;

                                                    if (isset($tmp_sort_of_multi_item[$NUM_OF_MULTI]))
                                                        $tmp_sort_of_multi_item[$NUM_OF_MULTI].=";";
                                                    else
                                                        $tmp_sort_of_multi_item[$NUM_OF_MULTI] = "";
                                                    $tmp_sort_of_multi_item[$NUM_OF_MULTI].=str_replace(";", "[&&&&&&]", $obj->AfterEdit($field['name'], $vvv, $field['add_values'], $field['multiple']));
                                                };
                                        };

                        $insert_values[$field['name']] = "";

                        if (isset($tmp_sort_of_multi_item))
                            if (is_array($tmp_sort_of_multi_item)) {
                                ksort($tmp_sort_of_multi_item);
                                if (count($tmp_sort_of_multi_item) > 0) {
                                    $insert_values[$field['name']] = implode(";", $tmp_sort_of_multi_item);
                                    $insert_values_tmp = explode(";", $insert_values[$field['name']]);
                                    $insert_values_tmp = array_filter($insert_values_tmp);
                                    $insert_values[$field['name']] = implode(";", $insert_values_tmp);
                                };
                            };
                    } else {
                        //@fix некрасиво
                        @$not_insert = $obj->NeedInsert($values[$field['name']]);

                        if (!isset($values[$field['name']]))
                            if (!$not_insert)
                                $values[$field['name']] = "";


                        if (!$not_insert) {

                            $update_now_value = $obj->AfterEdit($field['name'], $values[$field['name']], $field['add_values'], $field['multiple']);

                            $insert_values[$field['name']] = $update_now_value;
                        };
                    }
                };

                //если поле множественное первого типа, склеить значение
            };

        if (isset($values['folder']))
            $insert_values['folder'] = intval($values['folder']);
        if (isset($values['parent']))
            $insert_values['parent'] = intval($values['parent']);
        if (isset($values['sort']))
            $insert_values['sort'] = $values['sort'];
        if (isset($values['name']))
            $insert_values['name'] = $values['name'];
        if (isset($values['caption']))
            $insert_values['caption'] = $values['caption'];
        
        if ($insert_values !== NULL) {
            if (is_numeric($ID)) {
                $this->sql->Update(Array("id" => intval($ID)), $insert_values);
            } else {
                $this->sql->Update(Array("name" => $ID), $insert_values);
            }
        };
        
        if (function_exists($_func_name3)) {
                $insert_values = $_func_name3($_before_data, $insert_values);
            };
        if (function_exists($_func_name4)) {
                $insert_values = $_func_name4($_before_data, $insert_values);
        };

        $this->fieldsb->GetListByBlock($this->table);
        };
    }

    /**
     * Возвращает следующий элемент после запроса. Вызывается после <b>GetList()</b>.
     * 
     */
    function GetNext() {
        $tmp = $this->sql->GetNext();
        $this->fieldsb->GetList(Array("data_block" => $this->table, "multiple" => 1));
        while ($ttt = $this->fieldsb->GetNext())
            if (isset($tmp[$ttt['name']])) {
                $tmp[$ttt['name']] = explode(";", $tmp[$ttt['name']]);
                foreach ($tmp[$ttt['name']] as &$_tmp_item)
                    $_tmp_item = str_replace("[&&&&&&]", ";", $_tmp_item);
            };
        return $tmp;
    }

    /**
     * Добавляет новый элемент
     * 
     * @param array $values <p>массив значений</p>
     */
    function Add($values) {
        $_func_name1="DBlockBeforeAdd_".$this->table_name;
        $_func_name2="DBlockBeforeAdd";
        $_func_name3="DBlockAfterAdd_".$this->table_name;
        $_func_name4="DBlockAfterAdd";

        $_before_data=NULL;
        
        if (function_exists($_func_name1)) {
            $values = $_func_name1($values);
        };
        if (function_exists($_func_name2)) {
            if ($values !== NULL) {
                $values = $_func_name2($values);
            };
        };
        if ($values !== NULL){
        $insert_values = Array();
        //пройтись по полям
        //PrePrint($values);
        $this->fieldsb->GetListByBlock($this->table);
        while ($field = $this->fieldsb->GetNext()) {

            if (!isset($values[$field['name']]))
                $values[$field['name']] = ""; {
                $path = FOLDER_ROOT . "/site/forms/" . $this->typesb->GetNameByID($field['type']) . ".php";
                $path0 = FOLDER_ROOT . "/engine/api/forms/" . $this->typesb->GetNameByID($field['type']) . ".php";
                if (!file_exists($path)) {
                    $path = $path0;
                };
                if (!file_exists($path)) {
                    $field['type'] = "text";
                    $path = FOLDER_ROOT . "/engine/api/forms/text.php";
                };
                include_once($path);
                if (is_numeric($field['type']))
                    $name = "CForm_" . $this->typesb->GetNameByID($field['type']);
                else
                    $name = "CForm_" . $field['type'];
                $obj = new $name;

                $FIRST = 0;

                if ($field['multiple']) {
                    if (isset($values[$field['name']][0])) {
                        $insert_values[$field['name']] = "";
                        $spec_counter = -1;

                        unset($tmp_sort_of_multi_item);

                        foreach ($values[$field['name']] as $num_of_many => $vvv)
                            if ($num_of_many !== "SORT")
                                if ($vvv != "") {
                                    $spec_counter++;
                                    $FIRST++;
                                    if ($FIRST >= 2)
                                        $insert_values[$field['name']].=";";

                                    $field['add_values'].=";";
                                    if (isset($values[$field['name']]['SORT'][$spec_counter]))
                                        $NUM_OF_MULTI = $values[$field['name']]['SORT'][$spec_counter];
                                    else
                                        $NUM_OF_MULTI = 100;

                                    if (isset($tmp_sort_of_multi_item[$NUM_OF_MULTI]))
                                        $tmp_sort_of_multi_item[$NUM_OF_MULTI].=";";
                                    else
                                        $tmp_sort_of_multi_item[$NUM_OF_MULTI] = "";
                                    $tmp_sort_of_multi_item[$NUM_OF_MULTI].=str_replace(";", "[&&&&&&]", $obj->BeforeAdd($field['name'], $vvv, $field['add_values']));
                                };

                        $insert_values[$field['name']] = "";

                        if (is_array($tmp_sort_of_multi_item)) {
                            ksort($tmp_sort_of_multi_item);
                            if (count($tmp_sort_of_multi_item) > 0) {
                                $insert_values[$field['name']] = implode(";", $tmp_sort_of_multi_item);
                                $insert_values_tmp = explode(";", $insert_values[$field['name']]);
                                $insert_values_tmp = array_filter($insert_values_tmp);
                                $insert_values[$field['name']] = implode(";", $insert_values_tmp);
                            };
                        };
                    };
                }
                else
                    $insert_values[$field['name']] = $obj->BeforeAdd($field['name'], $values[$field['name']], $field['add_values']);
            }
        };
        //отдельно отрабатываем стандартные поля

        if (!isset($values['sort']))
            $values['sort'] = $this->sql->GetSortAuto();
        if (intval($values['sort']) == 0)
            $values['sort'] = $this->sql->GetSortAuto();

        if (!isset($values['parent']))
            $values['parent'] = 0;

        if (!isset($values['folder']))
            $values['folder'] = 0;

        if (!isset($values['name']))
            $values['name'] = MD5(time());
        if (!isset($values['caption']))
            $values['caption'] = time();

        $insert_values['folder'] = intval($values['folder']);
        $insert_values['parent'] = intval($values['parent']);
        $insert_values['sort'] = $values['sort'];
        $insert_values['name'] = $values['name'];
        $insert_values['caption'] = $values['caption'];

        if ($insert_values['parent'] < 0) {
            $insert_values['parent'] = 0;
        }
        
        if ($values !== NULL)
            $this->sql->Insert($insert_values);
        
        if (function_exists($_func_name3)) {
            if ($values !== NULL) {
                $values = $_func_name3($insert_values);
            };
        };
        if (function_exists($_func_name4)) {
            if ($values !== NULL) {
                $values = $_func_name4($insert_values);
            };
        };
        
        $this->fieldsb->GetListByBlock($this->table);
        };
    }

}

;
?>