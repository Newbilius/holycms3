<?php

class HolyORM {

    protected $_sql;
    protected $_table_name = "";
    protected $_load_on = false;
    protected $_data;
    protected $_filter = "";
    protected $_order = "";
    protected $_fields = "*";
    protected $_count_on_page = 0;
    protected $_page = 0;

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
        }

        return $this;
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

    function Loaded() {
        return $this->_load_on;
    }

    function LoadData() {
        foreach ($this->_data as $_code => $_item) {
            $this->$_code = $_item;
        }
    }

    function Load($id) {
        $this->_data = $this->_sql->SelectOne("id=" . intval($id));
        $this->LoadData();
        return $this;
    }

    function PrepareOrder($direction) {
        if ($direction != "ASC" && $direction != "DESC")
            return "ASC";
    }

    function Prepare() {
        
    }

    function Order($field, $direction = "ASC") {
        if ($this->_order != "") {
            $this->_order.=",";
        }
        $this->_order.=$field . " " . $direction;

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

    protected function PrepareValue($value) {
        if (is_numeric($value)) {
            return $value;
        } else {
            return "'{$value}'";
        }
    }

    function _where($field, $command, $value, $glue) {
        if ($this->_filter != "") {
            $this->_filter.=" {$glue} ";
        }
        $this->_filter.=$field . $command.=$this->PrepareValue($value);

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
        $this->_sql->Select($this->_filter, $this->_order, $this->_fields, $this->_count_on_page, $this->_page);
        $this->_data = $this->_sql->GetNext();
        $this->LoadData();
        return $this->_sql->GetNext();
    }

    function FindAll() {
        $items = array();

        $this->_sql->Select($this->_filter, $this->_order, $this->_fields, $this->_count_on_page, $this->_page);
        while ($item = $this->_sql->GetNext()) {
            $this->_data = $item;
            $this->LoadData();
            $items[] = clone $this;
        };

        return $items;
    }

}

?>