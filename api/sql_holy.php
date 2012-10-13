<?php

$holy_sql_cache = Array();
$holy_sql_cache_count = Array();

/**
 * Класс для работы с базой.
 * 
 */
class HolySQL {

    var $table;
    var $res;
    var $error;
    var $debug; //выводить ли в процессе отладочные сообщения
    var $safe;
    var $temp_clear_cache;
    var $counter;
    var $current_index;
    var $last_id;

    /**
     * Добавляет в таблицу новый столбец
     * 
     * @param string $name  <p>имя колонки</p>
     * @param string $type  <p>SQL-тип колонки</p>
     * 
     * @return bool результат запроса
     */
    function AddColumn($name, $type) {
        global $holy_sql_cache;
        foreach ($holy_sql_cache as $a => $b)
            unset($holy_sql_cache[$a]);

        DebugADD("SQL запросов");

        $table = $this->table;

        if (($type == "TEXT") || ($type == "LONGTEXT"))
            $type.=" CHARACTER SET cp1251 COLLATE cp1251_general_ci ";

        $query = "ALTER TABLE `" . $table . "` ADD `" . $name . "` " . $type . " NOT NULL";

        if ($this->debug)
            echo "<pre>" . $query . "</pre>";

        DebugAddValue('команды', $query);
        $this->ClearCache();
        return mysql_query($query);
    }

    /**
     * Переименовывает колонку в таблице, одновременно меняет тип
     * 
     * @param string $oldname  <p>текущее имя колонки</p>
     * @param string $newname  <p>новое имя колонки</p>
     * @param string $type  <p>SQL-тип колонки</p>
     * 
     * @return bool результат запроса
     */
    function RenameColumn($oldname, $newname, $type) {
        DebugADD("SQL запросов");

        global $holy_sql_cache;
        foreach ($holy_sql_cache as $a => $b)
            unset($holy_sql_cache[$a]);

        DebugADD("SQL запросов");

        $table = $this->table;

        $query = "ALTER TABLE `" . $table . "` CHANGE `" . $oldname . "` `" . $newname . "` " . $type . " NOT NULL";

        if ($this->debug)
            echo "<pre>" . $query . "</pre>";

        DebugAddValue('команды', $query);
        $this->ClearCache();
        return mysql_query($query);
    }

    /**
     * Удаляет колонку из таблицы
     * 
     * @param string $name  <p>имя колонки</p>
     * 
     * @return bool результат запроса
     */
    function DeleteColumn($name) {
        global $holy_sql_cache;
        foreach ($holy_sql_cache as $a => $b)
            unset($holy_sql_cache[$a]);

        DebugADD("SQL запросов");

        $table = $this->table;

        $query = "ALTER TABLE `" . $table . "` DROP `" . $name . "`";

        if ($this->debug)
            echo "<pre>" . $query . "</pre>";

        DebugAddValue('команды', $query);
        $this->ClearCache();
        return mysql_query($query);
    }

    /**
     * Переключает текущую таблицу
     * 
     * @param string $name  <p>имя таблицы</p>
     * 
     * @return bool результат запроса
     */
    function ChangeTable($name) {
        global $holy_sql_cache;
        foreach ($holy_sql_cache as $a => $b)
            unset($holy_sql_cache[$a]);
        if ($name != "") {
            $this->table = $name;
            if ($this->table != 'system_cache')
                $this->temp_clear_cache = new HolyCacheOut("", 1, $this->table);
        };
    }

    /**
     * Конструктор
     * 
     * @param string $name  <p>имя таблицы</p>
     */
    function HolySQL($name = "") {
        $this->table = $name;
        if ($this->table != 'system_cache')
            $this->temp_clear_cache = new HolyCacheOut("", 1, $this->table);
        $this->debug = false;
        $this->safe = true;
    }

