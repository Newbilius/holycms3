<?

class Component {

    protected $params;
    protected $name;
    protected $cache;
    protected $errors;
    protected $result_array = false;
    protected $paginator = false;
    protected $draw_on = true;

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
        $this->name = $name;
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
                foreach ($this->params as $key_key=>$key_part)
                    if (!is_array($key_part)) {
                        $key.=MD5($key_part) . "_";
                    }else{
                        $key.=MD5($key_key.serialize($key_part)) . "_";
                    }
                $this->params['cache_key'] = MD5($key);
            };
            return true;
        };

        return false;
    }

    protected function PrintErrors() {
        foreach ($this->errors as $_error) {
            echo "<b style='color:red;'>" . $_error . "</b><BR>";
        }
    }

    protected function Action() {
        echo "test component";
        return true;
    }

    protected function Execute_inner($view) {
        $result = $this->Action();

        if ($this->paginator)
            $view->Set("paginator", $this->paginator);

        if ($this->result_array) {
            foreach ($result as $result_index => $_result_item) {
                $view->Set($result_index, $_result_item);
            }
        } else {
            $view->Set("result", $result);
        }
        return $view;
    }
    
    public function Execute() {
        $validate = $this->ParamsValidate();
        if ($validate === true) {
            $view = View::Factory("components/" . $this->name . "/" . $this->params['template'])
                    ->Set("params", $this->params);

            if ($this->PrepareCache()) {
                if (isset($this->params['table']))
                    $cache_block=$this->params['table'];
                else
                    $cache_block="TEMP";
                $cache_component = new HolyCacheOut($this->params['cache_key'], $this->params['cache_time'], $cache_block);
                if ($cache_component->StartCacheOut()){
                    $view=$this->Execute_inner($view);
                    if ($this->draw_on)
                        $view->Draw();
                    $cache_component->EndCacheOut();
                }
                
            }else{
                $view=$this->Execute_inner($view);
                if ($this->draw_on)
                    $view->Draw();
            }
        } else {
            $this->PrintErrors();
        }
    }

}
?>