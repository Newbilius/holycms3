<? if (!isset($_GET['_ajax_mode'])) { ?>
    <!DOCTYPE html>
    <? global $_holy_vers; ?>
    <html lang="ru">
        <head>
            <meta http-equiv="content-type" content="text/html; charset=utf-8" />
            <title>Holy CMS 3</title>

            <link rel="stylesheet" type="text/css" href="/engine/js/bootstrap/css/bootstrap.min.css"  />
            <script src="/engine/js/jquery-1.7.min.js"></script>
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

        <div class="find0"></div>

    </head>

    <body>
        <div id=find0>
        </div>
		<div id="wrap">
        <div class="navbar navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container-fluid">
                    <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>
                    <a class="brand" href="<? echo URI_ADMIN ?>">Holy CMS 3</a>



                    <div style="display:none"><div id="about_div" s>
                            <?include_once(FOLDER_ENGINE."license.html")?>

                        </div></div>


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
                            <li><a class="about_show" href="#about_div">Лицензия</a></li>
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
        <div class="container-fluid">
            <div class="row-fluid">
                <div class="span3">

                    <? IncludeComponent("system\administration_panel.left_menu", "default"); ?>
                </div>



                <div class="span9">

                    <div class="row-fluid">
                        <div class="span12">

                            <div id="ajax_div" name="ajax_div">
                            <? }; ?>
                            <? IncludeComponent("system\global_bread", "system"); ?>
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
    <div id="footer">
      <div class="container">
            <hr style="margin-top:0px;">
                <p> <strong>HolyCMS <?= $_holy_vers ?></strong>, &copy <a href=http://www.siteszone.ru/ target=_new>Моисеев Дмитрий aka Newbilius</a>. 2011-2012 год</p>
      </div>
    </div>
    </body>
    </html>
<? }; ?>