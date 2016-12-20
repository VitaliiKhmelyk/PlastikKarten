{extends file="frontend/detail/card_formular.tpl"}

{* Hide head *}
{block name='frontend_index_header'}{/block}

{* Hide header *}
{block name='frontend_index_navigation'}{/block}

{* hide sidebar left *}
{block name='frontend_index_content_left'}{/block}

{* Hide top *}
{block name="frontend_index_content_top"}{/block}

{* Hide crossselling *}
{block name="frontend_detail_index_tabs_cross_selling"}{/block}

{* Hide breadcrumbs *}
{block name='frontend_index_breadcrumb'}{/block}

{* Hide footer *}
{block name="frontend_index_footer"}{/block}

{* Hide additional content before the actual content starts *}
{block name="frontend_index_after_body"}{/block}

{block name="frontend_index_header_javascript"}{/block}
{block name="frontend_index_header_javascript_jquery_lib"}{/block}
{block name="frontend_index_header_javascript_jquery"}{/block}
{block name="frontend_index_no_script_message"}{/block}
{block name='frontend_index_left_last_articles'}{/block}
{block name='frontend_detail_index_header'}{/block}
{block name='frontend_detail_index_image_container'}{/block}

{block name='frontend_detail_index_buy_container_inner'}
  <div itemprop="offers" itemscope itemtype="{if $sArticle.sBlockPrices}http://schema.org/AggregateOffer{else}http://schema.org/Offer{/if}" class="buybox--inner">
     <div class="product--image-container-cf">

         {block name='frontend_detail_index_data'}
            <div class="cf_ajax_container_price">
              {if $sArticle.sBlockPrices}
                 <meta itemprop="lowPrice" content="{$lowestPrice}" />
                 <meta itemprop="highPrice" content="{$highestPrice}" />
                 <meta itemprop="offerCount" content="{$sArticle.sBlockPrices|count}" />
              {else}
                 <meta itemprop="priceCurrency" content="{$Shop->getCurrency()->getCurrency()}"/>
              {/if}
              {include file="frontend/card_formular/detail/data.tpl" sArticle=$sArticle sView=1}
            </div>  
         {/block}
         
         {block name="frontend_detail_index_your_product"}
            <div class="cf_ajax_container_product_txt">
             {if !empty($myProductInfo)}
                 <div class="table-group-cf">
                    <h4>{s name="YourProduct" namespace='CardFormular'}Your Product{/s}</h4>
                 </div>   
                 <div class="table-group-cf">
                    {$myProductInfo}
                 </div>   
              {/if}
            </div>  
         {/block}

         {block name="frontend_detail_index_buybox"}
           <div class="cf_ajax_container_buy">
            {include file="frontend/card_formular/detail/buy.tpl"}
           </div> 
         {/block}  

         <div class="cf_ajax_container_market_price">
            {block name="frontend_detail_tabs_proposals_content_inner"}
              {include file="frontend/card_formular/detail/proposals.tpl"}
            {/block}
         </div>                          

     </div>
  </div>
{/block}

