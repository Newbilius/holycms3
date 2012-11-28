<?
global $_selected_page;

if (!isset($params['filter']))
    $params['filter'] = "";
if (!isset($params['debug']))
    $params['debug'] = false;
if (!isset($params['table']))
    die("не указана таблица");
if (!isset($params['item_url']))
    $params['item_url'] = "";
if (!isset($params['cost_var']))
    $params['cost_var'] = "";
if (!isset($params['cookie_var']))
    $params['cookie_var'] = "catalog_items";
if (!isset($params['cart_url']))
    $params['cart_url'] = "";
if (!isset($params['order']))
    $params['order']="sort ASC";

$cookie_work = new HolyCookie($params['cookie_var']);
$array_of_items = $cookie_work->GetArray();

foreach ($array_of_items as $num => $item)
    $array_for_filter[] = $num;

$array_for_filter[] = 0;

$filter = implode(",", $array_for_filter);

$filter="id IN (" . $filter . ")".$params['filter'];

unset($res);
$res = new DBlockElement($params['table']);

if (!isset($params['debug']))
    $params['debug']=false;
if (!isset($params['order']))
    $params['order']="sort ASC";

$res->sql->debug=$params['debug'];


$res->GetList($filter, $params['order']);

$all_summ=0;
$all_count=0;
while ($result1 = $res->GetNext()) {
    $result[] = $result1;
    $all_summ+=$array_of_items[$result1['id']]*$result1[$params['cost_var']];
    $all_count+=$array_of_items[$result1['id']];
};

$_count=0;

if (isset($result))
    $_count = count($result);

if (file_exists($full_template_path))
    include($full_template_path);
else
    SystemAlert("Не найден шаблон <b>" . $full_template_path . "</b>");
?>