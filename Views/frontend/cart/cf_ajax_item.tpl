{block name='frontend_checkout_ajax_cart_articlename' append}
	{if $basketItem.cf_pseudo_show}
		<div class="cf-item--wrapper">
			{foreach $basketItem.cf_pseudo_data as $blockData}
				<span class="item--name">
					{$blockData}
				</span>
			{/foreach}
		</div>
	{/if}
{/block}
