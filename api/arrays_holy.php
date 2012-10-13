<?
/**
 * ������� �� ������� ������ ��������.
 * 
 * @param array $array  <p>������</p>
 * @param bool $assoc=true  <p>���� true - ������� �������� �����������, ���� false - ���������� ������� � ���������� ��������.<br>
 * </p>
 * <p>������:</p>
 * $assoc=true  ������� ����� ���������� - 0,1,2,3,4,5<br>
 * $assoc=false  ������� ����� ���������� - 1,2,5<br>
 * @return array
 */
function RemoveEmptyFromArray($array, $assoc = true) {
    $Result = array();
    foreach ($array as $key => $value) {
        if ($value != '')
            $Result[$key] = $value;
    }
    if ($assoc)
        return array_values($Result);
    else
        return $Result;
}

/**
 * ������� ������ �� XSS ����� �������.
 * 
 * @param array $array  <p>������ ��� ������</p>
 * @return array
 */
function clear_array($array) {
    if (is_array($array))
        foreach ($array as &$item) {
            if (is_array($item))
                clear_array($item);
            else
                $item = filter_var($item, FILTER_SANITIZE_STRING);
        }
    else
        $array = filter_var($array, FILTER_SANITIZE_STRING);

    return $array;
}

/**
 * ���� ��������� ����� �� ���������� � ������� - ��������� ������
 * 
 * @param array $array  <p>������ ��� ����������</p>
 * @param array $keys  <p>������ ������</p>
 * @return array
 */
function fill_empty_array($array, $keys) {

    foreach ($keys as &$key) {
        if (!isset($array[$key]))
            $array[$key] = "";
    };

    return $array;
}
?>