<? if (!defined('HCMS')) die(); ?>

<? if (isset($result)) { ?>
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

            <a <? if ($item['SELECTED']) { ?>class="active"<? }; ?> href=<?= $url ?>><?= $item['caption'] ?></a>
        </td>
        <?
    };
    ?>
<? }; ?>