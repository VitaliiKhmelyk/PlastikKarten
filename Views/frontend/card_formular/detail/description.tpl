{extends file="parent:frontend/detail/tabs/description.tpl"}

{block name='frontend_detail_description_properties'}{/block}
{block name='frontend_detail_description_downloads'}{/block}
{block name='frontend_detail_description_our_comment'}{/block}

{block name='frontend_detail_actions_contact'}
  <li class="list--entry">
		<a href="{$sInquiry}" rel="nofollow" class="content--link link--contact" title="{"{s name='DetailLinkContact' namespace="frontend/detail/actions"}{/s}"|escape}">
			<i class="icon--arrow-right"></i> {s name="DetailLinkContact" namespace="frontend/detail/actions"}{/s}
		</a>
 </li>
{/block}