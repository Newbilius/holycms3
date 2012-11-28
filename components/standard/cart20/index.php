<?

if (!defined('HCMS'))
    die();

if (!isset($params['filter']))
    $params['filter'] = "";
if (!isset($params['table']))
    die("не указана таблица");
if (!isset($params['item_url']))
    $params['item_url'] = "";
if (!isset($params['cost_var']))
    $params['cost_var'] = "";
if (!isset($params['cookie_var']))
    $params['cookie_var'] = "catalog_items";
if (!isset($params['list_items_template']))
    $params['list_items_template'] = "cart_template";
if (!isset($params['form_template']))
    $params['form_template'] = "";
if (!isset($params['back_cart_url']))
    $params['back_cart_url'] = "";
if (!isset($params['form_component']))
    $params['form_component'] = "";


$add_need = 0; //номер товара, который будем добавлять
$delete_need = 0; //номер товара, который нужно удалить

if (!isset($_REQUEST['count']))
    $_REQUEST['count'] = 1;

$_REQUEST['count'] = intval($_REQUEST['count']);

if ($_REQUEST['count'] < 1)
    $_REQUEST['count'] = 1;

if (isset($_REQUEST['add']))
    $add_need = intval($_REQUEST['add']);

if (isset($_REQUEST['delete']))
    $delete_need = intval($_REQUEST['delete']);


$cookie_work = new HolyCookie($params['cookie_var']);
if (isset($_REQUEST['complete']))
    $cookie_work->Delete ();
//процесс добавления в корзину
if ($add_need > 0) {
    $cookie_work->AddToArray($add_need, $_REQUEST['count']);
    if ($params['back_cart_url'])
        Redirect($params['back_cart_url']);
};

if ($delete_need > 0) {
    $cookie_work->DeleteFormArray($delete_need);
    if ($params['back_cart_url'])
        Redirect($params['back_cart_url']);
};

if (isset($_REQUEST['recalc'])) {
    foreach ($_REQUEST['item'] as $num => $item) {
        $item = intval($item);
        if ($item == 0)
            unset($_REQUEST['item'][$num]);
    };

    $cookie_work->SetArray($_REQUEST['item']);

    if ($params['back_cart_url'])
        Redirect($params['back_cart_url']);
};

$array_of_items = $cookie_work->GetArray();

foreach ($array_of_items as $num => $item)
    $array_for_filter[] = $num;

$array_for_filter[] = 0;

$filter = implode(",", $array_for_filter);


if ($params['list_items_template'])
    IncludeComponent("list_items", $params['list_items_template'], Array(
        "table" => $params['table'],
        "filter" => "id IN (" . $filter . ")".$params['filter'],
        "debug" => false,
        "url" => $params['item_url'],
        "items_count" => $array_of_items,
    ));
if ($params['form_component'])
    if (count($array_for_filter)>1)
    IncludeComponent($params['form_component'], $params['form_template'], Array(
        "table" => $params['table'],
        "filter" => "id IN (" . $filter . ")".$params['filter'],
        "url" => $params['item_url'],
        "items_count" => $array_of_items,
        'back_cart_url'=>$params['back_cart_url'],
        'cookie_var'=>$params['cookie_var'],
        'cost_var'=>$params['cost_var'],
    ));

if (isset($_REQUEST['complete']))
    echo "<BR>Заказ успешно отправлен!";

//preprint($_COOKIE);
//preprint($_REQUEST);
?>