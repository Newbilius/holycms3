<? if (!defined('HCMS')) die(); ?>
<? View::Factory("templates/global_header")->Set("_OPTIONS", $_OPTIONS)->Draw();?>
<?= $CONTENT ?>
<?=$_OPTIONS['footer_code']?>