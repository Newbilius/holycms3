<?
if (!defined('HCMS'))
    die();
if ($max_count > $count) {
    ?>
    <div style="clear:both;"></div>
    <div style="text-align: center">

        <?
        $max_page_num = ceil($max_count / $count);
        if ($max_page_num > 1) {
            for ($_page = 1; $_page <= $max_page_num; $_page++) {
                $style = "";
                if ($_page == $page) {
                    $style = "font-weight:bold;font-size:20px";
                }
                $href = ReplaceURL($url, Array(
                    "PAGE" => $_page,
                        ));
                ?>
                <a href="<?= $href ?>"><span style="<?= $style ?>">[<?= $_page ?>]</span></a>
                <?
            }
        }
        ?>
    </div><?
};
    ?>