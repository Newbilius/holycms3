<?
$user_ifno_holy=$H_USER->GetInfo();
if (!$user_ifno_holy['block_control']) die("������������ ����");

	$form= new HFormAdd(Array('table'=>"system_data_block"));


	$form->Add(Array("name"=>"caption","caption"=>"��������","type"=>"short_text",'required'=>1));
	$form->Add(Array("name"=>"name","caption"=>"���","type"=>"short_text",'required'=>1));
	$form->Add(Array("name"=>"sort","caption"=>"����������","type"=>"sort"));
$form->Add(Array("name"=>"fav","caption"=>"���������","type"=>"checkbox",'required'=>false));
$form->Add(Array("name"=>"hide_folders","caption"=>"������ �����","type"=>"checkbox",'required'=>false));
$form->Add(Array("name"=>"hide_folders2","caption"=>"������ ����� 2�� ������","type"=>"checkbox",'required'=>false));
$form->Add(Array("name"=>"hide_code","caption"=>"������ ���","type"=>"checkbox",'required'=>false));
	
	$form->return_link="blocks_list.php?group=".$_GET['group'];
		
	if ($form->Draw())
		{
					if (!isset($_POST["fav"])) $_POST["fav"]=0; else $_POST["fav"]=1;
			if (!isset($_POST["hide_folders"])) $_POST["hide_folders"]=0; else $_POST["hide_folders"]=1;
		if (!isset($_POST["hide_folders2"])) $_POST["hide_folders2"]=0; else $_POST["hide_folders2"]=1;
			if (!isset($_POST["hide_code"])) $_POST["hide_code"]=0; else $_POST["hide_code"]=1;
			
			$gr= new DBlock();
			$gr->Create(Array("caption"=>$_POST['caption'],"name"=>$_POST['name'],
			"sort"=>$_POST['sort'],"group"=>$_GET['group'],
				"fav"=>$_POST["fav"],
				"hide_folders"=>$_POST["hide_folders"],
				"hide_folders2"=>$_POST["hide_folders2"],
				"hide_code"=>$_POST["hide_code"],
			));
			
			?>
				<span style="color:green;">
					������� ��������
				</span><script>
$(document).ready(function () {
window.location='<?=$form->return_link?>'
});
</script>
			<?
		};
?>&nbsp;