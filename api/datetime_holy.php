<?
/**
 * ����������� ������ ������ 10 ������� ����
 * 
 * @param string $pattern  <p>������ ������</p>
 * @return string
 */

function DateTimePrefix($num) {
    if ($num < 10)
        $num = "0" . $num;
    return $num;
}

;


/**
 * ������� ���� �� ������� �� �������� �������, ������������ strftime
 * 
 * @param string $pattern  <p>������ ������</p>
 * @param string $date  <p>���� � ������� ��</p>
 * @return string
 */
function PrintDate($pattern, $date) {
    return strftime($pattern, strtotime($date));
}
?>