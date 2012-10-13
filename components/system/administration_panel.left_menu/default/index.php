<?
global $_menu1;
global $_menu2;
global $_menu_best;
global $count_id_tree;
?>
<style>
    #f1
    {
        overflow-x:auto;
    }

    .admin_only_left_menu
    {
        list-style:none;

    }
</style>
<script src="/engine/js/jquery.cookie.js" type="text/javascript"></script>
<link rel="stylesheet" href="/engine/js/themes/default/style.css" />
<script src="/engine/js/jquery.jstree.js" type="text/javascript"></script>
<script>
    $(document).ready(function(){


        $("#f2").jstree({ core : {animation:100},"plugins" : [ "themes", "html_data", "ui", "cookies","types"  ],"save_opened":"jstree2","themes" : {"theme" : "classic"},
            
            "types" : {
                "types" : {
                    "group" : {
                        "icon" : {
                            "image" : "/engine/js/themes/group.png"
                        }
                    },
                    "main" : {
                        "icon" : {
                            "image" : "/engine/js/themes/main.png"
                        }
                    },
                    "base_type" : {
                        "icon" : {
                            "image" : "/engine/js/themes/main.png"
                        }
                    },
                    "block" : {
                        "icon" : {
                            "image" : "/engine/js/themes/block.png"
                        },
                        "start_drag" : false,
                        "move_node" : false,
                        "delete_node" : false,
                        "remove" : false
                    }
                }}});
        $("#f1").jstree({ core : {animation:100},"plugins" : [ "themes", "html_data", "ui", "cookies","types"  ],"save_opened":"jstree15","themes" : {"theme" : "classic"},
            "types" : {
                "types" : {
                    "group" : {
                        "icon" : {
                            "image" : "/engine/js/themes/group.png"
                        }
                    },
                    "block" : {
                        "icon" : {
                            "image" : "/engine/js/themes/block.png"
                        },
                        "start_drag" : false,
                        "move_node" : false,
                        "delete_node" : false,
                        "remove" : false
                    }
                }}});
	
        $(".jstree a").live("click", function(e) {

            if ($(this).attr('href')!="#")
                location.href=$(this).attr('href')

        }); 

    });
</script>
<?
global $H_USER;

$user_info = $H_USER->GetInfo();

function PrintFolder($id, $folder, $bl, $add = -1) {
    $counter = 0;
    foreach ($folder as $f)
        if ($f['parent'] == $id)
            $counter++;
    if ($counter > 0) {
        ?>
        <ul class="">
            <? /* p_class */ ?>
            <?
            //PrePrint($folder);
            foreach ($folder as $f)
                if ($f['parent'] == $id) {
                    if (!isset($_GET['parent']))
                        $_GET['parent'] = 0;
                    ?>
                    <li id="tree_block_<?= $bl ?>_element_<?= $f['id'] ?>">




                        <a class=ajax href=elements_list.php?dblock=<?= $bl ?>&parent=<?= $f['id']; ?>><?= $f['caption']; ?></a>


                        <? PrintFolder($f['id'], $folder, $bl, $add) ?>
                    </li>
                    <? };
            ?>
        </ul>
        <?
    };
}

function PrintArrayLeftMenuBlock($array0) {
    foreach ($array0 as $array) {
        ?>
        <div id=menu_block2 name=menu_block2>
            <li rel="group" id="tree_group_<?= $array['id'] ?>"><a class=ajax href=blocks_list.php?group=<?= $array['id'] ?>><?= $array['caption'] ?></a><ul>

        <?
        if (isset($array['ELEMENTS']))
            foreach ($array['ELEMENTS'] as $data) {
                ?>
                            <li rel="block" id="tree_group_<?= $array['id'] ?>_block_<?= $data['name'] ?>" >
                                <a class=ajax href=block_edit.php?group=<?= $array['id'] ?>&dblock=<?= $data['name'] ?>><?= $data['caption'] ?></a>
                            </li>
                <?
            }
        ?>
                </ul></li>
        </div>
                    <?
                };
            }

;

            function PrintArrayLeftMenuElements($array0, $admin = 0) {
                foreach ($array0 as $array) {
                    if (!isset($_GET['dblock']))
                        $_GET['dblock'] = -1;
                    ?>
        <? if (($array['name'] != "system") || ($admin)) { ?>
            <li rel="group" id="tree_group_<?= $array['id'] ?>">
                <a href=#><?= $array['caption'] ?></a>
                <ul>

            <?
            if (isset($array['ELEMENTS']))
                foreach ($array['ELEMENTS'] as $data) {
                    ?>
                            <li rel="block" id="tree_block_<?= $data['name'] ?>_element_0">

                                <a class=ajax href=elements_list.php?dblock=<?= $data['name'] ?>><?= $data['caption'] ?></a>

                    <?
                    if (isset($data['FOLDERS']))
                        PrintFolder(0, $data['FOLDERS'], $data['name'], $array['id']);
                    ?>
                            </li>
                                <?
                            }
                        //внутри нужно будет вызывать показ папок и элементов... позже
                        ?>
                </ul></li>
                <? }; ?>


        <?
    };
}

