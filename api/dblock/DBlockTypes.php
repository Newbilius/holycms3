<?php
    /**
     * Операции с типами свойств.
     */
class DBlockTypes extends DBaseClass {

    function DBlockTypes() {
        $this->sql = new HolySQL("system_data_block_types");
        //$this->sql->debug=true;
    }

    /**
     * Создаем новый тип для поля
     * @param array $values<p>набор параметров</p>
     * 
     * <p>
     * <b>sort</b> - [не обязательное] порядок сортировки<br>
     * <b>basetype</b> - [не обязательное] базовый (SQLный) тип. по-умолчанию - TEXT<br>
     * <b>name</b> - код свойства<br>
     * <b>caption </b>- отображаемое название свойства<br>
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