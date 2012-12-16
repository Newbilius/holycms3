<?

class Component {

    protected $params;
    protected $name;
    protected $cache;
    protected $errors;

    static public function Factory($path) {
        $base_component_path1 = FOLDER_ROOT . "/site/components/" . $path . ".php";
        $base_component_path2 = FOLDER_ROOT . "/engine/components/" . $path . ".php";

        if (file_exists($base_component_path1)) {
            include_once($base_component_path1);
        } elseif (file_exists($base_component_path2)) {
            include_once($base_component_path2);
        } else {
            SystemAlertFatal("Не найден компонент <b>$path</b>");
        };
        $component_class = "Component_" . str_replace("/", "_", $path);

        $component = new $component_class($path);
        return $component;
    }

    public function Component($name) {
        $this->params = $this->GetDefaults();
        $this->name=$name;
        $this->cache = false;
        return $this;
    }

    protected function GetDefaults() {
        return array(
            "template" => "default",
            "cache" => false,
            "cache_time" => 90,
            "cache_key" => null,
        );
    }

    protected function ParamsValidate() {
        $params = $this->GetParamsValidators();

        if (count($params) > 0) {
            $fields_list = array();
            foreach ($this->params as $_param_name => $_param) {
                $fields_list[$_param_name] = $_param_name;
            };
            $validator = new HolyValidator($fields_list);

            foreach ($params as $_param) {
                if (isset($_param['options'])) {
                    $validator->AddRule($_param['rule'], $_param['name'], $_param['options']);
                } else {
                    $validator->AddRule($_param['rule'], $_param['name']);
                }
            }
            $result = $validator->Check($this->params);
            $this->errors = $result;
            return $result;
        }
        return true;
    }

    protected function GetParamsValidators() {
        return array();
    }

    public function SetParam($name, $value) {
        $this->params[$name] = $value;
        return $this;
    }

    public function SetParams($values) {
        foreach ($values as $_name => $_value) {
            $this->params[$_name] = $_value;
        }
        return $this;
    }

    protected function PrepareCache() {
        global $_CONFIG;
        if (isset($_CONFIG['CACHE_SYSTEM'])) {
            if ($_CONFIG['CACHE_SYSTEM'] === false) {
                $this->params['cache'] = false;
            } elseif ($_CONFIG['CACHE_SYSTEM'] === "always") {
                if ($this->params['cache'] === "auto") {
                    $this->params['cache'] = true;
                };
            }
        };

        if ($this->params['cache']) {
            if ($this->params['cache_key'] === null) {
                $key = $this->name;
                foreach ($this->params as $key_part)
                    if (!is_array($key_part)) {
                        $key.=$key_part . "_";
                    };
                $this->params['cache_key'] = MD5($key);
            };
        return true;
            
        };
        
        return false;
    }

    protected function PrintErrors() {
        foreach ($this->errors as $_error){
            echo "<b style='color:red;'>".$_error."</b><BR>";
        }
    }

    protected function Action() {
        echo "test component";
        return true;
    }

    public function Execute() {
        $validate = $this->ParamsValidate();
        if ($validate===true) {
            $view = View::Factory("components/".$this->name."/".$this->params['template'])->Set("params", $this->params);
            $view->Set("result", $this->Action());
            if ($this->PrepareCache()) {
                $view->CacheOn($this->params['cache_key'], $this->params['table'], $this->params['cache_time']);
            };
            $view->Draw();
        } else {
            $this->PrintErrors();
        }
    }

}

/**
 * Подключает на страницу компонент
 * 
 * @param string $name <p>название компонента. Можно так же указать тип через слэш (тип\компонент)</p>
 * @param string $template <p>[не обязательное] шаблон компонента.</p>
 * @param array $params <p>параметры вызова компонента.</p>
 */
/*
 * порядок поиска компонента или шаблона.
 * 
 * /site/components/название компонента/
 * /site/components/тип/название компонента/
 * /engine/components/название компонента/
 * /engine/components/тип/название компонента/
 */
