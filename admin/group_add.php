<?
$user_ifno_holy=$H_USER->GetInfo();
if (!$user_ifno_holy['block_control']) die("недостаточно прав");

	$form= new HFormAdd(Array('table'=>"system_data_block_group"));


	$form->Add(Array("name"=>"caption","caption"=>"Название","type"=>"short_text",'required'=>1));
	$form->Add(Array("name"=>"name","caption"=>"Код","type"=>"short_text",'required'=>1));
	$form->Add(Array("name"=>"sort","caption"=>"Сортировка","type"=>"sort"));

	$form->return_link="group_list.php";
	
	if ($form->Draw())
		{
			$gr= new DBlockGroup();
			//print_r($_POST);
			$gr->Create(Array("caption"=>$_POST['caption'],"name"=>$_POST['name'],"sort"=>$_POST['sort']));
			
			?>
				<span style="color:green;">
					Элемент добавлен
				</span><script>
$(document).ready(function () {
window.location='<?=$form->return_link?>'
});
</script>
			<?
		};
?>&nbsp;