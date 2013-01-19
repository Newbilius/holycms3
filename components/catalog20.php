<?php

class Component_catalog20 extends Component {

    protected function GetDefaults() {
        return array(
            "cache" => false,
            "cache_time" => 90,
            "cache_key" => null,
            "order" => "sort ASC",
            "paginator_template" => "default",
            "count" => "",
            "filter" => array(),
            "add_to_bread" => "",
            "set_title" => "",
            "cart_url" => "",
            "debug" => false,
            "back_url" => "",
            "draw_paginator" => false,
        );
    }

    protected function GetParamsValidators() {
        return array(
            Array(
                'name' => "table",
                'rule' => "not_empty",
            )
        );
    }

    protected function PrepareCache() {
        return false;
    }

    protected function Action() {
        global $human_link;

        if (!isset($human_link[0]))
            $human_link[0] = 0;

        $human_link[0] = intval($human_link[0]);

        if (!is_array($this->params['filter'])) {
            $this->params['filter'] = array();
        };

        $filter = $this->params['filter'];
        $filter[] = Array("parent", "=", $human_link[0]);

        /*
         * костыль для вложенных папок для попадания в навигационную цепочку, установки заголовка
         * 
         */
        if ($human_link[0] > 0)
            if ($this->params['set_title'] != "") {
                $res = new DBlockElement($this->params['table']);
                $folder = $res->GetByID($human_link[0]);
                SetOptions('page_title', $folder[$this->params['set_title']]);
                SetMetatags($folder);
            };

        if ($human_link[0] > 0)
            if ($this->params['add_to_bread'] != "") {
                $res_i3 = new DBlockElement($this->params['table']);
                $res_i0 = $res_i3->GetOne("id=" . $human_link[0]);
                $add_menu[] = Array("caption" => $res_i0['caption'], "data" => $res_i0);
                SetMetatags($res_i0);
                while ($res_i0['parent'] != 0) {
                    $rid = $res_i0['parent'];
                    unset($res_i0);
                    $res_i0 = $res_i3->GetOne("id=" . $rid);
                    $add_menu[] = Array("caption" => $res_i0['caption'], "data" => $res_i0);
                    SetMetatags($res_i0);
                };
            };
        if (isset($add_menu))
            if (count($add_menu) > 0) {
                $add_menu = array_reverse($add_menu);
                foreach ($add_menu as $a_menu)
                    AddToBread($a_menu['caption'], ReplaceURL($this->params['url'], $a_menu['data']));
            };
        /*
         * конец костыля
         */

        $item_filter = $filter;
        $item_filter[] = Array("folder", "=", 0);

        $folder_filter = $filter;
        $folder_filter[] = Array("folder", "=", 1);
        if ($this->params['items_template']) {
            Component::Factory("list_items")
                    ->SetParam("template", $this->params['items_template'])
                    ->SetParam("table", $this->params['table'])
                    ->SetParam("draw_paginator", $this->params['draw_paginator'])
                    ->SetParam("url", $this->params['url'])
                    ->SetParam("count", $this->params['count'])
                    ->SetParam("paginator_template", $this->params['paginator_template'])
                    ->SetParam("order", $this->params['order'])
                    ->SetParam("cache", $this->params['cache'])
                    ->SetParam("cache_time", $this->params['cache_time'])
                    ->SetParam("cache_key", $this->params['cache_key'])
                    ->SetParam("debug", $this->params['debug'])
                    ->SetParam("count", $this->params['count'])
                    ->SetParam("filter", $item_filter)
                    ->Execute();
        }
        if ($this->params['folders_template']) {
            Component::Factory("list_items")
                    ->SetParam("template", $this->params['folders_template'])
                    ->SetParam("table", $this->params['table'])
                    ->SetParam("draw_paginator", $this->params['table'])
                    ->SetParam("url", $this->params['url'])
                    ->SetParam("count", $this->params['count'])
                    ->SetParam("paginator_template", $this->params['paginator_template'])
                    ->SetParam("order", $this->params['order'])
                    ->SetParam("cache", $this->params['cache'])
                    ->SetParam("cache_time", $this->params['cache_time'])
                    ->SetParam("cache_key", $this->params['cache_key'])
                    ->SetParam("debug", $this->params['debug'])
                    ->SetParam("count", $this->params['count'])
                    ->SetParam("filter", $folder_filter)
                    ->Execute();
        };

        if ($this->params['detail_template']) {
            if ($human_link[0]) {
                Component::Factory("detail_item")
                        ->SetParam("template", $this->params['detail_template'])
                        ->SetParam("table", $this->params['table'])
                        ->SetParam("back_url", $this->params['back_url'])
                        ->SetParam("set_title", $this->params['set_title'])
                        ->SetParam("ID", $human_link[0])
                        ->SetParam("cache", $this->params['cache'])
                        ->SetParam("cart_url", $this->params['cart_url'])
                        ->SetParam("cache_time", $this->params['cache_time'])
                        ->SetParam("cache_key", $this->params['cache_key'])
                        ->SetParam("debug", $this->params['debug'])
                        ->SetParam("filter", Array(Array("folder", "=", 0)))
                        ->Execute();
            };
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
