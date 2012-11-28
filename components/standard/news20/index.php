<?

if (!defined('HCMS'))
    die();
global $human_link;

if (!isset($params['order']))
    $params['order'] = "sort DESC";
if (!isset($params['paginator_template']))
    $params['paginator_template'] = "";
if (!isset($params['count']))
    $params['count'] = "";
if (!isset($params['filter']))
    $params['filter'] = "";
if (!isset($params['table']))
    die("не указана таблица");

if (!isset($human_link[0]))
    IncludeComponent("list_items", $params['list_template'], Array(
        "table" => $params['table'],
        "draw_paginator" => $params['table'],
        "url" => $params['url'],
        "count" => $params['count'],
        "paginator_template" => $params['paginator_template'],
        "order" => $params['order'],
        "filter"=>$params['filter'],
    ));
else
    IncludeComponent("detail_item", $params['detail_template'], Array(
        "table" => $params['table'],
        "add_to_bread" => $params['add_to_bread'],
        "set_title" => $params['set_title'],
        "ID" => $human_link[0],
        "back_url" => $params['url']));
?>