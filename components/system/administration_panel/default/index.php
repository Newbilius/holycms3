<? //if (!isset($_GET['_ajax_mode'])) 
    { ?>
    <!DOCTYPE html>
    <? global $_holy_vers; ?>
    <? global $force_filter;?>
    <html lang="ru">
        <head>
            <meta http-equiv="content-type" content="text/html; charset=utf-8" />
            <title>Holy CMS 3</title>

            <link rel="stylesheet" type="text/css" href="/engine/js/bootstrap/css/bootstrap.min.css"  />
            <script src="/engine/js/jquery.1.8.3.min.js"></script>
            <script src="/engine/js/bootstrap/js/bootstrap.min.js"></script>
            <script type="text/javascript" src="/engine/js/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
            <script type="text/javascript" src="/engine/js/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
            <link rel="stylesheet" type="text/css" href="/engine/js/fancybox/jquery.fancybox-1.3.4.css" media="screen" />
            <script type="text/javascript" src="/engine/js/jquery.tablednd_0_51.js"></script>

            
            <style>
                body.el-rte-structure { white-space: pre-wrap; }
            </style>
            <script src="/engine/js/bootstrap-dropdown.js"></script>

            <!-- elFinder CSS (REQUIRED) -->
            <link rel="stylesheet" type="text/css" media="screen" href="<? echo URI_ADMIN ?>ajax/elf/css/elfinder.min.css">
            <link rel="stylesheet" type="text/css" media="screen" href="<? echo URI_ADMIN ?>ajax/elf/css/theme.css">

            <!-- elFinder JS (REQUIRED) -->
            <script type="text/javascript" src="<? echo URI_ADMIN ?>ajax/elf/js/elfinder.min.js"></script>
            <link rel="stylesheet" type="text/css" media="screen" href="/engine/components/system/administration_panel/default/style.css">
            <script>
                var table="<? if (isset($_GET['dblock'])) echo $_GET['dblock']; ?>";
            </script>
            <script type="text/javascript" src="/engine/js/ckeditor/ckeditor.js"></script>
            <script type="text/javascript" src="/engine/js/ckeditor/adapters/jquery.js"></script>

            <script type="text/javascript" src="/engine/js/ui.datepicker.js"></script>
            <script type="text/javascript" src="/engine/js/jquery-ui.min.js"></script>
            <link rel="stylesheet" type="text/css" media="screen" href="/engine/js/js_ui_cupertino/jquery-ui-1.8.23.custom.css">

            <script type="text/javascript" src="/engine/components/system/administration_panel/default/default.js"></script>

            <script type="text/javascript" src="/engine/js/chosen/chosen.jquery.min.js"></script>
            <link rel="stylesheet" type="text/css" media="screen" href="/engine/js/chosen/chosen.css">

<link rel="stylesheet" href="http://blueimp.github.com/Bootstrap-Image-Gallery/css/bootstrap-image-gallery.min.css">
<link rel="stylesheet" href="/engine/js/jquery_file_upload/css/jquery.fileupload-ui.css">
<!-- CSS adjustments for browsers with JavaScript disabled -->
<noscript><link rel="stylesheet" href="/engine/js/jquery_file_upload/css/jquery.fileupload-ui-noscript.css"></noscript>
<!-- Shim to make HTML5 elements usable in older Internet Explorer versions -->
<!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
<script src="/engine/js/jquery_file_upload/js/vendor/jquery.ui.widget.js"></script>
<!-- The Templates plugin is included to render the upload/download listings -->
<script src="http://blueimp.github.com/JavaScript-Templates/tmpl.min.js"></script>
<!-- The Load Image plugin is included for the preview images and image resizing functionality -->
<script src="http://blueimp.github.com/JavaScript-Load-Image/load-image.min.js"></script>
<!-- The Canvas to Blob plugin is included for image resizing functionality -->
<script src="http://blueimp.github.com/JavaScript-Canvas-to-Blob/canvas-to-blob.min.js"></script>
<!-- Bootstrap JS and Bootstrap Image Gallery are not required, but included for the demo -->
<script src="http://blueimp.github.com/cdn/js/bootstrap.min.js"></script>
<script src="http://blueimp.github.com/Bootstrap-Image-Gallery/js/bootstrap-image-gallery.min.js"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="/engine/js/jquery_file_upload/js/jquery.iframe-transport.js"></script>
<!-- The basic File Upload plugin -->
<script src="/engine/js/jquery_file_upload/js/jquery.fileupload.js"></script>
<!-- The File Upload file processing plugin -->
<script src="/engine/js/jquery_file_upload/js/jquery.fileupload-fp.js"></script>
<!-- The File Upload user interface plugin -->
<script src="/engine/js/jquery_file_upload/js/jquery.fileupload-ui.js"></script>
<!-- The main application script -->
<script src="/engine/js/jquery_file_upload/js/main.js"></script>
<!-- The XDomainRequest Transport is included for cross-domain file deletion for IE8+ -->
<!--[if gte IE 8]><script src="/engine/js/jquery_file_upload/js/cors/jquery.xdr-transport.js"></script><![endif]-->
            
        <div class="find0"></div>

    </head>
    <script>
        function alertsize(pixels){
    pixels+=32;
    document.getElementById('child_iframe').style.height=pixels+"px";
}
function getDocHeight() {
    var D = document;
    return Math.max(
        Math.max(D.body.scrollHeight, D.documentElement.scrollHeight),
        Math.max(D.body.offsetHeight, D.documentElement.offsetHeight),
        Math.max(D.body.clientHeight, D.documentElement.clientHeight)
    );
}
</script>
    <body <? if ($force_filter){?>onload="parent.alertsize(getDocHeight());"<?};?> >
        <div id=find0>
        </div>
		<div id="wrap">
                    <? if (!$force_filter){?>
        <div class="navbar navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container-fluid">
                    <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>
                    <a class="brand" href="<? echo URI_ADMIN ?>">Holy CMS 3</a>



                    <div class="btn-group pull-right">
                        <a class="btn"  style="cursor:default;">
                            <i class="icon-user"></i> <?= $user_info['login'] ?>
                        </a>
                    </div>


                    <div class="nav-collapse pull-right">
                        <ul class="nav">
                            <li>&nbsp;&nbsp;&nbsp;&nbsp;</li>
                            <li><a target=_new href="/">На сайт</a></li>
                            <li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li>
							<li class="dropdown">
							<a href="#"
                                       class="dropdown-toggle"
                                       data-toggle="dropdown">
                                           Лицензия
                                        <b class="caret"></b>
                                    </a>
									<ul class="dropdown-menu">
									<li><a class="about_show" href="<?=URI_ENGINE."license.html"?>">Лицензия</a></li>
									<li><a class="about_show" href="<?=URI_ENGINE."third-party_solutions.html"?>">Сторонние решения</a></li>
									</ul>
									
									
							</li>
                            
                            <li><a href="<? echo URI_ADMIN ?>?exit=1">Выйти</a></li>
                        </ul>
                    </div>

                    <ul class="nav pull-right">
                        <?
                        global $_top_menu;
                        $user_ifno_holy = $H_USER->GetInfo();

                        foreach ($_top_menu as $top_num => $_top_menu_element)
                            if ($_top_menu_element['parent'] === "") {
                                ?>
                                <li class="dropdown">
                                    <a href="#"
                                       class="dropdown-toggle"
                                       data-toggle="dropdown">
                                           <? echo $_top_menu_element['caption'] ?>
                                        <b class="caret"></b>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <?
                                        foreach ($_top_menu as $item_menu)
                                            if ($item_menu['parent'] === $top_num) {
                                                if (!isset($item_menu['admin_right']))
                                                    $item_menu['admin_right']=false;
                                                
                                                if (($item_menu['admin_right']===false) || ($user_ifno_holy['block_control'])) {
                                                    if ($item_menu['caption'] == "-") {
                                                        ?><li class="divider"></li><? } else { ?>
                                                        <li><a href="<?= $item_menu['url'] ?>"><?= $item_menu['caption'] ?></a></li>
                                                        <?
                                                    };
                                                };
                                            }
                                        ?>
                                    </ul>
                                </li>
                                <?
                            }
                        ?>
                    </ul>		  

                </div>
            </div>
        </div>
		
        <BR><BR><BR>
        <?};?>
        <div class="container-fluid">
            <div class="row-fluid">
                <? if (!$force_filter){?>
                    <div class="span3">

                    <? IncludeComponent("system\administration_panel.left_menu", "default"); ?>
                </div>
            <?};?>



                <div class="<? if (!$force_filter) echo "span9"; else echo "span12";?>">

                    <div class="row-fluid">
                        <div class="span12">

                            <div id="ajax_div" name="ajax_div">
                            <? }; ?>
                                
                            <? if (!$force_filter) IncludeComponent("system\global_bread", "system"); ?>
                            <? if (isset($global_page_text)) echo $global_page_text ?>&nbsp;  
                            <? if (isset($_GET['_ajax_mode'])) { ?>
                                <script>
                                    /*$(document).ready(function() {
                                        AjaxLoadAlp();
                                    });*/
                                </script>
                            <? }; ?>				
                            <? if (!isset($_GET['_ajax_mode'])) { ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
</div>



<div id="push"></div>
        </div>
    <? if (!$force_filter) {?><div id="footer">
      <div class="container">
            <hr style="margin-top:0px;">
                <p> <strong>HolyCMS <?= $_holy_vers ?></strong>, &copy <a href=http://www.siteszone.ru/ target=_new>Моисеев Дмитрий aka Newbilius</a>. 2011-2012 год</p>
      </div>
        <?};?>
    </div>
    </body>
    </html>
<? }; ?>