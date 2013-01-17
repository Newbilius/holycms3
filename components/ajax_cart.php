<?php

class Component_ajax_cart extends Component {

    protected function GetDefaults() {
        return array(
            "filter" => array(),
            "cache" => false,
            "cache_time" => 90,
            "cache_key" => null,
            "cost_var" => "",
            "cookie_var" => "catalog_items",
            "back_cart_url" => "",
            "debug" => false,
            "order" => "caption ASC",
        );
    }

    protected function GetParamsValidators() {
        return array(
            Array(
                'name' => "table",
                'rule' => "not_empty",
            )
        );
    }

    protected function PrepareCache() {
        return false;
    }

    protected function _complete() {
        if ($this->params['back_cart_url']) {
            echo "<a href='" . $this->params['back_cart_url'] . "'>Состояние корзины</a> обновлено.";
        } else {
            echo "Состояние корзины обновлено.";
        }
    }

    protected function Action() {
        $add_need = 0; //номер товара, который будем добавлять
        $delete_need = 0; //номер товара, который нужно удалить
        //@todo много isset'ов
        if (!isset($_REQUEST['count']))
            $_REQUEST['count'] = 1;

        $_REQUEST['count'] = intval($_REQUEST['count']);

        if ($_REQUEST['count'] < 1)
            $_REQUEST['count'] = 1;

        if (isset($_REQUEST['add']))
            $add_need = intval($_REQUEST['add']);

        if (isset($_REQUEST['delete']))
            $delete_need = intval($_REQUEST['delete']);

        $cookie_work = new HolyCookie($this->params['cookie_var']);
        if (isset($_REQUEST['complete'])) {
            $cookie_work->Delete();
            $this->_complete();
        }

        //процесс добавления в корзину

        if ($add_need > 0) {
            $cookie_work->AddToArray($add_need, $_REQUEST['count']);
            $this->_complete();
        };

        if ($delete_need > 0) {
            $cookie_work->DeleteFormArray($delete_need);
            $this->_complete();
        };

        if (isset($_REQUEST['recalc'])) {
            foreach ($_REQUEST['item'] as $num => $item) {
                $item = intval($item);
                if ($item == 0)
                    unset($_REQUEST['item'][$num]);
            };

            $cookie_work->SetArray($_REQUEST['item']);
            $this->_complete();
        };

        //@fix нехорошо
        return true;
    }

    public function Execute() {
        $validate = $this->ParamsValidate();
        if ($validate === true) {
            $this->Action();
        } else {
            $this->PrintErrors();
        }
    }

}

?>
