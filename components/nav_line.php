<?php

class Component_nav_line extends Component {

    protected function GetDefaults() {
        return array(
            "template" => "default",
            "cache" => false,
            "cache_time" => 90,
            "cache_key" => $_SERVER['REQUEST_URI'],
        );
    }

    protected function Action() {
        global $_global_bread;
        return $_global_bread;
    }

}

?>
