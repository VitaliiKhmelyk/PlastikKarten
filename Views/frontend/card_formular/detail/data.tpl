{extends file="parent:frontend/detail/data.tpl"}

{block name="frontend_detail_data"}
	{if !$sArticle.liveshoppingData.valid_to_ts}
	  {if $sArticle.sBlockPrices}  
	      {foreach $sArticle.sBlockPrices as $blockPrice}
	      	{if $curQuantity>=$blockPrice.from}
	      		{$sArticle.price=$blockPrice.price}
	      	{/if}
	      {/foreach}
	  {/if} 
	  {$origPrice=$sArticle.price}
	  {$origPriceFrom=$sArticle.priceStartingFrom}	  
	  {if $sArticle.priceStartingFrom && !$sArticle.sConfigurator && $sView}
   			{$curPrice=$sArticle.priceStartingFrom|currency}
   			{$sArticle.priceStartingFrom=($sArticle.priceStartingFrom|replace:',':'.')*$curQuantity}
	  {else}
	  		{$curPrice=$sArticle.price|currency}
	  		{$sArticle.price=($sArticle.price|replace:',':'.') * $curQuantity}
	  {/if}
	{/if}  

	{$smarty.block.parent}
{/block}

{if $sArticle.sBlockPrices && !$sArticle.liveshoppingData.valid_to_ts}      
    {block name="frontend_detail_data_block_price_include"}
   		{include file="frontend/card_formular/detail/data_price.tpl"}
   		<div class='product--price price--unit'>{$curQuantity} X {$curPrice}</div>
    {/block}      
{/if}

{if $sArticle.purchaseunit}
{block name='frontend_detail_data_price'}	
	 {if $sArticle.sBlockPrices && !$sArticle.liveshoppingData.valid_to_ts}{else}
	 	<div class='product--price price--unit'>{$curQuantity} X {$curPrice}</div>
	 {/if}	
	 {$smarty.block.parent}
{/block}
{else}
{block name='frontend_detail_data_tax'}
     {if $sArticle.sBlockPrices && !$sArticle.liveshoppingData.valid_to_ts}{else}
	 	<div class='product--price price--unit'>{$curQuantity} X {$curPrice}</div>
	 {/if}	
	 {$smarty.block.parent}
{/block}

{/if}