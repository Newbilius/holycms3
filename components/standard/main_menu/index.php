<?

if (!defined('HCMS'))
    die();
/*
  параметры
  var			по какому параметру выбирать элементы
  filter		дополнительные фильтры
  parent		получать данные с какого начина€ элемента
  table		с какой таблицы брать данные
  (если не установлена,  данные берутс€ с $_OPTIONS['page_module'])
  not_root	true - получать все элементы, подход€щие, false - только из корн€
 */

global $_selected_page;
global $_OPTIONS;
global $global_current_link_array;

if (!isset($params['var']))
    $params['var'] = "in_menu";
if ($params['var'] == "")
    $params['var'] = "in_menu";

$res = new DBlockElement($_OPTIONS['page_module']);
$filter_opt = Array($params['var'] => 1, "not_visible" => 0);

if (!isset($params['not_root']))
    $filter_opt["parent"] = 0;

if (isset($params['filter']))
    if (is_array($params['filter']))
        if (count($params['filter']) > 0)
            $filter_opt = array_merge($filter_opt, $params['filter']);

$res->GetList($filter_opt);
if (!isset($global_current_link_array))
    $global_current_link_array = array();
while ($result_temp = $res->GetNext()) {
    if (($result_temp['id'] == $_selected_page['id']) || (in_array($result_temp['id'], $global_current_link_array)))
    {
        $result_temp['SELECTED'] = true;
        $result_select_id[]=$result_temp['id'];
    }
    else
        $result_temp['SELECTED'] = false;

    $result[$result_temp['parent']][] = $result_temp;
};
if (isset($result))
    $_count_result = count($result);
if (file_exists($full_template_path))
    include($full_template_path);
else
    SystemAlert("Ќе найден шаблон <b>" . $full_template_path . "</b>");
?>