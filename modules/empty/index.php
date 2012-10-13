<?
if ($_selected_page['folder']) {
    $page = new DBlockElement("pages");
    $inter->GetList("parent=" . $_selected_page['id']);
    while ($row = $inter->GetNext()) {
        $url=$_selected_page['name'] ."/".$row['name'];
        if ($row['redirect'])
            $url=$row['redirect'];
        ?>
        <a href='<? echo $url;?>'><?= $row['caption'] ?></a><BR>
        <?
    };
};
if ($_selected_page['detail_text'])
    echo $_selected_page['detail_text'];
?>
