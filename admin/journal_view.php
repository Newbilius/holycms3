<?
$user_ifno_holy=$H_USER->GetInfo();
$_global_bread[]=Array("������","/engine/admin/journal.php");

//$block= new DBlock();
//$tmp_block=$block->GetByID("journal");
$element= new DBlockElement("journal");
$item=$element->GetByID($_GET['id']);

                $item['action']=  str_replace(Array(
                    "update",
                    "add",
                    "delete",
                ), Array(
                    "�������",
                    "������",
                    "������"
                ), $item['action']);
                
$tmp_element_src= new DBlockElement("users");            
$tmp_element=$tmp_element_src->GetByID($item['user_id']);
$item['user_id']=$tmp_element['caption']." [".$item['user_id']."]";

$tmp_element_src= new DBlockElement("system_data_block");
$tmp_element=$tmp_element_src->GetOne("name='".$item['block_name']."'");
$item['block_name']=$tmp_element['caption']." [".$item['block_name']."]";
 
$item['data_after']=  unserialize($item['data_after']);
$item['data_before']=  unserialize($item['data_before']);
//PrePrint($item);
?>


<table>
    <tr>
        <td>
            <b> ID ��������:</b>
        </td>
        <td>
            <? echo $item['id'];?>
        </td>
    </tr>
    <tr>
        <td>
            <b> ��������:</b>
        </td>
        <td>
            <? echo $item['data_caption'];?>
        </td>
    </tr>
    <tr>
        <td>
            <b> �����:</b>
        </td>
        <td>
            <? if ($item['folder']){?>��<?}else{?>���<?};?>
        </td>
    </tr>
    <tr>
        <td>
            <b> ������������:</b>
        </td>
        <td>
            <? echo $item['user_id'];?>
        </td>
    </tr>
    <tr>
        <td>
            <b> Data-����:</b>
        </td>
        <td>
            <? echo $item['block_name'];?>
        </td>
    </tr>
    <tr>
        <td>
            <b> ��������:</b>
        </td>
        <td>
            <? echo $item['action'];?>
        </td>
    </tr>
    <tr>
        <td>
            <b> �����:</b>
        </td>
        <td>
            <? echo $item['date_time'];?>
        </td>
    </tr>
</table>

<table width="100%">
    <tr>
        <? if ($item['data_before']){?><th>
            ��
        </th><?};?>
       <? if ($item['data_after']){?> <th>
            �����
        </th><?};?>
        
    </tr>
    <tr>
<? if ($item['data_before']){?>
        <td valign="top">
            <?
                preprint($item['data_before']);
            ?>
        </td>
        <?};?>
      <? if ($item['data_after']){?>  <td valign="top">
            <?
                preprint($item['data_after']);
            ?>
        </td><?};?>
    </tr>
    
</table>&nbsp;