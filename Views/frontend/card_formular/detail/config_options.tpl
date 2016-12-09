{block name='frontend_detail_configurator_error'}
	{if $sArticle.sError && $sArticle.sError.variantNotAvailable}
		{include file="frontend/_includes/messages.tpl" type="error" content="{s name='VariantAreNotAvailable' namespace='frontend/detail/config_step'}{/s}"}
	{/if}
{/block}

<form method="post" action="{url sArticle=$sArticle.articleID sCategory=$sArticle.categoryID}" class="configurator--form selection--form">
	<div class="variant--group group-option-cf group-option-qty-cf">
		<p class="configurator--label">{s name='Quantity' namespace='CardFormular'}{/s}:</p>
		<div class="field--select">
			<span class="arrow"></span>			
			<input type="text" min="{$sArticle.minpurchase}" max="{$maxQuantity}" value="{$curQuantity}"  
				id="qty" name="qty" class="buybox--quantity"  
				onkeydown="return ( event.ctrlKey || event.altKey 
				                    || (47<event.keyCode && event.keyCode<58 && event.shiftKey==false) 
				                    || (95<event.keyCode && event.keyCode<106)
				                    || (event.keyCode==8) || (event.keyCode==9) 
				                    || (event.keyCode>34 && event.keyCode<40) 
				                    || (event.keyCode==46) )" 
				onkeyup="setQtyTextInputVal(this, false, {$sArticle.minpurchase}, {$maxQuantity})" 
				oninput="setQtyTextInputVal(this, false, {$sArticle.minpurchase}, {$maxQuantity})"
				onchange="setQtyTextInputVal(this, true, {$sArticle.minpurchase}, {$maxQuantity})"
			>
			{if $sArticle.packunit} {$sArticle.packunit}{/if} &nbsp; &nbsp; 
			<a href="javascript:void(0)" title="{s name='RecalculatePrice' namespace='CardFormular'}{/s}" data-ajax-variants="true">
				<i class="icon--cycle"></i>
			</a>			
		</div>	
	</div>	

	{foreach from=$sArticle.sConfigurator item=sConfigurator name=group key=groupID}
		{$group_type = ""}
		{$group_info = ""}
		{if $sConfigurator["group_attributes"]}
			{if $sConfigurator["group_attributes"]["cf_grouptype"]}
				{$group_type = $sConfigurator["group_attributes"]["cf_grouptype"]}
			{/if}
			{if $sConfigurator["group_attributes"]["cf_groupinfo"]}
				{$group_info = $sConfigurator["group_attributes"]["cf_groupinfo"]}
			{/if}
		{/if}

		<div class="variant--group group-option-cf group-option-common-cf">
			{* Group name *}
			{block name='frontend_detail_group_name'}
				<p class="configurator--label">{$sConfigurator.groupname}:</p>
			{/block}

			{$pregroupID=$groupID-1}

			{if ($group_type!="RadioBox")}

				{* Configurator drop down *}		
				{block name='frontend_detail_group_selection'}
					<div class="field--select{if $groupID gt 0 && empty($sArticle.sConfigurator[$pregroupID].user_selected)} is--disabled{/if}">
						<span class="arrow"></span>
					
						<select{if $groupID gt 0 && empty($sArticle.sConfigurator[$pregroupID].user_selected)} disabled="disabled"{/if} name="group[{$sConfigurator.groupID}]" data-ajax-select-variants="true">

							{* Please select... *}
							{if empty($sConfigurator.user_selected)}
								<option value="" selected="selected">{s name="DetailConfigValueSelect" namespace="frontend/detail/config_step"}{/s}</option>
							{/if}

							{foreach from=$sConfigurator.values item=configValue name=option key=optionID}
								{assign var=optionID value=$configValue.optionID}
								<option {if !$configValue.selectable}disabled{/if} {if $configValue.selected && $sConfigurator.user_selected} selected="selected"{/if} value="{$configValue.optionID}">
									{$configValue.optionname}{if $configValue.upprice && !$configValue.reset} {if $configValue.upprice > 0}{/if}{/if}
									{if !$configValue.selectable}{s name="DetailConfigValueNotAvailable" namespace="frontend/detail/config_step"}{/s}{/if}
									{if $cf_show_markup}{if $cf_markups.$optionID.price_mod}<span class="{$cf_markups.$optionID.price_color_class}">{$cf_markups.$optionID.price_mod|currency}</span>{/if}{/if}
								</option>
							{/foreach}
						</select>
				
						{if !empty($group_info)}
							<p class="modal--size-table link-show-tooltip" data-content="" data-modalbox="true" data-targetSelector="a" 
								data-width="640" data-height="480" data-mode="ajax">

								<a href="javascript:void(0);" 
									onclick="openModalInfo('{$sConfigurator.groupname}','{$group_info}')"
									title="{s name='ShowTooltip' namespace='CardFormular'}{/s}">
										<i class="icon--info2" ></i>&nbsp;{s name='ShowTooltip' namespace='CardFormular'}{/s}
								</a>
							</p>
						{else}
							<div class='product--options-spacer-cf'>&nbsp;</div> 
						{/if}
					</div>
				{/block}

			{else}

				{block name='frontend_detail_configurator_variant_group_options'}
					{foreach from=$sConfigurator.values item=option name=config_option key=optionID}
						{assign var=optionID value=$option.optionID}
						{block name='frontend_detail_configurator_variant_group_option'}
							<div class="variant--option{if $option.media} is--image{/if}">
								<span class="arrow"></span>
								{block name='frontend_detail_configurator_variant_group_option_input'}
									<input type="radio"
										class="option--input"
										id="group[{$option.groupID}][{$option.optionID}]"
										name="group[{$option.groupID}]"
										value="{$option.optionID}"
										title="{$option.optionname}"
										data-ajax-select-variants="true"
										{if !$option.selectable || ($groupID gt 0 && empty($sArticle.sConfigurator[$pregroupID].user_selected))}disabled="disabled"{/if}
										{if $option.selected && $option.selectable}checked="checked"{/if} />
								{/block}
								{block name='frontend_detail_configurator_variant_group_option_label'}
									<label for="group[{$option.groupID}][{$option.optionID}]" class="option--label{if !$option.selectable} is--disabled{/if}">
										{if $option.media}
											{$media = $option.media}
											{block name='frontend_detail_configurator_variant_group_option_label_image'}
												<span class="image--element">
													<span class="image--media">
														{if isset($media.thumbnails)}
															<img srcset="{$media.thumbnails[0].sourceSet}" alt="{$option.optionname}" />
														{else}
															<img src="{link file='frontend/_public/src/img/no-picture.jpg'}" alt="{$option.optionname}">
														{/if}
													</span>
												</span>
											{/block}
										{else}
											{block name='frontend_detail_configurator_variant_group_option_label_text'}
												{$option.optionname}
											{/block}
										{/if}
									</label>

									{if $cf_show_markup}
										{if $cf_markups.$optionID.price_mod}
										<span class="{$cf_markups.$optionID.price_color_class}">{$cf_markups.$optionID.price_mod|currency}</span>
										{/if}
									{/if}
								{/block}
							</div>
						{/block}
					{/foreach}
				{/block}

				{if !empty($group_info)}
					<p class="modal--size-table link-show-tooltip" data-content="" data-modalbox="true" data-targetSelector="a" 
						data-width="640" data-height="480" data-mode="ajax">
						<a href="javascript:void(0);" 
							onclick="openModalInfo('{$sConfigurator.groupname}','{$group_info}')"
							title="{s name='ShowTooltip' namespace='CardFormular'}{/s}">
							<i class="icon--info2" ></i>&nbsp;{s name='ShowTooltip' namespace='CardFormular'}{/s}
						</a>
					</p>
				{else}
					<div class='product--options-spacer-cf'>&nbsp;</div> 
					<div class='product--options-spacer-cf'>&nbsp;</div> 
				{/if}
			{/if}		
		</div>	
	{/foreach}	

	{block name='frontend_detail_configurator_noscript_action'}
		<noscript>
			<input name="recalc" type="submit" value="{s name='DetailConfigActionSubmit' namespace='frontend/detail/config_step'}{/s}" />
		</noscript>
	{/block}
</form>

{block name='frontend_detail_configurator_step_reset'}
	{include file="frontend/detail/config_reset.tpl"}
{/block}
