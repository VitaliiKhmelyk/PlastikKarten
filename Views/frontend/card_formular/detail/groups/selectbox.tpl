<div class="field--select">
<span class="arrow"></span>
<table>
<tr><td>
{$groupnameprefix=""}
{if ($sConfigurator["pseudo"])}
  {$groupnameprefix="custom"}
{/if}
<select name="{$groupnameprefix}group[{$sConfigurator.groupID}]" data-ajax-select-variants="true">
	{if empty($sConfigurator.user_selected)}
		<option value="" selected="selected">{s name="DetailConfigValueSelect" namespace="frontend/detail/config_step"}Please select{/s}</option>
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
</td></tr>
{if $option["option_attributes"]["cf_subgroupid"]}
	{$parent_subgroup_id=$option["option_attributes"]["cf_subgroupid"]}
	{if ($parent_subgroup_id!="") && ($parent_subgroup_id!="0")}
		{if in_array($parent_subgroup_id, $subgroups)}
		  <tr><td class="td-color-cf""><div id="parent_subgroup_{$parent_subgroup_id}_container"></div></td></tr>
		{/if}
	{/if}
{/if}
</table>
</div>