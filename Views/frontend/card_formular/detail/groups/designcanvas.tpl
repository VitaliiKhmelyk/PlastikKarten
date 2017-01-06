<div class="field--select">
<span class="arrow"></span>

{$groupnameprefix="custom"}
{$colspan=3}

{$cnt=0}
{foreach from=$sConfigurator.values item=option name=config_option key=optionID}
{$cnt=$cnt+1}
{/foreach}

<table>
<tr><td class="td-color-cf">

{foreach from=$sConfigurator.values item=option name=config_option key=optionID}
<div style="float:left;">
<table>
{if $cnt>1}
<tr>
 <td class="td-color-cf" colspan="{$colspan}">
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
</tr>
{/if}
<tr>
 <td class="td-color-cf" colspan="{$colspan}">
   <div class="variant--option is--image">
 		{block name='frontend_detail_configurator_variant_group_option_canvas'}
		  <span class="image--element image--media">
		     {$media = $option.media_data.src.original}		     
			 {if isset($media)  && isset($option.media_data.res.original.width)}
			    {$w = $option.media_data.res.original.width}
		        {$h = $option.media_data.res.original.height}
				<img style="width:{$w}px;height:{$h}px;" src="{$media}" alt="{$option.optionname}" />
			 {else} 
			    <img src="{link file='frontend/_public/src/img/no-picture.jpg'}" alt="{$option.optionname}">
			 {/if}
		  </span>
		{/block}	
	</div>
	<div style="display:none">
		{block name='frontend_detail_configurator_variant_group_option_input'}
		  <input type="text"
						class="option--input"
						id="{$groupnameprefix}group[{$option.groupID}][{$option.optionID}]"
						name="{$groupnameprefix}group[{$option.groupID}][{$option.optionID}]"
						value=""
						title="{$option.optionname}"
						onchange="saveCustomParamsStatus('{$option.groupID}','{$option.optionID}')"
		  />
		{/block}	
	</div>	
 </td>
</tr>
</table>
</div>
{/foreach}

</td></tr>

{foreach from=$sArticle.sConfigurator item=sConfiguratorTmp name=groupTmp key=groupIDTmp}
   {if $sConfiguratorTmp["group_attributes"]}
	  {if $sConfiguratorTmp["group_attributes"]["cf_grouptype"]}
	 	  {$group_type_tmp = $sConfiguratorTmp["group_attributes"]["cf_grouptype"]}
	  {/if}
   {/if}
   {if !$group_type_tmp || ($group_type_tmp=="")}
	  {$group_type_tmp="SelectBox"}
   {/if}
   {if ($group_type_tmp!="DesignCanvas") && ($group_type_tmp!="Container") }
		{$is_single_val=($group_type_tmp=="SelectBox")||($group_type_tmp=="RadioBox")}
		{foreach from=$sConfiguratorTmp.values item=option name=config_option key=optionID}
		  {if (!$is_single_val)||($option["design_data_json"])}
		  	<tr class="cf_design_group_{$sConfiguratorTmp.groupID}_{$option.optionID}">
		  	<td class="td-color-cf">{$option.optionname}:</td>
            <td class="td-color-cf cf_design_mode_{$sConfiguratorTmp.groupID}_{$option.optionID}">

            </td>

            <td class="td-color-cf cf_design_actions_{$sConfiguratorTmp.groupID}_{$option.optionID}">
			{if !($option["design_data_fixed"])}
			<a href="javascript:void(0)" title="{s name='ShiftLeft' namespace='CardFormular'}{/s}"><i class="icon--arrow-left3"></i></a>	
			<a href="javascript:void(0)" title="{s name='ShiftRight' namespace='CardFormular'}{/s}"><i class="icon--arrow-right3"></i></a>
			<a href="javascript:void(0)" title="{s name='ShiftUp' namespace='CardFormular'}{/s}"><i class="icon--arrow-up2"></i></a>
			<a href="javascript:void(0)" title="{s name='ShiftDown' namespace='CardFormular'}{/s}"><i class="icon--arrow-down3"></i></a>
			<a href="javascript:void(0)" title="{s name='ResizeEnlarge' namespace='CardFormular'}{/s}"><i class="icon--resize-enlarge"></i></a>
			<a href="javascript:void(0)" title="{s name='ResizeShrink' namespace='CardFormular'}{/s}"><i class="icon--resize-shrink"></i></a>
			{/if}
            </td>
		  	</tr>
		  {/if}	
       {/foreach}
   {/if}
{/foreach}

</table>

</div>