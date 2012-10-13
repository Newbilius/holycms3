<?php

if (!defined('HCMS'))
    die();

/**
 * �������� �������� ������
 */
class DBlockGroup extends DBaseClass {

    function DBlockGroup() {
        $this->sql = new HolySQL("system_data_block_group");
        //$this->sql->debug=true;
    }

    /**
     * ������� ����� ������ ������
     * @param array $values<p>����� ����������</p>
     * 
     * <p>
     * <b>sort</b> - [�� ������������] ������� ����������<br>
     * <b>name</b> - ��� ��������<br>
     * <b>caption </b>- ������������ �������� ��������<br>
     * </p>
     */
    function Create($values) {
        $name = $values['name'];
        $caption = $values['caption'];

        if (!isset($values['sort']))
            $values = $values['sort'];

        $sort = $values['name'];
        if (intval($sort) == 0)
            $sort = $this->sql->GetSortAuto();
        $this->sql->Insert(Array(
            "caption" => $caption,
            "name" => $name,
            "sort" => $sort
                )
        );
    }

}

;
?>