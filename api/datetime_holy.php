<?
/**
 * Приписывает числам меньше 10 ведущий ноль
 * 
 * @param string $pattern  <p>шаблон вывода</p>
 * @return string
 */

function DateTimePrefix($num) {
    if ($num < 10)
        $num = "0" . $num;
    return $num;
}

;


/**
 * Выводит дату из формата БД согласно шаблону, аналогичному strftime
 * 
 * @param string $pattern  <p>шаблон вывода</p>
 * @param string $date  <p>дата в формате БД</p>
 * @return string
 */
function PrintDate($pattern, $date) {
    return strftime($pattern, strtotime($date));
}
?>