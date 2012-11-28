<?php

if (!defined('HCMS'))
    die();

/**
 * Операции группами блоков
 */
class DBlockGroup extends DBaseClass {

    function DBlockGroup() {
        $this->sql = new HolySQL("system_data_block_group");
        //$this->sql->debug=true;
    }

    /**
     * Создаем новую группу блоков
     * @param array $values<p>набор параметров</p>
     * 
     * <p>
     * <b>sort</b> - [не обязательное] порядок сортировки<br>
     * <b>name</b> - код свойства<br>
     * <b>caption </b>- отображаемое название свойства<br>
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