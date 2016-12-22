<div class="cf_ajax_container_group_{$option.groupID} cf_ajax_type_radio">
{foreach from=$sConfigurator.values item=option name=config_option key=optionID}
<span class="cf_acgr_{$option.groupID}_{$option.optionID}">{if $option.selectable}1{else}0{/if},{if $is_disabled}1{else}0{/if},{if $option.selected}1{else}0{/if}</span>
{/foreach}
</div>