<?php
    /**
     * �������� � ������ �������.
     */
class DBlockTypes extends DBaseClass {

    function DBlockTypes() {
        $this->sql = new HolySQL("system_data_block_types");
        //$this->sql->debug=true;
    }

    /**
     * ������� ����� ��� ��� ����
     * @param array $values<p>����� ����������</p>
     * 
     * <p>
     * <b>sort</b> - [�� ������������] ������� ����������<br>
     * <b>basetype</b> - [�� ������������] ������� (SQL���) ���. ��-��������� - TEXT<br>
     * <b>name</b> - ��� ��������<br>
     * <b>caption </b>- ������������ �������� ��������<br>
     * </p>
     */
    function Create($values) {
        //$name,$caption,$basetype="TEXT",$sort=""
        if (!isset($values['sort']))
            $values['sort'] = $this->sql->GetSortAuto();
        if (intval($values['sort']) == 0)
            $values['sort'] = $this->sql->GetSortAuto();
        if (!isset($values['basetype']))
            $values['basetype'] = "TEXT";

        $this->sql->Insert(Array(
            "caption" => $values['caption'],
            "name" => $values['name'],
            "sort" => $values['sort'],
            "basetype" => $values['basetype'],
                )
        );
    }

}

;
?>