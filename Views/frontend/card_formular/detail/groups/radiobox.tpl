<div class="field--select">
<span class="arrow"></span>

{$is_horizontal=false}
{$is_media_presents=false}
{$is_markup=false}
{$cnt=0}
{foreach from=$sConfigurator.values item=option name=config_option key=optionID}
  {if isset($option.media_data.src.original) && isset($option.media_data.res.original.width)}
    {if ($option.media_data.src.original!="")}
      {$is_horizontal=true}
      {$is_media_presents=true}
    {/if}   
  {/if}
  {if $cf_show_markup}
		{if $cf_markups.$optionID.price_mod}
		  {$is_markup=true}
		{/if}
  {/if}
  {$cnt=$cnt+1}
{/foreach}
{$tdw=100/$cnt|ceil-1}

{$groupnameprefix=""}
{if ($sConfigurator["pseudo"])}
  {$groupnameprefix="custom"}
{/if}

<div class='cf_ajax_container_group_{$sConfigurator.groupID} {if $is_disabled}disabled_object{/if}'>
<table>

{if $is_horizontal}

<tr>
	{foreach from=$sConfigurator.values item=option name=config_option key=optionID}
		<td style="width:{$tdw}%;" class='cf_ajax_container_group_{$option.groupID}_{$option.optionID} cf_ajax_type_radio td-color-cf' {if !$option.selectable}style="display:none"{/if}>
		  	<div class="variant--option is--image">
		  		{block name='frontend_detail_configurator_variant_group_option_label_image'}
					<span class="image--element">
						<span class="image--media">
							{$media = $option.media_data.src.original}
							{if isset($media) && isset($option.media_data.res.original.width)}
								<img class="img_option_cf" src="{$media}" alt="{$option.optionname}" />
							{else}
								<img src="{link file='frontend/_public/src/img/no-picture.jpg'}" alt="{$option.optionname}">
							{/if}
						</span>
					</span>
				{/block}
		  	</div>	
		</td>
	{/foreach}
</tr>
<tr>
	{foreach from=$sConfigurator.values item=option name=config_option key=optionID}
		<td class='cf_ajax_container_group_{$option.groupID}_{$option.optionID} cf_ajax_type_radio td-color-cf' {if !$option.selectable}style="display:none"{/if}>
		  <div class="variant--option">
		  {block name='frontend_detail_configurator_variant_group_option_input'}
		  	<input type="radio"
				class="option--input  cf_ajax_container_group_{$option.groupID}_{$option.optionID} cf_ajax_type_radio_btn"
				id="{$groupnameprefix}group[{$option.groupID}][{$option.optionID}]"
				name="{$groupnameprefix}group[{$option.groupID}]"
				value="{$option.optionID}"
				title="{$option.optionname}"
				{if !($sConfigurator["pseudo"])}data-ajax-select-variants="true"{else}onchange="saveCustomParamsStatus('{$option.groupID}','{$option.optionID}')"{/if}
				{if $option.selected && $option.selectable && $sConfigurator.user_selected}checked="checked"{/if} />
			{/block}	
			{block name='frontend_detail_configurator_variant_group_option_label'}
			<label for="{$groupnameprefix}group[{$option.groupID}][{$option.optionID}]" class="option--label">
				{block name='frontend_detail_configurator_variant_group_option_label_text'}
					{$option.optionname}
				{/block}
			</label>
			{/block}	
			</div>	
		</td>
	{/foreach}
</tr>

{if $cf_show_markup}	
<tr class='cf_ajax_container_group_{$option.groupID}_{$option.optionID} cf_ajax_type_radio_markup' {if !$is_markup}style="display:none"{/if}>
	{foreach from=$sConfigurator.values item=option name=config_option key=optionID}
		<td class='cf_ajax_container_group_{$option.groupID}_{$option.optionID} cf_ajax_type_radio td-color-cf' {if !$option.selectable}style="display:none"{/if}>
			  <span class="{$cf_markups.$optionID.price_color_class} cf_ajax_container_group_{$option.groupID}_{$option.optionID} cf_data_markup">
			   {if $cf_markups.$optionID.price_mod}{$cf_markups.$optionID.price_mod|currency}{/if}
			  </span>			
		</td>
	{/foreach}
</tr>
{/if}	

