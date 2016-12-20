{if $sArticle.priceStartingFrom && !$sArticle.sConfigurator && $sView}
                        {* Price - Starting from *}
                        {block name='frontend_detail_data_price_configurator_starting_from_content'}
                            <span class="price--content content--starting-from cf_ajax_container_price_value">
                                {s name="DetailDataInfoFrom" namespace="frontend/detail/data"}{/s} {$sArticle.priceStartingFrom|currency} {s name="Star" namespace="frontend/listing/box_article"}{/s}
                            </span>
                        {/block}
{else}
                        {* Regular price *}
                        {block name='frontend_detail_data_price_default'}                           
                            <span class="price--content content--default cf_ajax_container_price_value">
                                <meta itemprop="price" content="{$sArticle.price|replace:',':'.'}">
                                {if $sArticle.priceStartingFrom && !$sArticle.liveshoppingData}{s name='ListingBoxArticleStartsAt' namespace="frontend/listing/box_article"}{/s} {/if}{$sArticle.price|currency} {s name="Star" namespace="frontend/listing/box_article"}{/s}
                            </span>
                        {/block}
{/if}