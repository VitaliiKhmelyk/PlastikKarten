{block name='frontend_checkout_cart_item_details_inline' append}
	{if $sBasketItem.cf_pseudo_show}
		<div class="cf-item--wrapper">
			{foreach $sBasketItem.cf_pseudo_data as $blockData}
				<span class="item--name">
					{$blockData}
				</span>
			{/foreach}
		</div>
	{/if}
{/block}
