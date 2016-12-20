{extends file="parent:frontend/detail/tabs.tpl"}

{block name="frontend_detail_tabs_content_description_description_inner"}
   {include file="frontend/card_formular/detail/description.tpl"}
{/block}


{block name="frontend_detail_tabs_navigation_inner"}	
    
  {$smarty.block.parent}

	{block name="frontend_detail_tabs_proposals"}
	 	<a href="#" class="tab--link" title="{s name='DetailTabsProposals' namespace='CardFormular'}{/s}" data-tabName="proposals">{s name='DetailTabsProposals' namespace='CardFormular'}{/s}</a>
	{/block}	

{/block}

{block name="frontend_detail_tabs_content_inner"}
	
	{$smarty.block.parent}

	{* Proposals tab *}
	{block name="frontend_detail_tabs_proposals_description"}
		<div class="tab--container">
             {block name="frontend_detail_tabs_proposals_description_inner"}

             {* proposals title *}
             {block name="frontend_detail_tabs_proposals_title"}
               <div class="tab--header">
                  {block name="frontend_detail_tabs_proposals_title_inner"}
                   <a href="#" class="tab--title" title="{s name='DetailTabsProposals' namespace='CardFormular'}{/s}">{s name='DetailTabsProposals' namespace='CardFormular'}{/s}</a>
                  {/block}
               </div>
             {/block}

             {* proposals preview *}
             {block name="frontend_detail_tabs_proposals_preview"}
               <div class="tab--preview">
                  {block name="frontend_detail_tabs_proposals_preview_inner"}
                      {s name="ProposalsPreviewText" namespace='CardFormular'}{/s}<a href="#" class="tab--link" title="{s name='PreviewTextMore' namespace='CardFormular'}{/s}">{s name='PreviewTextMore' namespace='CardFormular'}{/s}</a>
                  {/block}
              </div>
             {/block}

             {* proposals content *}
             {block name="frontend_detail_tabs_proposals_content"}
               <div class="tab--content cf_ajax_container_market_price">
                   {block name="frontend_detail_tabs_proposals_content_inner"}
                      {include file="frontend/card_formular/detail/proposals.tpl"}
                   {/block}
               </div>
             {/block}

             {/block}
        </div>	 	
	{/block}

{/block}

