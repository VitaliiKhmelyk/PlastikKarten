{$groupnameprefix=""}
{if ($sConfigurator["pseudo"])}
	{$groupnameprefix="custom"}
{/if}

<div class='cf_ajax_container_group_{$sConfigurator.groupID} cf_ajax_type_selectbox'>
<select id="{$groupnameprefix}group[{$sConfigurator.groupID}]"  name="{$groupnameprefix}group[{$sConfigurator.groupID}]" {if !($sConfigurator["pseudo"])}data-ajax-select-variants="true"{else}onchange="saveCustomParamsStatus('{$sConfigurator.groupID}')"{/if}
{if $is_disabled}disabled="disabled"{/if}>
	{if empty($sConfigurator.user_selected)}
		<option value="" selected>{s name="DetailConfigValueSelect" namespace="frontend/detail/config_step"}Please select{/s}</option>
	{/if}
	{foreach from=$sConfigurator.values item=configValue name=option key=optionID}
	{if $configValue.selectable}
		{assign var=optionID value=$configValue.optionID}
				<option {if !$configValue.selectable}disabled{/if} {if $configValue.selected && $sConfigurator.user_selected} selected{/if} value="{$configValue.optionID}">
					{$configValue.optionname}{if $configValue.upprice && !$configValue.reset} {if $configValue.upprice > 0}{/if}{/if}
					{if !$configValue.selectable}{s name="DetailConfigValueNotAvailable" namespace="frontend/detail/config_step"}{/s}{/if}
					{if $cf_show_markup}{if $cf_markups.$optionID.price_mod}<span class="{$cf_markups.$optionID.price_color_class}">{$cf_markups.$optionID.price_mod|currency}</span>{/if}{/if}
				</option>
	{/if}
	{/foreach}
</select>	
</div>