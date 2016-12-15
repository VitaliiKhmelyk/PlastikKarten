<div class="field--select">
<span class="arrow"></span>

{$groupnameprefix="custom"}
<table>
<tr>
	{$cnt=0}
	{foreach from=$sConfigurator.values item=option name=config_option key=optionID}
		{if $cnt<2}
			<td class="td-color-cf" >
			  <div class="variant--option">
				{block name='frontend_detail_configurator_variant_group_option_label'}
				<label for="group[{$option.groupID}][{$option.optionID}]" class="option--label">
					{block name='frontend_detail_configurator_variant_group_option_label_text'}
						{$option.optionname}
					{/block}
				</label>
				{/block}	
				</div>	
			</td>
		{/if}
		{$cnt=$cnt+1}	
	{/foreach}
</tr>
<tr>
	{$cnt=0}
	{foreach from=$sConfigurator.values item=option name=config_option key=optionID}
	{if $cnt<2}
		<td class="td-color-cf">
		  	<div class="variant--option is--image">
		  		{block name='frontend_detail_configurator_variant_group_option_label_image'}
					<span class="image--element">
						<span class="image--media">
							{$media = $option.media_data.src.original}
							{if isset($media)}
								<img class="img_side_cf" src="{$media}" alt="{$option.optionname}" />
							{else} 
								{if $cnt==0}
									{$fname="front"}
								{else}
									{$fname="back"}
								{/if}
								<img src="engine/Shopware/Plugins/Local/Backend/CardFormular/resources/images/{$fname}.jpg" alt="{$option.optionname}">
							{/if}
						</span>
					</span>
				{/block}
		  	</div>	
		  	<div style="display:none">
			  	{block name='frontend_detail_configurator_variant_group_option_input'}
					<input type="text"
						class="option--input"
						id="group[{$option.groupID}][{$option.optionID}]"
						name="{$groupnameprefix}group[{$option.groupID}][{$option.optionID}]"
						value=""
						title="{$option.optionname}"
					/>
				{/block}	
			</div>
		</td>
	{/if}
	{$cnt=$cnt+1}	
	{/foreach}
</tr>
</table>
</div>