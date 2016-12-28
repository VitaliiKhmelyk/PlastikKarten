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
  <div itemtype="{if $sArticle.sBlockPrices}http://schema.org/AggregateOffer{else}http://schema.org/Offer{/if}" class="buybox--inner">

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
         
         {if !empty($myProductInfo)}
           <div class="cf_ajax_container_product_txt">             
              <div><h4>{s name="YourProduct" namespace='CardFormular'}Your Product{/s}<h4></div>   
              <div>{$myProductInfo}</div>                  
           </div>           
         {/if}

         <div class="cf_ajax_container_buy">
            {include file="frontend/card_formular/detail/buy.tpl"}
         </div> 

         <div class="cf_ajax_container_market_price">
              {include file="frontend/card_formular/detail/proposals.tpl"}
         </div>    

         <div class="cf_ajax_container_link1">
             {$sInquiry}
        </div>   

        <div class='cf_ajax_container_count'>     
            <input type="text" class='cf_ajax_container_count_input' min="{$sArticle.minpurchase}" max="{$maxQuantity}" value="{$curQuantity}"  
              id="qty" name="qty" class="buybox--quantity">
            {if $sArticle.packunit} {$sArticle.packunit}{/if}  
        </div>   

        {if $sArticle.attr3}
          <div class="cf_ajax_container_comments">          
            <table>
            <tr><td>
                  <div class="product--data-comments-cf">
                    {$sArticle.attr3}
                  </div>  
            </td></tr>
            </table>            
          </div>
        {/if}

        {$group_enabled = true}
        {foreach from=$sArticle.sConfigurator item=sConfigurator name=group key=groupID}
            {$group_type = ""}
            {if $sConfigurator["group_attributes"]}
              {if $sConfigurator["group_attributes"]["cf_grouptype"]}
                {$group_type = $sConfigurator["group_attributes"]["cf_grouptype"]}
              {/if}     
            {/if}      
            {if !$sConfigurator["pseudo"] && (($group_type=="RadioBox")||($group_type=="SelectBox")||($group_type==""))}
              {$is_disabled=!$group_enabled}
              {$group_enabled = false}
              {foreach from=$sConfigurator.values item=option}
                {if $option.selectable && !$is_disabled && $option.selected}
                    {$group_enabled = true}
                {/if}
              {/foreach}
              {if ($group_type=="RadioBox")}
                {include file="frontend/card_formular/detail/groups/radiobox_object.tpl"} 
              {else}
                {$ajax_selectbox_mode = 1}
                {include file="frontend/card_formular/detail/groups/selectbox_object.tpl"} 
              {/if}
            {/if}
        {/foreach}                       

  </div>
{/block}

