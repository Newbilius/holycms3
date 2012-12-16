<?php

class Component_cart_status extends Component {

    protected function GetDefaults() {
        return array(
        "template" => "default",
        "cache" => false,
        "cache_time" => 90,
        "cache_key" => null,
        "filter" => "",
        "debug" => false,
        "item_url" => "",
        "cost_var" => "",
        "cookie_var" => "catalog_items",
        "cart_url" => "",
        "order" => "sort ASC"
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

    protected function Action() {
        $cookie_work = new HolyCookie($this->params['cookie_var']);
        $array_of_items = $cookie_work->GetArray();

        foreach ($array_of_items as $num => $item)
            $array_for_filter[] = $num;

        $array_for_filter[] = 0;

        $filter = implode(",", $array_for_filter);

        $filter = "id IN (" . $filter . ")" . $this->params['filter'];

        $res = new DBlockElement($this->params['table']);

        $res->sql->debug = $this->params['debug'];

        $res->GetList($filter, $this->params['order']);

        $all_summ = 0;
        $all_count = 0;
        while ($result1 = $res->GetNext()) {
            $result['items'] = $result1;
            $all_summ+=$array_of_items[$result1['id']] * $result1[$this->params['cost_var']];
            $all_count+=$array_of_items[$result1['id']];
        };

        $result['count']=$all_count;
        $result['summ']=$all_summ;
        
        return $result;
    }

}

?>
