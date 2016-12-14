<div class="product--price price--default{if $sArticle.has_pseudoprice} price--discount{/if}">
                {* Default price *}
                {block name='frontend_detail_data_price_configurator'}
                    {if $sArticle.priceStartingFrom && !$sArticle.sConfigurator && $sView}
                        {* Price - Starting from *}
                        {block name='frontend_detail_data_price_configurator_starting_from_content'}
                            <span class="price--content content--starting-from">
                                {s name="DetailDataInfoFrom" namespace="frontend/detail/data"}{/s} {$sArticle.priceStartingFrom|currency} {s name="Star" namespace="frontend/listing/box_article"}{/s}
                            </span>
                        {/block}
                    {else}
                        {* Regular price *}
                        {block name='frontend_detail_data_price_default'}
                            <span class="price--content content--default">
                                <meta itemprop="price" content="{$sArticle.price|replace:',':'.'}">
                                {if $sArticle.priceStartingFrom && !$sArticle.liveshoppingData}{s name='ListingBoxArticleStartsAt' namespace="frontend/listing/box_article"}{/s} {/if}{$sArticle.price|currency} {s name="Star" namespace="frontend/listing/box_article"}{/s}
                            </span>
                        {/block}
                    {/if}
                {/block}

                {* Discount price *}
                {block name='frontend_detail_data_pseudo_price'}
                    {if $sArticle.has_pseudoprice}

                        {block name='frontend_detail_data_pseudo_price_discount_icon'}
                            <span class="price--discount-icon">
                                <i class="icon--percent2"></i>
                            </span>
                        {/block}

                        {* Discount price content *}
                        {block name='frontend_detail_data_pseudo_price_discount_content'}
                            <span class="content--discount">

                                {block name='frontend_detail_data_pseudo_price_discount_before'}
                                    {s name="priceDiscountLabel" namespace="frontend/detail/data"}{/s}
                                {/block}

                                <span class="price--line-through">{$sArticle.pseudoprice|currency} {s name="Star" namespace="frontend/listing/box_article"}{/s}</span>

                                {block name='frontend_detail_data_pseudo_price_discount_after'}
                                    {s name="priceDiscountInfo" namespace="frontend/detail/data"}{/s}
                                {/block}

                                {* Percentage discount *}
                                {block name='frontend_detail_data_pseudo_price_discount_content_percentage'}
                                    {if $sArticle.pseudopricePercent.float}
                                        <span class="price--discount-percentage">({$sArticle.pseudopricePercent.float|number}% {s name="DetailDataInfoSavePercent" namespace="frontend/detail/data"}{/s})</span>
                                    {/if}
                                {/block}
                            </span>
                        {/block}
                    {/if}
                {/block}
</div>