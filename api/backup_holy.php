<?php

/**
 * Производит бэкап текущей базы данных в файл. Конвертирует на выходе данные в utf-8.
 * 
 * @param string $tables  <p>список таблиц (массивом или через запятую). Если не указан, сохраняются ВСЕ.</p>
 * @param string $skip_id  <p>не сохранять id элементов (не обязательное)</p>
 * @param string $file_out_name  <p>имя файла на выходе, учитывать DOCUMENT_ROOT (не обязательное)</p>
 * @param string $skip_create_tables  <p>не сохранять команды создания таблиц (не обязательное)</p>
 */
function backup_database_tables($tables = "*", $skip_id = false, $file_out_name = "", $skip_create_tables = false) {
    $return = "";
    $razdel = "\n";
    $razdel2 = $razdel;
    $razdel3 = $razdel;
    //get all of the tables
    if ($tables == '*') {
        $tables = array();
        $result = mysql_query('SHOW TABLES');
        while ($row = mysql_fetch_row($result)) {
            $tables[] = $row[0];
        }
    } else {
        $tables = is_array($tables) ? $tables : explode(',', $tables);
    }
    //cycle through each table and format the data
    foreach ($tables as $table) {
        $result = mysql_query('SELECT * FROM ' . $table);
        $num_fields = mysql_num_fields($result);
        if (!$skip_create_tables) {
            $return.= 'DROP TABLE IF EXISTS ' . $table . ';';
            $row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE ' . $table));
            $row2 = preg_replace("/\\n/", "", $row2);
            $return.= $razdel2 . "" . $row2[1] . ";";
        };
        $line1 = 0;
        for ($i = 0; $i < $num_fields; $i++) {
            while ($row = mysql_fetch_row($result)) {
                $line1++;
                if (($line1 > 1) || (!$skip_create_tables)) {
                    $return.= $razdel;
                }
                $return.= 'INSERT INTO ' . $table . ' VALUES(';
                for ($j = 0; $j < $num_fields; $j++) {
                    $row[$j] = mysql_escape_string($row[$j]);
                    if (($skip_id) && ($j == 0))
                        $row[$j] = "";
                    if (isset($row[$j])) {
                        $return.= '"' . $row[$j] . '"';
                    } else {
                        $return.= '""';
                    }
                    if ($j < ($num_fields - 1)) {
                        $return.= ',';
                    }
                }
                $return.= ");";
            }
        }
        if (!$skip_create_tables)
            $return.=$razdel3;
    }
    //save the file
    global $_CONFIG;
    global $base_base;
    if (!file_exists(FOLDER_UPLOAD))
        mkdir(FOLDER_UPLOAD);
    if (!file_exists(FOLDER_UPLOAD . "backup/"))
        mkdir(FOLDER_UPLOAD . "backup/");

    if (!isset($base_base))
        $base_base = $_CONFIG['BASE'];
    if ($file_out_name == "")
        $fname = FOLDER_UPLOAD . 'backup/' . $base_base . '__' . date("d.m.Y_H_i_s") . '__' . (md5(implode(',', $tables))) . '.sql';
    else
        $fname = $file_out_name;
    $handle = fopen($fname, 'w+');
	global $_CONFIG;
	if ($_CONFIG['CODEPAGE']!="utf8")
    $return = iconv($_CONFIG['CODEPAGE'], 'utf-8', $return);
    fwrite($handle, $return);
    fclose($handle);
}

/**
 * Возвращает информацию о полях выбранных таблиц
 * 
 * @param string $array_of_table  <p>массив таблиц</p>
 * @return array
 */
function GetTableDatas($array_of_table = array()) {
    $result = mysql_query('SHOW TABLES');
    while ($row = mysql_fetch_row($result)) {
        $tables[] = $row[0];
    }

    if (count($array_of_table) > 0)
        foreach ($array_of_table as $table)
            if (in_array($table, $tables)) {
                unset($result);
                unset($row);
                $result = mysql_query('DESC ' . $table);
                while ($row[] = mysql_fetch_row($result)) {
                    
                };
                $tables_info[$table] = $row;
            };
    return $tables_info;
};
?>
<?

/**
 * Построчно импортирует SQL-файл. В каждой строке должна храниться законченная команда!
 * 
 * @param string $path  <p>путь к файлу.</p>
 * @param array $conv  <p>Переконвертировать файл перед импортом. [0] - ИЗ какой кодировки, [1] - В какую кодировку</p>
 */
function ImportSQL($path, $conv = array()) {

    $f_data = file($path);

    foreach ($f_data as $f_line)
        if ($f_line != "") {
            if (count($conv) > 0)
                $f_line = iconv($conv[0], $conv[1], $f_line);
            mysql_query($f_line);
        };
}

;
?>