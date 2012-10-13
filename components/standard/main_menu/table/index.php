<? if (!defined('HCMS')) die(); ?>

<? if (isset($result)) { ?>
    <table>
        <tr>
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
                <td class="<?= $class ?>">
                    <? if ($item['SELECTED']) { ?><b><? }; ?>
                        <a href=/<?= $url ?>><?= $item['caption'] ?></a>
                        <? if ($item['SELECTED']) { ?></b><? }; ?>
                </td>
                <?
            };
            ?>
        </tr>
    </table>	
<? }; ?>