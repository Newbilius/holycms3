<BR>Добро пожаловать в систему управления сайтом. Ниже представлены все доступные блоки данных.
<HR>
<div class="row" style="padding-left:40px;">

    <?php
    if (!defined('HCMS'))
        die();
//наполнить списки
    $blockGroups = new DBlockGroup();
    $block = new DBlock();

//список групп
    $blockGroups->GetList();

    global $H_USER;
    $user_info = $H_USER->GetInfo();

    $counter = 0;
    while ($data = $blockGroups->GetNext())
        if (($data['name'] != "system") || ($user_info['block_control'])) {
            $counter++;
            if ($counter == 4) {
                $counter = 1;
                ?>
                <hr>
            </div>
            <div class="row" style="padding-left:40px;">
                <?
            };
            ?>
            <div class="span4">
                <div class="well sidebar-nav">
                    <ul class="nav nav-list">
                        <li class="nav-header"><h2><?= $data['caption'] ?></h2></li>
                        <?
                        $cnt = 0;
                        $name_of_group = $data['name'];

                        $block->GetListByGroup($name_of_group);
                        while ($data2 = $block->GetNext()) {
                            ?>
                            <li>
                                <h4>
                                    <a href="/engine/admin/elements_list.php?dblock=<?= $data2['name'] ?>">
                                        <?= $data2['caption'] ?>
                                    </a>
                                </h4>
                            </li>
                            <?
                        }
                        ?>
                    </ul>
                </div><!--/.well -->
            </div><!--/span-->
            <?
        };
    ?>

    <div style="padding-left:40px;" class="row">
        <div class="span4" style="padding-left:40px;">
            <div class="well sidebar-nav">
                <? global $_holy_vers; ?>
                Ваша версия: <b><?= $_holy_vers ?></b><br>
                Текущая версия:
                <div id="ajax_info_of_main_page" style="display:inline">
                    <img src="/engine/admin/img/ajax_loader_mini.gif">
                </div>
                <script>
                    $(function() {
                        $.get('/engine/admin/info.php', function(data) {
                            $("#ajax_info_of_main_page").html(data);
                        });
                    });
                </script>
            </div><!--/.well -->
        </div><!--/span-->


    </div>
    <?
    ?>
</div>