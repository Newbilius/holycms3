<?
if (isset($result))
if (count($result)>0)
{
?>
	<div class="spec_block">
		<h1>Специальные предложения!</h1>

		<? foreach ($result as $item){
		$item_link=$params['url'].$item['id'];
		?>
		<div class="spec_item">
			<?
			if ($params['_image'])
			if ($item[$params['_image']])
			{
			?>
			<div class="img"><a href="<?=$item_link?>"><?=GetImageResizeCache("/upload/".$item[$params['_image']],110,110,"style=''")?></a></div>
			<?};?>
				<div class="info">
					<div class="title"><a href="<?=$item_link?>"><?=$item['caption']?></a></div>
					<div class="desc"><? if ($params['_text']) echo $item[$params['_text']]?></div>
					<div class="price">
						<? if ($params['_old_price']) if ($item[$params['_old_price']]){?><span class="old_price"><?=$item[$params['_old_price']]?></span><?};?>
						<? if ($params['_price']){?><span><?=$item[$params['_price']]?> рублей</span><?};?>
					</div>
					<div class="buy"><a href="<?=$params['cart_url']?>?new_basket=<?=$item['id']?>">купить</a></div>
				</div>
		</div>
		<?};?>
	<div class="cleaner"></div>
	</div>
<?
};
?>