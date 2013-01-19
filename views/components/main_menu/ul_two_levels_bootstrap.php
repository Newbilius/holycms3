<? if (!defined('HCMS')) die(); ?>
<? if (isset($result)) { ?>
    <div class="well sidebar-nav" style="margin-right:20px;">
        <ul class="nav nav-list">
            <?
            foreach ($result[0] as $id => $item) {
                $class = "";
                if ($id == count($result) - 1)
                    $class = "last";
                if ($id == 0)
                    $class = "first";

                if ($item['redirect'])
                    $url = $item['redirect'];
                else
                    $url = "/" . $item['name'];
                ?>
                <li <? if ($item['SELECTED']) { ?>class="active"<? }; ?>>
                    <a href=<?= $url ?>><?= $item['caption'] ?></a>
                    <?
                    if (isset($result[$item['id']]))
                        if (count($result[$item['id']]) > 0) {
                            ?>
                            <ul class="nav" style="padding-left:20px;margin-bottom:4px;">
                                <?
                                foreach ($result[$item['id']] as $item2) {
                                    if ($item2['redirect'])
                                        $url = $item2['redirect'];
                                    else
                                        $url = "/" . $item['name'] . "/" . $item2['name'];
                                    ?>
                                    <li <? if ($item2['SELECTED']) { ?>class="active"<? }; ?>>
                                        <a href=<? echo $url ?>><?= $item2['caption'] ?></a>
                                    </li>
                                    <?
                                };
                                ?>
                            </ul>
                            <?
                        };
                    ?>
                </li>
                <?
            };
            ?>
        </ul>
    </div>
<? }; ?>