<?php

class Component_news20 extends Component {

    protected function GetDefaults() {
        return array(
            "cache" => false,
            "cache_time" => 90,
            "cache_key" => null,
            "order" => "sort DESC",
            "filter" => "1",
            "debug" => false,
            "draw_paginator" => false,
            "pagination_template" => "default",
            "count" => "",
            "list_template" => "default",
            "detail_template" => "default",
            "add_to_bread" => "",
            "back_url" => "",
            "url" => "",
            "set_title"=>"",
            
        );
    }

    protected function PrepareCache() {
        return false;
    }

    protected function GetParamsValidators() {
        return array(
            Array(
                'name' => "table",
                'rule' => "not_empty",
            ),
            Array(
                'name' => "list_template",
                'rule' => "not_empty",
            ),
            Array(
                'name' => "detail_template",
                'rule' => "not_empty",
            ),
        );
    }

    protected function Action() {
        global $human_link;

        if (!isset($human_link[0])) {
            Component::Factory("list_items")
                    ->SetParam("template", $this->params['list_template'])
                    ->SetParam("table", $this->params['table'])
                    ->SetParam("draw_paginator", $this->params['table'])
                    ->SetParam("url", $this->params['url'])
                    ->SetParam("count", $this->params['count'])
                    ->SetParam("paginator_template", $this->params['pagination_template'])
                    ->SetParam("order", $this->params['order'])
                    ->SetParam("cache", $this->params['cache'])
                    ->SetParam("cache_time", $this->params['cache_time'])
                    ->SetParam("cache_key", $this->params['cache_key'])
                    ->SetParam("debug", $this->params['debug'])
                    ->SetParam("count", $this->params['count'])
                    ->SetParam("filter", $this->params['filter'])
                    ->Execute();
        } else {
            Component::Factory("detail_item")
                    ->SetParam("template", $this->params['detail_template'])
                    ->SetParam("table", $this->params['table'])
                    ->SetParam("add_to_bread", $this->params['add_to_bread'])
                    ->SetParam("back_url", $this->params['back_url'])
                    ->SetParam("set_title", $this->params['set_title'])
                    ->SetParam("ID", $human_link[0])
                    ->SetParam("cache", $this->params['cache'])
                    ->SetParam("cache_time", $this->params['cache_time'])
                    ->SetParam("cache_key", $this->params['cache_key'])
                    ->SetParam("debug", $this->params['debug'])
                    ->SetParam("filter", $this->params['filter'])
                    ->Execute();
        }
        return true;
    }

    public function Execute() {
        $validate = $this->ParamsValidate();
        if ($validate === true) {
            $this->Action();
        } else {
            $this->PrintErrors();
        }
    }

}

?>
