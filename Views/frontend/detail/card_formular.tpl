{extends file="parent:frontend/detail/index.tpl"}

{block name='frontend_detail_index_configurator_settings'}
   {* Variable for tracking active user variant selection *}
   {$activeConfiguratorSelection = true}
   {if $sArticle.sConfigurator}
      {* If user has no selection in this group set it to false *}
      {foreach $sArticle.sConfigurator as $configuratorGroup}
         {if (!$configuratorGroup["pseudo"]) && (!$configuratorGroup.selected_value)}
            {$activeConfiguratorSelection = false}
         {/if}
     {/foreach}
   {/if}
{/block}

{block name='frontend_detail_index_image_container'}

      {$maxQuantity=$sArticle.maxpurchase}
      {if $sArticle.laststock && $sArticle.instock < $sArticle.maxpurchase}
        {$maxQuantity=$sArticle.instock}
      {/if}
      {$curQuantity=$sArticle.minpurchase}
      
      {if !($sArticle.quantity) }
        {$sArticle.quantity = $smarty.get.qty - 0}
      {/if}
      {if $sArticle.quantity && ($sArticle.quantity >= $sArticle.minpurchase) && ($sArticle.quantity <= $maxQuantity) }
        {$curQuantity=$sArticle.quantity}
      {/if}

   <table class="table-group-cf">
   <tr><td>
    <div class="product--image-container-cf">
       {$smarty.block.parent}
    </div>
    <div class="product--image-container-right-cf">
      <table class="table-group-cf">
          <tr><td>
              {block name="frontend_detail_index_tabs_cf"}
                  {include file="frontend/card_formular/detail/tabs.tpl"}
              {/block}
          </td></tr>
      </table>

      {block name='frontend_detail_description_downloads'}
        {if $sArticle.sDownloads}
            {* Downloads list *}
            {block name='frontend_detail_description_downloads_content_cf'}
             <table class="table-group-cf">          
             <tr><td>
             <div class="table-inner-cf">
                <div class="table-td-inner-cf">
                <ul class="content--list list--unstyled">
                    {foreach $sArticle.sDownloads as $download}
                        {block name='frontend_detail_description_downloads_content_link_cf'}
                            <li class="list--entry">
                                <a href="{$download.filename}" target="_blank" class="content--link link--download" title="{"{s name="DetailDescriptionLinkDownload" namespace="frontend/detail/tabs/description"}{/s}"|escape} {$download.description|escape}">
                                    <i class="icon--download"></i> {s name="DetailDescriptionLinkDownload" namespace="frontend/detail/tabs/description"}{/s} {$download.description}
                                </a>
                            </li>
                        {/block}
                    {/foreach}
                </ul>
                </div>
             </div>     
             </td></tr>
             </table>   
            {/block}
        {/if}
      {/block}

    </div>
    </td></tr>
   </table>
   {block name='frontend_detail_description_our_comment_cf'}
        {if $sArticle.attr3}
        <table class="table-group-cf">
        <tr><td>
            {block name='frontend_detail_description_our_comment_title_content_cf'}
              <div class="product--data-comments-cf">
                {$sArticle.attr3}
              </div>  
            {/block}
        </td></tr>
        </table>    
        {/if}
   {/block}   
 {/block}

 {block name='frontend_detail_index_buy_container'}
            <div class="product--buybox block product--data-container-cf">

                    {block name="frontend_detail_rich_snippets_brand"}
                        <meta itemprop="brand" content="{$sArticle.supplierName|escape}"/>
                    {/block}

                    {block name="frontend_detail_rich_snippets_weight"}
                        {if $sArticle.weight}
                            <meta itemprop="weight" content="{$sArticle.weight} kg"/>
                        {/if}
                    {/block}

                    {block name="frontend_detail_rich_snippets_height"}
                        {if $sArticle.height}
                            <meta itemprop="height" content="{$sArticle.height} cm"/>
                        {/if}
                    {/block}

                    {block name="frontend_detail_rich_snippets_width"}
                        {if $sArticle.width}
                            <meta itemprop="width" content="{$sArticle.width} cm"/>
                        {/if}
                    {/block}

                    {block name="frontend_detail_rich_snippets_depth"}
                        {if $sArticle.length}
                            <meta itemprop="depth" content="{$sArticle.length} cm"/>
                        {/if}
                    {/block}

                    {block name="frontend_detail_rich_snippets_release_date"}
                        {if $sArticle.sReleasedate}
                            <meta itemprop="releaseDate" content="{$sArticle.sReleasedate}"/>
                        {/if}
                    {/block}

                    {block name='frontend_detail_buy_laststock'}
                        {if !$sArticle.isAvailable && ($sArticle.isSelectionSpecified || !$sArticle.sConfigurator)}
                            {include file="frontend/_includes/messages.tpl" type="error" content="{s name='DetailBuyInfoNotAvailable' namespace='frontend/detail/buy'}{/s}"}
                        {/if}
                    {/block}

                    {* Product email notification *}
                    {block name="frontend_detail_index_notification"}
                        {if $sArticle.notification && $sArticle.instock <= 0 && $ShowNotification}
                            {include file="frontend/plugins/notification/index.tpl"}
                        {/if}
                    {/block}

                    

                    {* Product data *}
                    {block name='frontend_detail_index_buy_container_inner'}
                        <div itemprop="offers" itemscope itemtype="{if $sArticle.sBlockPrices}http://schema.org/AggregateOffer{else}http://schema.org/Offer{/if}" class="buybox--inner">

                            <div class="product--image-container-right-cf">
                            {* Configurator drop down menu's *}
                            {block name="frontend_detail_index_configurator"}
                                <div class="product--configurator">
                                    {if $sArticle.sConfigurator}
                                        {include file="frontend/card_formular/detail/config_options.tpl"}
                                    {/if}
                                </div>
                            {/block}
                            </div>

                            <div class="product--image-container-cf">
                            {block name='frontend_detail_index_data'}
                                {if $sArticle.sBlockPrices}
                                    {$lowestPrice=false}
                                    {$highestPrice=false}
                                    {foreach $sArticle.sBlockPrices as $blockPrice}
                                        {if $lowestPrice === false || $blockPrice.price < $lowestPrice}
                                            {$lowestPrice=$blockPrice.price}
                                        {/if}
                                        {if $highestPrice === false || $blockPrice.price > $highestPrice}
                                            {$highestPrice=$blockPrice.price}
                                        {/if}
                                    {/foreach}

                                    <meta itemprop="lowPrice" content="{$lowestPrice}" />
                                    <meta itemprop="highPrice" content="{$highestPrice}" />
                                    <meta itemprop="offerCount" content="{$sArticle.sBlockPrices|count}" />
                                {else}
                                    <meta itemprop="priceCurrency" content="{$Shop->getCurrency()->getCurrency()}"/>
                                {/if}
                                {include file="frontend/card_formular/detail/data.tpl" sArticle=$sArticle sView=1}
                            {/block}

                            {block name='frontend_detail_index_after_data'}{/block}
                            
                            {block name="frontend_detail_index_your_product"}                               
                               {$myProductInfo=""}  
                               {foreach from=$sArticle.sConfigurator item=sConfigurator name=group key=groupID}
                                 {$curOptionInfo=""}
                                 {if (!$sConfigurator["pseudo"] && !$sConfigurator["hidden"]) }
                                     {foreach from=$sConfigurator.values item=configValue name=option key=optionID}
                                        {if $configValue.selected && $sConfigurator.user_selected}                                        
                                            {$curOptionInfoName=$configValue.optionname}
                                            {$curOptionInfo=$curOptionInfo|cat:"<span>"|cat:$curOptionInfoName|cat:"; </span>"}  
                                        {/if}                                        
                                     {/foreach}  
                                 {/if}     
                                 {if !empty($curOptionInfo)}
                                    {$curOptionInfoName=$sConfigurator.groupname}
                                    {$myProductInfo=$myProductInfo|cat:"<p><span class='configurator--label'>"|cat:$curOptionInfoName|cat:": </span>"|cat:$curOptionInfo|cat:"</p>"}            
                                {/if}
                               {/foreach}   
                               {if !empty($myProductInfo)}
                                 <div class="table-group-cf">
                                    <h4>{s name="YourProduct" namespace='CardFormular'}Your Product{/s}<h4>
                                 </div>   
                                 <div class="table-group-cf">
                                    {$myProductInfo}
                                 </div>   
                               {/if}
                            {/block}

                            {* Include buy button and quantity box *}
                            {block name="frontend_detail_index_buybox"}
                                {include file="frontend/card_formular/detail/buy.tpl"}
                            {/block}                            

                            </div>

                            {* Product actions *}
                            {block name="frontend_detail_index_actions"}
                                <nav class="product--actions">
                                    {include file="frontend/detail/actions.tpl"}
                                </nav>
                            {/block}
                        </div>
                    {/block}

                    {* Product - Base information *}
                    {block name='frontend_detail_index_buy_container_base_info'}
                        <ul class="product--base-info list--unstyled">

                            {* Product SKU *}
                            {block name='frontend_detail_data_ordernumber'}
                                <li class="base-info--entry entry--sku">

                                    {* Product SKU - Label *}
                                    {block name='frontend_detail_data_ordernumber_label'}
                                        <strong class="entry--label">
                                            {s name="DetailDataId" namespace="frontend/detail/data"}{/s}
                                        </strong>
                                    {/block}

                                    {* Product SKU - Content *}
                                    {block name='frontend_detail_data_ordernumber_content'}
                                        <meta itemprop="productID" content="{$sArticle.articleDetailsID}"/>
                                        <span class="entry--content" itemprop="sku">
                                            {$sArticle.ordernumber}
                                        </span>
                                    {/block}
                                </li>
                            {/block}

                            {* Product attributes fields *}
                            {block name='frontend_detail_data_attributes'}

                                {* Product attribute 1 *}
                                {block name='frontend_detail_data_attributes_attr1'}
                                    {if $sArticle.attr1}
                                        <li class="base-info--entry entry-attribute">
                                            <strong class="entry--label">
                                                {s name="DetailAttributeField1Label" namespace="frontend/detail/index"}{/s}:
                                            </strong>

                                            <span class="entry--content">
                                                {$sArticle.attr1|escape}
                                            </span>
                                        </li>
                                    {/if}
                                {/block}

                                {* Product attribute 2 *}
                                {block name='frontend_detail_data_attributes_attr2'}
                                    {if $sArticle.attr2}
                                        <li class="base-info--entry entry-attribute">
                                            <strong class="entry--label">
                                                {s name="DetailAttributeField2Label" namespace="frontend/detail/index"}{/s}:
                                            </strong>

                                            <span class="entry--content">
                                                {$sArticle.attr2|escape}
                                            </span>
                                        </li>
                                    {/if}
                                {/block}
                            {/block}
                        </ul>
                    {/block}

                </div>  
 {/block} 

{block name="frontend_detail_index_detail"}{/block}       