<?php

class Component_list_items extends Component {

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
            "filter" => "1",
            "debug" => false,
            "draw_paginator" => false,
        );
    }

    protected function GetParamsValidators() {
        return array(
            Array(
                'name'=>"table",
                'rule'=>"not_empty",
            )
        );
    }

    protected function Action() {
        global $_selected_page;

        $res = new DBlockElement($this->params['table']);

        $res->sql->debug = $this->params['debug'];

        $res->GetList($this->params['filter'], $this->params['order']);
        $max_count_now = $res->GetCount();
        $res->GetList($this->params['filter'], $this->params['order'], $this->params['count'], $this->params['page']);        
        
        $result=$res->GetFullList();


        /* if ($this->params['draw_paginator']) {
          $res->SetPaginator($this->params['count'], $this->params['page']);
          $res->DrawPaginator($this->params['paginator_template']);
          }; */

        return $result;
    }

}
?>
