<?php

class Component_nav_line extends Component {

    protected function GetDefaults() {
        return array(
            "template" => "default",
            "cache" => false,
            "cache_time" => 90,
            "cache_key" => MD5($_SERVER['REQUEST_URI']),
        );
    }

    protected function Action() {
        global $_global_bread;
        if (count($_global_bread)<2)
            $this->draw_on=false;
        return $_global_bread;
    }

}

?>
