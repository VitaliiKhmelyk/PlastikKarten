<div class="field--select">
<span class="arrow"></span>

{$groupnameprefix="custom"}
{foreach from=$sConfigurator.values item=option name=config_option key=optionID}
	{block name='frontend_detail_configurator_variant_group_option_input'}
		<input type="text"
			class="option--input"
			id="{$groupnameprefix}group[{$option.groupID}][{$option.optionID}]"
			name="{$groupnameprefix}group[{$option.groupID}][{$option.optionID}]"
			value=""
			title="{$option.optionname}"
			style="display:none;"
			onchange="saveCustomParamsStatus('{$option.groupID}','{$option.optionID}')"
			/>
	{/block}	
{/foreach}
<table>
{foreach from=$sConfigurator.values item=option name=config_option key=optionID}
{assign var=optionID value=$option.optionID}
	{block name='frontend_detail_configurator_variant_group_option'}
		{if $option["option_attributes"]["cf_subgroupid"]}
			{$parent_subgroup_id=$option["option_attributes"]["cf_subgroupid"]}
			{if ($parent_subgroup_id!="") && ($parent_subgroup_id!="0")}
				{if in_array($parent_subgroup_id, $subgroups)}
				  <tr><td class="td-color-cf"><div id="parent_subgroup_{$parent_subgroup_id}_container"></div></td></tr>
				{/if}
			{/if}
		{/if}
	{/block}
{/foreach}
</table>
</div>