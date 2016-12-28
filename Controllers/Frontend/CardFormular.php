<?php

class Shopware_Controllers_Frontend_CardFormular extends Enlight_Class
{
  private $plugin;

  public function init($plugin)
  {
//print_r('<pre>Front: ');print_r(__METHOD__);print_r('</pre>');
    $this->plugin = $plugin;
  }

	public function getOrderSendMailFilterVariables(Enlight_Event_EventArgs $arguments) {
error_log('function: ');
error_log(__METHOD__);
		$variables = $arguments->getReturn();

/*		$isHtml = Shopware()->Db()->fetchOne('SELECT `ishtml` FROM `s_core_config_mails` WHERE `name` = "sORDER"');

		if(count($variables['sOrderDetails'])) {
			$appendedOrderDetails = array();
			foreach($variables['sOrderDetails'] as $product) {
				$appendProduct = $product;

				$searchArticle = Shopware()->Modules()->Articles()->sGetArticleById($product['articleID']);

				$resultText = $appendProduct['attributes']['createyourownone_text'];
				$getText = $resultText;
				$getText = trim(str_replace('_', ' ', $getText));
				$resultBorder = $appendProduct['attributes']['createyourownone_border'];
				$getBorder = $resultBorder;
				$checkPreselectedText = $this->getPreselectedText($searchArticle, $getText);

				if($getText && !$checkPreselectedText) {
					if($isHtml) {
						$name = $appendProduct['articlename'];
						$name .= '<br />'.Shopware()->Snippets()->getNamespace('frontend/createyourownone/detail')->get('sWish').': '.$getText;
						if($getBorder) {
							$name .= '<br />'.Shopware()->Snippets()->getNamespace('frontend/createyourownone/detail')->get('sBorder').': '.$getBorder;
						}
					} else {
						$name = $appendProduct['articlename'];
						$name .= "\n\t".Shopware()->Snippets()->getNamespace('frontend/createyourownone/detail')->get('sWish').': '.$getText;
						if($getBorder) {
							$name .= "\n\t".Shopware()->Snippets()->getNamespace('frontend/createyourownone/detail')->get('sBorder').': '.$getBorder;
						}
					}
					$appendProduct['articlename'] = $name;
				}

				$appendedOrderDetails[] = $appendProduct;
			}

			$variables['sOrderDetails'] = $appendedOrderDetails;
		}*/

		return $variables;
	}

