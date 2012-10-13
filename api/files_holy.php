<?
/**
 * ������� ���������� ���������� - �� ����� ��������������� � �������.
 * 
 * @param string $path  <p>���� � ��������� ����������</p>
 */
function RemoveDir($path) {
    if (file_exists($path) && is_dir($path)) {
        $dirHandle = opendir($path);
        while (false !== ($file = readdir($dirHandle))) {
            if ($file != '.' && $file != '..') {// ��������� ����� � ��������� '.' � '..' 
                $tmpPath = $path . '/' . $file;
                chmod($tmpPath, 0777);
                if (is_dir($tmpPath)) {  // ���� �����
                    RemoveDir($tmpPath);
                } else {
                    if (file_exists($tmpPath)) {
                        // ������� ���� 
                        unlink($tmpPath);
                    }
                }
            }
        }
        closedir($dirHandle);
        // ������� ������� �����
        if (file_exists($path)) {
            rmdir($path);
        }
    } else {
        echo "��������� ����� �� ���������� ��� ��� ����!";
    }
}

/**
 * ������� ����������. ���� �� ������ DOCUMENT_ROOT, ��������� ���
 * 
 * @param string $path  <p>���� � ��������� ����������</p>
 */
function DeleteDir($path) {
    if (strpos($path, $_SERVER['DOCUMENT_ROOT']) === FALSE)
        $path = $_SERVER['DOCUMENT_ROOT'] . $path;
    RemoveDir($path);
}

;

/**
 * �������������� ���� � ���������� � ����
 * 
 * @param string $path  <p>���� � �����</p>
 * @param bool $copy=false  <p>true - ���������� ����, false - ��������� �� ������ ����</p>
 * @return array
 */
function PrepareFile($path, $copy = false) {
    $arReturn = Array(
        "error" => 0,
        "tmp_name" => $path,
        "name" => $path,
    );
    if ($copy)
        $arReturn['copy'] = true;
    else
        $arReturn['move'] = true;
    return $arReturn;
}

;
?>