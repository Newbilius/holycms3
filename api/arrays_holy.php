<?
/**
 * Удаляет из массива пустые элементы.
 * 
 * @param array $array  <p>массив</p>
 * @param bool $assoc=true  <p>если true - индексы остаются порядковыми, если false - появляются провалы в порядковых индексах.<br>
 * </p>
 * <p>Пример:</p>
 * $assoc=true  индексы после фильтрации - 0,1,2,3,4,5<br>
 * $assoc=false  индексы после фильтрации - 1,2,5<br>
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
 * Очищаем массив от XSS перед выводом.
 * 
 * @param array $array  <p>массив для чистки</p>
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
 * Если указанные ключи не существуют в массиве - создаются пустые
 * 
 * @param array $array  <p>массив для наполнения</p>
 * @param array $keys  <p>массив ключей</p>
 * @return array
 */
function fill_empty_array($array, $keys) {

    foreach ($keys as &$key) {
        if (!isset($array[$key]))
            $array[$key] = "";
    };

    return $array;
}

/**
 * Если указанные ключи не существуют в массиве - они создаются, с указанными значениями
 * 
 * @param array $array  <p>массив для наполнения</p>
 * @param array $keys  <p>массив ключей=>значений</p>
 * @return array
 */
function fill_array_defaults($array, $keys) {

    foreach ($keys as $key=>&$value) {
        if (!isset($array[$key]))
            $array[$key] = $value;
    };

    return $array;
}
?>