	public function afterBackendOrderGetListAction(Enlight_Event_EventArgs $arguments) {
error_log('function: ');
error_log(__METHOD__);
		$controller = $arguments->getSubject();
		$view = $controller->View();
        $request = Shopware()->Container()->get('front')->Request();

		$data = $view->getAssign('data');

		if(count($data)) {
			foreach($data as &$orderData) {
				if(count($orderData['details'])) {
					foreach($orderData['details'] as &$detailData) {
						$orderDetailId = $detailData['id'];
						$articleNumber = $detailData['articleNumber'];

        $detailData['cf_pseudo_show'] = 0;
        $pseudoStyle = $this->createPseudoStyle($orderDetailId, 'order', true);
        if($pseudoStyle) {
	      $detailData['articlename'] = $detailData['articlename'] . ' ' . $pseudoStyle['articlename'];
	      if($pseudoStyle['cf_pseudo_show']) {
            $detailData['cf_pseudo_show'] = 1;
            for($i = 0; $i < count($pseudoStyle['cf_pseudo_data']); $i++) {
			  $detailData['articleName'] .= '<br />'.$pseudoStyle['cf_pseudo_data'][$i];
			}
	      }
        }
/*						$result = Shopware()->Db()->fetchOne(
							'SELECT createyourownone_text
								FROM s_order_details_attributes AS d
								WHERE d.detailID = '.$orderDetailId
						);

						$getText = $result;

						$id = Shopware()->Db()->fetchOne(
							'SELECT articleID
								FROM s_order_details AS d
								WHERE d.id = '.$orderDetailId
						);

						$builder = Shopware()->Models()->createQueryBuilder();
						$builder->select([
							'article',
							'articleDetail',
							'articleConfiguratorOptions',
							'mainDetail',
							'mainDetailPrices',
							'tax',
							'propertyValues',
							'configuratorOptions',
							'supplier',
							'priceCustomGroup',
							'mainDetailAttribute',
							'propertyGroup',
							'customerGroups'
						])
						->from('Shopware\Models\Article\Article', 'article')
						->leftJoin('article.details', 'articleDetail')
						->leftJoin('articleDetail.configuratorOptions', 'articleConfiguratorOptions')
						->leftJoin('article.mainDetail', 'mainDetail')
						->leftJoin('mainDetail.prices', 'mainDetailPrices')
						->leftJoin('mainDetailPrices.customerGroup', 'priceCustomGroup')
						->leftJoin('article.tax', 'tax')
						->leftJoin('article.propertyValues', 'propertyValues')
						->leftJoin('article.supplier', 'supplier')
						->leftJoin('mainDetail.attribute', 'mainDetailAttribute')
						->leftJoin('mainDetail.configuratorOptions', 'configuratorOptions')
						->leftJoin('article.propertyGroup', 'propertyGroup')
						->leftJoin('article.customerGroups', 'customerGroups')
						->where('articleDetail.number = ?1')
						->setParameter(1, $articleNumber);

						$article = $builder->getQuery()->getOneOrNullResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);

						if(!$this->getBackendPreselectedText($article, $getText)) {
							$result = Shopware()->Db()->fetchOne(
								'SELECT createyourownone_border
									FROM s_order_details_attributes AS d
									WHERE d.detailID = '.$orderDetailId
							);
							$getBorder = $result;
							if($getText) {
								$detailData['articleName'] .= '<br />'.Shopware()->Snippets()->getNamespace('frontend/createyourownone/detail')->get('sWish').': ';
								if(strlen($getText) > 4) {
									$detailData['articleName'] .= '<br />'.substr($getText, 0, 7);
									$detailData['articleName'] .= '<br />'.substr($getText, 7, 10);
									$detailData['articleName'] .= '<br />'.substr($getText, 17, 7);
								} else {
									$detailData['articleName'] .= $getText;
								}
							}
							if($getBorder) {
								$detailData['articleName'] .= '<br />'.Shopware()->Snippets()->getNamespace('frontend/createyourownone/detail')->get('sBorder').': '.$getBorder;
							}
						}*/
					}
				}
			}
		}

        $view->assign('data', $data);
	}

  public function getOpenOrderDataFilterResult(Enlight_Event_EventArgs $arguments) {
error_log('function: ');
error_log(__METHOD__);
    $orderData = $arguments->getReturn();

    for($i = 0; $i < count($orderData); $i++) {
      for($j = 0; $j < count($orderData[$i]['details']); $j++) {
        $orderDetailId = $orderData[$i]['details'][$j]['id'];
        $orderData[$i]['details'][$j]['cf_pseudo_show'] = 0;
        $pseudoStyle = $this->createPseudoStyle($orderNumber, 'order', true);
        if($pseudoStyle) {
	      $orderData[$i]['details'][$j]['articlename'] = $article['articlename'] . ' ' . $pseudoStyle['articlename'];
	      if($pseudoStyle['cf_pseudo_show']) {
            $orderData[$i]['details'][$j]['cf_pseudo_show'] = 1;
            $orderData[$i]['details'][$j]['cf_pseudo_data'] = $pseudoStyle['cf_pseudo_array'];
	      }
        }
      }
    }
error_log('orderData');
ob_start();
print_r($orderData);
$contents = ob_get_contents();
ob_end_clean();
error_log($contents);


    return $orderData;
  }


