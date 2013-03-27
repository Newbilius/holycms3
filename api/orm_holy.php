<?php

class HolyElements extends HolyORM {

    protected $fields;
    protected $fields_list;

    public static function GetClassName($name) {
        $class_name = "HolyElements";
        if (class_exists($name)) {
            $class_name = $name;
        };
        if (class_exists($name . "Model")) {
            $class_name = $name . "Model";
        };
        if (class_exists($name . "_Model")) {
            $class_name = $name . "Model";
        };
        return $class_name;
    }

    protected function LoadPropertys() {
        $this->fields = new DBlockFields();
        $this->fields->GetListByBlock($this->_table_name);
        $this->fields_list = $this->fields->GetFullList();
        //if (count($this->fields_list)>0)
        //preprint($this->fields_list);
    }

    function SetTable($name = "") {
        parent::SetTable($name);
        $this->LoadPropertys();
        return $this;
    }

}

class HolyORM {

    protected $_sql;
    protected $_table_name = "";
    protected $_load_on = false;
    protected $_data;
    protected $_filter = "";
    protected $_order = "";
    protected $_fields_get = "*";
    protected $_count_on_page = 0;
    protected $_page = 0;
    protected $_item_number = 0;
    protected $_changed_fields;
    protected $_fields;

    function Debug($mode = true) {
        $this->_sql->debug = $mode;
        return $this;
    }

    function SetTable($name = "") {
        if ($name != "") {
            $this->_table_name = $name;
        };

        if ($this->_table_name != "") {
            $this->_sql->ChangeTable($this->_table_name);
            $this->_PrepareDefaultData();
        }

        return $this;
    }

    protected function _PrepareDefaultData() {
        //preprint($this);
        $this->_sql->Query("DESC {$this->_table_name}");
        $this->_fields = array();
        $_fields_tmp = $this->_sql->GetAll();
        foreach ($_fields_tmp as $field) {
            $this->_fields[$field['Field']] = $field['Field'];
            $this->_data[$field['Field']] = "";
        };
        preprint($this->_fields);
    }

    function HolyORM($name = "") {
        $this->_sql = new HolySQL();
        $this->SetTable($name);
        return $this;
    }

    public static function GetClassName($name) {
        $class_name = "HolyORM";
        if (class_exists($name)) {
            $class_name = $name;
        };
        if (class_exists($name . "Model")) {
            $class_name = $name . "Model";
        };
        if (class_exists($name . "_Model")) {
            $class_name = $name . "Model";
        };
        return $class_name;
    }

    public static function Factory($name) {
        $class_name = HolyORM::GetClassName($name);
        return new $class_name();
    }

    function IsLoaded() {
        return $this->_load_on;
    }

    function LoadData() {
        $this->_load_on = true;
    }

    function set($name, $value) {
        $this->$name = $value;
    }

    function Load($id) {
        $this->_load_on = true;
        $this->_data = $this->_sql->SelectOne("id=" . intval($id));
        $this->LoadData();
        return $this;
    }

    function PrepareOrder($direction) {
        if ($direction != "ASC" && $direction != "DESC")
            return "ASC";
    }

    function Order($field, $direction = "ASC") {
        if ($this->_order != "") {
            $this->_order.=",";
        }
        $this->_order.=$field . " " . $this->PrepareOrder($direction);

        return $this;
    }

    function ClearOder() {
        $this->_order = "";
    }

    function ClearWhere() {
        $this->_filter = "";
    }

    function StartSubQuery() {
        $this->_filter.=" ( ";
    }

    function EndSubQuery() {
        $this->_filter.=" ) ";
    }

    protected function PrepareOneValue($value) {
        if (is_numeric($value)) {
            return $value;
        } else {
            return "'{$value}'";
        }
    }

    protected function PrepareValue($value) {
        if (is_array($value)) {
            $full_text = "";
            foreach ($value as $val) {
                if ($full_text != "") {
                    $full_text.=",";
                } else {
                    $full_text.="(";
                };
                $full_text.=$this->PrepareOneValue($val);
            }
            $full_text.=")";
            return $full_text;
        } else {
            return $this->PrepareOneValue($value);
        }
    }

