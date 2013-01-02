<?php

class Component_main_menu extends Component {

    protected function GetDefaults() {
        return array(
            "template" => "default",
            "cache" => false,
            "cache_time" => 90,
            "cache_key" => null,
            "order" => "sort ASC",
            "filter" => Array("not_visible" => 0, "in_menu" => 1, "parent" => 0),
            "debug" => false,
            "draw_paginator" => false,
        );
    }

    protected function Action() {
        global $_selected_page;
        global $_OPTIONS;
        global $global_current_link_array;

        $res = new DBlockElement($_OPTIONS['page_module']);

        $res->GetList($this->params['filter']);
        if (!isset($global_current_link_array))
            $global_current_link_array = array();
        while ($result_temp = $res->GetNext()) {
            if (($result_temp['id'] == $_selected_page['id']) || (in_array($result_temp['id'], $global_current_link_array))) {
                $result_temp['SELECTED'] = true;
                $result_select_id[] = $result_temp['id'];
            }
            else
                $result_temp['SELECTED'] = false;

            $result[$result_temp['parent']][] = $result_temp;
        };

        return $result;
    }

}

?>
