<?
global $_selected_page;

/*
 * table    ������� �� ������� ����� ������
 * count    ������� �������� ���������
 * page     �������� (��� ������ ������������� ����� ���������)
 *          ���� �� �������, ������ �� $_GET['page']
 * paginator_template   ������ ������������ ���������
 * page_base_link       ������ � ����� ������
 * filter               ������
 * draw_paginator       �������� �� ������������ ���������
 * debug                �������� �� ��������
 */

unset($res);
$res = new DBlockElement($params['table']);
if (!isset($params['debug']))
    $params['debug']=false;
if (!isset($params['count']))
    $params['count']="";
if (!isset($params['order']))
    $params['order']="sort ASC";
if (!isset($params['page']))
    $params['page']="";

$res->sql->debug=$params['debug'];

if ($params['count'])
    if ($params['page']==="") {
        if (isset($_GET['page']))
            $params['page'] = $_GET['page'];
        else
            $params['page'] = 1;
    };

if (!isset($params['paginator_template']))
    $params['paginator_template'] = "";

if (!isset($params['page_base_link']))
    $params['page_base_link'] = "?page=";

if (!isset($params['filter']))
    $params['filter'] = "1";
if ($params['filter'] == "")
    $params['filter'] = "1";

if (!isset($params['draw_paginator']))
    $params['draw_paginator'] = false;

$res->GetList($params['filter'], $params['order']);
$max_count_now = $res->GetCount();
$res->GetList($params['filter'], $params['order'], $params['count'], $params['page']);

while ($result1 = $res->GetNext()) {
    $result[] = $result1;
};

$_count=0;

if (isset($result))
    $_count = count($result);

if (file_exists($full_template_path))
    include($full_template_path);
else
    SystemAlert("�� ������ ������ <b>" . $full_template_path . "</b>");

if ($params['draw_paginator']) {
    $res->SetPaginator($params['count'], $params['page']);
    $res->DrawPaginator($params['paginator_template']);
};
?>