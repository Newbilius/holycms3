var dialog;
var obj0;
function GetFileIMG()
{
    if (!dialog) {
        dialog= $("#find0").dialogelfinder({
            url : '/engine/admin/ajax/elf/php/connector_img.php', // connector URL (REQUIRED)
            onlyMimes: ["image"],
            commandsOptions: {
                getfile: {
                    oncomplete: 'close', // destroy elFinder after file selection
                    multiple : false,
                    onlyURL  : false
                }
            },
            //getFileCallback: SelectFileImg // pass callback to editor
            getFileCallback: function(file) { 
                var tmp_name=file.path.replace(new RegExp("www",'g'),"");
                tmp_name = tmp_name.replace(/\\/g, "/");
				tmp_name="/"+tmp_name;
                /*tmp_name = tmp_name.replace("<?=$_SERVER['HTTP_HOST']?>", "");*/
                obj0.find('input.fileimg2').val(tmp_name);
            }
        });
    }
    else {
        // reopen elFinder
        dialog.dialogelfinder('open')
    }
};
function AjaxLoadAlp()
{/*
$('a.ajax').unbind('click');
$('a.ajax').bind('click', function(){
  //alert($(this).attr("href"));
  $("#ajax_div").empty();
  $("#ajax_div").html("<center><img src=/engine/admin/img/ajax_loader.gif></center>");
  history.pushState({}, '', $(this).attr("href"));
  
$.get(
  $(this).attr("href")+"&_ajax_mode=1",
  {
    param1: "param1",
    param2: 2
  },
function onAjaxSuccess(data)
{
  // Здесь мы получаем данные, отправленные сервером и выводим их на экран.
  $("#ajax_div").html(data);
}
);
 


  return false; //@fix вот тут проблемка - не вызывается смена левого меню =(
});
*/
};



$(document).ready(function() {

    AjaxLoadAlp();

    $(".meta_tr").hide();
    $('ul.tabs.tabs_item li a').click(MenuTopItemsHideShow);

    function MenuTopItemsHideShow(){
        var thisRel = $(this).attr('rel');
        //alert(thisRel);
        if (thisRel=="tab1")
        {
            //$('.item_table tr').show();
            $('.no_meta_tr').show();
            $('.meta_tr').hide();
        };
        if (thisRel=="tab2")
        {
            $('.no_meta_tr').hide();
            $('.meta_tr').show();
        };
	
        $('ul.tabs.tabs_item li').removeClass('active');
        $(this).parent("li").addClass('active');
    }
    
    $("#tableform").tableDnD({
        onDragClass: "myDragClass",
        dragHandle: "dragHandle",
        onDrop: function(table, row) {
		
            $("#tableform tr:even").removeClass('odd');
            $("#tableform tr:odd").removeClass('odd');
            $("#tableform tr:odd").addClass('odd');
            $("#tableform thead tr").removeClass('odd');
            $("#tableform tr").removeClass('selected_tr');
 
        },
        onDropProcess: function(id1, id2) {
            var val1=$("#"+id1).find("input[name=value0]").val();
            var val2=$("#"+id2).find("input[name=value0]").val();
            var idval1=$("#"+id1).find("input[name=vid]").val();
            var idval2=$("#"+id2).find("input[name=vid]").val();
            if (id2!="")
                if (id1!="")
                {
                    $("#"+id1).find("input[name=value0]").val(val2);
                    $("#"+id1).find("[name=value1]").html(val2);
                    $("#"+id2).find("input[name=value0]").val(val1);
                    $("#"+id2).find("[name=value1]").html(val1);

                    $.get('/engine/admin/ajax/move_twin.php?id='+idval1+'&sort='+val2+'&table='+table, function(data){});
                    $.get('/engine/admin/ajax/move_twin.php?id='+idval2+'&sort='+val1+'&table='+table, function(data){});
                };
        }
    });
    $(".alert").alert();
});


$(document).ready(function() {

    $(".file_img_select").live("click", function(e) {
        var tmp_obj=$(this).parents("div.inner_foto");
        obj0=tmp_obj;
        GetFileIMG();
    }); 


    $(".fileimg2").hide();

    $(".fileimg2_button").live("click", function(e) {

        var parent=$(this).parents("div.inner_foto");
        parent.find('.fileimg1').hide();
        parent.find('.fileimg2').show();
        parent.find('li').removeClass("active");
        parent.find('.fileimg2_button_li').addClass("active");
    }); 

    $(".fileimg1_button").live("click", function(e) {
        var parent=$(this).parents("div.inner_foto");
        parent.find('.fileimg2').hide();
        parent.find('.fileimg1').show();
        parent.find('li').removeClass("active");
        parent.find('.fileimg1_button_li').addClass("active");
    }); 


});
$(document).ready(function() {
    $("a.about_show").fancybox({
        //'hideOnContentClick': true
        });
	$(".chosen").chosen();
});
function DeleteTmp()
{
$(".delete_before_add").empty();
return true;
};
var cntglobal=0;
function AddElementDiv(element,element2,typus){
    var new_line;
    cntglobal++;
    new_line=$(element).html();
    new_line=new_line.replace(new RegExp("###",'g'),cntglobal);
    $(element2).before("<div>"+new_line+"</div>");
 $(".wisiwig"+typus+cntglobal).ckeditor();


}