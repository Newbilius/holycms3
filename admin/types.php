<?

$user_ifno_holy = $H_USER->GetInfo();
if (!$user_ifno_holy['block_control'])
    die("недостаточно прав");

global $_global_bread;
$_global_bread[] = Array("Доступные типы", "/engine/admin/types.php");
?>
<?

$block = new DBlock();
$table = new HTypeTable(Array("table" => "system_data_block_types","short_links"=>false));

$mega_array[] = Array("name", "Код", "type_text", "");
$mega_array[] = Array("caption", "Название", "type_text", "");
$mega_array[] = Array("basetype", "Базовый тип", "type_text", "");
$mega_array[] = Array("sort", "Сортировка", "type_text", "");

foreach ($mega_array as $i => $mg)
    $table->Add(Array("name" => $mg[0], "caption" => $mg[1], "type" => $mg[2], "add_values" => $mg[3]));
if ($table->GO()) {
    $tp = new DBlockTypes();
    foreach ($_POST['id'] as $num => $id) {
        if (!isset($_POST['name'][$num]))
            $_POST['name'][$num] = "";
        if ($_POST['name'][$num] != "")
            if ($_POST['caption'][$num] != "") {

                $values = Array(
                    "name" => $_POST['name'][$num],
                    "caption" => $_POST['caption'][$num],
                    "sort" => intval($_POST['sort'][$num]),
                    "basetype" => $_POST['basetype'][$num],
                );
                if (isset($_POST['delete_id'][$num])) {
                    $tp->Delete($_POST['delete_id'][$num]);
                } else {
                    if ($id > 0)
                        $tp->Update($id, $values);
                    else
                        $tp->Create($values);
                };
            };
    };
    $table->Load();
};
$table->Draw();
?>