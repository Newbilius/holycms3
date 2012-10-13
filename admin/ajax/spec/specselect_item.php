<?
require_once($_SERVER['DOCUMENT_ROOT']."/engine/engine.php");
//вывести список иблоков

$src_block=new DBlockElement(Array("table"=>$_GET['block']));
$src_block->GetList();
while ($data=$src_block->GetNext())
$array_of_items[$data['parent']][]=$data;

//pre_print($array_of_items);
function PrintTree($parent,&$array_of)
	{
		?>
			<ul>
		<?
		foreach ($array_of[$parent] as $element)
			{
				?>
				<li><?if (!$element['folder']){?>
				<a href="javascript:specselect_item_complete('<?=$_GET['spec_id']?>','<?=$element['id']?>')">
				<?};?>
				<?=$element['caption']?>
				
				<?if ($element['folder']){?></a><?};?>
				<?
				if ($element['folder'])
				PrintTree($element['id'],$array_of);
				?>
				</li>
				<?
			};
		?>
			</ul>
		<?
	};
	
PrintTree(0,$array_of_items);
?>