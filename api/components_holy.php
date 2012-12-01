<?

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
        } elseif ($_CONFIG['CACHE_SYSTEM']==="always")
        {
            if (isset($params['cache']))
            if ($params['cache']==="auto")
            {
            $params['cache']=true;
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

            if ($params['cache_time'] == 0)
                $params['cache_time'] = 90;
            $key = $name . "__" . $template . "_";
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