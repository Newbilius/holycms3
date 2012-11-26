<?

class CForm_image_multiple extends CForm_Text {

    function HTML($name, $data = null) {
        ?>
        <? /* <input name=<?= $name ?> value='<? if (isset($_POST[$name])) echo $_POST[$name]; ?>' style="width:100%"> */ ?>
        <? $_data_tmp = explode(";", $data[$name]); ?>
<textarea style="width:100%;height:100px;" id="<?= $name ?>" name=<?= $name ?>><?echo $data[$name]?></textarea>
        <? //pre_print($_data_tmp); ?>

        <!-- The file upload form used as target for the file upload widget -->
        <div id="fileupload">
            <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
            <div class="row fileupload-buttonbar">
                <div class="" style="padding-left:20px;padding-top:0px;">
                    <!-- The fileinput-button span is used to style the file input field as button -->
                    <span class="btn btn-success fileinput-button">
                        <i class="icon-plus icon-white"></i>
                        <span>Добавить файлы...</span>
                        <input type="file" name="files[]" multiple>
                    </span>
                    <button type="submit" class="btn btn-primary start">
                        <i class="icon-upload icon-white"></i>
                        <span>Начать загрузку</span>
                    </button>
                    <button type="reset" class="btn btn-warning cancel">
                        <i class="icon-ban-circle icon-white"></i>
                        <span>Отменить загрузку</span>
                    </button>
                    <button type="button" class="btn btn-danger delete">
                        <i class="icon-trash icon-white"></i>
                        <span>Удалить</span>
                    </button>
                    <input type="checkbox" class="toggle">
                </div>
                <!-- The global progress information -->
                <div class="span5 fileupload-progress fade">
                    <!-- The global progress bar -->
                    <div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                        <div class="bar" style="width:0%;"></div>
                    </div>
                    <!-- The extended global progress information -->
                    <div class="progress-extended">&nbsp;</div>
                </div>
            </div>
            <!-- The loading indicator is shown during file processing -->
            <div class="fileupload-loading"></div>
            <!-- The table listing the files available for upload/download -->
            <table role="presentation" class="table table-striped"><tbody class="files" data-toggle="modal-gallery" data-target="#modal-gallery"></tbody></table>
        </div>
        <!-- modal-gallery is the modal dialog used for the image gallery -->
        <div id="modal-gallery" class="modal modal-gallery hide fade" data-filter=":odd" tabindex="-1">
            <div class="modal-header">
                <a class="close" data-dismiss="modal">&times;</a>
                <h3 class="modal-title"></h3>
            </div>
            <div class="modal-body"><div class="modal-image"></div></div>
            <div class="modal-footer">
                <a class="btn modal-download" target="_blank">
                    <i class="icon-download"></i>
                    <span>Download</span>
                </a>
                <a class="btn btn-success modal-play modal-slideshow" data-slideshow="5000">
                    <i class="icon-play icon-white"></i>
                    <span>Slideshow</span>
                </a>
                <a class="btn btn-info modal-prev">
                    <i class="icon-arrow-left icon-white"></i>
                    <span>Previous</span>
                </a>
                <a class="btn btn-primary modal-next">
                    <span>Next</span>
                    <i class="icon-arrow-right icon-white"></i>
                </a>
            </div>
        </div>
        <!-- The template to display files available for upload -->
        <script id="template-upload" type="text/x-tmpl">
            {% for (var i=0, file; file=o.files[i]; i++) { %}
            <tr class="template-upload fade">
                <td class="preview"><span class="fade"></span></td>
                <td class="name"><span>{%=file.name%}</span></td>
                <td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td>
                {% if (file.error) { %}
                <td class="error" colspan="2"><span class="label label-important">Error</span> {%=file.error%}</td>
                {% } else if (o.files.valid && !i) { %}
                <td>
                    <div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="bar" style="width:0%;"></div></div>
                </td>
                <td class="start">{% if (!o.options.autoUpload) { %}
                    <button class="btn btn-primary">
                        <i class="icon-upload icon-white"></i>
                        <span>Старт</span>
                    </button>
                    {% } %}</td>
                {% } else { %}
                <td colspan="2"></td>
                {% } %}
                <td class="cancel">{% if (!i) { %}
                    <button class="btn btn-warning">
                        <i class="icon-ban-circle icon-white"></i>
                        <span>Отмена</span>
                    </button>
                    {% } %}</td>
            </tr>
            {% } %}
        </script>
        <!-- The template to display files available for download -->
        <script id="template-download" type="text/x-tmpl">
            {% for (var i=0, file; file=o.files[i]; i++) { %}
            <tr class="template-download fade">
                {% if (file.error) { %}
                <td></td>
                <td class="name"><span>{%=file.name%}</span></td>
                <td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td>
                <td class="error" colspan="2"><span class="label label-important">Error</span> {%=file.error%}</td>
                {% } else { %}
                <td class="preview">{% if (file.thumbnail_url) { %}
                    <a href="{%=file.url%}" title="{%=file.name%}" rel="gallery" download="{%=file.name%}"><img src="{%=file.thumbnail_url%}"></a>
                    {% } %}</td>
                <td class="name">
                    <a href="{%=file.url%}" title="{%=file.name%}" rel="{%=file.thumbnail_url&&'gallery'%}" download="{%=file.name%}">{%=file.name%}</a>
                </td>
                <td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td>
                <td colspan="2"></td>
                {% } %}
                <td class="delete">
                    <button class="btn btn-danger" data-type="{%=file.delete_type%}" data-url="{%=file.delete_url%}"{% if (file.delete_with_credentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                            <i class="icon-trash icon-white"></i>
                        <span>Удалить</span>
                    </button>
                    <input type="checkbox" name="delete" value="1">
                </td>
            </tr>
            {% } %}
        </script>
        <script>
            /*
             * jQuery File Upload Plugin JS Example 6.11
             * https://github.com/blueimp/jQuery-File-Upload
             *
             * Copyright 2010, Sebastian Tschan
             * https://blueimp.net
             *
             * Licensed under the MIT license:
             * http://www.opensource.org/licenses/MIT
             */

            /*jslint nomen: true, unparam: true, regexp: true */
            /*global $, window, document */

            $(function () {
                'use strict';

                // Initialize the jQuery File Upload widget:
                $('#fileupload').fileupload({
                    // Uncomment the following to send cross-domain cookies:
                    //xhrFields: {withCredentials: true},
                    url: 'server/php/'
                });

                // Enable iframe cross-domain access via redirect option:
                $('#fileupload').fileupload(
                'option',
                'redirect',
                window.location.href.replace(
                /\/[^\/]*$/,
                '/cors/result.html?%s'
            )
            );
                
$('#fileupload').bind('fileuploaddestroyed',
function (e, data) {
    for(var prop in data) {
    if (!data.hasOwnProperty(prop)) continue;
    var filename = data.url;
    var text_tmp=$("#<?=$name?>").val();
    text_tmp=text_tmp.replace(new RegExp(filename+";",'g'),"");
    text_tmp=text_tmp.replace(new RegExp(";".filename,'g'),"");
    text_tmp=text_tmp.replace(new RegExp(filename,'g'),"");
    $("#<?=$name?>").val(text_tmp);
    alert(filename);
};
});

$('#fileupload').bind('fileuploaddone', function (e, data) {
 $.each(data.files, function (index, file) {
        var text_tmp=$("#<?=$name?>").val();
        text_tmp=text_tmp+";"+file.name;
    $("#<?=$name?>").val(text_tmp);    
});

});

                // Demo settings:
                $('#fileupload').fileupload('option', {
                    url: 'http://jquery-file-upload.appspot.com/',
                    maxFileSize: 5000000,
                    acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
                    process: [
                        {
                            action: 'load',
                            fileTypes: /^image\/(gif|jpeg|png)$/,
                            maxFileSize: 20000000 // 20MB
                        },
                        {
                            action: 'resize',
                            maxWidth: 1440,
                            maxHeight: 900
                        },
                        {
                            action: 'save'
                        }
                    ]
                });
        <? if ($data !== null) { ?>
                    // Load existing files:
                    $.ajax({
                        // Uncomment the following to send cross-domain cookies:
                        //xhrFields: {withCredentials: true},
                        //url: $('#fileupload').fileupload('option', 'url'),
                        url: "/engine/admin/ajax/json_uploader_info.php?dblock=<? echo $this->GetBlock(); ?>&id=<?= $data['id'] ?>&field=<?= $name ?>",
                        dataType: 'json',
                        context: $('#fileupload')[0]
                    }).done(function (result) {
                        if (result && result.length) {
                            $(this).fileupload('option', 'done')
                            .call(this, null, {result: result});
                        }
                    });
        <? }; ?>

            });

        </script>
        <?
    }

    function Add($name, $add, $multiple = false) {
        $this->HTML($name);
    }

    function Edit($name, $data, $add, $multiple = false) {
        ?>
        <? /*
         * <input name=<?= $name ?><? if ($multiple) { ?>[]<? }; ?> value="<?= htmlspecialchars($data[$name]) ?>" style="width:100%">
         */ ?>
        <? $this->HTML($name, $data); ?>
        <?
    }

}
?>