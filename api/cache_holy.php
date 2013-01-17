<?

class HolyCache_memcached {

    var $sql;
    var $key;
    var $time;
    var $temp_data;
    var $out_data;
    var $module;
    var $mem_obj;

    function HolyCache_memcached($key, $time, $module) {
        $this->key = $key . "_" . $module;
        $this->module = $module;
        $this->time = $time;

        global $_CONFIG;
        $this->mem_obj = new Memcache;
        $this->mem_obj->connect($_CONFIG['CACHE_SYSTEM_HOST'], $_CONFIG['CACHE_SYSTEM_PORT']);
    }

    protected function TestCache($key = "") {
        if ($key != "") {
            $this->out_data = $this->mem_obj->get($this->key);
            if ($this->out_data !== false)
                return true;
        }
        return false;
    }

    function StartCacheOut() {
        $create_cache = true;

        if ($this->TestCache($this->key)) {
            $create_cache = false;
            echo $this->out_data;
        };

        if ($create_cache) {
            ob_start();
            return true;
        };
        return false;
    }

    function EndCacheOut() {
        $this->temp_data = ob_get_contents();
        ob_end_clean();
        echo $this->temp_data;
        $this->mem_obj->set($this->key, $this->temp_data, 0, $this->time);
    }

    function ClearFull() {
        $this->mem_obj->flush();
    }

    function Clear() {
        $this->mem_obj->delete($this->key);
    }

}

class HolyCache_files {

    var $sql;
    var $key;
    var $time;
    var $temp_data;
    var $module;
    var $full_path;

    function HolyCache_files($key, $time, $module) {
        $this->key = $key . "_" . $module;
        $this->module = $module;
        $this->time = $time;
    }

    protected function TestCache($key = "") {
        if (!file_exists(FOLDER_UPLOAD))
            mkdir(FOLDER_UPLOAD);
        if (!file_exists(FOLDER_UPLOAD . "cache/"))
            mkdir(FOLDER_UPLOAD . "cache/");
        if (!file_exists(FOLDER_UPLOAD . "cache/" . $this->module))
            mkdir(FOLDER_UPLOAD . "cache/" . $this->module);
        $part_of_path = FOLDER_UPLOAD . "cache/" . $this->module . "/";
        if (!file_exists($part_of_path))
            mkdir($part_of_path);

        if ($key != "") {
            $fold1 = substr($key, 0, 2);
            $fold2 = substr($key, 2, 2);

            if (!file_exists($part_of_path . $fold1))
                mkdir($part_of_path . $fold1);
            if (!file_exists($part_of_path . $fold1 . "/" . $fold2))
                mkdir($part_of_path . $fold1 . "/" . $fold2);

            $this->full_path = $part_of_path . $fold1 . "/" . $fold2 . "/" . $key;
            if (file_exists($this->full_path))
                return true;
        }
        return false;
    }

    function StartCacheOut() {
        $create_cache = true;

        if ($this->TestCache($this->key)) {
            $time = filemtime($this->full_path);
            if (time() - $time < $this->time) {
                $content = file_get_contents($this->full_path);
                echo $content;
                $create_cache = false;
            };
        }

        if ($create_cache) {
            ob_start();
            return true;
        };
        return false;
    }

    function EndCacheOut() {
        $this->temp_data = ob_get_contents();
        ob_end_clean();
        echo $this->temp_data;

        $this->TestCache($this->key);

        file_put_contents($this->full_path, $this->temp_data);
    }

    function ClearFull() {
        DeleteDir(FOLDER_UPLOAD . "cache/");
    }

    function Clear() {
        if ($this->module) {
            DeleteDir(FOLDER_UPLOAD . "cache/" . $this->module);
        }
    }

}

class HolyCache_base {

    var $sql;
    var $key;
    var $time;
    var $temp_data;
    var $module;

    function HolyCache_base($key, $time, $module) {
        $this->key = $key . "_" . $module;
        $this->module = $module;
        $this->time = $time;
        $this->sql = new HolySQL('system_cache');
    }

    function StartCacheOut() {
        $tmpdata = $this->sql->SelectOnce("id='" . $this->key . "'", 'id');

        if ((isset($tmpdata['id'])) && (time() - $tmpdata['time'] < $this->time)) {
            echo $tmpdata['value'];
            return false;
        } else {
            ob_start();
            return true;
        };
    }

    function EndCacheOut() {
        $this->temp_data = ob_get_contents();
        ob_end_clean();
        echo $this->temp_data;

        $this->sql->Delete("id='" . $this->key . "'");
        $this->sql->Insert(array(
            "id" => $this->key,
            "value" => $this->temp_data,
            "time" => time(),
            "module" => $this->module,
        ));
    }

    function ClearFull() {
        $this->sql->Delete("1");
    }

    function Clear() {
        $this->sql->Delete("module='" . $this->module . "' OR module='TEMP'");
    }

}

/**
 * Класс для работы с кэшированием вывода.
 * 
 * Пример использования:
 * $cache_component = new HolyCacheOut($key, $params['cache_time'], $params['block']);
 * $result_cache = $cache_component->StartCacheOut();
 * if ($result_cache) {
 * [...]
 * $cache_component->EndCacheOut();
 * };
 */
class HolyCacheOut {

    var $key;
    var $time;
    var $temp_data;
    var $module;
    protected $driver;
    protected $cache_class_name;

    /**
     * Инициализирует класс.
     * 
     * @param string $key  <p>индентификатор кэша - уникальный ключ</p>
     * @param int $time  <p>время жизни кэша не обязательное. по-умолчанию - 90 секунд.</p>
     * @param string $module  <p>дополнение ключа, используемый модуль. Если совпадает с одной из таблиц - при изменении данных в таблице кэш будет очищаться.</p>
     */
    function HolyCacheOut($key = "", $time = 90, $module = "TEMP") {
        global $_CONFIG;
        if ($_CONFIG['CACHE_SYSTEM']) {

            $this->cache_class_name = "HolyCache_" . $_CONFIG['CACHE_MODE'];

            if ($key == "")
                $key = MD5(time());


            $this->key = $key . "_" . $module;
            $this->module = $module;
            $this->time = $time;

            $this->driver = new $this->cache_class_name($this->key, $this->time, $this->module);
        };
    }

    /**
     * Начать кэшировать вывод
     * 
     * @return bool нужно ли выводить данные из текущие (true) или из кэша (false)
     */
    function StartCacheOut() {
        global $_CONFIG;
        if ($_CONFIG['CACHE_SYSTEM']) {
            return $this->driver->StartCacheOut();
        };
    }

    /**
     * Закончить работу и сохранить данные в кэше.
     * 
     */
    function EndCacheOut() {
        global $_CONFIG;
        if ($_CONFIG['CACHE_SYSTEM']) {
            $this->driver->EndCacheOut();
        };
    }

    /**
     * Очистить всеь кэш на сайте
     * 
     */
    function ClearFull() {
        global $_CONFIG;
        if ($_CONFIG['CACHE_SYSTEM']) {
            $this->driver->ClearFull();
        }
    }

    /**
     * Очистить кэш, связанный с данным модулем
     * 
     */
    function Clear() {
        global $_CONFIG;
        if ($_CONFIG['CACHE_SYSTEM'])
            $this->driver->Clear();
    }

}

;
?>