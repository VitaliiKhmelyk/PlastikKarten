<div class="field--select">
<span class="arrow"></span>
<table>
<tr><td class="td-color-cf">
  {$ajax_selectbox_mode = 0}
  {include file="frontend/card_formular/detail/groups/selectbox_object.tpl"}	
</td></tr>

{foreach from=$sConfigurator.values item=option name=config_option key=optionID}
{if $option["option_attributes"]["cf_subgroupid"]}
	{$parent_subgroup_id=$option["option_attributes"]["cf_subgroupid"]}
	{if ($parent_subgroup_id!="") && ($parent_subgroup_id!="0")}
		{if in_array($parent_subgroup_id, $subgroups)}
		  <tr class='cf_ajax_container_group_{$option.groupID}_{$option.optionID} cf_ajax_type_selectbox_sub' {if empty($sConfigurator.user_selected) || !$option.selectable || $is_disabled || !$option.selected}style="display:none"{/if}>
		    <td class="td-color-cf" ><div id="parent_subgroup_{$parent_subgroup_id}_container"></div></td>
		  </tr>
		{/if}
	{/if}
{/if}
{/foreach}
</table>
</div>