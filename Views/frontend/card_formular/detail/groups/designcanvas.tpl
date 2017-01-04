<div class="field--select">
<span class="arrow"></span>

{$groupnameprefix="custom"}


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
 <td class="td-color-cf" >
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
{/if}
<tr>
 <td class="td-color-cf" >
   <div class="variant--option is--image">
 		{block name='frontend_detail_configurator_variant_group_option_canvas'}
		  <span class="image--element image--media">
		     {$media = $option.media_data.src.original}
		     {$w = $option.media_data.res.original.width}
		     {$h = $option.media_data.res.original.height}
			 {if isset($media)}
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
<tr><td class="td-color-cf">

<div>

</div>

</td></tr>
</table>

</div>