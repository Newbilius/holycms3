<?php 
if (!defined('HCMS')) die();
//��������� ������
$BlockGroups=new DBlockGroup();
$Block=new DBlock();

//������ �����
$BlockGroups->GetList();
while($data=$BlockGroups->GetNext())
{
	$cnt=0;
	$name_of_group=$data['name'];
	$Block->GetListByGroup($name_of_group);
	while($data2=$Block->GetNext())
		{
			//preprint($data2);
			if (isset($data2['fav']))
			if ($data2['fav'])
				$fav[]=$data2;
			$cnt++;	
			//�������� �����
			$Elements=new DBlockElement(Array("table"=>$data2['name']));
			$Elements->GetList("folder=1");
			
			while($data3=$Elements->GetNext())
					$data2['FOLDERS'][$data3['id']]=$data3;
					
			$data['ELEMENTS'][]=$data2;
		};
	$groups[]=$data;

};
//PrePrint($groups);
include($full_template_path);
?>