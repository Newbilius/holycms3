<? if (!defined('HCMS')) die(); ?>
<?
if ($result['reg_ok']) {
    if (isset($result['errors'])) {
        ?>
        <div class="alert alert-error">
            <?
            foreach ($result['errors'] as $error) {
                echo $error . "<BR>";
            }
            ?>
        </div>
    <? } else {
        ?>
        <div class="alert alert-success">
            <?= $result['message'] ?>
        </div>
        <?
    };
    ?><?
} else {
    ?>
    <div id="social_out_text" style="display:none;"></div>
    <script src="//ulogin.ru/js/ulogin.js"></script>
    <div id="content_inner">
        <div id="uLogin" data-ulogin="callback=ajaxautho;display=panel;fields=email;optional=first_name,nickname,last_name;providers=vkontakte,twitter,google,yandex,steam,facebook;hidden=other;redirect_uri"></div>
    </div>

    <?
    if (isset($result['list_of_socials'])) {
        echo "Уже привязанные аккаунты:<ul>";
        if (is_array($result['list_of_socials']['socials']))
        foreach ($result['list_of_socials']['socials'] as $social_name) 
            if ($social_name){
            $social_name_new = explode("//", $social_name);
            $social_name_new = $social_name_new[1];
            $social_name_new = explode("/", $social_name_new);
            $social_name_new = $social_name_new[0];
            ?>
            <li><a href="?delete_social=<?= $social_name ?>"><?= $social_name_new ?></a></li>
            <?
        }
        echo "</ul>";
    }
    ?>

    <script>
        function ajaxautho(token)
        {
            $.post("<?= $result['reg_url'] ?>", {"token":token},
            function(data) {
                $("#social_out_text").show();
                $("#social_out_text").html(data);
            });
        };
    </script>
<? }; ?>

<? if (isset($result['redirect_url'])) {
    ?>
    <script>
        window.location='<?= $result['redirect_url'] ?>';
    </script>
<? }
?>