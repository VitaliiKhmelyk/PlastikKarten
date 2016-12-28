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
