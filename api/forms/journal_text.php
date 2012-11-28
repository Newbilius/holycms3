<?
class CForm_journal_text extends CForm_Text{
	function View($name,$data,$add,$multiple=false)
		{
                $data[$name]=  str_replace(Array(
                    "update",
                    "add",
                    "delete",
                ), Array(
                    "Изменен",
                    "Создан",
                    "Удален"
                ), $data[$name]);
		?>
			<? if (strpos($data[$name],"<script")===FALSE){?>
			<?=$data[$name]?>
			<?}else{?><?};?>
		<?
		}
}
?>