;
?>
<? if ($user_info['block_control']) { ?>
    <ul class="tabs tabs1 nav nav-pills" style="padding-left:0px;">
        <li class="t1 active">
            <a href="#">Данные</a>
        </li>
        <li class="t2"><a href="#">Настройки</a></li>
    </ul>
    <script>
        //$(".t1_div").hide();
        //$(".t2_div").hide();

        $(document).ready(function() {
            $('ul.tabs.tabs1 li').click(MenuLeftHideShow);
            var cnow;
            if($.cookie("MenuLeftHideShow"))
                cnow=$.cookie("MenuLeftHideShow");
            else
                cnow="t1";
            $('ul.tabs.tabs1 li.'+cnow).click();
            $('ul.tabs.tabs1').show();
            //alert(cnow);
            function MenuLeftHideShow(){
                var thisClass = this.className.slice(0,2);
    	
                if (thisClass=="t1")
                {
                    $('div.t2_div').hide();
                    $('div.t1_div').show();
                };
                if (thisClass=="t2")
                {
                    $('div.t1_div').hide();
                    $('div.t2_div').show();
                };
                $.cookie("MenuLeftHideShow", thisClass);
    	
                $('ul.tabs.tabs1 li').removeClass('active');
                $(this).addClass('active');
            }


        });
    </script>
<? }; ?>
<div class="t1_div" <? if ($user_info['block_control']) { ?>style="display:none;"<? }; ?>>
<?
if ((isset($fav)) || (count($_menu_best['before']) > 0) || (count($_menu_best['after']) > 0)) {
    ?>
        <div class="well sidebar-nav">
            <h3>Избранные блоки данных</h3>
            <ul class="nav nav-list">

    <? if (isset($_menu_best['before'])) foreach ($_menu_best['before'] as $m_item) {
            $count_id_tree++; ?><li id="tree_<?= $count_id_tree ?>" class=sys3><a class=ajax href="<?= $m_item[0] ?>"><?= $m_item[1] ?></a></li><? }; ?>
                <? //preprint($fav);?>
                <?
                if (isset($fav))
                    foreach ($fav as $ff) {
                        ?><li><a onclick="$('#f1').jstree('close_all');$('#f1').jstree('deselect_all');$('#f1').jstree('select_node', '#tree_block_<?= $ff['name'] ?>_element_0');" href=/engine/admin/elements_list.php?dblock=<?= $ff['name'] ?>><?= $ff['caption'] ?></a></li><?
        };
                ?>
                <? if (isset($_menu_best['after'])) foreach ($_menu_best['after'] as $m_item) {
                        $count_id_tree++; ?><li id="tree_<?= $count_id_tree ?>"><a class=ajax href="<?= $m_item[0] ?>"><?= $m_item[1] ?></a></li><? }; ?>
            </ul>
        </div>
    <? }; ?>
    <h3>Управление данными</h3>
    <div id=f1 name=f1>
        <ul class="p_class filetree filetree_data">
            <? if (isset($_menu1['before'])) foreach ($_menu1['before'] as $m_item) {
                    $count_id_tree++; ?>
                    <li id="tree_<?= $count_id_tree ?>" class=sys3><a class=ajax href="<?= $m_item[0] ?>"><?= $m_item[1] ?></a></li><? }; ?>
            <?
            PrintArrayLeftMenuElements($groups, $user_info['block_control']);
            ?>

            <?
            if (isset($_menu1['after']))
                foreach ($_menu1['after'] as $m_item) {
                    $count_id_tree++;
                    ?><li class=sys3 id="tree_<?= $count_id_tree ?>"><a class=ajax href="<?= $m_item[0] ?>"><?= $m_item[1] ?></a></li><? }; ?>
        </ul>
    </div>
</div>
<div class="t2_div"  style="display:none;">
    <? if ($user_info['block_control']) { ?>
        <h3>Управление настройками</h3>
                <? $count_id_tree++; ?>
        <div id=f2 name=f2>
            <ul class="p_class filetree">
                        <? if (isset($_menu2['before'])) foreach ($_menu2['before'] as $m_item) {
                                $count_id_tree++; ?><li id="tree_<?= $count_id_tree ?>" class=sys3><a class=ajax href="<?= $m_item[0] ?>"><?= $m_item[1] ?></a></li><? }; ?>
                        <? $count_id_tree++; ?>
                <li rel="main" id="tree_<?= $count_id_tree ?>"><a class=ajax href=group_list.php>Блоки</a><ul>
    <?
    PrintArrayLeftMenuBlock($groups);
    ?>
                    </ul>
                </li>
        <? if (isset($_menu2['after'])) foreach ($_menu2['after'] as $m_item) {
                $count_id_tree++; ?><li id="tree_<?= $count_id_tree ?>" class=sys3><a class=ajax href="<?= $m_item[0] ?>"><?= $m_item[1] ?></a></li><? }; ?>
            </ul>
        </div>
<? } ?>
</div>