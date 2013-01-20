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
        <? if (isset($result['redirect_url'])) {
            ?>
            <script>
                window.location='<?= $result['redirect_url'] ?>';
            </script>
            <? }
        ?>
        <?
    };
    ?><?
} else {
    ?>
    <div id="social_out_text" style="display:none;"></div>
    <script src="//ulogin.ru/js/ulogin.js"></script>
    <div id="content_inner">
        <div id="uLogin" data-ulogin="display=panel;fields=email;optional=first_name,nickname,last_name;providers=vkontakte,twitter,google,yandex,steam,facebook;hidden=other;redirect_uri=<?= urlencode("http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']) ?>"></div>
    </div>

    <script>
        function ajaxautho(token)
        {
            $.ajax({
                url: 'http://ulogin.ru/token.php',
                type: 'GET',
                dataType:'jsonp',
                data: {'token': token, 'host': encodeURIComponent(location.toString())},
                success: function (jqXHR){
                    var data_get = jQuery.parseJSON(jqXHR);
                    data_get["token"]=token;
                    $.post("<?= $result['reg_url'] ?>", data_get,
                    function(data) {
                        $("#social_out_text").show();
                        $("#social_out_text").html(data);
                    });
                },
                complete: function(jqXHR,status){
                    //console.log(status);
                }
            });
        };
    </script>
<? }; ?>