  public function getMarkupPrice($article, $configuratorSetId)
  {
    $baseArticle = Shopware()->Modules()->Articles()->sGetArticleById($article['articleID']);

    $pricegroup = $article['pricegroup'];
    $tax = $article['tax'];
    $sql = 'SELECT tax, taxinput FROM s_core_customergroups WHERE groupkey = "'.$pricegroup.'"';
    $checkForTaxShow = Shopware()->Db()->fetchRow($sql);

    $prices = array();
    $markups = array();
    if($configuratorSetId) {
      $sql = 'SELECT `options`, `variation`, `is_gross` FROM `s_article_configurator_price_variations` WHERE `configurator_set_id` = '.$configuratorSetId;
      $prices = Shopware()->Db()->fetchAll($sql);

      $selected = $this->getSelectedOptions($article);
      $currentAppendPrice = $this->getAppendPrice($prices, $selected);
      for($i = 0; $i < count($baseArticle['sConfigurator']); $i++) {
        for($j = 0; $j < count($selected); $j++) {
          if(isset($baseArticle['sConfigurator'][$i]['values'][$selected[$j]])) {
            foreach($baseArticle['sConfigurator'][$i]['values'] as $option) {
              $temporary = array_diff($selected, array($selected[$j]));
              $temporary[] = $option['optionID'];
              $variantAppendPrice = $this->getAppendPrice($prices, $temporary) - $currentAppendPrice;
              if($checkForTaxShow['taxinput']) {
                $variantAppendPrice = $variantAppendPrice * (100 + $tax) / 100;
              }
            
              $colorClass = 'mod-upper';
              if($variantAppendPrice < 0) {
                $colorClass = 'mod-minify';
              }
              if($variantAppendPrice) {
                $markups[$option['optionID']] = array(
                  'price_color_class'   => $colorClass,
                  'price_mod'       => $variantAppendPrice,
                );
              }
            }
          }
        }
      }
    }
    return $markups;
  }

  public function afterCheckBasketQuantities(Enlight_Event_EventArgs $arguments)
  {
    $basket = $arguments->getReturn();

    $orderNumbers = array_keys($basket['articles']);
    $find = array();
    if(count($orderNumbers)) {
      foreach($orderNumbers as $number) {
        $result = Shopware()->Db()->fetchRow(
          'SELECT d.instock, d.ordernumber, d.active, a.laststock
            FROM s_articles_details AS d
            LEFT JOIN s_articles AS a ON a.id = d.articleID
            WHERE d.ordernumber IN ("'.implode('", "', explode('_', $number)).'")'
        );
        $find[$number] = $result;
      }
    }

    if(count($find)) {
      $result = Shopware()->Db()->fetchAll(
        'SELECT b.quantity as stock, b.ordernumber,
          a.laststock, IF(a.active=1, d.active, 0) as active
        FROM s_order_basket b
        LEFT JOIN s_articles_details d
          ON d.ordernumber = b.ordernumber
          AND d.articleID = b.articleID
        LEFT JOIN s_articles a
          ON a.id = d.articleID
        WHERE b.sessionID = "'.Shopware()->Session()->get('sessionId').'"
          AND b.modus = 0
        GROUP BY b.ordernumber',
        array(Shopware()->Session()->get('sessionId'))
      );
      $hideBasket = false;
      $articles = array();
      foreach($result as $article) {
        if(empty($article['active']) && $find[$article['active']] == 0) {
        }

        if(!empty($find[$article['ordernumber']]['laststock']) && (($find[$article['ordernumber']]['instock'] - $article['stock']) < 0)) {
        }

        if(empty($find[$article['ordernumber']]['active'])
        || (!empty($find[$article['ordernumber']]['laststock']) && (($find[$article['ordernumber']]['instock'] - $article['stock']) < 0))) {
          $hideBasket = true;
          $articles[$article['ordernumber']]['OutOfStock'] = true;
        } else {
          $articles[$article['ordernumber']]['OutOfStock'] = false;
        }
      }
      $basket = array('hideBasket' => $hideBasket, 'articles' => $articles);
    }

    return $basket;
  }

  public function getBasketFilterItemEnd(Enlight_Event_EventArgs $arguments)
  {
    $article = $arguments->getReturn();

    $additionalDetails = $this->getBasketAdditionalDetails(array($article['ordernumber']));
    $article['additional_details'] = $additionalDetails[$article['ordernumber']];

    return $article;
  }

