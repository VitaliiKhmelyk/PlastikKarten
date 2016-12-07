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
            Something will be here
        </div>
	{/block}

</div>
{/block}
