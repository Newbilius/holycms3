<?
require_once(realpath(str_replace("\\","/",dirname(dirname(dirname(dirname(__FILE__))))."/engine.php")));
//вывести список иблоков

$src_block=new DBlockElement(Array("table"=>$_GET['block']));
$src_block->GetList("folder=0");
while ($data=$src_block->GetNext())
$array_of_items[$data['parent']][]=$data;

function PrintTree($parent,&$array_of)
	{
		?>
			<ul>
		<?
		foreach ($array_of[$parent] as $element)
			{
				?>
				<li>
				<a href="javascript:specselect_item_complete('<?=$_GET['spec_id']?>','<?=$element['id']?>')">
				
				<?=$element['caption']?>
				
				</a>
				<?
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