<?

class CForm_image_multiple extends CForm_Text {

    function View($name, $data, $add, $multiple = false) {
        //echo $data[$name];
        echo "";
    }

    function BeforeAdd($name, $value, $add) {
        if ($value == "")
            return $value;
        if (is_array($value)) {
            $empty_array = $value;
            $value = "";
            foreach ($empty_array as $tmp_file) {
                $tmp_name = FOLDER_ROOT . $tmp_file['tmp_name'];
                $exp = explode(".", $tmp_name);
                $extens = end($exp);
                $file_name = MD5(time() . $tmp_name . rand()) . "." . $extens;
                $fold1 = substr($file_name, 0, 2);
                $fold2 = substr($file_name, 2, 2);
                if (!file_exists(FOLDER_UPLOAD))
                    mkdir(FOLDER_UPLOAD);
                if (!file_exists(FOLDER_IMAGE))
                    mkdir(FOLDER_IMAGE);
                if (!file_exists(FOLDER_IMAGE . $fold1))
                    mkdir(FOLDER_IMAGE . $fold1);
                if (!file_exists(FOLDER_IMAGE . $fold1 . "/" . $fold2))
                    mkdir(FOLDER_IMAGE . $fold1 . "/" . $fold2);
                $file_name_save = URI_IMAGE . $fold1 . "/" . $fold2 . "/" . $file_name;
                $file_name = FOLDER_IMAGE . $fold1 . "/" . $fold2 . "/" . $file_name;
                rename($tmp_name, $file_name);
                if ($value != "")
                    $value.=";";
                $value.=$file_name_save;
            };
        } else {
            $tmp = explode(";", $value);
            foreach ($tmp as $num => $_item)
                if (!$_item) {
                    unset($tmp[$num]);
                };
            $value = implode(";", $tmp);
        }
        return $value;
    }

    function AfterEdit($name, $value, $add) {
        if ($value == "")
            return $value;
        if (is_array($value)) {
            $empty_array = $value;
            $value = "";
            foreach ($empty_array as $tmp_file) {
                $tmp_name = FOLDER_ROOT . $tmp_file['tmp_name'];
                $exp = explode(".", $tmp_name);
                $extens = end($exp);
                $file_name = MD5(time() . $tmp_name . rand()) . "." . $extens;
                $fold1 = substr($file_name, 0, 2);
                $fold2 = substr($file_name, 2, 2);
                if (!file_exists(FOLDER_UPLOAD))
                    mkdir(FOLDER_UPLOAD);
                if (!file_exists(FOLDER_IMAGE))
                    mkdir(FOLDER_IMAGE);
                if (!file_exists(FOLDER_IMAGE . $fold1))
                    mkdir(FOLDER_IMAGE . $fold1);
                if (!file_exists(FOLDER_IMAGE . $fold1 . "/" . $fold2))
                    mkdir(FOLDER_IMAGE . $fold1 . "/" . $fold2);
                $file_name_save = URI_IMAGE . $fold1 . "/" . $fold2 . "/" . $file_name;
                $file_name = FOLDER_IMAGE . $fold1 . "/" . $fold2 . "/" . $file_name;
                rename($tmp_name, $file_name);
                if ($value != "")
                    $value.=";";
                $value.=$file_name_save;
            };
        } else {
            $tmp = explode(";", $value);
            foreach ($tmp as $num => $_item)
                if (!$_item) {
                    unset($tmp[$num]);
                };
            $value = implode(";", $tmp);
        };
        return $value;
    }

    function HTML($name, $data = null) {
        $file_id_name = "fileupload_" . $name
        ?>
        <? $_data_tmp = explode(";", $data[$name]); ?>
        <textarea style="display: none;width:100%;height:200px;" id="<?= $name ?>" name=<?= $name ?>><? echo $data[$name] ?></textarea>

        <!-- The file upload form used as target for the file upload widget -->
        <div id="<?= $file_id_name ?>">
            <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
            <div class="row fileupload-buttonbar">
                <div class="" style="padding-left:20px;padding-top:0px;">
                    <!-- The fileinput-button span is used to style the file input field as button -->
                    <span class="btn btn-success fileinput-button">
                        <i class="icon-plus icon-white"></i>
                        <span>Добавить файлы...</span>
                        <input type="file" name="json_json_<?= $file_id_name ?>[]" multiple>
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
                    <span>Скачать</span>
                </a>
                <a class="btn btn-info modal-prev">
                    <i class="icon-arrow-left icon-white"></i>
                    <span>Назад</span>
                </a>
                <a class="btn btn-primary modal-next">
                    <span>Вперед</span>
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
                        
                        <span>Старт</span>
                    </button>
                    {% } %}</td>
                {% } else { %}
                <td colspan="2"></td>
                {% } %}
                <td class="cancel">{% if (!i) { %}
                    <button class="btn btn-warning">
                        <i class="icon-ban-circle icon-white"></i>
                        <span></span>
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
                        <span></span>
                    </button>
                    <input type="checkbox" name="delete" value="1">
                </td>
            </tr>
            {% } %}
        </script>
        <script>
            $(function () {
                'use strict';

                // Initialize the jQuery File Upload widget:
                $('#<?= $file_id_name ?>').fileupload();
                                                                                                        
                $('#<?= $file_id_name ?>').bind('fileuploaddestroyed',
                function (e, data) {
                    for(var prop in data) {
                        if (!data.hasOwnProperty(prop)) continue;
                        var filename = data.url.substring(data.url.indexOf("=") + 1);
                        var text_tmp=$("#<?= $name ?>").val();
                        text_tmp=text_tmp.replace(new RegExp(filename+";",'g'),"");
                        text_tmp=text_tmp.replace(new RegExp(";".filename,'g'),"");
                        text_tmp=text_tmp.replace(new RegExp(filename,'g'),"");
                        $("#<?= $name ?>").val(text_tmp);
                    };
                });

                $('#<?= $file_id_name ?>').bind('fileuploaddone', function (e, data) {
                    var filename = data.result[0].url;
                    var text_tmp=$("#<?= $name ?>").val();
                    text_tmp=text_tmp+";"+filename;
                    $("#<?= $name ?>").val(text_tmp);
                });

                $('#<?= $file_id_name ?>').fileupload('option', {
                    url: '/engine/admin/ajax/json_upload.php',
                    sequentialUploads:true,
                    limitConcurrentUploads:1,
                    acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i
                });
        <? if ($data !== null) { ?>
                    // Load existing files:
                    $.ajax({
                        url: "/engine/admin/ajax/json_uploader_info.php?dblock=<? echo $this->GetBlock(); ?>&id=<?= $data['id'] ?>&field=<?= $name ?>",
                        dataType: 'json',
                        context: $('#<?= $file_id_name ?>')[0]
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