    /**
     * Запрос на получение данных
     * 
     * @param string/array $where  <p>список условий</p>
     * @param string $order_by  <p>по какому столбцу сортировать</p>
     * @param string/array $what  <p>список столбцов для получения данных</p>
     * @param int $count_on_page  <p>число элементов на странице</p>
     * @param int $page  <p>страница постраничной</p>
     */
    function Select($where = "1", $order_by = "sort ASC", $what = "*", $count_on_page = 0, $page = 0) {
        DebugADD("SQL запросов");

        $table = $this->table;

        $query = "SELECT ";

        if (is_array($what)) {
            $cnt = 0;
            foreach ($what as $w) {
                if ($cnt > 0)
                    $query.=",";
                $query . "'" . $w . "'";
                $cnt++;
            };
        }
        else
            $query.=$what . " ";

        $query.="FROM " . $table . " WHERE ";

        if (is_array($where)) {
            $cnt = 0;
            foreach ($where as $i => $w) {
                if ($cnt > 0)
                    $query.=" AND ";
                if (is_numeric($w))
                    $query.=$i . "='" . $w . "' ";
                else
                    $query.=$i . " = '" . $w . "' ";

                $cnt++;
            };
        }
        else
            $query.=$where . " ";

        $query.="ORDER BY " . $order_by;

        if ($count_on_page != 0) {
            $query.=" LIMIT " . ($count_on_page * ($page - 1)) . " , " . $count_on_page;
        }

        if ($this->debug)
            echo "<pre>" . $query . "</pre>";

        $this->counter = 0;
        global $holy_sql_cache;
        $this->current_index = $query;

        if (!isset($holy_sql_cache[$query])) {
            DebugADD("SQL запросов");
            DebugAddValue('команды', $query);
            $this->res = mysql_query($query);
            if ($this->res) {
                while ($row = mysql_fetch_array($this->res)) {
                    if (is_array($row))
                        foreach ($row as $i => $r)
                            $row[$i] = stripslashes($row[$i]);
                    else
                        $row = stripslashes($row);

                    foreach ($row as $iii => $rrr)
                        if (is_int($iii))
                            unset($row[$iii]);

                    $holy_sql_cache[$query][] = $row;
                };
                if (!isset($holy_sql_cache[$query]))
                    $holy_sql_cache[$query][0] = Array();
                if (!isset($holy_sql_cache[$query]))
                    $holy_sql_cache_count[$query] = 0;
                else
                    $holy_sql_cache_count[$query] = count($holy_sql_cache[$query]);
            };
        };
    }

    /**
     * Получение следующего элемента после запроса.
     * 
     * @return array
     */
    function GetNext() {
        global $holy_sql_cache;
        if (isset($holy_sql_cache[$this->current_index])) {
            if (isset($holy_sql_cache[$this->current_index][$this->counter])) {
                $num = $this->counter;
                $this->counter++;
                return $holy_sql_cache[$this->current_index][$num];
            }
            else
                return false;
        } else
            return false;
    }

    /**
     * Получение списка полученных запросом элементов
     * 
     * @return int
     */
    function GetCount() {
        if (isset($holy_sql_cache_count[$this->current_index]))
            return $holy_sql_cache_count[$this->current_index];
        else
        if ($this->res)
            return mysql_num_rows($this->res);
    }

    /**
     * Получает значение для поля 'sort' или выбранного, на 100 больше предыдущего
     * 
     * @param string $sort_name='sort' <p>[не обязательное] имя поля</p>
     * @return int
     */
    function GetSortAuto($sort_name = 'sort') {
        $data = $this->SelectOnce("1", $order_by = "sort DESC", $sort_name);
        if (!isset($data[$sort_name]))
            $data[$sort_name] = 0;
        return (intval($data[$sort_name]) + 100);
    }

    /**
     * Возвращает массив с одиночным элементом
     * 
     * @param string/array $where  <p>список условий</p>
     * @param string $order_by  <p>по какому столбцу сортировать</p>
     * @param string/array $what  <p>список столбцов для получения данных</p>
     * 
     * @return array
     */
    function SelectOnce($where = "1", $order_by = "sort ASC", $what = "*") {
        $this->Select($where, $order_by, $what);

        return $this->GetNext();
    }

    /**
     * Возвращает массив с одиночным элементом
     * 
     * @param string/array $where  <p>список условий</p>
     * @param string $order_by  <p>по какому столбцу сортировать</p>
     * @param string/array $what  <p>список столбцов для получения данных</p>
     * 
     * @return array
     */
//@fix избавиться от функции-дублёра
    function SelectOne($where = "1", $order_by = "sort ASC", $what = "*") {
        return $this->SelectOnce($where, $order_by, $what);
    }

    /**
     * Вставляет в таблицу строку
     * 
     * @param array $values  <p>данные, массив вида столбец=>значение</p>
     * 
     * @return bool результат запроса
     */
    function Insert($values) {
        global $holy_sql_cache;
        foreach ($holy_sql_cache as $a => $b)
            unset($holy_sql_cache[$a]);
        DebugADD("SQL запросов");

        $table = $this->table;

        $cnt = 0;
        $names = "";
        $value_names = "";
        foreach ($values as $key => $value) {
            if ($cnt > 0) {
                $names.=",";
                $value_names.=",";
            };
            $names.=$key;

            if ($value == "NULL")
                $value_names.=$value;
            else {
                if ($this->safe)
                    $value_names.="'" . mysql_real_escape_string($value) . "'";
                else
                    $value_names.="'" . $value . "'";
            };
            $cnt++;
        };

        $query = "INSERT INTO " . $table . " (" . $names . ") VALUES (" . $value_names . ")";
        if ($this->debug)
            echo "<pre>" . $query . "</pre>";
        DebugAddValue('команды', $query);
        $this->ClearCache();
        $_tmp = mysql_query($query);
        $this->last_id = mysql_insert_id();
        return $_tmp;
    }

