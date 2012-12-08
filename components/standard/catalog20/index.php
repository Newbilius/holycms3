<?

if (!defined('HCMS'))
    die();
global $human_link;

if (!isset($params['inner_cache']))
    $params['inner_cache'] = false;
if (!isset($params['order']))
    $params['order'] = "sort ASC";
if (!isset($params['paginator_template']))
    $params['paginator_template'] = "";
if (!isset($params['count']))
    $params['count'] = "";
if (!isset($params['filter']))
    $params['filter'] = "";
if (!isset($params['table']))
    die("не указана таблица");
if (!isset($params['add_to_bread']))
    $params['add_to_bread'] = "";
if (!isset($params['set_title']))
    $params['set_title'] = "";
if (!isset($params['set_title']))
    $params['set_title'] = "";
if (!isset($params['cart_url']))
    $params['cart_url'] = "/cart/?add=#id#";
if (!isset($params['debug']))
    $params['debug'] = false;

if (!isset($human_link[0]))
    $human_link[0] = 0;

$human_link[0] = intval($human_link[0]);
if ($params['filter'] == "")
    $list_filters = "parent=" . $human_link[0];
else
    $list_filters.=" AND parent=" . $human_link[0];

/*
 * костыль для вложенных папок для попадания в навигационную цепочку, установки заголовка
 * 
 */
if ($human_link[0] > 0)
    if ($params['set_title'] != "") {
        $res = new DBlockElement($params['table']);
        $folder = $res->GetByID($human_link[0]);
        SetOptions('page_title', $folder[$params['set_title']]);
        SetMetatags($folder);
    };

if ($human_link[0] > 0)
    if ($params['add_to_bread'] != "") {
        $res_i3 = new DBlockElement($params['table']);
        $res_i0 = $res_i3->GetOne("id=" . $human_link[0]);
        $add_menu[] = Array("caption" => $res_i0['caption'], "data" => $res_i0);
        SetMetatags($res_i0);
        while ($res_i0['parent'] != 0) {
            $rid = $res_i0['parent'];
            unset($res_i0);
            $res_i0 = $res_i3->GetOne("id=" . $rid);
            $add_menu[] = Array("caption" => $res_i0['caption'], "data" => $res_i0);
            SetMetatags($res_i0);
        };
    };
    if (isset($add_menu))
if (count($add_menu) > 0) {
    $add_menu = array_reverse($add_menu);
    foreach ($add_menu as $a_menu)
        AddToBread($a_menu['caption'], ReplaceURL($params['url'], $a_menu['data']));
};
//AddToBread($folder);
/*
 * конец костыля
 */

$item_filter = $list_filters;
if ($item_filter == "")
    $item_filter = "folder=0";
else
    $item_filter.=" AND folder=0";

$folder_filter = $list_filters;
if ($folder_filter == "")
    $folder_filter = "folder=1";
else
    $folder_filter.=" AND folder=1";

IncludeComponent("list_items", $params['items_template'], Array(
    "table" => $params['table'],
    "draw_paginator" => $params['table'],
    "url" => $params['url'],
    "count" => $params['count'],
    "paginator_template" => $params['paginator_template'],
    "order" => $params['order'],
    "filter" => $item_filter,
    "debug"=>$params['debug'],
    'cache'=>$params['inner_cache'],
));

if ($params['folders_template'])
    IncludeComponent("list_items", $params['folders_template'], Array(
        "table" => $params['table'],
        "draw_paginator" => $params['table'],
        "url" => $params['url'],
        "count" => $params['count'],
        "paginator_template" => $params['paginator_template'],
        "order" => $params['order'],
        "filter" => $folder_filter,
        "debug"=>$params['debug'],
        'cache'=>$params['inner_cache'],
    ));

if ($params['detail_template'])
    IncludeComponent("detail_item", $params['detail_template'], Array(
        "table" => $params['table'],
        "set_title" => $params['set_title'],
        "ID" => $human_link[0],
        "debug"=>$params['debug'],
        "filter"=>"folder=0",
        "cart_url"=>$params['cart_url'],
        'cache'=>$params['inner_cache'],
            //"back_url" => $params['back_url']
    ));
?>