  public function createPseudoStyle($orderNumber, $table, $html = false)
  {
error_log('function: ');
error_log(__METHOD__);
    $pseudoStyle = array();
    if($table == 'basket') {
	  $sql = 'SELECT `cf_cardformular` FROM `s_order_basket_attributes`
        LEFT JOIN `s_order_basket` ON `s_order_basket`.`id` = `s_order_basket_attributes`.`basketID`
        WHERE `s_order_basket`.`ordernumber` = "'.$orderNumber.'"';
      $getPseudo = json_decode(Shopware()->Db()->fetchOne($sql), true);
    } elseif($table == 'order') {
	  $sql = 'SELECT `cf_cardformular`
          FROM s_order_details_attributes AS d
          WHERE d.detailID = '.$orderNumber;
error_log('sql');
ob_start();
print_r($sql);
$contents = ob_get_contents();
ob_end_clean();
error_log($contents);

      $getPseudo = json_decode(Shopware()->Db()->fetchOne($sql), true);
	} else {
      return false;
	}

    $articleId = $this->getIdByNumber($orderNumber);
    list($code, $append) = explode('_', $orderNumber);
    if($table == 'basket') {
      $originalArticle = Shopware()->Modules()->Articles()->sGetArticleById($articleId, null, $code);
    } elseif($table == 'order') {
      $builder = Shopware()->Models()->createQueryBuilder();
      $builder->select([
        'article',
        'articleDetail',
        'articleConfiguratorOptions',
        'mainDetail',
        'mainDetailPrices',
        'tax',
        'propertyValues',
        'configuratorOptions',
        'supplier',
        'priceCustomGroup',
        'mainDetailAttribute',
        'propertyGroup',
        'customerGroups'
      ])
      ->from('Shopware\Models\Article\Article', 'article')
      ->leftJoin('article.details', 'articleDetail')
      ->leftJoin('articleDetail.configuratorOptions', 'articleConfiguratorOptions')
      ->leftJoin('article.mainDetail', 'mainDetail')
      ->leftJoin('mainDetail.prices', 'mainDetailPrices')
      ->leftJoin('mainDetailPrices.customerGroup', 'priceCustomGroup')
      ->leftJoin('article.tax', 'tax')
      ->leftJoin('article.propertyValues', 'propertyValues')
      ->leftJoin('article.supplier', 'supplier')
      ->leftJoin('mainDetail.attribute', 'mainDetailAttribute')
      ->leftJoin('mainDetail.configuratorOptions', 'configuratorOptions')
      ->leftJoin('article.propertyGroup', 'propertyGroup')
      ->leftJoin('article.customerGroups', 'customerGroups')
      ->where('articleDetail.number = ?1')
      ->setParameter(1, $articleNumber);

      $originalArticle = $builder->getQuery()->getOneOrNullResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);
    }
    $appendNameArray = array();
    $appendPseudoArray = array();
    if(count($originalArticle['sConfigurator']) && $originalArticle['template'] == 'card_formular.tpl') {
      foreach($originalArticle['sConfigurator'] as $option) {
        if(isset($option['pseudo']) && $option['pseudo']) {
          $pseudoType = $option['group_attributes']['cf_grouptype'];
          $optionName = $option['groupname'];
          switch($pseudoType) {
            case 'SelectBox':
            case 'RadioBox':
              $optionValue = $option['values'][$getPseudo[$option['groupID']]]['optionname'];
              $appendPseudoArray[] = '<strong>'.$optionName.'</strong> '.$optionValue;
              break;
            case 'TextFields':
            case 'TextArea':
              $optionValue = strip_tags($getPseudo[$option['groupID']]);
              if(strlen($optionValue) > $config['max_string_length']) {
                $optionValue = substr($optionValue, 0, $config['max_string_length']) . '&hellip;';
              }
              $appendPseudoArray[] = '<strong>'.$optionName.'</strong> '.$optionValue;
              break;
            case 'FrontBackSide':
              $optionValue = '';
              break;
            case 'Upload':
              $optionValue = '';
              break;
            case 'Container':
              $optionValue = '';
              break;
            default:
              $optionValue = '';
              break;
          }
        } else {
          if(count($option['values'])) {
            foreach($option['values'] as $value) {
              if($value['user_selected']) {
                $appendNameArray[] = $value['optionname'];
                break;
              }
            }
          }
        }
      }
    }
    if(count($appendNameArray)) {
      $pseudoStyle['articlename'] = implode(' ', $appendNameArray);
    }
    if(count($appendPseudoArray)) {
      $pseudoStyle['cf_pseudo_show'] = 1;
      $pseudoStyle['cf_pseudo_data'] = $appendPseudoArray;
    }

