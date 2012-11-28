<?php 
if (!defined('HCMS')) die();
global $H_USER;

if (!isset($_POST['login'])) $_POST['login']="";
if (!isset($_POST['pass'])) $_POST['pass']="";

if (isset($_POST['submit']))
	{
		if (isset($_POST['login'])) if ($_POST['login']=="") $error[]="Не введён логин";
		if (isset($_POST['pass'])) if ($_POST['pass']=="") $error[]="Не введён пароль";
	};

if (($_POST['login']!="") && ($_POST['pass']!=""))
	if ($H_USER->Auth($_POST['login'],$_POST['pass']))
		return true;
	else $error[]="Неверный пароль или логин";
	
	include($full_template_path);
	return false;
?>