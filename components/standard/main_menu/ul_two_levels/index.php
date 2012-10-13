<? if (!defined('HCMS')) die(); ?>

<? if (isset($result)) { ?>
    <ul>
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
            <li>
                <a <? if ($item['SELECTED']) { ?>class="active"<? }; ?> href=<?= $url ?>><?= $item['caption'] ?></a>
                <?
                if (isset($result[$item['id']]))
                    if (count($result[$item['id']]) > 0) {
                        ?>
                        <ul>
                            <?
                            foreach ($result[$item['id']] as $item2) {
                                if ($item2['redirect'])
                                    $url = $item2['redirect'];
                                else
                                    $url = "/" . $item['name'] . "/" . $item2['name'];
                                ?>
                                <li>
                                    <a <? if ($item2['SELECTED']) { ?>class="active"<? }; ?> href=<? echo $url ?>><?= $item2['caption'] ?></a>
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
<? }; ?>