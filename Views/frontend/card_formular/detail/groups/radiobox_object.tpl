{$is_markup=false}
{foreach from=$sConfigurator.values item=option name=config_option key=optionID}
  {if $cf_show_markup}
    {if $cf_markups.$optionID.price_mod}
		  {$is_markup=true}
		{/if}
  {/if}
{/foreach}

<div class="cf_ajax_container_group_{$sConfigurator.groupID} cf_ajax_type_radio">
{if $is_disabled}1{else}0{/if},{if $is_markup}1{else}0{/if};
{foreach from=$sConfigurator.values item=option name=config_option key=optionID}
{$option.optionID},{if $option.selectable}1{else}0{/if},{if $option.selected && $sConfigurator.user_selected}1{else}0{/if},{if $cf_markups.$optionID.price_mod}{$cf_markups.$optionID.price_mod|currency}{/if};
{/foreach}
</div>