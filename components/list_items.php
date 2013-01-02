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
            "paginator_template" => "default",
            "paginator_url" => "#PAGE#",
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
        if ($this->params['page']<1)
            $this->params['page']=1;
        $res->GetList($this->params['filter'], $this->params['order'], $this->params['count'], $this->params['page']);        
        
        $result=$res->GetFullList();

        if ($this->params['draw_paginator']) {
           //получить полное число элементов
           $res2 = new DBlockElement($this->params['table']);
           $res2->GetList($this->params['filter']);
           $max_count=$res2->GetCount();
           //вызывать вьюшку с параметрами
           $this->paginator=$view = View::Factory("paginator/".$this->params['paginator_template'])
                    ->Set("count", $this->params['count'])
                    ->Set("max_count", $max_count)
                    ->Set("page", $this->params['page'])
                    ->Set("url", $this->params['paginator_url'])
                  ;
          }; 

        return $result;
    }

}
?>
