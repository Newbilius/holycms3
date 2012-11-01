<?php

/**
 * Преобразует XML-данные в массив
 */
class HolyTags {

    protected $res;
    protected $tags;
    protected $tags_out;
    protected $count_max;
    protected $count_tags;
    protected $property;

    /**
     * Конструктор
     * 
     * @param string $table  <p>таблицы</p>
     * @param string $property  <p>свойство</p>
     * @param string $url  <p>шаблон для ссылки (#tag# - место для тэга)</p>
     * @return array
     */
    function HolyTags($table, $property, $url="") {
        $this->property = $property;
        $this->count_max = 0;

        $res = new DBlockElement($table);
        $data = $res->GetList()->GetFullList();
        foreach ($data as $item) {
            $tmp_items = explode(",", $item[$property]);
            foreach ($tmp_items as $tag) {
                $tag = trim($tag);
                if ($tag)
                    if ($tag != " ") {
                        $this->count_max++;
                        if (!isset($this->tags[$tag]))
                            $this->tags[$tag] = 0;
                        $this->tags[$tag]++;
                    };
            };
        };
        $this->count_tags = count($this->tags);
        foreach ($this->tags as $tag => $tag_count) {
            $this->tags_out[$tag] = Array(
                "caption" => $tag,
                "url" => str_replace('#tag#', urlencode($tag), $url),
                "count" => $tag_count,
            );
        };
        asort($this->tags_out);
        ksort($this->tags);
        return $this;
    }

    /**
     * Возвращает массив вида "тэг"=>"число упоминаний"
     * 
     * @return array
     */
    public function GetTagsList() {
        return $this->tags;
    }

    /**
     * Возвращает обработанный массив для конкретного элемента
     * 
     * @param array $item <p>Массив данных элемента</a>
     * 
     * @return array
     */
    public function GetList($item) {
        $tmp_items = explode(",", $item[$this->property]);
        $out_array = array();

        foreach ($tmp_items as $tag) {
            $tag = trim($tag);
            if ($tag)
                if ($tag != " ") {
                    $out_array[] = $this->tags_out[$tag];
                    ;
                };
        };

        asort($out_array);
        return $out_array;
    }

    /**
     * Возвращает простой массив тэгов, которые есть в наличии у данного элемента.
     * 
     * @param array $item <p>Массив данных элемента</a>
     * 
     * @return array
     */
    public function GetListSimple($item) {
        $tmp_items = explode(",", $item[$this->property]);
        $out_array = array();

        foreach ($tmp_items as $tag) {
            $tag = trim($tag);
            if ($tag)
                if ($tag != " ") {
                    $out_array[] = $this->tags_out[$tag]['caption'];
                    ;
                };
        };

        asort($out_array);
        return $out_array;
    }
    
    /**
     * Возвращает обработанный массив всех тэгов
     * 
     * @return array
     */
    public function GetFullList() {
        return $this->tags_out;
    }

    /**
     * Возвращает общее число упоминаний
     * 
     * @return int
     */
    public function GetTagsCount() {
        return $this->count_tags;
    }

    /**
     * Возвращает общее число упоминаний
     * 
     * @return int
     */
    public function GetCount() {
        return $this->count_max;
    }

}

?>