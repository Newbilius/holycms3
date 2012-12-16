<? if (!defined('HCMS')) die(); ?>
<form method=post>
    <table align=center valign=center halign=center>
        <tr>
            <td>Логин</td>
            <td><input type=text value="<?= $_POST['login'] ?>" name=login></td>
        </tr>
        <tr>
            <td>Пароль</td>
            <td><input type=password value="" name=pass></td>
        </tr>
        <tr>
            <td colspan=2 align=center>
                <input name=submit type=submit value=Войти>
            </td>
        </tr>
        <tr>
            <td colspan=2>
                <?
                if (isset($result['errors']))
                    foreach ($result['errors'] as $e) {
                        ?>
                        <span style="color:red;">
                            <?= $e ?>
                        </span><BR>
                        <?
                    };
                ?>
            </td>
        </tr>
    </table>
</form>