{foreach from=$sConfigurator.values item=option name=config_option key=optionID}	
	{if $option["option_attributes"]["cf_subgroupid"]}
		{$parent_subgroup_id=$option["option_attributes"]["cf_subgroupid"]}
		{if ($parent_subgroup_id!="") && ($parent_subgroup_id!="0")}
			{if in_array($parent_subgroup_id, $subgroups)}
			  <tr class='cf_ajax_container_group_{$option.groupID}_{$option.optionID} cf_ajax_type_radio_sub' {if !$option.selectable || !$option.selected || $is_disabled}style="display:none"{/if}>
			    <td class="td-color-cf"><div id="parent_subgroup_{$parent_subgroup_id}_container"></div></td>
			  </tr>
			{/if}
		{/if}
	{/if}
{/foreach}

{else}

{foreach from=$sConfigurator.values item=option name=config_option key=optionID}
{assign var=optionID value=$option.optionID}
{$column_cnt=0}
{block name='frontend_detail_configurator_variant_group_option'}
<tr class='cf_ajax_container_group_{$option.groupID}_{$option.optionID} cf_ajax_type_radio' {if !$option.selectable}style="display:none"{/if}>
{$column_cnt=$column_cnt+1}
<td class="td-color-cf img_radio_cf">
	<div class="variant--option">
	{block name='frontend_detail_configurator_variant_group_option_input'}
		<input type="radio"
			class="option--input cf_ajax_container_group_{$option.groupID}_{$option.optionID} cf_ajax_type_radio_btn"
			id="{$groupnameprefix}group[{$option.groupID}][{$option.optionID}]"
			name="{$groupnameprefix}group[{$option.groupID}]"
			value="{$option.optionID}"
			title="{$option.optionname}"
			{if !($sConfigurator["pseudo"])}data-ajax-select-variants="true"{else}onchange="saveCustomParamsStatus('{$option.groupID}','{$option.optionID}')"{/if}
			{if $option.selected && $option.selectable && $sConfigurator.user_selected}checked="checked"{/if} />
	{/block}
	</div>
</td>
{if $is_media_presents}
{$column_cnt=$column_cnt+1}
<td class="td-color-cf img_td_cf">
	<div class="variant--option is--image">
		{block name='frontend_detail_configurator_variant_group_option_label_image'}
		<span class="image--element">
			<span class="image--media">
				{$media = $option.media_data.src.original}
				{if isset($media)  && isset($option.media_data.res.original.width)}
					<img class="img_option_cf" src="{$media}" alt="{$option.optionname}" />
				{else}
					<img src="{link file='frontend/_public/src/img/no-picture.jpg'}" alt="{$option.optionname}">
				{/if}
			</span>
		</span>
			{/block}
	</div>	
</td>
{/if}
{$column_cnt=$column_cnt+1}
<td class="td-color-cf">
	<div class="variant--option">
	{block name='frontend_detail_configurator_variant_group_option_label'}
		<label for="{$groupnameprefix}group[{$option.groupID}][{$option.optionID}]" class="option--label">
	  		{block name='frontend_detail_configurator_variant_group_option_label_text'}
				{$option.optionname}				
			{/block}
		</label>
	{/block}								
	</div>
</td>
{if $cf_show_markup}			
{$column_cnt=$column_cnt+1}
<td class="td-color-cf">
	<div class="variant--option" style="width:100%;text-align:right;">
	{block name='frontend_detail_configurator_variant_group_option_markup'}
		<span class="{$cf_markups.$optionID.price_color_class} cf_ajax_container_group_{$option.groupID}_{$option.optionID} cf_data_markup">
				{if $cf_markups.$optionID.price_mod} {$cf_markups.$optionID.price_mod|currency}{/if}
		</span>		
	{/block}								
	</div>
</td>	
{/if}

</tr>

{if $option["option_attributes"]["cf_subgroupid"]}
	{$parent_subgroup_id=$option["option_attributes"]["cf_subgroupid"]}
	{if ($parent_subgroup_id!="") && ($parent_subgroup_id!="0")}
		{if in_array($parent_subgroup_id, $subgroups)}
		  <tr class='cf_ajax_container_group_{$option.groupID}_{$option.optionID} cf_ajax_type_radio_sub' {if !$option.selectable || !$option.selected || $is_disabled }style="display:none"{/if}>
		    <td class="td-color-cf" colspan="{$column_cnt}"><div id="parent_subgroup_{$parent_subgroup_id}_container"></div></td>
		  </tr>
		{/if}
	{/if}
{/if}

{/block}
{/foreach}

{/if}
</table>
</div>

</div>
