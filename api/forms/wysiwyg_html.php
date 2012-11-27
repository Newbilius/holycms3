<?

class CForm_wysiwyg_html extends CForm_Text {

    function HTML($name) {
        global $_text_counter;
        ?>
        <script type="text/javascript" charset="utf-8">
            $(function() {
                //$( '.wisiwig' ).ckeditor();
                
$('.wisiwig')
    .ckeditor( function() { 
        var has_event = typeof event != 'undefined';
        if (has_event){
        
        var editor = event.editor;
    var dialogDefinition = event.data.definition;
    var dialogName = event.data.name;
    
    var tabCount = dialogDefinition.contents.length;
    for (var i = 0; i < tabCount; i++) { //цикл для замены клика всех кнопок "Посмотреть на сервере"
        var browseButton = dialogDefinition.contents[i].get('browse');
        if (browseButton !== null) {
            browseButton.hidden = false;
            browseButton.onClick = function (dialog, i) {
                $('<div \>').dialog({ // вызов диалога при клике на кнопку
                    modal: true,
                    width: "80%",
                    title: 'elFinder',
                    zIndex: 99999,
                    create: function (event, ui) { //инициализация elFinder внутри модального окна
                        $(this).elfinder({
                            resizable: false,
                            lang: 'ru',
                            url: '/engine/admin/ajax/elf/php/connector_img.php?mode=' + dialogName,
                            getFileCallback: function (url) { // возврат ссылки, изображения, флэш
                                var dialog = CKEDITOR.dialog.getCurrent();
                                if (dialog._.name == "image") {
                                    var urlObj = 'txtUrl'
                                } else if (dialog._.name == "flash") {
                                    var urlObj = 'src'
                                } else if (dialog._.name == "link") {
                                    var urlObj = 'url'
                                } else {
                                    return false
                                };
                                dialog.setValueOf(dialog._.currentTabId, urlObj, url);
                                $('a.ui-dialog-titlebar-close[role="button"]').click(); //закрыть elFinder после выбора
								return false;
                            }
                        }).elfinder('instance')
                    }

                })
            }
        }
    }
    };
}, { } )
    .ckeditor( );
    
            });
        </script>
        <?
    }

    function Edit($name, $data, $add, $multiple = false) {
        $add=explode(";",$add);
        $add=$add[count($add)-1];
        
        global $_text_counter;
        $_text_counter = intval($_text_counter);
        $_text_counter++;

        if (($data[$name]) || (!$multiple))
            $this->HTML($name);
        else {
            ?>

            <?
        };
        ?>
        <?if ($add!="hidden_add"){?><script>
            cntglobal=<?echo $_text_counter;?>;
            </script><?};?>
        <textarea class="wisiwig<? if ((!$data[$name]) && ($multiple)) { ?><?= $name ?>###<? }; ?>" name="<?= $name ?><? if ($multiple) { ?>[<?if ($add=="hidden_add"){?>###<?}else{?><?echo $_text_counter;?><?}?>]<? }; ?>" style="width:100%;height:240px;"><?= $data[$name] ?></textarea>
        <?
    }

    function Add($name, $add, $multiple = false) {
        $spec_name = "glocalcnt" . $name;
        global $_cnt_global;
        if (!isset($_cnt_global[$spec_name]))
            $_cnt_global[$spec_name] = 0;
        $_cnt_global[$spec_name] = intval($_cnt_global[$spec_name]);
        $_cnt_global[$spec_name]++;

        if ($_cnt_global[$spec_name] == 1)
            $this->HTML($name);
        ?>
        <textarea class="wisiwig<? if ($_cnt_global[$spec_name] != 1) { ?><?= $name ?>###<? }; ?>" name=<?= $name ?><? if ($multiple) { ?>[<? if ($_cnt_global[$spec_name] != 1) { ?>###<? }; ?>]<? }; ?> style="width:100%;height:240px;"><? if (isset($_POST[$name])) { ?><?= $_POST[$name] ?><? }; ?><p>&nbsp;</p></textarea>
        <?
    }

}
?>