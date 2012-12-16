<?

/**
 * Класс для работы с отображениями.
 */
class View {

    protected $params;
    protected $full_path;
    protected $cache_key;
    public $fatal_if_not_find;

    static public function Factory($path,$fatal_if_not_find=true) {
        $view = new View($path,$fatal_if_not_find);
        return $view;
    }

    public function IsExists() {
        if ($this->full_path)
            return true;
        else
            return false;
    }

    public function CacheOn($key, $block = "TEMP", $time = 90) {
        $this->cache_key = $key;
        $this->cache_component = new HolyCacheOut($key, $time, $block);
    }

    public function CacheOff() {
        $this->cache_key = null;
    }

    public function View($path,$fatal_if_not_find=true) {
        $this->params = array();
        $this->full_path = "";
        if (file_exists(FOLDER_SITE . "views/" . $path . ".php")) {
            $this->full_path = FOLDER_SITE . "views/" . $path . ".php";
        } elseif (file_exists(FOLDER_ENGINE . "views/" . $path . ".php")) {
            $this->full_path = FOLDER_ENGINE . "views/" . $path . ".php";
        } else {
            if ($fatal_if_not_find)
            SystemAlert("не найдено отображение $path");
        }
        $this->cache_key = null;
        return $this;
    }

    public function Set($name, $value) {
        $this->params[$name] = $value;
        return $this;
    }

    public function Get($name) {
        if (isset($this->params[$name]))
            return $this->params[$name];
        else
            return null;
    }

    public function Draw() {
        if ($this->full_path) {
            if ($this->cache_key) {
                $result = $this->cache_component->StartCacheOut();
            } else {
                $result = true;
            };

            if ($result) {
                extract($this->params);
                include_once($this->full_path);

                if ($this->cache_key) {
                    $this->cache_component->EndCacheOut();
                };

                return $this;
            };
        };
    }

}

?>