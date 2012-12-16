<?php

class Component_tags_list extends Component {

    protected function GetDefaults() {
        return array(
            "template" => "default",
            "cache" => false,
            "cache_time" => 90,
            "cache_key" => null,
            "min_size" => 12,
            "coefficient" => 4,
            "url" => "",
        );
    }

    protected function GetParamsValidators() {
        return array(
            Array(
                'name' => "table",
                'rule' => "not_empty",
            ),
            Array(
                'name' => "field",
                'rule' => "not_empty",
            ),
        );
    }

    protected function Action() {
        $tags = new HolyTags($this->params['table'], $this->params['field'], $this->params['url']);
        $tags_list = $tags->GetFullList();
        $tag_count = $tags->GetCount();
        $different = 10;
        $different_proc = $different / $tag_count;
        $result['list'] = $tags_list;
        $result['count'] = $tag_count;
        $result['tags'] = $tags;
        return $result;
    }

}

?>
