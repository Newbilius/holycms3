<?

/**
 * Класс для работы с отображениями.
 */
class View {

    protected $params;
    protected $full_path;
    protected $cache_key;
    protected $cache;

    static public function Factory($path) {
        $view = new View($path);
        return $view;
    }

    public function IsExists(){
        if ($this->full_path)
            return true;
        else
            return false;
    }
    
    public function CacheOn($key, $block = "TEMP", $time = 90) {
        $this->cache_key = $key;
        $this->cache = new HolyCacheOut($key, $time, $block);
    }

    public function CacheOff() {
        $this->cache_key = null;
    }

    public function View($path) {
        $this->params = array();
        $this->full_path = "";
        if (file_exists(FOLDER_SITE . "views/" . $path . ".php")) {
            $this->full_path = FOLDER_SITE . "views/" . $path . ".php";
        } elseif (file_exists(FOLDER_ENGINE . "views/" . $path . ".php")) {
            $this->full_path = FOLDER_ENGINE . "views/" . $path . ".php";
        };
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
    }

}

?>