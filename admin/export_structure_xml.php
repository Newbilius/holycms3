<?php
require_once("../engine.php");

global $_global_bread;
$_global_bread[] = Array("Экспорт структуры в XML");

//получить список групп датаблоков
$groups = new DBlockGroup();
$groups->GetList();
$groups_list = $groups->GetFullList();

$fields=new DBlockFields();

$blocks = new DBlock;

if (isset($_POST['go'])) {
    if (count($_POST['block']) == 0) {
        ?>
<div class="alert alert-error fade in" style="max-width:400px;">
<button type="button" class="close" data-dismiss="alert">&times;</button>
Не выбран ни один блок данных, нечего экспортировать.
</div>
<?
    }
    else
    {
        $filter="";
        foreach ($_POST['block'] as $block_id)
        {
            if ($filter!="")
                $filter.=",";
               $filter.=$block_id;
        };
        $blocks->GetList("id in (".$filter.")");
        $block_datas=$blocks->GetFullList();
        
        $dom = new DOMDocument('1.0', 'utf-8');
        $element0 = $dom->createElement('blocks');
        $dom->appendChild($element0);
        
        foreach ($block_datas as $data)
        {       
                $element1 = $dom->createElement($data['name']);
                foreach ($data as $_block_data_id=>$_block_data)
			{
                                $_block_data=iconv('windows-1251', 'utf-8',$_block_data);
				$element2 = $dom->createElement($_block_data_id,$_block_data);
				$element1->appendChild($element2);
			};                
        $element3 = $dom->createElement("fields");
        $fields->GetListByBlock($data['id']);
        
        $fields_list=$fields->GetFullList();
        
        foreach ($fields_list as $_field)
        {
             $element4 = $dom->createElement($_field['name']);
                foreach ($_field as $_field_data_id=>$_field_data)
			{
                                $_field_data=iconv('windows-1251', 'utf-8',$_field_data);
				$element5 = $dom->createElement($_field_data_id,$_field_data);
				$element4->appendChild($element5);
			};   
             $element3->appendChild($element4);
        }
        
        $element1->appendChild($element3);
	$element0->appendChild($element1);
        
        };
        
    if (!file_exists($_SERVER['DOCUMENT_ROOT'] . "/upload/"))
        mkdir($_SERVER['DOCUMENT_ROOT'] . "/upload/");
    if (!file_exists($_SERVER['DOCUMENT_ROOT'] . "/upload/export/"))
        mkdir($_SERVER['DOCUMENT_ROOT'] . "/upload/export/");
    
    $fname='/upload/export/blocks_'.date("d.m.Y_H_i_s") . '.xml';
    $fname_full = $_SERVER['DOCUMENT_ROOT'] . $fname;
    
    $dom->save($fname_full);
    
?>
<div class="alert alert-success fade in" style="max-width:400px;">
<button type="button" class="close" data-dismiss="alert">&times;</button>
Файл экспорта сохранен. <a href="<?=$fname?>">Скачть файл.</a>
</div>
<?
    }
};
?>
<form method="post">
    <input type="hidden" name="go" value="1">
    <ul>
<?
foreach ($groups_list as $_group) {
    ?>
            <li>
            <? echo $_group['caption'] ?>
                <ul>
                <?
                $blocks->GetListByGroup($_group['id']);
                $block_list = $blocks->GetFullList();
                foreach ($block_list as $_block) {
                    ?>
                        <label class="checkbox">
                            <input type="checkbox" name=block[] value="<? echo $_block['id'] ?>"><? echo $_block['caption'] ?><br>
                        </label>

        <?
    };
    ?>
                </ul>
            </li>
    <?
}
//получить список датаблоков в группе
?>
    </ul>
    <input name=submit type=submit value="Запустить экспорт" style="width:40%;HEIGHT:28px;" class="btn btn-success">
</form>