    return $pseudoStyle;
  }

  public function getBasketFilterItemStart(Enlight_Event_EventArgs $arguments)
  {
    $article = $arguments->getReturn();
    $config = $this->plugin->Config()->toArray();

    $orderNumber = $article['ordernumber'];

	$pseudoStyle = $this->createPseudoStyle($orderNumber, 'basket', true);
	if($pseudoStyle) {
	  $article['articlename'] = $article['articlename'] . ' ' . $pseudoStyle['articlename'];
	  if($pseudoStyle['cf_pseudo_show']) {
        $article['cf_pseudo_show'] = 1;
        $article['cf_pseudo_data'] = $pseudoStyle['cf_pseudo_data'];
	  }
    }

    $find = explode('_', $orderNumber);

    $remake = array_shift($find);

    $article['ordernumber'] = $remake;

    $article['image'] = Shopware()->Modules()->Articles()->sGetArticlePictures(
      $article['articleID'],
      true,
      Shopware()->Config()->get('sTHUMBBASKET'),
      $remake
    );

    return $article;
  }

  public function getPriceForUpdateArticleFilterPrice(Enlight_Event_EventArgs $arguments)
  {
    $queryNewPrice = $arguments->getReturn();
    $id = $arguments->getId();
    $quantity = $arguments->getQuantity();

    $request = Shopware()->Container()->get('front')->Request();
    $action = $request->getParam('action', '');
    $params = $request->getParams();

    $sql = 'SELECT ob.ordernumber FROM s_order_basket AS ob
      WHERE ob.id = '.$id;
    $sAdd = Shopware()->Db()->fetchOne($sql);

    $articleId = $this->getIdByNumber($sAdd);
    if(!$articleId) {
      return $queryNewPrice;
    }
    list($code, $append) = explode('_', $sAdd);
    $article = Shopware()->Modules()->Articles()->sGetArticleById($articleId, null, $code);

    $articledetailsId = $this->getDetailIdByNumber($sAdd);

    $sql = 'SELECT `cf_cardformular` FROM `s_order_basket_attributes` WHERE `basketID` = '.$id;
    $getCardFormular = Shopware()->Db()->fetchOne($sql);//die;

    if($getCardFormular) {
      $checkPseudo = $this->checkPseudoAttributes($article, json_decode($getCardFormular, true));
      if($checkPseudo) {
        $queryNewPrice = $this->getConfigPrice($article, $quantity, $id);
      }
    } else {
      $checkPseudo = $this->checkPseudoAttributes($article, $params['customgroup']);
      if($action == 'ajaxAddArticleCart') {
        if($checkPseudo) {
          $cardFormular = json_encode($params['customgroup']);
          $sql = 'UPDATE `s_order_basket_attributes` SET `cf_cardformular` = '.Shopware()->Db()->quote($cardFormular).' WHERE `basketID` = '.$id;
          Shopware()->Db()->query($sql);

          $queryNewPrice = Shopware()->Db()->fetchRow(
            'SELECT price, s_core_tax.tax AS tax
            FROM s_articles_prices, s_core_tax
            WHERE s_articles_prices.pricegroup = ?
            AND s_articles_prices.articledetailsID = ?
            AND s_core_tax.id = ?',
            array(
              Shopware()->Modules()->Articles()->sSYSTEM->sUSERGROUP,
              $articledetailsId,
              $article['taxID'],
            )
          ) ? : array();

          $queryNewPrice = $this->getConfigPrice($article, $quantity, $id);
        }
      }
    }

    return $queryNewPrice;
  }

  public function getArticleForAddArticleFilterArticle(Enlight_Event_EventArgs $arguments)
  {
    $article = $arguments->getReturn();

    $request = Shopware()->Container()->get('front')->Request();
    $params = $request->getParams();

    $sConfigurator = Shopware()->Modules()->Articles()->sGetArticleById($article['articleID']);
    $sOriginal = Shopware()->Modules()->Articles()->sGetArticleById($article['articleID'], null, $article['ordernumber']);

    $checkPseudo = $this->checkPseudoAttributes($sConfigurator, $params['customgroup']);
    if($checkPseudo == 0) {
      return $article;
    }

    if(isset($params['customgroup'])) {
      $cardFormular = json_encode($params['customgroup']);
      $orderNumber = $article['ordernumber'].'_'.md5($cardFormular);

// непонятно, нужно ли будет менять название продукта, пока закрою этот блок
/*    if(count($sOriginal['sConfigurator'])) {
      for($i = 0; $i < count($sOriginal['sConfigurator']); $i++) {
        if(isset($sOriginal['sConfigurator'][$i]['pseudo']) && ($sOriginal['sConfigurator'][$i]['pseudo'] == 1)) {
          foreach($sOriginal['sConfigurator'][$i]['values'] as $value) {
            if($params['customgroup'][$sOriginal['sConfigurator'][$i]['groupID']] == $value['optionID']) {
              $article['articleName'] .= ' '.$value['optionname'];
            }
          }
        }
      }
    }*/

      $article['ordernumber'] = $orderNumber;
      $article['configurator_set_id'] = 0;
    }

    return $article;
  }

  public function getConfigPrice($article, $quantity, $basketId)
  {
    $id = $basketId;

    // Price groups
    if($article['pricegroupActive']) {
      $quantitySQL = 'AND s_articles_prices.from = 1 LIMIT 1';
    } else {
      $quantitySQL = Shopware()->Db()->quoteInto(
        ' AND s_articles_prices.from <= ? AND (s_articles_prices.to >= ? OR s_articles_prices.to = 0)',
        $quantity
      );
    }

    $sql = 'SELECT *
      FROM s_order_basket
      WHERE s_order_basket.id = '.$id.'
    ';
    $queryBasket = Shopware()->Db()->fetchRow($sql);

    $find = explode('_', $queryBasket['ordernumber']);
    $ordernumber = array_shift($find);

    // Get the order number
    $sql = 'SELECT s_articles_prices.price AS price, taxID, s_core_tax.tax AS tax,
      tax_rate, s_articles_details.id AS articleDetailsID, s_articles_details.articleID,
      s_order_basket.config, s_order_basket.ordernumber
    FROM s_articles_details, s_articles_prices, s_order_basket,
      s_articles, s_core_tax
    WHERE s_order_basket.id = '.$id.' AND s_order_basket.sessionID = "'.Shopware()->Session()->get('sessionId').'"
      AND s_articles_details.ordernumber = "'.$ordernumber.'"
      AND s_articles_details.id=s_articles_prices.articledetailsID
      AND s_articles_details.articleID = s_articles.id
      AND s_articles.taxID = s_core_tax.id
      AND s_articles_prices.pricegroup = "'.Shopware()->Modules()->Articles()->sSYSTEM->sUSERGROUP.'"';

    $queryNewPrice = Shopware()->Db()->fetchRow(
      $sql . ' ' . $quantitySQL,
      array(
        $id,
        Shopware()->Session()->get('sessionId'),
        Shopware()->Modules()->Articles()->sSYSTEM->sUSERGROUP
      )
    ) ? : array();

    // Load prices from default group if article prices are not defined
    if(!$queryNewPrice['price']) {
      $sql = 'SELECT *
        FROM s_order_basket
        WHERE s_order_basket.id = '.$id.'
      ';
      $queryBasket = Shopware()->Db()->fetchRow($sql);
    
      // In the case no price is available for this customer group, use price of default customer group
      $sql = 'SELECT s_articles_prices.price AS price, taxID, s_core_tax.tax AS tax,
        s_articles_details.id AS articleDetailsID, s_articles_details.articleID,
        s_order_basket.config, s_order_basket.ordernumber
      FROM s_articles_details, s_articles_prices, s_order_basket,
        s_articles, s_core_tax
      WHERE s_order_basket.id = ? AND s_order_basket.sessionID = ?
        AND s_articles_details.ordernumber = "'.$ordernumber.'"
        AND s_articles_details.id=s_articles_prices.articledetailsID
        AND s_articles_details.articleID = s_articles.id
        AND s_articles.taxID = s_core_tax.id
        AND s_articles_prices.pricegroup = \'EK\'';

      $queryNewPrice = Shopware()->Db()->fetchRow(
        $sql . ' ' . $quantitySQL,
        array(
          $id,
          Shopware()->Session()->get('sessionId'),
        )
      ) ? : array();
    }

    return $queryNewPrice;
  }

  public function checkPseudoAttributes($article, $pseudo)
  {
    for($i = 0; $i < count($article['sConfigurator']); $i++) {
      if(isset($article['sConfigurator'][$i]['pseudo']) && ($article['sConfigurator'][$i]['pseudo'] == 1)) {
        $groupID = (int)$article['sConfigurator'][$i]['groupID'];
        if(isset($pseudo[$groupID]) && $pseudo[$groupID] != '') {
          return 1;
        }
      }
    }

    return 0;
  }

  public function getDetailIdByNumber($orderNumber)
  {
    $checkForArticle = Shopware()->Db()->fetchRow('SELECT id FROM s_articles_details WHERE ordernumber = ?', array($orderNumber));

    if(isset($checkForArticle['id'])) {
      return $checkForArticle['id'];
    } else {
      return false;
    }
  }

  public function getIdByNumber($orderNumber)
  {
    $find = explode('_', $orderNumber);
    $checkForArticle = Shopware()->Db()->fetchRow('SELECT articleID FROM s_articles_details WHERE ordernumber IN ("'.implode('", "', $find).'")');

    if(isset($checkForArticle['articleID'])) {
      return $checkForArticle['articleID'];
    } else {
      return false;
    }
  }

  public function getBasketAdditionalDetails($numbers)
  {
    $container = Shopware()->Container();
    $listProduct = $container->get('shopware_storefront.list_product_service');
    $propertyService = $container->get('shopware_storefront.property_service');
    $context = $container->get('shopware_storefront.context_service');
    $legacyStructConverter = $container->get('legacy_struct_converter');

    $products = $listProduct->getList($numbers, $context->getShopContext());
    $propertySets = $propertyService->getList($products, $context->getShopContext());

    $covers = $container->get('shopware_storefront.variant_cover_service')
      ->getList($products, $context->getShopContext());

    $details = [];
    foreach($products as $product) {
      $promotion = $legacyStructConverter->convertListProductStruct($product);

      if($product->hasConfigurator()) {
        $variantPrice = $product->getVariantPrice();
        $promotion['referenceprice'] = $variantPrice->getCalculatedReferencePrice();
      }

      if(isset($covers[$product->getNumber()])) {
        $promotion['image'] = $legacyStructConverter->convertMediaStruct($covers[$product->getNumber()]);
      }

      if($product->hasProperties() && isset($propertySets[$product->getNumber()])) {
        $propertySet = $propertySets[$product->getNumber()];

        $promotion['sProperties'] = $legacyStructConverter->convertPropertySetStruct($propertySet);
        $promotion['filtergroupID'] = $propertySet->getId();
        $promotion['properties'] = array_map(function ($property) {
          return $property['name'] . ':&nbsp;' . $property['value'];
        }, $promotion['sProperties']);
        $promotion['properties'] = implode(',&nbsp;', $promotion['properties']);
      }
      $details[$product->getNumber()] = $promotion;
    }

    return $details;
  }

  public function getSelectedOptions($article)
  {
    $result = array();
    if(count($article['sConfigurator'])) {
      foreach($article['sConfigurator'] as $group) {
        if(count($group['values'])) {
          foreach($group['values'] as $value) {
            if($value['selected']) {
              $result[] = $value['optionID'];
            }
          }
        }
      }
    }
    return $result;
  }

  public function getAppendPrice($prices, $selected)
  {
    $appendPrice = 0;
    for($i = 0; $i < count($prices); $i++) {
      $list = explode('|', $prices[$i]['options']);
      $prepare = array_diff($list, array(''));

      $found = true;
      if($found) {
        if(count($prepare)) {
          if(array_diff($prepare, $selected) == array()) {
            if($prices[$i]['is_gross']) {
              $appendPrice = $appendPrice + ($prices[$i]['variation'] * 100) / (100 + $tax);
            } else {
              $appendPrice = $appendPrice + $prices[$i]['variation'];
            }
          }
        }
      }
    }

    return $appendPrice;
  }

}
