<?php

/**
 * ����� ��� ������ �� ���������� ������.
 */
class DBlockFields extends DBaseClass {

    var $datab;
    var $typeb;
    var $sql2;

    /**
     * �����������.
     */
    function DBlockFields() {
        $this->sql = new HolySQL("system_data_block_fields");
        $this->sql2 = new HolySQL("system_data_block_fields");
        $this->datab = new DBlock();
        $this->typeb = new DBlockTypes();
    }

    /**
     * ���������� ������ ������� �� ���������� �����
     * 
     * @param string/int dblock  <p>��������� ����</p>
     * @param string/array $filter=Array()  <p>[�� ������������] ������ �������</p>
     * @param string $sort = "sort ASC"  <p>[�� ������������] ������� ���������� �������</p>
     */
    function GetListByBlock($dblock, $filter = Array(), $sort = "sort ASC") {
        if (is_numeric($dblock))
            $filter['data_block'] = $dblock;
        else
            $filter['data_block'] = $this->datab->GetIDByName($dblock);
        $this->sql->Select($filter, $sort);
    }

    /**
     * �������� ������ �������� (������ ����� ��������� �������!)
     * 
     * @param string $id  <p>��� ��������</p>
     * @param array $values  <p>������ ��������</p>
     */
    function Update($id, $values) {
        //@fix ��������� ��� id-������
        if (is_numeric($values['data_block'])) {
            
        } else {
            $values['data_block'] = $this->datab->GetIDByName($values['data_block']);

            //���������, �� ��������� �� ���, ���� �� - �� �� ����� �������
            $type_info = $this->sql2->SelectOne("id=" . $id);
            if (isset($type_info['name']))
                if (isset($values['name']))
                    if ($type_info['name'] != $values['name']) {
                        //����� ������������� �������, � ����� ��� �� ���������
                        $type = $this->typeb->GetByID($values['type']);

                        $type = $type['basetype'];
                        $block_name = $this->datab->GetByID($values['data_block']);

                        $block_name = $block_name['name'];

                        $sql2 = new HolySQL($block_name);
                        $sql2->RenameColumn($type_info['name'], $values['name'], $type);
                    };
        }
        parent::Update($id, $values);
    }

    /**
     * ������� ����� ��������
     * 
     * @param array $values  <p>������ ��������:</p>
     * <br><b>caption</b> - string - ���������
     * <br><b>name</b> - string - ���
     * <br><b>data_block</b> - string - name �����
     * <br><b>type</b> - string - name ����
     * <br><b>sort</b> - int - ������� ����������
     * <br><b>required</b> - bool - ������������ �� ����, �� ��������� false
     * <br><b>multiple</b> - bool - �������������,false (0 ���, 1 ��)
     * <br><b>owner_type</b> - int - �������� (0 - ��������,1 - ����� � ��������)
     */
    
    function Create($values = Array()) {
        $block_name = $values['data_block'];
        $values['data_block'] = $this->datab->GetIDByName($values['data_block']);
        
        //@fix ��������� ������ � id
        if (is_numeric($values['type'])) {
            
        }
        else
            $values['type'] = $this->typeb->GetIDByName($values['type']);

        if (!isset($values['sort']))
            $values['sort'] = $this->sql->GetSortAuto();
        if ($values['sort'] == 0)
            $values['sort'] = $this->sql->GetSortAuto();
        if (!isset($values['required']))
            $values['required'] = 0;
        if (!isset($values['multiple']))
            $values['multiple'] = 0;
        if (!isset($values['dgroup']))
            $values['dgroup'] = 0;
        if (!isset($values['owner_type']))
            $values['owner_type'] = 0;
        if (!isset($values['not_element']))
            $values['not_element'] = 0;

        $this->sql->Insert($values);


        if ($values['multiple'] == 1)
            $type_info['basetype'] = "TEXT";
        elseif ($values['multiple'] == 2)
            $type_info['basetype'] = "INT";
        else
            $type_info = $this->typeb->GetByID($values['type']);
        $sql2 = new HolySQL($block_name);
        $sql2->AddColumn($values['name'], $type_info['basetype']);
    }

    /**
     * ������� ��������
     * 
     * @param int/string $name <p>ID/��� ���������� ��������</p>
     */
    function Delete($name) {
        if (is_numeric($name))
            $name_name = $this->GetNameByID($name);
        else {
            $name_name = $name;
            $name = $this->GetIDByName($name);
        };
        $fields = $this->sql->SelectOnce("id='" . $name . "'");
        if (isset($fields['data_block']))
            if ($fields['data_block']) {
                $bname = $this->datab->GetNameByID($fields['data_block']);
                $sql2 = new HolySQL($bname);
                $sql2->DeleteColumn($name_name);
                $this->sql->Delete("id='" . $name . "'");
            };
    }

}

;
?>