function IncludeComponent($name, $template = "", $params = array()) {
    $temp = explode('\\', $name);

    $type = "";

    if (count($temp) == 2) {
        $type = $temp[0];
        $name = $temp[1];
    };

    if ($type == "")
        $type = "standard";
    if ($template == "")
        $template = "default";

    if (isset($params['table']))
        $params['block'] = $params['table'];
    elseif (isset($params['block']))
        $params['table'] = $params['block'];

    global $_CONFIG;
    if (isset($_CONFIG['CACHE_SYSTEM'])) {
        if ($_CONFIG['CACHE_SYSTEM'] === false) {
            unset($params['cache']);
        } elseif ($_CONFIG['CACHE_SYSTEM'] === "always") {
            if (isset($params['cache']))
                if ($params['cache'] === "auto") {
                    $params['cache'] = true;
                    if (!isset($params['cache_time']))
                        $params['cache_time'] = 90;
                };
        }
    }
    $result_cache = true;
    if (isset($params['cache']))
        if ($params['cache']) {
            if (!isset($params['cache_time']))
                $cache_time = 90;
            else
                $params['cache_time'] = intval($params['cache_time']);

            if ((!isset($params['cache_time'])) || ($params['cache_time'] == 0)) {
                $params['cache_time'] = 90;
            };
            if (isset($params['cache_key']))
                $cache_key = $params['cache_key'];
            else
                $cache_key = "";
            $key = $name . "__" . $template . "_" . $cache_key;
            foreach ($params as $key_part)
                if (!is_array($key_part))
                    $key.=$key_part . "_";
            $key = MD5($key);
            if (!isset($params['block']))
                $params['block'] = "TEMP";
            //echo $key;
            $cache_component = new HolyCacheOut($key, $params['cache_time'], $params['block']);
            $result_cache = $cache_component->StartCacheOut();
        };
    if ($result_cache) {
        //-----------------------------------------------------
        //выбираем шаблон
        //-----------------------------------------------------
        global $_selected_page;
        if (!isset($_selected_page['template_name']))
            $_selected_page['template_name'] = "";

        $template_variants = array("templates/" . $_selected_page['template_name'] . "/", "");

        $select = false;
        $array_of_type = array($type, "classic");

        foreach ($array_of_type as $type2)
            if (!$select) {
                $base_template_path = "/engine/components/" . $type2 . "/" . $name . "/" . $template . "/";
                $base_template_path_s1 = array("site", "engine");

                if (!$select) {
                    $base_template_path_100 = FOLDER_ROOT . "/site/components/" . $name . "/" . $template . "/index.php";
                    if (file_exists($base_template_path_100)) {
                        $full_template_path = $base_template_path_100;
                        $select = true;
                    };
                };

                foreach ($base_template_path_s1 as $bss1)
                    if (!$select)
                        foreach ($template_variants as $tv)
                            if (!$select) {
                                $base_component_path = FOLDER_ROOT . "/" . $bss1 . "/" . $tv . "components/" . $type2 . "/" . $name . "/";
                                $full_template_path = $base_component_path . $template . "/index.php";
                                if (file_exists($full_template_path))
                                    $select = true;
                            };
            };
        $base_component_path = FOLDER_ROOT . "/engine/components/" . $type . "/" . $name . "/";
        $base_component_path25 = FOLDER_ROOT . "/engine/components/classic/" . $name . "/";
        $base_component_path2 = FOLDER_ROOT . "/site/components/" . $type . "/" . $name . "/";
        $base_component_path3 = FOLDER_ROOT . "/site/components/" . $name . "/";
        //-----------------------------------------------------
        //закончили выбор шаблона
        //-----------------------------------------------------		
        if (file_exists($base_component_path3 . "index.php"))
            include($base_component_path3 . "index.php");
        elseif (file_exists($base_component_path2 . "index.php"))
            include($base_component_path2 . "index.php");
        elseif (file_exists($base_component_path25 . "index.php"))
            include($base_component_path25 . "index.php");
        elseif (file_exists($base_component_path . "index.php"))
            include($base_component_path . "index.php");
        else
            SystemAlert("Не найден компонент <b>" . $type . "/" . $name . "</b>");

        if (isset($params['cache']))
            if ($params['cache'])
                $cache_component->EndCacheOut();
    };
}

;
?>