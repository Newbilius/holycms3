<?

global $_global_bread;
global $_OPTIONS;
$res = new DBlockElement($params['table']);
//$res->sql->debug=true;

if (!isset($params['filter']))
    $params['filter'] = ""; else
    $params['filter'] = " AND " . $params['filter'];

if (isset($params['ID']))
    if (is_numeric($params['ID']))
        $res->GetList("id='" . $params['ID'] . "'" . $params['filter'], "sort ASC");
    else
        $res->GetList("name='" . $params['ID'] . "'" . $params['filter'], "sort ASC");

if (isset($params['NAME']))
    $res->GetList("name='" . $params['NAME'] . "'" . $params['filter'], "sort ASC");

if (!isset($params['set_title']))
    $params['set_title'] = "";
if (!isset($params['add_to_bread']))
    $params['add_to_bread'] = "";

$result = $res->GetNext();
if (!isset($result['id']))
    unset($result);

if (isset($params['add_to_bread_parent']))
    if ($params['add_to_bread_parent'])
        if (isset($result['parent']))
            if ($result['parent']) {
                $res2 = new DBlockElement($params['table']);
                $res2_data = $res2->GetOne("id=" . $result['parent']);
                if (isset($res2_data[$params['add_to_bread_parent']]))
                    AddToBread($res2_data[$params['add_to_bread_parent']], $params['back_url'] . $res2_data['id']);
                $_OPTIONS['now_item_folder'] = $res2_data;
            };

if ($params['add_to_bread'])
    if (isset($result[$params['add_to_bread']]))
        AddToBread($result[$params['add_to_bread']]);

if ($params['set_title'] != "")
    if (isset($result[$params['set_title']]))
        $_OPTIONS['page_title'] = $result[$params['set_title']];

if (isset($result))
{
    SetMetatags($result);
};
if (file_exists($full_template_path))
    include($full_template_path);
else
    SystemAlert("Не найден шаблон <b>" . $full_template_path . "</b>");
?>