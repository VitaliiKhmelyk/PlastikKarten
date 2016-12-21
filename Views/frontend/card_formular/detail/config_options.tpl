{block name='frontend_detail_configurator_error'}
	{if $sArticle.sError && $sArticle.sError.variantNotAvailable}
		{include file="frontend/_includes/messages.tpl" type="error" content="{s name='VariantAreNotAvailable' namespace='frontend/detail/config_step'}{/s}"}
	{/if}
{/block}

<form method="post" action="{url sArticle=$sArticle.articleID sCategory=$sArticle.categoryID}" class="configurator--form selection--form">
	<div class="variant--group group-option-cf group-option-common-cf">
		<p class="configurator--label">{s name='Quantity' namespace='CardFormular'}{/s}:</p>
		<table>
        <tr><td>
		<div class="field--select">
			<span class="arrow"></span>
			<span class='cf_ajax_container_count'>			
				<input type="text" class='cf_ajax_container_count_input' min="{$sArticle.minpurchase}" max="{$maxQuantity}" value="{$curQuantity}"  
					id="qty" name="qty" class="buybox--quantity">
				{if $sArticle.packunit} {$sArticle.packunit}{/if}</span> 
			&nbsp; &nbsp; 			
			<a href="javascript:void(0)" class="call_ajax_repaint" title="{s name='RecalculatePrice' namespace='CardFormular'}{/s}" data-ajax-variants="true"><i class="icon--cycle"></i></a>			
		</div>	
		</td></tr>
		</table>
		<div class="product--data-spacer-cf"></div>
	</div>	

	{block name='frontend_detail_workflow'}
        {$workflow_length=count($sArticle.sWorkflow)}
        <input type="hidden" id="workflow_stage_cnt_" value="{$workflow_length}">  
        {if $workflow_length > 1}
	     <div>
	        <table style="width:auto">
	        <tr>
	          {$cnt=0}
	          {foreach from=$sArticle.sWorkflow item=sWorkflow}            
	            <td style="padding:0 10px 20px 0">
	            <a href="javascript:void(0)" 
	               id="workflow_stage_cnt_{$cnt}"
	               class="block btn is--secondary is--icon-right is--center is--small" 
	               title='{if $sWorkflow=="default"}{s name="DefaultWorkflow" namespace="CardFormular"}General{/s}{else}{$sWorkflow}{/if}'
	               onclick="setWorkflowStage({$cnt});">
	            	{if $sWorkflow=="default"}{s name="DefaultWorkflow" namespace='CardFormular'}General{/s}{else}{$sWorkflow}{/if}
	            	<i class="icon--arrow-down3"></i>
	            </a>
	            </td>
	            {$cnt=$cnt+1}
	          {/foreach}
	        </tr>
	        </table>  
	   </div>       
        {/if}
   {/block}

	{$subgroups = array()}
	{foreach from=$sArticle.sConfigurator item=sConfigurator name=group key=groupID}
		{if $sConfigurator["group_attributes"]["cf_subgroupid"]}
		  {$group_parentid = $sConfigurator["group_attributes"]["cf_subgroupid"]}
		  {if ($group_parentid!="") && ($group_parentid!="0")}
			  {if !(in_array($group_parentid, $subgroups))}
				  {$parent_found=false}			  
				  {foreach from=$sArticle.sConfigurator item=sConfiguratorTmp}
				      {if !($parent_found)}
						  {if $sConfigurator.groupname!=$sConfiguratorTmp.groupname}
							  {foreach from=$sConfiguratorTmp.values item=option name=config_option key=optionID}
								{if $option["option_attributes"]["cf_subgroupid"]}
									{if $option["option_attributes"]["cf_subgroupid"]==$group_parentid}
									  {$parent_found=true}
									{/if}
								{/if}
							  {/foreach}
						  {/if}
					  {/if}
				  {/foreach}
				  {if $parent_found}
					 {$subgroups[] = $group_parentid}
				  {/if}
			  {/if}
		  {/if}
		{/if}
	{/foreach}	

	{$group_enabled = true}
	{foreach from=$sArticle.sConfigurator item=sConfigurator name=group key=groupID}
		{$group_type = ""}
		{$group_info = ""}
		{$group_parentid = ""}
		{$group_workflow = "default"}
		{$group_workflow_idx = ""} 
		{$is_subgroup = false}
		{if $sConfigurator["group_attributes"]}
			{if $sConfigurator["group_attributes"]["cf_grouptype"]}
				{$group_type = $sConfigurator["group_attributes"]["cf_grouptype"]}
			{/if}
			{if $sConfigurator["group_attributes"]["cf_groupinfo"]}
				{$group_info = $sConfigurator["group_attributes"]["cf_groupinfo"]}
			{/if}
			{if $sConfigurator["group_attributes"]["cf_subgroupid"]}
				{$group_parentid = $sConfigurator["group_attributes"]["cf_subgroupid"]}
				{if ($group_parentid!="") && ($group_parentid!="0")}
					{if in_array($group_parentid, $subgroups)}
					  {$is_subgroup = true}				  
					{/if}
				{/if}
			{/if}
			{if ($sConfigurator["group_attributes"]["cf_workflowlabel"]) && ($sConfigurator["group_attributes"]["cf_workflowlabel"]!="")}
				{$group_workflow = $sConfigurator["group_attributes"]["cf_workflowlabel"]}
			{/if}
		{/if}
		{$cnt=0}		
		{foreach from=$sArticle.sWorkflow item=sWorkflow}
		  {if $group_workflow==$sWorkflow}
		    {$group_workflow_idx=$cnt}
		  {/if} 	
		  {$cnt=$cnt+1}	
		{/foreach}
		
		{if $sConfigurator["hidden"]}<div style="display:none;">{/if} 
		{if $is_subgroup}
			<div class="child_subgroup_{$group_parentid}_container" style="display:none;">
		{else}
			<div class="variant--group group-option-cf group-option-common-cf workflow_stage_{$group_workflow_idx}" {if count($sArticle.sWorkflow)>1}style="display:none;"{/if}>
		{/if}
			{* Group name *}
			{block name='frontend_detail_group_name'}
				{if $is_subgroup}
					{if $group_info!=""}
					  <div>{$group_info}</div>
					{else}
					  <span class="configurator--label">{$sConfigurator.groupname}:{$sConfigurator.position}</span>
					{/if}
				{else}
					<p class="configurator--label">{$sConfigurator.groupname}:</p>
				{/if}
			{/block}
            
		
			{if !$sConfigurator["pseudo"] && (($group_type=="RadioBox")||($group_type=="SelectBox")||($group_type==""))}
				{$is_disabled=!$group_enabled}
				{$group_enabled = false}
				{foreach from=$sConfigurator.values item=option}
				  {if $option.selectable && !$is_disabled && $option.selected}
				      {$group_enabled = true}
				  {/if}
				{/foreach}
			{else}
				 {$is_disabled=false}
			{/if}

			{if ($group_type=="RadioBox")}
  			   {block name='frontend_detail_group_radiobox'}
				 {include file="frontend/card_formular/detail/groups/radiobox.tpl"}	
			   {/block}
			{else} {if ($group_type=="TextFields")}			  
			   {block name='frontend_detail_group_textfields'}
				{include file="frontend/card_formular/detail/groups/textbox.tpl"}	
			   {/block}
			{else} {if ($group_type=="TextArea")}			  
			   {block name='frontend_detail_group_textarea'}
				{include file="frontend/card_formular/detail/groups/textbox.tpl"}	
			   {/block}   
			{else} {if ($group_type=="FrontBackSide")}
			   {block name='frontend_detail_group_sidebox'}
				{include file="frontend/card_formular/detail/groups/sidebox.tpl"}	
			   {/block}
			{else} {if ($group_type=="Container")}
			   {block name='frontend_detail_group_container'}
				{include file="frontend/card_formular/detail/groups/container.tpl"}	
			   {/block}   
			{else} {if ($group_type=="Upload")}
			   {block name='frontend_detail_group_container'}
				{include file="frontend/card_formular/detail/groups/upload.tpl"}	
			   {/block}     
			{else}
			  {block name='frontend_detail_group_selection'}
				{include file="frontend/card_formular/detail/groups/selectbox.tpl"}	
			  {/block}
			{/if}{/if}{/if}{/if}{/if}{/if}

			{if !($is_subgroup)}
				{block name='frontend_detail_group_radio_info'}					
					{include file="frontend/card_formular/detail/groups/infolink.tpl"}					
				{/block}	
			{/if}
		</div>
		{if $sConfigurator["hidden"]}</div>{/if} 	
	{/foreach}	

	{block name='frontend_detail_configurator_noscript_action'}
		<noscript>
			<input name="recalc" type="submit" value="{s name='DetailConfigActionSubmit' namespace='frontend/detail/config_step'}{/s}" />
		</noscript>
	{/block}

	{block name='frontend_detail_configurator_subgroup_script_action'}
  	<script>
	   var aSubGroupsArray = [{$cnt=0}{foreach from=$subgroups item=sI}{if $cnt>0},{/if}"{$sI}"{/foreach}];
       var aGroupsDataArray = [
       	    {$cnt=0}
         	{foreach from=$sArticle.sConfigurator item=sConfigurator}
         	  {if $cnt>0},{/if}[{$sConfigurator.groupID},'',[{$cnt2=0}{foreach from=$sConfigurator.values item=option}{if $cnt2>0},{/if}[{$option.optionID},'']{$cnt2=$cnt2+1}{/foreach}],{if $sConfigurator['pseudo']}true{else}false{/if}]         	          	  
         	  {$cnt=$cnt+1}		
         	{/foreach}	       	    
       	];
       	var isCardFormular = {if $sArticle.isCF}true{else}false{/if};
	</script>
	{/block}

</form>

