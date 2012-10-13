<?
$user_ifno_holy=$H_USER->GetInfo();
if (!$user_ifno_holy['block_control']) die("недостаточно прав");

global $_global_bread;
$_global_bread[]=Array("Файловый менеджер","/engine/admin/file_manager.php");
?>
<BR>
<?require_once($_SERVER['DOCUMENT_ROOT']."/engine/engine.php");

global $H_USER;
$user_ifno_holy=$H_USER->GetInfo();

if ($H_USER->GetID()!=0)
if ($user_ifno_holy['block_control'])
{
?>
		<title>elFinder 2.0</title>

		<!-- jQuery and jQuery UI (REQUIRED) -->
		<link rel="stylesheet" type="text/css" media="screen" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/themes/smoothness/jquery-ui.css">
		
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js"></script>
		<!-- elFinder CSS (REQUIRED) -->
		<link rel="stylesheet" type="text/css" media="screen" href="ajax/elf/css/elfinder.min.css">
		<link rel="stylesheet" type="text/css" media="screen" href="ajax/elf/css/theme.css">

		<!-- elFinder initialization (REQUIRED) -->
		<script type="text/javascript" charset="utf-8">
			$().ready(function() {
				var elf = $('#elfinder').elfinder({
					url : 'ajax/elf/php/connector.php',  // connector URL (REQUIRED)
					 lang: 'ru',            // language (OPTIONAL)
				}).elfinder('instance');
			});
		</script>

		<!-- Element where elFinder will be created (REQUIRED) -->
		<div id="elfinder"></div>
<?};?>