<?
global $_selected_page;

$sql=new DBlockElement($params['table']);
$sql->GetList("1");

//PrePrint($params);
if (!isset($params['url'])) $params['url']="?tags=";
if (!isset($params['min_size'])) $params['min_size']=8;
if (!isset($params['max_size'])) $params['max_size']=80;
$tags_max_count=0;
$different=$params['max_size']-$params['min_size'];
				while ($add_data=$sql->GetNext())
					{
					$add_tmp_massiv=explode(",",$add_data[$params['field']]);
					foreach ($add_tmp_massiv as $atm)
						if ($atm!="")
						if ($atm!=" ")
						if (isset($tags[trim($atm)])) {$tags[trim($atm)]++;$tags_max_count++;}
						else
						{$tags[trim($atm)]=1;$tags_max_count++;};
					};

	if (file_exists($full_template_path))
		include($full_template_path);
	else
		SystemAlert("Не найден шаблон <b>".$full_template_path."</b>");
?>