    protected function _where($field, $command, $value, $glue) {
        if ($this->_filter != "") {
            $this->_filter.=" {$glue} ";
        }
        $this->_filter.="`" . $field . "`" . $command . " " . $this->PrepareValue($value);

        return $this;
    }

    function OrWhere($field, $command, $value) {
        $this->_where($field, $command, $value, "OR");

        return $this;
    }

    function AndWhere($field, $command, $value) {
        $this->_where($field, $command, $value, "AND");

        return $this;
    }

    function Where($field, $mode, $value) {
        $this->AndWhere($field, $mode, $value);

        return $this;
    }

    function Find() {
        $this->_select();
        $this->_data = $this->_sql->GetNext();
        $this->LoadData();
        return $this->_sql->GetNext();
    }

    protected function FindAsObjects() {
        $items = array();
        while ($item = $this->_sql->GetNext()) {
            $this->_data = $item;
            $this->LoadData();
            $items[$this->_data['id']] = clone $this;
        };
        return $items;
    }

    protected function FindAsArray() {
        $items = array();

        while ($item = $this->_sql->GetNext()) {
            $this->_data = $item;
            $items[$this->_data['id']] = $this->_data;
        };
        return $items;
    }

    protected function _select() {
        $this->_sql->Select($this->_filter, $this->_order, $this->_fields_get, $this->_count_on_page, $this->_page);
        if ($this->_sql->GetCount() > 0) {
            $this->_load_on = true;
        }
    }

    function FindAllAsObjects() {
        $this->_select();
        $items = $this->FindAsObjects();
        return $items;
    }

    function FindAllAsArray() {
        $this->_select();
        $items = $this->FindAsArray();
        return $items;
    }

    function FindAll() {
        return $this->FindAllAsObjects();
    }

    function FindAllArray() {
        return $this->FindAllAsArray();
    }

    public function __set($name, $value) {
        if (isset($this->_data[$name])) {
            $this->_changed_fields[$name] = $name;
            $this->_data[$name] = $value;
        } else {
            die("несуществующе поле {$name}");  //@todo эксепшн
        };
    }

    public function __get($name) {
        if (isset($this->_data[$name])) {
            return $this->_data[$name];
        } else {
            die("несуществующе поле {$name}");  //@todo эксепшн
        }
    }

    protected function _PrepareDataToSave($data) {
        foreach ($data as $num => &$item) {
            if (is_array($item)) {
                $item = serialize($item);
            }
        }
        if (intval($data['id'])==0){
            unset($data['id']);
        };
        return $data;
    }

    protected function _UpdateItem($data) {
        $data = $this->_PrepareDataToSave($data);
        $this->_sql->Update(Array("id" => intval($this->_data['id'])), $data);
    }

    protected function _CreateItem($data) {
        $data = $this->_PrepareDataToSave($data);
        preprint($data);
        $this->_sql->Insert($data);
        //$this->_sql->Update(Array("id" => intval($this->_data['id'])), $data);
    }
    
    public function _SaveComplete($data) {
        if ($this->IsLoaded()) {
            $this->_UpdateItem($data);
        } else {
            $this->_CreateItem($data);
        }
    }

    public function Save($data_to_save = array()) {
        if (count($this->_changed_fields) == 0)
            return false;
        if (count($data_to_save) == 0) {
            foreach ($this->_changed_fields as $field) {
                $data_to_save[$field] = $this->_data[$field];
            }
        };
        $this->_SaveComplete($data_to_save);
        return true;
    }

    public function SaveAll($data_to_save = array()) {
        if (count($this->_changed_fields) == 0)
            return false;
        if (count($data_to_save) == 0) {
            foreach ($this->_data as $field => $value) {
                $data_to_save[$field] = $value;
            }
        };
        $this->_SaveComplete($data_to_save);
        return true;
    }

}

?>