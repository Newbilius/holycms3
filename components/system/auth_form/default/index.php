<!DOCTYPE html>
<html lang="ru">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Holy CMS 3</title>
<link rel="stylesheet" type="text/css" href="/engine/js/bootstrap/css/bootstrap.min.css"  />
<script src="/engine/js/bootstrap/js/bootstrap.min.js"></script>
	
</head>
<body>
<div class="container" style="padding-top:100px;">
<? if (!defined('HCMS')) die(); ?>

<div class="row">
<div class="span7 offset2 well">

<form method=post class="form-horizontal">
<fieldset>
<legend>Вход в систему администрирования</legend>
<div class="control-group">

            <label class="control-label" for="login">Логин</label>
            <div class="controls">
			  <input class="input-xlarge" type=text value="<?=$_POST['login']?>" name=login id=login>
            </div>
</BR>			 
            <label class="control-label" for="pass">Пароль</label>
            <div class="controls">
			  <input class="input-xlarge" type=password value="" name=pass id=pass>
            </div>
			</BR>			 
			<div class="row">
				<div class="span1 offset1">
				<div class="controls">
					<input name=submit type=submit value=Войти class="btn btn-primary">
				</div>
				</div>
			</div>
			<?
				if (isset($error))
				foreach ($error as $i=>$e)
					{
			?>
				<span style="color:red;">
					<?=$e?>
				</span><BR>
			<?
					};
			?>
</fieldset>
</div>
</form>
</div>
</div>
</div>
</body>
</html>