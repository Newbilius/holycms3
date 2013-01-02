<?php

class Component_option_out extends Component {

    protected function GetDefaults() {
        return array(
            "template" => "default",
            "cache" => false,
            "cache_time" => 90,
            "cache_key" => null,
            "cache_key" => $_SERVER['REQUEST_URI'],
            "default_value" => "",
        );
    }

    protected function GetParamsValidators() {
        return array(
            Array(
                'name' => "param",
                'rule' => "not_empty",
            )
        );
    }

    protected function Action() {
        global $_OPTIONS;
        $result = $this->params['default_value'];

        if (isset($_OPTIONS[$this->params['param']])) {
            $result = $_OPTIONS[$this->params['param']];
        };

        return $result;
    }

}

?>
