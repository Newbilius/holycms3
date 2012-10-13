<?php
/**
 * �����-������ ��� ���������.
 */
class DBaseClass {

    var $sql;
    var $count_on_page;
    var $page;
    var $real_count;
    var $base_link;

    function DBaseClass() {
        $this->count_on_page = 0;
        $this->page = 0;
    }
/**
 * ������������� ��������� ����������
 * 
 * @param int $count  <p>����� ����� ���������</p>
 * @param int $page  <p>������� ��������</p>
 * @param string $base_link="?page="  <p>[�� ������������] ���������� � ������ ��� �����������</p>
 */
    function SetPaginator($count, $page, $base_link = "?page=") {
        $this->count_on_page = $count;
        $this->page = $page;
        $this->base_link = $base_link;
    }

/**
 * ������� ��������� � ������ ��������
 * 
 * @param string $template  <p>[�� ������������] ������</p>
 */
    function DrawPaginator($template = "") {
        if ($this->count_on_page > 0)
            IncludeComponent("page_navigator", $template, Array(
                "count" => $this->count_on_page,
                "page" => $this->page,
                "max_count" => $this->real_count,
                "base_link" => $this->base_link
            ));
    }
/**
 * ���������� ��� �������� �� ��� id
 * 
 * @param int $id  <p>id ��������</p>
 * 
 * @return string $name
 */
    function GetNameByID($id) {
        $dat = $this->sql->SelectOnce(Array("id" => $id));
        if (!isset($dat['name']))
            $dat['name'] = "";
        return $dat['name'];
    }

/**
 * ���������� id �������� �� ��� �����
 * 
 * @param string $name  <p>��� ��������</p>
 * 
 * @return int $id
 */
    function GetIDByName($name) {
        $dat = $this->sql->SelectOnce(Array("name" => $name));
        if (!isset($dat['id']))
            $dat['id'] = false;
        return $dat['id'];
    }

/**
 * ����������� ������� �� $id ��� ����
 * 
 * @param string/int $id  <p>id ��� ��� ��������</p>
 * @param string $sort='sort ASC'  <p>[�� ������������] ������ ����������</p>
 * 
 * @return array
 */
    function GetByID($id = 0, $sort = "sort ASC") {
        if (is_numeric($id)) {
            if ($id != 0)
                return $this->sql->SelectOnce("id=" . intval($id), $sort);
        } else {
            return $this->sql->SelectOnce("name='" . $id . "'", $sort);
        }
    }

/**
 * ����� �������� ����� GetList. �������� ������� ������ ��������� � ������������ � �����������
 * 
 * @return array
 */    
    function GetFullList() {
        $result = array();
        while ($tmp = $this->GetNext())
            if (isset($tmp['id']))
                $result[] = $tmp;

        return $result;
    }
    
/**
 * ����� �������� ����� GetList. �������� ������� ������ ��������� � id � �������� ������
 * 
 * @return array
 */    
    function GetIDList() {
        $result = array();
        while ($tmp = $this->GetNext())
            if (isset($tmp['id']))
                $result[$tmp['id']] = $tmp;

        return $result;
    }

/**
 * ����������� ������ ���������
 * 
 * @param string $filter=1  <p>[�� ������������] ������� ��� ������</p>
 * @param string $sort='sort ASC'  <p>[�� ������������] ������ ����������</p>
 * @param int $count_on_page=0  <p>[�� ������������] ����� ��������� �� ��������. 0 - ��� �����������</p>
 * @param int $page=0  <p>[�� ������������] ����� ��������. ������ �� ������ �� ������ ��� $count_on_page=0</p>
 */    
    function GetList($filter = "1", $sort = "sort ASC", $count_on_page = 0, $page = 0) {

        if ($this->count_on_page != 0)
            $count_on_page = $this->count_on_page;
        if ($this->page != 0)
            $page = $this->page;


        if ($count_on_page != 0) {
            $this->sql->Select($filter, $sort, "*");
            $this->real_count = $this->GetCount();
            $this->sql->Select($filter, $sort, "*", $count_on_page, $page);
        }
        else
            $this->sql->Select($filter, $sort, "*", $count_on_page, $page);
    
        return $this;
    }

/**
 * ����������� �������
 * 
 * @param string $filter=1  <p>[�� ������������] ������� ��� ������</p>
 * @param string $sort='sort ASC'  <p>[�� ������������] ������ ����������</p>
 * 
 * @return array
 */
    
    function GetOne($filter = "1", $sort = "sort ASC") {
        return $this->sql->SelectOnce($filter, $sort);
    }

 /**
 * ��������� ����� ���������, ���������� ��������� ��������
 */
    function GetCount() {
        return $this->sql->GetCount();
    }

/**
 * ��������� ��������� ������� ����� ������� ������.
 */
    function GetNext() {
        return $this->sql->GetNext();
    }

/**
 * ��������� �������
 * 
 * @param int/string $id  <p>id ��� ��� ��������</p>
 * @param array $values  <p>������ ��������</p>
 */
    function Update($id, $values) {
        if (is_numeric($id)) {
            if (intval($id) != 0)
                $this->sql->Update(Array("id" => $id), $values);
        } else {
            $this->sql->Update(Array("name" => $id), $values);
        }
    }

    protected function RecursiveDelete($id) {
        $id = intval($id);
        if ($id > 0) {
            $this->sql->Select("parent=" . $id);
            $list_of_delete = array();

            while ($list_of_delete[] = $this->sql->GetNext());

            if (count($list_of_delete) > 0)
                foreach ($list_of_delete as $item_to_delete)
                    if (isset($item_to_delete['id']))
                        if (intval($item_to_delete['id']) > 0) {
                            $this->RecursiveDelete($item_to_delete['id']);
                        };

            $this->sql->Delete("id='" . $id . "'");
        };
    }

/**
 * ������� ������� �� ID
 * 
 * @param int $id  <p>ID ���������� ��������</p>
 */
    function Delete($id) {
        if (is_numeric($id)) {
            if ($id != 0) {
                $this->RecursiveDelete(intval($id));
            };
        } else {
            //@fix �������� �� ���� ��� �������� ���� ������?
        }
    }

}

;
?>