<?
//@todo очистка журнала - только для админов... или нет?
$_global_bread[] = Array("Очистка кэша", "");
if (isset($_POST['form_go'])) {
    $SQL = new HolySQL('system_cache');

    if (isset($_POST['clear_base'])) {
        $ok[] = "Кэш базы очищен";
        $_tmp_holy_cache=new HolyCacheOut();
        $_tmp_holy_cache->ClearFull();
    };
    if (isset($_POST['clear_resize'])) {
        $ok[] = "Кэш картинок очищен";
        $cache_img_folder = FOLDER_UPLOAD . "resize_cache/";
        if (file_exists($cache_img_folder)) {
            DeleteDir($cache_img_folder);
            mkdir($cache_img_folder);
        };
    };
    if (isset($_POST['clear_tmp'])) {
        $ok[] = "Временные файлы удалены";
        $tmp_folder = FOLDER_UPLOAD . "tmp/";
        if (file_exists($tmp_folder)) {
            DeleteDir($tmp_folder);
            mkdir($tmp_folder);
        };
    };

    if (isset($_POST['clear_orphan'])) {
        $arBlock = new DBlock();

        //получаем свойства ID
        $types = new DBlockTypes();
        $type_id = $types->GetIDByName("image");
        $type_id2 = $types->GetIDByName("image_multiple");

        //получаем свойства-картинки
        $fields = new DBlockFields();
        $fields->GetList("type=" . $type_id . " OR type=" . $type_id2);
        //идем по списку свойств-картинок
        while ($field = $fields->GetNext()) {
            $block = $arBlock->GetByID($field['data_block']);
            if (isset($block['name']))
                if ($block['name']) {
                    $elements = new DBlockElement($block['name']);
                    $elements->GetList();
                    //получаем непосредственно картинки
                    while ($tmp = $elements->GetNext()) {
                        $tmp = explode(";", $tmp[$field['name']]);
                        foreach ($tmp as $_img)
                            if ($_img) {
                                $pic[] = FOLDER_ROOT . $_img;
                            }
                    }
                };
        };
        //ищем реальные файлы
        $real_pics = FileFindRecursive(FOLDER_IMAGE, Array("jpeg", "jpg", "gif", "png"));
        if (is_array($pic))
            if (is_array($real_pics)) {
                //сравниваем списки реальных файлов и сохраненных в базе
                $need_to_delete = array_diff($real_pics, $pic);

                //удаляем
                if ($need_to_delete)
                    if (is_array($need_to_delete)) {
                        foreach ($need_to_delete as $_delete) {
                            unlink($_delete);
                        }
                    };
            };
        $ok[] = "Картинки-сиротки удалены";
    };
};
?>
<BR>

<?
if (isset($ok)) {
    foreach ($ok as $ok_text) {
        ?>
        <div class="alert alert-success"><?= $ok_text ?></div>
        <?
    };
    ?>
    <?
};
?>
<form method=post>
    <input type=hidden name="form_go" value="512">
    Выберите, что очищать:<BR><BR>
    <?
    global $_CONFIG;
    if ($_CONFIG['CACHE_SYSTEM']) {
        ?>
        <label class="checkbox"><input checked type=checkbox name="clear_base" value="1"> &nbsp; Очистить кэш базы</label>

    <? }; ?>
    <label class="checkbox"><input checked type=checkbox name="clear_resize" value="1"> &nbsp; Удалить кэш картинок</label>

    <label class="checkbox"><input checked type=checkbox name="clear_tmp" value="1"> &nbsp; Удалить временные файлы</label>

    <label class="checkbox"> <input checked type=checkbox name="clear_orphan" value="1"> &nbsp; Удалить картинки-сиротки</label>
    <? /* <BR>
      <input type=checkbox name="clear_orphan" value="1"> &nbsp; Удалить несуществующие картинки (долгий процесс, но позволяет сэкономить место)
     */ ?>

    </br><br><input name=submit type=submit value="Очистить кэш" style="width:40%;HEIGHT:28px;" class="btn btn-success">
</form>