<?

if (!defined('HCMS'))
    die();


if (!isset($params['area']))
    SystemAlert("не указана область поиска!");
if (!isset($params['find_text']))
    $params['find_text'] = "";


$result = array();

if ($params['find_text'] != "")
    if (isset($params['area'])) {

        $params['find_text'] = mysql_real_escape_string($params['find_text']);
        $sqlt = "";
        foreach ($params['area'] as $id => $data) {
            $data['block'] = $id;
            if ($sqlt != "")
                $sqlt.=" UNION ALL ";

            if (is_array($data['where'])) {
                $temp = "";
                foreach ($data['where'] as $s_item) {
                    if ($temp != "")
                        $temp.=" OR ";
                    $temp.=$s_item . " LIKE '%" . $params['find_text'] . "%'";
                };
                $where = $temp;
            };
            if (is_array($data['where']))
                $sqlt.="SELECT id,caption,name,'" . $data['block'] . "' as table_name FROM " . $data['block'] . " WHERE " . $where;
            else
                $sqlt.="SELECT id,caption,name,'" . $data['block'] . "' as table_name FROM " . $data['block'] . " WHERE " . $data['where'] . " LIKE '%" . $_GET['find'] . "%' ";
        };
        $sql = new HolySQL("table");
        //$sql->debug=true;
        $sql->Query($sqlt);
        $counter = 0;

        if ($sql->GetCount() > 0)
            while ($sdata = $sql->GetNext()) {
                $counter++;
                $link = $params['area'][$sdata['table_name']]['link'];
                $link=ReplaceURL($link,$sdata);
                $result[] = array("link" => $link,
                    "caption" => $sdata['caption'],
                    "count" => $counter);
            };
    };

if (file_exists($full_template_path))
    include($full_template_path);
else
    SystemAlert("Не найден шаблон <b>" . $full_template_path . "</b>");
?>