    /**
     * Выполняет произвольный запрос
     * 
     * @param string $query  <p>запрос</p>
     * 
     * @return bool результат запроса
     */
    function Query($query) {
        global $holy_sql_cache;
        foreach ($holy_sql_cache as $a => $b)
            unset($holy_sql_cache[$a]);
        DebugADD("SQL запросов");

        if ($this->debug)
            echo "<pre>" . $query . "</pre>";

        $this->counter = 0;
        global $holy_sql_cache;
        $this->current_index = $query;

        if (!isset($holy_sql_cache[$query])) {
            DebugADD("SQL запросов");
            DebugAddValue('команды', $query);
            //echo "<HR>1:".$query.":2<HR>";
            $this->res = mysql_query($query);
            if (($this->res) && (strpos($query, 'CREATE TABLE') === FALSE) && (strpos($query, 'DROP TABLE') === FALSE)) {
                if ($this->res !== FALSE)
                    while (@$row = mysql_fetch_array($this->res)) {
                        if (is_array($row))
                            foreach ($row as $i => $r)
                                $row[$i] = stripslashes($row[$i]);
                        else
                            $row = stripslashes($row);

                        foreach ($row as $iii => $rrr)
                            if (is_int($iii))
                                unset($row[$iii]);

                        $holy_sql_cache[$query][] = $row;
                    };
                if (!isset($holy_sql_cache[$query]))
                    $holy_sql_cache[$query][0] = Array();
                if (!isset($holy_sql_cache[$query]))
                    $holy_sql_cache_count[$query] = 0;
                else
                    $holy_sql_cache_count[$query] = count($holy_sql_cache[$query]);
            };
        };
        $this->ClearCache();
        return mysql_query($query);
    }

    /**
     * Обновляет данные в таблице
     * 
     * @param string/array $where  <p>условие, массив вида столбец=>значение или просто SQL-строка</p>
     * @param string/array $values  <p>данные, массив вида столбец=>значение или просто SQL-строка</p>
     * 
     * @return bool результат запроса
     */
    function Update($where, $values) {
        global $holy_sql_cache;
        foreach ($holy_sql_cache as $a => $b)
            unset($holy_sql_cache[$a]);
        DebugADD("SQL запросов");

        $table = $this->table;

        $query = "UPDATE " . $table . " SET ";

        if (is_array($values)) {
            $cnt = 0;
            foreach ($values as $i => $w) {
                if ($cnt > 0)
                    $query.=",";
                if (is_numeric($w))
                    $query.=$i . "=" . $w . "";
                else
                    $query.=$i . "='" . mysql_real_escape_string($w) . "'";
                $cnt++;
            };
        }
        else
            $query.=$values;

        $query.=" WHERE ";

        if (is_array($where)) {
            $cnt = 0;
            foreach ($where as $i => $w) {
                if ($cnt > 0)
                    $query.=",";
                if (is_numeric($w))
                    $query.=$i . "=" . $w . "";
                else
                    $query.=$i . "='" . mysql_real_escape_string($w) . "'";
                $cnt++;
            };
        }
        else
            $query.=$where;

        if ($this->debug)
            echo "<pre>" . $query . "</pre>";
        DebugAddValue('команды', $query);
        $this->ClearCache();
        return mysql_query($query);
    }

    /**
     * Очистка кэша, связанного с текущей таблицей
     * 
     */
    function ClearCache() {
        if ($this->table != 'system_cache') {
            $this->temp_clear_cache->Clear();
        };
    }

    /**
     * Удаялет данные из таблицы
     * 
     * @param string/array $where  <p>условие, массив вида столбец=>значение или просто SQL-строка</p>
     * 
     * @return bool результат запроса
     */
    function Delete($where = "") {
        global $holy_sql_cache;
        foreach ($holy_sql_cache as $a => $b)
            unset($holy_sql_cache[$a]);
        DebugADD("SQL запросов");

        if ($where == "") {
            return false;
        } else {
            $table = $this->table;

            $query = "DELETE FROM " . $table . " WHERE ";

            if (is_array($where)) {
                $cnt = 0;
                foreach ($where as $i => $w) {
                    if ($cnt > 0)
                        $query.=",";
                    if (is_numeric($w))
                        $query.=$i . "=" . $w . "";
                    else
                        $query.=$i . "='" . $w . "'";
                    $cnt++;
                };
            }
            else
                $query.=$where;

            if ($this->debug)
                echo "<pre>" . $query . "</pre>";
            DebugAddValue('команды', $query);
            $this->ClearCache();
            return mysql_query($query);
        }
    }

}

;
?>