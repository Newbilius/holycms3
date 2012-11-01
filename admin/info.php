<?php
require_once("../engine.php");

$_holy_new_vers = FileDownloadCURL("https://raw.github.com/Newbilius/holycms3/master/VERSION");
$new_change_log=FileDownloadCURL("http://raw.github.com/Newbilius/holycms3/master/changelog.txt");
?>
<b><?echo $_holy_new_vers;?></b>
<hr style="margin-top:0px;margin-bottom:5px;">
<div id="new_in_new_version" style="display:none;height: 200px;overflow-y: scroll;width: 230px;">
<? echo nl2br($new_change_log);?>
</div>
<a onclick="$('#new_in_new_version').show();$(this).hide();return false;" href="#">Что нового в новой версии</a>