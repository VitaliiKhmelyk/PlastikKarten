{* Offcanvas buttons *}
{block name='frontend_detail_proposals_buttons_offcanvas'}
    <div class="buttons--off-canvas">
        {block name='frontend_detail_proposals_buttons_offcanvas_inner'}
            <a href="#" title="{"{s name="OffcanvasCloseMenu" namespace="frontend/detail/description"}{/s}"|escape}" class="close--off-canvas">
                <i class="icon--arrow-left"></i>
                {s name="OffcanvasCloseMenu" namespace="frontend/detail/description"}{/s}
            </a>
        {/block}
    </div>
{/block}

{block name="frontend_detail_proposals"}
<div class="content--proposals">

	{* Headline *}
	{block name='frontend_detail_proposals_title'}
		<div class="content--title">
			{s name="DetailProposalsHeader" namespace="CardFormular"}{/s} "{$sArticle.articleName}"
		</div>
	{/block}

	{* Description *}
	{block name='frontend_detail_proposals_text'}
        <div class="product--proposals"> 
            {if !$sArticle.liveshoppingData.valid_to_ts && $sArticle.sBlockPrices && !$sArticle.liveshoppingData.valid_to_ts}            
                {block name="frontend_detail_data_block_price_include2"}
                    {include file="frontend/detail/block_price.tpl" sArticle=$sArticle}
                {/block}
            {else}
                {s name="DetailProposalsNotAvailable" namespace="CardFormular"}Special price is not available{/s}
            {/if}
        </div>
	{/block}

</div>
{/block}
