<?
$user_ifno_holy=$H_USER->GetInfo();
if (!$user_ifno_holy['block_control']) die("������������ ����");

	$form= new HFormAdd(Array('table'=>"system_data_block_group"));


	$form->Add(Array("name"=>"caption","caption"=>"��������","type"=>"short_text",'required'=>1));
	$form->Add(Array("name"=>"name","caption"=>"���","type"=>"short_text",'required'=>1));
	$form->Add(Array("name"=>"sort","caption"=>"����������","type"=>"sort"));

	$form->return_link="group_list.php";
	
	if ($form->Draw())
		{
			$gr= new DBlockGroup();
			//print_r($_POST);
			$gr->Create(Array("caption"=>$_POST['caption'],"name"=>$_POST['name'],"sort"=>$_POST['sort']));
			
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