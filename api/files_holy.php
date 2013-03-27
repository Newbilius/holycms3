<?
/**
 * Удаляет директорию рекурсивно - со всеми поддиректориями и файлами.
 * 
 * @param string $path  <p>путь к удаляемой директории</p>
 */
function RemoveDir($path) {
    if (file_exists($path) && is_dir($path)) {
        $dirHandle = opendir($path);
        while (false !== ($file = readdir($dirHandle))) {
            if ($file != '.' && $file != '..') {// исключаем папки с назварием '.' и '..' 
                $tmpPath = $path . '/' . $file;
                chmod($tmpPath, 0777);
                if (is_dir($tmpPath)) {  // если папка
                    RemoveDir($tmpPath);
                } else {
                    if (file_exists($tmpPath)) {
                        // удаляем файл 
                        unlink($tmpPath);
                    }
                }
            }
        }
        closedir($dirHandle);
        // удаляем текущую папку
        if (file_exists($path)) {
            rmdir($path);
        }
    } else {
        //echo "Удаляемой папки не существует или это файл!";
    }
}

/**
 * Удаляет директорию. Если не указан FOLDER_ROOT, добавляет сам
 * 
 * @param string $path  <p>путь к удаляемой директории</p>
 */
function DeleteDir($path) {
    if (strpos($path, FOLDER_ROOT) === FALSE)
        $path = FOLDER_ROOT . $path;
    RemoveDir($path);
}

;

/**
 * Подготавливает файл к сохранению в базе
 * 
 * @param string $path  <p>путь к файлу</p>
 * @param bool $copy=false  <p>true - копировать файл, false - перенести по новому пути</p>
 * @return array
 */
function PrepareFile($path, $copy = false) {
    if (!file_exists($path)){
        return "";
    };
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