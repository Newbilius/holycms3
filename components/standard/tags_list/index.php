<?
global $_selected_page;


if (!isset($params['min_size'])){
    $params['min_size']=12;
};
if (!isset($params['coefficient'])){
    $params['coefficient']=4;
};

$tags = new HolyTags($params['table'], $params['field'], $params['url']);
$tags_list = $tags->GetFullList();
$tag_count=$tags->GetCount();
$different=10;
$different_proc=$different/$tag_count;
        
	if (file_exists($full_template_path))
		include($full_template_path);
	else
		SystemAlert("Не найден шаблон <b>".$full_template_path."</b>");
?>