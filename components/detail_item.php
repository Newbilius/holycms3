<?php

class Component_detail_item extends Component {

    protected function GetDefaults() {
        return array(
            "template" => "default",
            "cache" => false,
            "cache_time" => 90,
            "cache_key" => null,
            "count" => "",
            "order" => "sort ASC",
            "page" => "1",
            "paginator_template" => "",
            "page_base_link" => "?page=",
            "filter" => array(),
            "debug" => false,
            "draw_paginator" => false,
            "add_to_bread" => false,
            "set_title" => false,
            "add_to_bread_parent" => false,
        );
    }

    protected function GetParamsValidators() {
        return array(
            Array(
                'name' => "table",
                'rule' => "not_empty",
            ),
            Array(
                'name' => "ID",
                'rule' => "not_empty",
            ),
        );
    }

    protected function Action() {
        global $_global_bread;
        global $_OPTIONS;

        if (!is_array($this->params['filter'])) {
            $this->params['filter'] = Array();
        }

        $res = new DBlockElement($this->params['table']);
        $filter = $this->params['filter'];

        if (is_numeric($this->params['ID']))
            $filter[] = Array("id", "=", $this->params['ID']);
        else
            $filter[] = Array("name", "=", $this->params['ID']);

        $res->sql->debug = $this->params['debug'];
        $result = $res->GetOne($filter, $this->params['order']);

        if (isset($result['id'])) {
            if ($this->params['add_to_bread_parent'])
                if (isset($result['parent']))
                    if ($result['parent']) {
                        $res2 = new DBlockElement($this->params['table']);
                        $res2_data = $res2->GetOne("id=" . $result['parent']);
                        if (isset($res2_data[$this->params['add_to_bread_parent']]))
                            AddToBread($res2_data[$this->params['add_to_bread_parent']], $this->params['back_url'] . $res2_data['id']);
                        $_OPTIONS['now_item_folder'] = $res2_data;
                    };

            if ($this->params['add_to_bread'])
                AddToBread($result[$this->params['add_to_bread']]);

            if ($this->params['set_title'])
                $_OPTIONS['page_title'] = $result[$this->params['set_title']];

            SetMetatags($result);

            return $result;
        };
        return false;
    }

}

?>
