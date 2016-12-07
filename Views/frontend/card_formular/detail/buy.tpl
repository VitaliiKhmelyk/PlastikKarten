{extends file="parent:frontend/detail/buy.tpl"}

{block name='frontend_detail_buy_quantity_select'}
	<div style="display:none;">
	 <input type="text" min="{$sArticle.minpurchase}" max="{$maxQuantity}" value="{$curQuantity}"  id="sQuantity" name="sQuantity">
	</div>
{/block}

{block name='frontend_detail_buy_quantity'}
<div class="table-group-cf">
  {$smarty.block.parent} 
</div>
{/block}

{block name='frontend_detail_buy_button'}
<div class="product--data-container-cf product--data-spacer-cf">
  {$smarty.block.parent} 
</div>
{/block}