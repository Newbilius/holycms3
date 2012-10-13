<?php

/**
 * ���������� ����� ������� ���� ������ � ����. ��-��������� ������������ ������ �� ��������� win-1251 � utf-8
 * 
 * @param string $tables  <p>������ ������. ���� �� ������, ����������� ���.</p>
 * @param string $skip_id  <p>�� ��������� id ��������� (�� ������������)</p>
 * @param string $file_out_name  <p>��� ����� �� ������, ��������� DOCUMENT_ROOT (�� ������������)</p>
 */
function backup_database_tables($tables = "*", $skip_id = false, $file_out_name = "") {
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
        $return.= 'DROP TABLE IF EXISTS ' . $table . ';';
        $row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE ' . $table));
        $row2 = preg_replace("/\\n/", "", $row2);
        $return.= $razdel2 . "" . $row2[1] . ";";
        for ($i = 0; $i < $num_fields; $i++) {
            while ($row = mysql_fetch_row($result)) {
                $return.= $razdel . 'INSERT INTO ' . $table . ' VALUES(';
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
        $return.=$razdel3;
    }
    //save the file
    global $_CONFIG;
    global $base_base;
    if (!file_exists($_SERVER['DOCUMENT_ROOT'] . "/upload/"))
        mkdir($_SERVER['DOCUMENT_ROOT'] . "/upload/");
    if (!file_exists($_SERVER['DOCUMENT_ROOT'] . "/upload/backup/"))
        mkdir($_SERVER['DOCUMENT_ROOT'] . "/upload/backup/");

    if (!isset($base_base))
        $base_base = $_CONFIG['BASE'];
    if ($file_out_name == "")
        $fname = $_SERVER['DOCUMENT_ROOT'] . '/upload/backup/' . $base_base . '__' . date("d.m.Y_H_i_s") . '__' . (md5(implode(',', $tables))) . '.sql';
    else
        $fname = $file_out_name;
    $handle = fopen($fname, 'w+');
    $return = iconv('windows-1251', 'utf-8', $return);
    fwrite($handle, $return);
    fclose($handle);
}

/**
 * ���������� ���������� � ����� ��������� ������
 * 
 * @param string $array_of_table  <p>������ ������</p>
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
}

;
?>
<?

/**
 * ��������� ����������� SQL-����. � ������ ������ ������ ��������� ����������� �������!
 * 
 * @param string $path  <p>���� � �����. ���� �� ������ DOCUMENT_ROOT, �� ����������� �������������</p>
 * @param array $conv  <p>������������������ ���� ����� ��������. [0] - �� ����� ���������, [1] - � ����� ���������</p>
 */
function ImportSQL($path, $conv = array()) {
    if (strpos($path, $_SERVER['DOCUMENT_ROOT']) === FALSE)
        $path = $_SERVER['DOCUMENT_ROOT'] . $path;

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