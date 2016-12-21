<div class="field--select">
<span class="arrow"></span>

{$is_horizontal=false}
{$is_media_presents=false}
{$is_markup=false}
{foreach from=$sConfigurator.values item=option name=config_option key=optionID}
{if $option.selectable|| $is_subgroup }
  {if isset($option.media_data.src.original)}
    {if ($option.media_data.src.original!="")}
      {$is_horizontal=true}
      {$is_media_presents=true}
    {/if}
    {if $cf_show_markup}
		{if $cf_markups.$optionID.price_mod}
		  {$is_markup=true}
		{/if}
	{/if}	
  {/if}
 {/if} 
{/foreach}

{$groupnameprefix=""}
{if ($sConfigurator["pseudo"])}
  {$groupnameprefix="custom"}
{/if}

<div class='cf_ajax_container_group[{$sConfigurator.groupID}]'>
<table>

{if $is_horizontal}

<tr>
	{foreach from=$sConfigurator.values item=option name=config_option key=optionID}
	{if $option.selectable}
		<td class="td-color-cf">
		  	<div class="variant--option is--image">
		  		{block name='frontend_detail_configurator_variant_group_option_label_image'}
					<span class="image--element">
						<span class="image--media">
							{$media = $option.media_data.src.original}
							{if isset($media)}
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
	{/foreach}
</tr>
<tr>
	{foreach from=$sConfigurator.values item=option name=config_option key=optionID}
	{if $option.selectable}
		<td class="td-color-cf">
		  <div class="variant--option">
		  {block name='frontend_detail_configurator_variant_group_option_input'}
		  	<input type="radio"
				class="option--input"
				id="{$groupnameprefix}group[{$option.groupID}][{$option.optionID}]"
				name="{$groupnameprefix}group[{$option.groupID}]"
				value="{$option.optionID}"
				title="{$option.optionname}"
				{if !$option.selectable || $is_disabled}disabled="disabled"{/if}
				{if !($sConfigurator["pseudo"])}data-ajax-select-variants="true"{else}onchange="saveCustomParamsStatus('{$option.groupID}')"{/if}
				{if $option.selected && $option.selectable}checked="checked"{/if} />
			{/block}	
			{block name='frontend_detail_configurator_variant_group_option_label'}
			<label for="{$groupnameprefix}group[{$option.groupID}][{$option.optionID}]" class="option--label">
				{block name='frontend_detail_configurator_variant_group_option_label_text'}
					{$option.optionname}
					{if !$option.selectable}{s name="DetailConfigValueNotAvailable" namespace="frontend/detail/config_step"}{/s}{/if}
				{/block}
			</label>
			{/block}	
			</div>	
		</td>
	{/if}	
	{/foreach}
</tr>
{if $is_markup}
<tr>
	{foreach from=$sConfigurator.values item=option name=config_option key=optionID}
	{if $option.selectable}
		<td class="td-color-cf">
		  {if $cf_show_markup}
		  	{if $cf_markups.$optionID.price_mod}
			  <span class="{$cf_markups.$optionID.price_color_class}">{$cf_markups.$optionID.price_mod|currency}</span>
			{/if}
		  {/if}	
		</td>
	{/if}	
	{/foreach}
</tr>
{/if}
{foreach from=$sConfigurator.values item=option name=config_option key=optionID}	
{if $option.selectable}
	{if $option["option_attributes"]["cf_subgroupid"]}
		{$parent_subgroup_id=$option["option_attributes"]["cf_subgroupid"]}
		{if ($parent_subgroup_id!="") && ($parent_subgroup_id!="0")}
			{if in_array($parent_subgroup_id, $subgroups)}
			  <tr><td class="td-color-cf""><div id="parent_subgroup_{$parent_subgroup_id}_container"></div></td></tr>
			{/if}
		{/if}
	{/if}
{/if}	
{/foreach}

{else}

{foreach from=$sConfigurator.values item=option name=config_option key=optionID}
{if $option.selectable}
{assign var=optionID value=$option.optionID}
{$column_cnt=0}
{block name='frontend_detail_configurator_variant_group_option'}
<tr>
{$column_cnt=$column_cnt+1}
<td class="td-color-cf img_radio_cf">
	<div class="variant--option">
	{block name='frontend_detail_configurator_variant_group_option_input'}
		<input type="radio"
			class="option--input"
			id="{$groupnameprefix}group[{$option.groupID}][{$option.optionID}]"
			name="{$groupnameprefix}group[{$option.groupID}]"
			value="{$option.optionID}"
			title="{$option.optionname}"
			{if !($sConfigurator["pseudo"])}data-ajax-select-variants="true"{else}onchange="saveCustomParamsStatus('{$option.groupID}')"{/if}
			{if !$option.selectable || $is_disabled}disabled="disabled"{/if}
			{if $option.selected && $option.selectable}checked="checked"{/if} />
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
				{if isset($media)}
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
				{if !$option.selectable}{s name="DetailConfigValueNotAvailable" namespace="frontend/detail/config_step"}{/s}{/if}
			{/block}
		</label>
		{if $cf_show_markup}
			{if $cf_markups.$optionID.price_mod}
				<span class="{$cf_markups.$optionID.price_color_class}">{$cf_markups.$optionID.price_mod|currency}</span>
			{/if}
		{/if}
	{/block}								
	</div>
</td>
</tr>

{if $option["option_attributes"]["cf_subgroupid"]}
	{$parent_subgroup_id=$option["option_attributes"]["cf_subgroupid"]}
	{if ($parent_subgroup_id!="") && ($parent_subgroup_id!="0")}
		{if in_array($parent_subgroup_id, $subgroups)}
		  <tr><td class="td-color-cf" colspan="{$column_cnt}"><div id="parent_subgroup_{$parent_subgroup_id}_container"></div></td></tr>
		{/if}
	{/if}
{/if}

{/block}
{/if}
{/foreach}

{/if}
</table>
</div>

</div>
