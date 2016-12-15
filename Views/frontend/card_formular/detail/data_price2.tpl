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