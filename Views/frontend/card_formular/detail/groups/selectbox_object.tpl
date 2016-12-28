{$groupnameprefix=""}
{if ($sConfigurator["pseudo"])}
	{$groupnameprefix="custom"}
{/if}

{if $ajax_selectbox_mode == 1}
<div class='cf_ajax_container_group_{$sConfigurator.groupID} cf_data'>
	{if $is_disabled}1{else}0{/if};
	{foreach from=$sConfigurator.values item=option name=config_option key=optionID}
	{$option.optionID},{if $option.selectable}1{else}0{/if},{if $option.selected && $sConfigurator.user_selected}1{else}0{/if};
	{/foreach}
</div>	
{/if}

<div class='cf_ajax_container_group_{$sConfigurator.groupID} cf_ajax_type_selectbox'>
<select id="{$groupnameprefix}group[{$sConfigurator.groupID}]"  name="{$groupnameprefix}group[{$sConfigurator.groupID}]" {if !($sConfigurator["pseudo"])}data-ajax-select-variants="true"{else}onchange="saveCustomParamsStatus('{$sConfigurator.groupID}')"{/if}
{if $is_disabled}disabled="disabled"{/if}>
	<option value="" {if empty($sConfigurator.user_selected)}selected{/if} disabled>--- 
	{if !$is_disabled}
	  {s name="DetailConfigValueSelect" namespace="frontend/detail/config_step"}Please select{/s} 	
	{else}
	  {s name="DetailConfigValueSelectAbove" namespace="CardFormular"}Please select above{/s} 
	{/if}
	---</option>	
	{foreach from=$sConfigurator.values item=configValue name=option key=optionID}
	{if $configValue.selectable}
		{assign var=optionID value=$configValue.optionID}
				<option {if $sConfigurator.user_selected && $configValue.selected && $configValue.selectable} selected{/if} value="{$configValue.optionID}">
					{$configValue.optionname}{for $i=1 to 5}&nbsp;{/for}
					{if $cf_show_markup}{if $cf_markups.$optionID.price_mod}<span class="{$cf_markups.$optionID.price_color_class}">{$cf_markups.$optionID.price_mod|currency}</span>{/if}{/if}					
				</option>
	{/if}
	{/foreach}
</select>	
</div>