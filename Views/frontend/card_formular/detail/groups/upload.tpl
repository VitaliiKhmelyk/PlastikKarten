<div class="field--select">
<span class="arrow"></span>

<table>
{foreach from=$sConfigurator.values item=option name=config_option key=optionID}
{assign var=optionID value=$option.optionID}
{block name='frontend_detail_configurator_variant_group_option'}
<tr>
<td class="td-color-cf">
	<div class="variant--option">
	{block name='frontend_detail_configurator_variant_group_option_input'}
		<!--div class="js--modal sizing--content" style="width: 600px; height: auto; display: block; opacity: 1;">
			<div class="header">
				<div class="title">
					{$option.optionname}
				</div>
			</div>
			<div class="content">
				Modal box content
			</div>
			<div class="btn icon--cross is--small btn--grey modal--close">
			</div>
		</div>

		<input type="button"
			class="option--input"
			id="customgroup[{$option.groupID}][{$option.optionID}]"
			name="customgroup[{$option.groupID}][{$option.optionID}]"
			value=""
			title="{$option.optionname}"
			data-modalbox="true"
			/-->
		<p class="modal--size-table" data-content="" data-modalbox="true" data-targetSelector="a" data-width="400" data-height="400" data-mode="ajax">
			<a class="block btn is--secondary is--center is--large" href="{url controller=CardFormularUpload action=modal sOption=$option.optionID}">{$option.optionname}</a>
		</p>
	{/block}						
	</div>
</td>
</tr>
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
