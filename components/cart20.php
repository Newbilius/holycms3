<?php

class Component_cart20 extends Component {

    protected function GetDefaults() {
        return array(
            "filter" => array(),
            "cache" => false,
            "cache_time" => 90,
            "cache_key" => null,
            "item_url" => "",
            "cost_var" => "",
            "cookie_var" => "catalog_items",
            "list_items_template" => "cart_template",
            "form_template" => "default",
            "back_cart_url" => "",
            "form_component" => "",
            "debug" => false,
            "order" => "caption ASC",
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
        $add_need = 0; //номер товара, который будем добавлять
        $delete_need = 0; //номер товара, который нужно удалить
        //@todo много isset'ов
        if (!isset($_REQUEST['count']))
            $_REQUEST['count'] = 1;

        $_REQUEST['count'] = intval($_REQUEST['count']);

        if ($_REQUEST['count'] < 1)
            $_REQUEST['count'] = 1;

        if (isset($_REQUEST['add']))
            $add_need = intval($_REQUEST['add']);

        if (isset($_REQUEST['delete']))
            $delete_need = intval($_REQUEST['delete']);

        $cookie_work = new HolyCookie($this->params['cookie_var']);
        if (isset($_REQUEST['complete']))
            $cookie_work->Delete();

        //процесс добавления в корзину

        if ($add_need > 0) {
            $cookie_work->AddToArray($add_need, $_REQUEST['count']);
            if ($this->params['back_cart_url'])
                Redirect($this->params['back_cart_url']);
        };

        if ($delete_need > 0) {
            $cookie_work->DeleteFormArray($delete_need);
            if ($this->params['back_cart_url'])
                Redirect($this->params['back_cart_url']);
        };

        if (isset($_REQUEST['recalc'])) {
            foreach ($_REQUEST['item'] as $num => $item) {
                $item = intval($item);
                if ($item == 0)
                    unset($_REQUEST['item'][$num]);
            };

            $cookie_work->SetArray($_REQUEST['item']);

            if ($this->params['back_cart_url'])
                Redirect($this->params['back_cart_url']);
        };

        $array_of_items = $cookie_work->GetArray();

        foreach ($array_of_items as $num => $item)
            $array_for_filter[] = $num;

        $array_for_filter[] = 0;

        if (!is_array($this->params['filter'])) {
            $this->params['filter'] = array();
        }

        $filter = $this->params['filter'];
        $filter[] = Array("id", "IN", $array_for_filter);

        if ($this->params['list_items_template']) {
            Component::Factory("list_items")
                    ->SetParam("template", $this->params['list_items_template'])
                    ->SetParam("table", $this->params['table'])
                    ->SetParam("draw_paginator", false)
                    ->SetParam("url", $this->params['item_url'])
                    ->SetParam("order", $this->params['order'])
                    ->SetParam("cache", $this->params['cache'])
                    ->SetParam("cache_time", $this->params['cache_time'])
                    ->SetParam("debug", $this->params['debug'])
                    ->SetParam("filter", $filter)
                    ->SetParam("items_count", $array_of_items)
                    ->Execute();
        };

        if ($this->params['form_component']) {
            if (count($array_for_filter) > 1) {
                Component::Factory($this->params['form_component'])
                        ->SetParam("template", $this->params['form_template'])
                        ->SetParam("table", $this->params['form_template'])
                        ->SetParam("url", $this->params['item_url'])
                        ->SetParam("items_count", $array_of_items)
                        ->SetParam("filter", $filter)
                        ->SetParam("back_cart_url", $this->params['back_cart_url'])
                        ->SetParam("order", $this->params['order'])
                        ->SetParam("cache", $this->params['cache'])
                        ->SetParam("cache_time", $this->params['cache_time'])
                        ->SetParam("debug", $this->params['debug'])
                        ->SetParam("cookie_var", $this->params['cookie_var'])
                        ->SetParam("cost_var", $this->params['cost_var'])
                        ->Execute();
            }
        }

        //@fix нехорошо
        if (isset($_REQUEST['complete']))
            echo "<BR><b>Заказ успешно отправлен!</b>";
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
