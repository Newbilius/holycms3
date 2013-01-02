<?php

class Component_search extends Component {

    protected function GetDefaults() {
        return array(
            "template" => "default",
            "find_text" => "",
            "debug" => false,
        );
    }

    protected function GetParamsValidators() {
        return array(
            Array(
                'name' => "area",
                'rule' => "not_empty",
            )
        );
    }

    protected function Action() {
        $result = array();
        if ($this->params['find_text'] != "")
            if (isset($this->params['area'])) {

                $this->params['find_text'] = mysql_real_escape_string($this->params['find_text']);
                $sqlt = "";
                foreach ($this->params['area'] as $id => $data) {
                    $data['block'] = $id;
                    if ($sqlt != "")
                        $sqlt.=" UNION ALL ";

                    if (is_array($data['where'])) {
                        $temp = "";
                        foreach ($data['where'] as $s_item) {
                            if ($temp != "")
                                $temp.=" OR ";
                            $temp.=$s_item . " LIKE '%" . $this->params['find_text'] . "%'";
                        };
                        $where = $temp;
                    };
                    if (is_array($data['where']))
                        $sqlt.="SELECT id,caption,name,'" . $data['block'] . "' as table_name FROM " . $data['block'] . " WHERE " . $where;
                    else
                        $sqlt.="SELECT id,caption,name,'" . $data['block'] . "' as table_name FROM " . $data['block'] . " WHERE " . $data['where'] . " LIKE '%" . $_GET['find'] . "%' ";
                };
                $sql = new HolySQL("table");
                $sql->debug = $this->params['debug'];
                $sql->Query($sqlt);
                $counter = 0;

                if ($sql->GetCount() > 0)
                    while ($sdata = $sql->GetNext()) {
                        $counter++;
                        $link = $this->params['area'][$sdata['table_name']]['link'];
                        $link = ReplaceURL($link, $sdata);
                        $result[] = array("link" => $link,
                            "caption" => $sdata['caption'],
                            "count" => $counter);
                    };
            };
        return $result;
    }

}

?>
