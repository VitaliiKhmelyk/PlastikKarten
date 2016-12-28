<div class="field--select">
<span class="arrow"></span>

{$is_media_presents=false}
{foreach from=$sConfigurator.values item=option name=config_option key=optionID}
  {if isset($option.media_data.src.original)}
    {if ($option.media_data.src.original!="")}
      {$is_media_presents=true}
    {/if}
  {/if}
{/foreach}
{$groupnameprefix="custom"}

<table>
{foreach from=$sConfigurator.values item=option name=config_option key=optionID}
{assign var=optionID value=$option.optionID}
{block name='frontend_detail_configurator_variant_group_option'}
<tr>
{$column_cnt=0}
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
				{$option.optionname}:
			{/block}
		</label>		
	{/block}	
	</div>
</td>
</tr>

<tr>
<td class="td-color-cf" colspan="{$column_cnt}">
	<div class="variant--option">
	{block name='frontend_detail_configurator_variant_group_option_input'}
		<textarea
			rows=4
			id="{$groupnameprefix}group[{$option.groupID}][{$option.optionID}]"
			name="{$groupnameprefix}group[{$option.groupID}][{$option.optionID}]"
			value=""
			title="{$option.optionname}"
			onkeyup="saveCustomParamsStatus('{$option.groupID}','{$option.optionID}')"
			oninput="saveCustomParamsStatus('{$option.groupID}','{$option.optionID}')"
			onchange="saveCustomParamsStatus('{$option.groupID}','{$option.optionID}')"
			style="width:100%;"
			>
		</textarea>	
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
{/foreach}
</table>

</div>
