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
{$smarty.block.parent} 
{/block}

{block name='frontend_detail_buy_variant' append}
	{if $sArticle.sConfigurator}
		{foreach $sArticle.sConfigurator as $configurator}
			{if $configurator.pseudo eq 1}
				{$optionValue = ''}
				{foreach $configurator.values as $option}
					{if $option.user_selected eq 1}
						{$optionValue = $option.optionID}
					{/if}
				{/foreach}
				<input id="pseudo-{$configurator.groupID}" name="customgroup[{$configurator.groupID}]" type="hidden" value="{$optionValue}">
			{/if}
		{/foreach}
	{/if}
{/block}
