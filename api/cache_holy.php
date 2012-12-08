<?

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

        $this->cache_class_name = "HolyCache_" . $_CONFIG['CACHE_MODE'];

        if ($key == "")
            $key = MD5(time());

        if ($_CONFIG['CACHE_SYSTEM']) {
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
            $this->driver->StartCacheOut();
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