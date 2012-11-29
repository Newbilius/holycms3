<?
global $_global_bread;
global $_photo_id;
global $_folder_selector;
if (isset($_photo_id))
    if ($_photo_id) {
        ?>
<form>
<?
        $_global_bread[] = Array("Массовая загрузка фото");
        ?>
        <? if ($_folder_selector) { ?><BR>В какую папку грузить:
            <select class=par id=parent name=parent class="where_move">
                <option value=0>[ корень ]</option>
            <?

            function DrawFolderTree($parent, $array_of, $name_add = "") {
                //получаем список папок
                foreach ($array_of as $data)
                    if ($data['parent'] == $parent) {
                        ?>
                            <option value=<?= $data['id'] ?>><?= $name_add . $data['caption'] ?>&nbsp;</option>
                            <?
                            DrawFolderTree($data['id'], $array_of, $name_add . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
                        };
                }

;
                $el = new DBlockElement($_photo_id);
                $el->GetList("folder=1");
                while ($data = $el->GetNext())
                    $folders[] = $data;

                DrawFolderTree(0, $folders);
                ?>
            </select>
        <? } else { ?>
            <select class=par id=parent name=parent class="where_move" style="display:none;">
                <option value=0>[ корень ]</option>
            </select>
        <? }; ?>
        
        <div style="margin-top:10px;" id="multiple_foto">
            <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
            <div class="row fileupload-buttonbar">
                <div class="" style="padding-left:20px;padding-top:0px;">
                    <!-- The fileinput-button span is used to style the file input field as button -->
                    <span class="btn btn-success fileinput-button">
                        <i class="icon-plus icon-white"></i>
                        <span>Добавить файлы...</span>
                        <input type="file" name="json_json_multiple_foto[]" multiple>
                    </span>
                    <button type="submit" class="btn btn-primary start">
                        <i class="icon-upload icon-white"></i>
                        <span>Начать загрузку</span>
                    </button>
                    <button type="reset" class="btn btn-warning cancel">
                        <i class="icon-ban-circle icon-white"></i>
                        <span>Отменить загрузку</span>
                    </button>
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
            </tr>
            {% } %}
        </script>
        </form>
        <script>
            $(function () {
                // Initialize the jQuery File Upload widget:
                $('#multiple_foto').fileupload();

                // Demo settings:
                $('#multiple_foto').fileupload('option', {
                    url: '/engine/admin/ajax/json_upload_foto.php',
                    acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i
                });
            });

        </script>
        
        <? /* <BR><BR>
          <input type=buton value="Начать загрузку изображений" onclick="$('#file_upload').uploadify('upload','*')" class="btn btn-success">
          <BR>
          <BR>
          <script type="text/javascript" src="/engine/js/uploadify/jquery.uploadify-3.1.min.js"></script>
          <link rel="stylesheet" type="text/css" href="/engine/js/uploadify/uploadify.css" />
          <input type="file" name="file_upload" id="file_upload" />
          <script>
          $(function() {
              $('#file_upload').uploadify({
                  debug:false,
          'swf'      : '/engine/js/uploadify/uploadify.swf',
                  'uploader' : '/engine/admin/ajax/uploadify.php',
                  // Put your options here
          'auto'     : false,
          //'debug'     : true,
          'buttonText' : 'ВЫБРАТЬ ФАЙЛЫ',
          'formData'      : {'parent' : 0,'CODE':"asdasdas.sklfhjsjf.sdjl_akfsdhjf"},
          'onUploadStart' : function(file) {
          $("#file_upload").uploadify("settings", "formData",{'parent' : $("select").val()});
          }
              });
          });
          </script> */ ?>
    <?
    }else
        die("модуль отсутствует");?>