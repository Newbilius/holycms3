<?
global $_global_bread;
global $_photo_id;
global $_folder_selector;
if (isset($_photo_id))
if ($_photo_id)
{
$_global_bread[]=Array("�������� �������� ����");

?>
<? if ($_folder_selector){?><BR>� ����� ����� �������:
					<select class=par id=parent name=parent class="where_move">
					<option value=0>[ ������ ]</option>
						<?
						function DrawFolderTree($parent,$array_of,$name_add="")
						{
						//�������� ������ �����
						foreach ($array_of as $data)
						if ($data['parent']==$parent)
							{
							?>
								<option value=<?=$data['id']?>><?=$name_add.$data['caption']?>&nbsp;</option>
							<?
							DrawFolderTree($data['id'],$array_of,$name_add."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
							};
						};
		$el=new DBlockElement($_photo_id);
		$el->GetList("folder=1");
		while ($data=$el->GetNext())
			$folders[]=$data;
			
						DrawFolderTree(0,$folders);
						?>
						</select>
						<?}else{?>
						<select class=par id=parent name=parent class="where_move" style="display:none;">
					<option value=0>[ ������ ]</option>
				</select>
						<?};?>
<BR><BR>
<input type=buton value="������ �������� �����������" onclick="$('#file_upload').uploadify('upload','*')" class="btn btn-success">						
<BR>
<BR>
<script type="text/javascript" src="/engine/js/uploadify/jquery.uploadify-3.1.min.js"></script>
<link rel="stylesheet" type="text/css" href="/engine/js/uploadify/uploadify.css" />
<input type="file" name="file_upload" id="file_upload" />
<script>
$(function() {
����$('#file_upload').uploadify({
��������debug:false,
		'swf'������: '/engine/js/uploadify/uploadify.swf',
��������'uploader' : '/engine/admin/ajax/uploadify.php',
��������// Put your options here
		'auto'     : false,
		//'debug'     : true,
		'buttonText' : '������� �����',
		'formData'      : {'parent' : 0,'CODE':"asdasdas.sklfhjsjf.sdjl_akfsdhjf"},
		'onUploadStart' : function(file) {
            $("#file_upload").uploadify("settings", "formData",{'parent' : $("select").val()});
        }
����});
});
</script>
<?}else die("������ �����������");?>