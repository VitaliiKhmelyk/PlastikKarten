<?php

class Shopware_Plugins_Backend_CardFormular_Bootstrap extends Shopware_Components_Plugin_Bootstrap
{

private $mainController;

public function getVersion()
{
  return "1.0.0";
}

public function getInfo()
  {
    $info = array(
      'version' => $this->getVersion(),
      'label' => 'Card Formular Configurator',
      'author' => 'webiprog.com',
      'copyright' => 'Copyright © 2016-2017, WebiProg',
      'support' => 'info@webiprog.com',
      'link' => 'http://www.webiprog.com'
    );
    
    return $info;
  }

private $clearCache = array(
        'template', 'backend', 'proxy'
    );

private $cf_template_orig = "card_formular"; 
private $cf_template = "card_formular.tpl";  
private $cf_template_name = 'PlastikKarten';

private $cf_prefix = 'cf';

private $dbeCartAttributeFilelds = array(
        array('name'=>'cardformular', 
              'column_type'=>'text', 
              'required'=>false,
              'data'=>''
              )
    );

private $dbeOrderAttributeFilelds = array(
        array('name'=>'cardformular', 
              'column_type'=>'text', 
              'required'=>false,
              'data'=>''
              )
    );

private $dbeConfGroupFilelds = array(
        array('name'=>'grouptype', 
              'type'=>'combobox', 
              'column_type'=>'mediumtext',
              'label'=>'Group type', 
              'help'=>'Sets type of elements inside option group for frontend', 
              'support'=>'', 
              'data'=>'[{"key":"SelectBox","value":"Selector"},{"key":"RadioBox","value":"Radio buttons"},{"key":"TextFields","value":"Text input"},{"key":"TextArea","value":"Text area"},{"key":"Upload","value":"Upload"},{"key":"Container","value":"Container"},{"key":"DesignCanvas","value":"Design canvas"}]'
              ),
        array('name'=>'groupinfo', 
              'type'=>'html', 
              'column_type'=>'mediumtext',
              'label'=>'Group info', 
              'help'=>'Sets additional information for the group', 
              'support'=>'', 
              'data'=>''
              ),
       array('name'=>'subgroupid', 
              'type'=>'integer', 
              'column_type'=>'int(11)',
              'label'=>'Subgroup ID', 
              'help'=>'Sets subgroup ID to build dependencies with other options', 
              'support'=>'', 
              'data'=>''
              ),
       array('name'=>'workflowlabel', 
              'type'=>'string', 
              'column_type'=>'varchar(500)',
              'label'=>'Workflow label', 
              'help'=>'Sets stage for the group in frontend workflow', 
              'support'=>'', 
              'data'=>''
              ),
       array('name'=>'pseudo', 
              'type'=>'boolean', 
              'column_type'=>'int(1)',
              'label'=>'Skip variants', 
              'help'=>'Allows to ignore current group during variants generation (for "Selector" and "Radio buttons" types only)', 
              'support'=>'For "Selector" and "Radio buttons" types only', 
              'data'=>''
              )             
    );

private $dbeConfOptionFilelds = array(       
        array('name'=>'designinfo', 
              'type'=>'string', 
              'column_type'=>'varchar(500)',
              'label'=>'Design info', 
              'help'=>'Sets additional info to display on the design canvas', 
              'support'=>'', 
              'data'=>''
              ),        
        array('name'=>'mediaid', 
              'type'=>'integer', 
              'column_type'=>'int(11)',
              'label'=>'Media source', 
              'help'=>'', 
              'support'=>'', 
              'data'=>'',
              'customtype'=>'media'
              ),
        array('name'=>'designmediaid', 
              'type'=>'integer', 
              'column_type'=>'int(11)',
              'label'=>'Design media', 
              'help'=>'', 
              'support'=>'', 
              'data'=>'',
              'customtype'=>'media'
              ),
        array('name'=>'subgroupid', 
              'type'=>'integer', 
              'column_type'=>'int(11)',
              'label'=>'Subgroup ID', 
              'help'=>'Sets subgroup ID to build dependencies with other groups', 
              'support'=>'', 
              'data'=>''
              )
    );

private $db_table_version = 1;
private $full_clear_on_uninstall = true;

private $cf_template_mode = '';

public function install()
{
  try {
    $this->registerControllers();  
    $this->CreateAttributeTables();
    $this->installConfiguratorAttributes();
    $this->CreateEvents();
    $this->CreateForm();  
    
    return array('success' => true, 'invalidateCache' => $this->clearCache);

  } catch (Exception $exception) {
    $this->uninstall();
    throw new Exception($exception->getMessage());
  }
}

public function uninstall()
{
    $this->unInstallConfiguratorAttributes();
    $this->DropAttributeTables();
    $this->DropArticleTemplates();
    parent::uninstall();
    return true;
}

public function afterInit()
{
   $this->registerNamespaces();
   include_once $this->Path() . 'Controllers/Frontend/CardFormular.php';
   $this->mainController = new Shopware_Controllers_Frontend_CardFormular($this);
}

public function registerNamespaces()
{
   $this->Application()->Loader()->registerNamespace('Shopware\Plugins\CardFormular', $this->Path());
   $this->Application()->Loader()->registerNamespace('Shopware\Plugins\CardFormular\resources', $this->Path().'\resources');
   $this->Application()->Loader()->registerNamespace('Shopware\Plugins\CardFormular\Controllers', $this->Path().'\Controllers');
}

public function registerControllers()
{
   $this->registerController('backend', 'customattributedata');
   $this->registerController('frontend', 'customattributedata');
   $this->registerController('frontend', 'CardFormularUpload');
}

///////////////////////////////////////////////////

public function getEntityManager()
{
    return Shopware()->Models();
}

public function installConfiguratorAttributes()
{
    $all_entries = $this->dbeCartAttributeFilelds;
    $tname='s_order_basket_attributes';
    foreach ($all_entries as $cur_entry) {
      Shopware()->Models()->addAttribute(
        $tname, 
        $this->cf_prefix,
        $cur_entry['name'],
        $cur_entry['column_type']);
    }  
    $all_entries = $this->dbeOrderAttributeFilelds;
    $tname='s_order_details_attributes';
    foreach ($all_entries as $cur_entry) {
      Shopware()->Models()->addAttribute(
        $tname, 
        $this->cf_prefix,
        $cur_entry['name'],
        $cur_entry['column_type']);
    }  
    $all_entries = $this->dbeConfGroupFilelds;
    $tname='s_article_configurator_groups_attributes';
    $c=1;
    foreach ($all_entries as $cur_entry) {
      if ($cur_entry['customtype']) {   
        $cbackend=0;
      } else {
        $cbackend=1;
      }
      Shopware()->Models()->addAttribute(
        $tname, 
        $this->cf_prefix,
        $cur_entry['name'],
        $cur_entry['column_type']);
      $cname=($this->cf_prefix).'_'.$cur_entry['name'];
      $sqlQuery = "INSERT INTO s_attribute_configuration(table_name, column_name, column_type, position, translatable, display_in_backend, custom, help_text, support_text, label, entity, array_store) VALUES (?,?,?,?,1,?,0,?,?,?,?,?)";
        Shopware()->Db()->query($sqlQuery,[$tname,$cname,$cur_entry["type"],$c,$cbackend,$cur_entry["help"],$cur_entry["support"],$cur_entry["label"],"",$cur_entry["data"]]); 
      $c+=1;  
    }  
    $all_entries = $this->dbeConfOptionFilelds;
    $tname='s_article_configurator_options_attributes';
    $c=1;
    foreach ($all_entries as $cur_entry) {
      if ($cur_entry['customtype']) {   
        $cbackend=0;
      } else {
        $cbackend=1;
      }
      Shopware()->Models()->addAttribute(
          $tname, 
          $this->cf_prefix,
          $cur_entry['name'],
          $cur_entry['column_type']);        
      $cname=($this->cf_prefix).'_'.$cur_entry['name'];
      $sqlQuery = "INSERT INTO s_attribute_configuration(table_name, column_name, column_type, position, translatable, display_in_backend, custom, help_text, support_text, label, entity, array_store) VALUES (?,?,?,?,1,?,0,?,?,?,?,?)";
      Shopware()->Db()->query($sqlQuery,[$tname,$cname,$cur_entry["type"],$c,$cbackend,$cur_entry["help"],$cur_entry["support"],$cur_entry["label"],"",$cur_entry["data"]]); 
      $c+=1; 
    }  
    $metaDataCacheDoctrine = Shopware()->Models()->getConfiguration()->getMetadataCacheImpl();
    $metaDataCacheDoctrine->deleteAll();    
    Shopware()->Models()->generateAttributeModels('s_article_configurator_groups_attributes', 's_article_configurator_options_attributes', 's_order_basket_attributes', 's_order_details_attributes');
}

public function unInstallConfiguratorAttributes()
{
    $all_entries = $this->dbeCartAttributeFilelds;
    $tname='s_order_basket_attributes';
    foreach ($all_entries as $cur_entry) {
      $this->getEntityManager()->removeAttribute($tname, $this->cf_prefix, $cur_entry['name']);
    }  
    $all_entries = $this->dbeOrderAttributeFilelds;
    $tname='s_order_details_attributes';
    foreach ($all_entries as $cur_entry) {
      $this->getEntityManager()->removeAttribute($tname, $this->cf_prefix, $cur_entry['name']);
    }  
    $all_entries = $this->dbeConfGroupFilelds;
    $tname='s_article_configurator_groups_attributes';
    foreach ($all_entries as $cur_entry) {
      $this->getEntityManager()->removeAttribute($tname, $this->cf_prefix, $cur_entry['name']);  
      $cname=($this->cf_prefix).'_'.$cur_entry['name'];  
      $sqlQuery = "DELETE FROM s_attribute_configuration WHERE table_name=? AND column_name=?";
      Shopware()->Db()->query($sqlQuery,[$tname,$cname]);
    }
    $all_entries = $this->dbeConfOptionFilelds;
    $tname='s_article_configurator_options_attributes';
    foreach ($all_entries as $cur_entry) {
      $this->getEntityManager()->removeAttribute($tname, $this->cf_prefix, $cur_entry['name']);
      $cname=($this->cf_prefix).'_'.$cur_entry['name']; 
      $sqlQuery = "DELETE FROM s_attribute_configuration WHERE table_name=? AND column_name=?";
      Shopware()->Db()->query($sqlQuery,[$tname,$cname]);
    }
    $metaDataCacheDoctrine = $this->getEntityManager()->getConfiguration()->getMetadataCacheImpl();
    $metaDataCacheDoctrine->deleteAll();    
    $this->getEntityManager()->generateAttributeModels('s_article_configurator_groups_attributes', 's_article_configurator_options_attributes', 's_order_basket_attributes', 's_order_details_attributes'); 
}

public function CreateEvents()
{
  $this->subscribeEvent('Enlight_Bootstrap_InitResource_shopware_attribute.table_mapping', 'onTableMappingConstruct');
  
  $this->subscribeEvent('Enlight_Controller_Action_PostDispatchSecure_Backend_Base','onBackendBasePostDispatch');
  $this->subscribeEvent('Enlight_Controller_Action_PostDispatchSecure_Backend_Article','onBackendArticlePostDispatch');
  $this->subscribeEvent('Enlight_Controller_Action_PostDispatch_Frontend_Detail','onFrontendDetailPostDispatch');

  $this->subscribeEvent('Shopware_Controllers_Frontend_Detail::indexAction::before', 'beforeFrontendDetailIndexAction');
  $this->subscribeEvent('Shopware_Controllers_Frontend_Detail::indexAction::after', 'afterFrontendDetailIndexAction');

  $this->subscribeEvent('sArticles::sGetArticleById::after', 'onArticleGetProduct');

  $this->subscribeEvent('Shopware_Controllers_Backend_Article::loadStoresAction::after', 'afterBackendArticleLoadStoresAction');
  $this->subscribeEvent('Shopware_Controllers_Backend_Article::createConfiguratorVariantsAction::before', 'beforeCreateConfiguratorVariantsAction');

  $this->subscribeEvent('Legacy_Struct_Converter_Convert_Configurator_Set', 'onFilterConvertConfiguratorStruct');
  $this->subscribeEvent('Legacy_Struct_Converter_Convert_Configurator_Price', 'onFilterConvertConfiguratorPrice');

  $this->subscribeEvent('Enlight_Controller_Action_PostDispatch_Frontend_Ajax', 'onPriceCorrect');
  $this->subscribeEvent('Enlight_Controller_Action_PostDispatch_Frontend_Checkout', 'onCartCorrect');
  $this->subscribeEvent('Enlight_Controller_Action_PostDispatch_Frontend_Account', 'onAccountCorrect');
  $this->subscribeEvent('Shopware_Modules_Basket_getArticleForAddArticle_FilterArticle', 'getArticleForAddArticleFilterArticle');
  $this->subscribeEvent('Shopware_Modules_Basket_getPriceForUpdateArticle_FilterPrice', 'getPriceForUpdateArticleFilterPrice');
  $this->subscribeEvent('Shopware_Modules_Basket_GetBasket_FilterItemStart', 'getBasketFilterItemStart');
  $this->subscribeEvent('Shopware_Modules_Basket_GetBasket_FilterItemEnd', 'getBasketFilterItemEnd');
  $this->subscribeEvent('sBasket::sCheckBasketQuantities::after', 'afterCheckBasketQuantities');

  $this->subscribeEvent('Shopware_Modules_Admin_GetOpenOrderData_FilterResult', 'getOpenOrderDataFilterResult');
  $this->subscribeEvent('Shopware_Controllers_Backend_Order::getListAction::after', 'afterBackendOrderGetListAction');
  $this->subscribeEvent('Shopware_Modules_Order_SendMail_FilterVariables', 'getOrderSendMailFilterVariables');  
}

public function getOrderSendMailFilterVariables(Enlight_Event_EventArgs $arguments)
{
  return $this->mainController->getOrderSendMailFilterVariables($arguments);
}

public function afterBackendOrderGetListAction(Enlight_Event_EventArgs $arguments)
{
  $this->mainController->afterBackendOrderGetListAction($arguments);
}

public function getOpenOrderDataFilterResult(Enlight_Event_EventArgs $arguments)
{
  return $this->mainController->getOpenOrderDataFilterResult($arguments);
}

public function afterCheckBasketQuantities(Enlight_Event_EventArgs $arguments)
{
  $arguments->setReturn($this->mainController->afterCheckBasketQuantities($arguments));
}

public function getBasketFilterItemEnd(Enlight_Event_EventArgs $arguments)
{
  return $this->mainController->getBasketFilterItemEnd($arguments);
}

public function getBasketFilterItemStart(Enlight_Event_EventArgs $arguments)
{
  return $this->mainController->getBasketFilterItemStart($arguments);
}

public function getPriceForUpdateArticleFilterPrice(Enlight_Event_EventArgs $arguments)
{
  return $this->mainController->getPriceForUpdateArticleFilterPrice($arguments);
}

public function getArticleForAddArticleFilterArticle(Enlight_Event_EventArgs $arguments)
{
  return $this->mainController->getArticleForAddArticleFilterArticle($arguments);
}

public function onAccountCorrect(Enlight_Event_EventArgs $arguments)
{
error_log('function: ');
error_log(__METHOD__);
/*  $controller = $arguments->getSubject();
  $view = $controller->View();
  $view->addTemplateDir($this->Path() . 'Views/');
  $request = Shopware()->Container()->get('front')->Request();

  $view->extendsTemplate('frontend/account/createyourownone_order_item.tpl');*/
}

public function onCartCorrect(Enlight_Event_EventArgs $arguments)
{
error_log('function: ');
error_log(__METHOD__);
  $controller = $arguments->getSubject();
  $view = $controller->View();
  $view->addTemplateDir($this->Path() . 'Views/');
  $request = Shopware()->Container()->get('front')->Request();

  $view->extendsTemplate('frontend/cart/cf_ajax_item.tpl');
  $view->extendsTemplate('frontend/cart/cf_cart_item.tpl');
  $view->extendsTemplate('frontend/detail/styles.tpl');
}

public function onPriceCorrect(Enlight_Event_EventArgs $arguments)
{
error_log('function: ');
error_log(__METHOD__);
/*  $controller = $arguments->getSubject();
  $view = $controller->View();
  $view->addTemplateDir($this->Path() . 'Views/');
  $request = Shopware()->Container()->get('front')->Request();
  $sConfigurator = $view->getAssign('sArticle');
  if(count($sConfigurator['images']) > 0) {
    if($sConfigurator['image']['main']) {
      $sConfigurator['image'] = $sConfigurator['images'][0];
      unset($sConfigurator['images'][0]);
    } else {
      for($i = 0; $i < count($sConfigurator['images']); $i++) {
        if($sConfigurator['images'][$i]['main']) {
          unset($sConfigurator['images'][$i]);
          break;
        }
      }
    }
    $sConfigurator['images'] = array_values($sConfigurator['images']);
    $view->assign('sArticle', $sConfigurator);
  }
  $mainArticle = Shopware()->Modules()->Articles()->sGetArticleById($sConfigurator['articleID']);

  $input = $request->getParam('input', '');

  $aluOption = $this->Config()->get('createyourownoneAluOption', 0);
  $copOption = $this->Config()->get('createyourownoneCopOption', 0);

  $symbolsRaw = $this->Config()->get('createyourownoneConfigSymbols', '');
  $symbols = array();
  if($symbolsRaw) {
    $optionsSets = explode('|', $symbolsRaw);
    for($i = 0; $i < count($optionsSets); $i++) {
      list($optionsSet, $image) = explode('/', $optionsSets[$i]);
      $symbols[$optionsSet] = $image;
    }
  }
  $view->assign('prepareSymbols', json_encode($symbols));

  $chars = $this->Config()->get('createyourownoneConfigChars', '');
  $view->assign('prepareChars', $chars);
    
  $sql = 'SELECT m.name, m.extension, m.width, m.height FROM s_media AS m
    LEFT JOIN s_articles_img AS ai ON ai.media_id = m.id
    WHERE ai.articleID = '.$sConfigurator['articleID'];
  $checkForArticleImages = Shopware()->Db()->fetchAll($sql);
  $allImages = array();
  for($i = 0; $i < count($checkForArticleImages); $i++) {
    $canvasWidth = $checkForArticleImages[$i]['width'];
    $canvasHeight = $checkForArticleImages[$i]['height'];

    $allImages[] = array(
      'imageName'   => $checkForArticleImages[$i]['name'],
      'imageWidth'  => $checkForArticleImages[$i]['width'],
      'imageHeight' => $checkForArticleImages[$i]['height'],
      'left'      => $canvasWidth / 2,
      'top'     => $canvasHeight * 5 / 6,
    );
  }

  $selectedOptions = array();
  $biases = array();
  for($i = 0; $i < count($sConfigurator['sConfigurator']); $i++) {
    if(count($sConfigurator['sConfigurator'][$i]['values'])) {
      foreach($sConfigurator['sConfigurator'][$i]['values'] as $value) {
        if($value['optionID'] == $aluOption || $value['optionID'] == $copOption) {
          if($value['optionID'] == $aluOption) {
            $option2 = $value['optionname'];
            $option2code = $value['optionID'];
            $resizeCanvas = $mainArticle['createyourownone_alu_resize'];
            $objectWidth = floor($this->Config()->get('createyourownonePreviewWidthSize', 100) * $resizeCanvas / 100);
            $objectHeight = floor($this->Config()->get('createyourownonePreviewHeightSize', 100) * $resizeCanvas / 100);
            $biases[$value['optionID']] = array(
              'x'       => $mainArticle['createyourownone_alu_x'],
              'y'       => $mainArticle['createyourownone_alu_y'],
              'resizeCanvas'  => $resizeCanvas,
              'width'     => $objectWidth,
              'height'    => $objectHeight,
            );
          }
          if($value['optionID'] == $copOption) {
            $option1 = $value['optionname'];
            $option1code = $value['optionID'];
            $resizeCanvas = $mainArticle['createyourownone_cop_resize'];
            $objectWidth = floor($this->Config()->get('createyourownonePreviewWidthSize', 100) * $resizeCanvas / 100);
            $objectHeight = floor($this->Config()->get('createyourownonePreviewHeightSize', 100) * $resizeCanvas / 100);
            $biases[$value['optionID']] = array(
              'x' => $mainArticle['createyourownone_cop_x'],
              'y' => $mainArticle['createyourownone_cop_y'],
              'resizeCanvas'  => $resizeCanvas,
              'width'     => $objectWidth,
              'height'    => $objectHeight,
            );
          }
        }
        if($value['selected']) {
          $selectedOptions[$value['optionID']] = $value['optionname'];
        }
      }
    }
  }

  if(isset($selectedOptions[$aluOption])) {
    $xBias = $mainArticle['createyourownone_alu_x'];
    $yBias = $mainArticle['createyourownone_alu_y'];
    $resizeCanvas = $mainArticle['createyourownone_alu_resize'];
    $currentOption = $aluOption;
    unset($selectedOptions[$aluOption]);
  } elseif(isset($selectedOptions[$copOption])) {
    $xBias = $mainArticle['createyourownone_cop_x'];
    $yBias = $mainArticle['createyourownone_cop_y'];
    $resizeCanvas = $mainArticle['createyourownone_cop_resize'];
    $currentOption = $copOption;
    unset($selectedOptions[$copOption]);
  }
  $objectWidth = floor($this->Config()->get('createyourownonePreviewWidthSize', 100) * $resizeCanvas / 100);
  $objectHeight = floor($this->Config()->get('createyourownonePreviewHeightSize', 100) * $resizeCanvas / 100);

  $image = $sConfigurator['image']['source'];
    
  $prepareText = implode(' ', $selectedOptions);
  if($input != '') {
    $prepareText = $input;
  }
  $view->assign('prepareText', $prepareText);

  $servicePrice = $this->Config()->get('createyourownoneConfigCost', 0);

  $article = Shopware()->Modules()->Articles()->sGetArticleById($sConfigurator['articleID']);

  $sql = 'SELECT aa.createyourownone_active FROM s_articles_attributes AS aa
    LEFT JOIN s_articles AS a ON a.main_detail_id = aa.articledetailsID
    WHERE a.id = '.$sConfigurator['articleID'].'';
  $checkForOptions = Shopware()->Db()->fetchRow($sql);
  if(isset($checkForOptions['createyourownone_active'])) {
    $createyourownoneActive = $checkForOptions['createyourownone_active'];
  } else {
    $createyourownoneActive = 0;
  }

  $article = $sConfigurator;

  $canvasWidth = $sConfigurator['image']['width'];
  $canvasHeight = $sConfigurator['image']['height'];

  $view->assign('setNumber', $number);
  $view->assign('serviceImage', $image);

  $prepareCanvasData = array(
    'x_bias'    => $xBias,
    'y_bias'    => $yBias,
    'biases'    => $biases,
    'images'    => $allImages,
    'canvas_resize' => $resizeCanvas,
    'object_width'  => $objectWidth,
    'canvas_width'  => $canvasWidth,
    'object_height' => $objectHeight,
    'canvas_height' => $canvasHeight,
    'background'  => $image,
    'objects'   => array(
      array(
        'width'     => $objectWidth,
        'height'    => $objectHeight,
        'left'      => $canvasWidth / 2,
        'top'     => $canvasHeight * 5 / 6,
        'text'      => $prepareText,
        'textAlign'   => 'center',
        'fontSize'    => $this->Config()->get('createyourownoneFontSize', 24),
        'fontColor'   => $this->Config()->get('createyourownoneFontColor', '#000000'),
      ),
    ),
  );
  $view->assign('prepareCanvasData', json_encode($prepareCanvasData));

  $view->extendsTemplate('frontend/detail/createyourownone_options.tpl');
  $view->extendsTemplate('frontend/detail/createyourownone_scripts.tpl');
  $view->extendsTemplate('frontend/detail/createyourownone_calculate.tpl');

  $view->assign('option1', $option1);
  $view->assign('option2', $option2);
  $view->assign('option1code', $option1code);
  $view->assign('option2code', $option2code);
  $view->assign('currentOption', $currentOption);

  if($article) {
    if($createyourownoneActive == 1) {
      $view->extendsTemplate('frontend/detail/createyourownone_modal.tpl');
      $view->extendsTemplate('frontend/detail/createyourownone_buy.tpl');

      $sArticleId = $sConfigurator['articleID'];
      $sArticleDetailsID = $sConfigurator['articleDetailsID'];
        
      $cheapest = Shopware()->Modules()->Articles()->sGetCheapestPrice($sArticleId, 0, 0, true);
      $userGroup = Shopware()->Modules()->Articles()->sSYSTEM->sUSERGROUP;
      $checkForPrice = Shopware()->Db()->fetchRow(
        'SELECT price FROM s_articles_prices WHERE articleID = '.$sArticleId.' AND pricegroup = "'.$userGroup.'" AND articledetailsID = '.$sArticleDetailsID.' AND `from` = 1'
      );
      if(isset($checkForPrice['price'])) {
        $workPrice = $checkForPrice['price'];
      } else {
        $workPrice = $article['price_numeric'];
      }

      $showCalculatedPrice = 1;
      $view->assign('maxSize', $this->Config()->get('createyourownoneInputSize', 10));
    }
  }*/
}

public function isPseudoGroup($id) {
  $res = false; 
  $sql = "SELECT cf_pseudo, cf_grouptype FROM s_article_configurator_groups_attributes WHERE groupID = ".$id;
  $data = Shopware()->Db()->fetchAll($sql);
  if ($data) {
    $t = $data[0]["cf_grouptype"]; 
    if (($data[0]["cf_pseudo"] == 1) || (($t!="RadioBox") && ($t!="SelectBox") && (!empty($t)))) {
      $res = true; 
    } 
  }  
  return $res;
}  

public function checkConfiguratorSetSelectionSpecified($set) {
  //$res = $set->isSelectionSpecified();  
  $res = true;  
  foreach ($set->getGroups() as $group) {
     if ((!$group->isSelected()) && (!$this->isPseudoGroup($group->getId()))) {
        $res = false;
     }
  }
  return $res;
}

public function onFilterConvertConfiguratorStruct(Enlight_Event_EventArgs $args) {
  $set = $args->get('configurator_set'); 
  $result = $args->getReturn();
  if (!$result['isSelectionSpecified']) {
    $result["isSelectionSpecified"] = $this->checkConfiguratorSetSelectionSpecified($set);
  }     
  return $result;
}

public function onFilterConvertConfiguratorPrice(Enlight_Event_EventArgs $args) {
  $set = $args->get('configurator_set'); 
  $result = $args->getReturn();
  if ($this->checkConfiguratorSetSelectionSpecified($set)) {
    $result=[];
  }     
  return $result;
}

public function beforeFrontendDetailIndexAction(Enlight_Event_EventArgs $args) {
  $controller = $args->getSubject();
  $request = $controller->Request();
  if (!empty($request->templatemode)) {
    $this->cf_template_mode = $request->templatemode;
  }
}  

public function afterFrontendDetailIndexAction(Enlight_Event_EventArgs $args) {
  $this->cf_template_mode = '';
} 

public function onArticleGetProduct(Enlight_Event_EventArgs $args) {
    $params = $args->getReturn();
    if ($params["template"] == $this->cf_template) {
      $all_groups=$params["sConfigurator"];
      $workflow=array();
      if ($all_groups) {
        $cnt=0;
        foreach ($all_groups as $grp) {                      
           if ($grp["groupID"]) {
              $all_values=$grp["values"];
              //if pseudo group
              if ($this->isPseudoGroup($grp["groupID"]))  {
                 $params["sConfigurator"][$cnt]["pseudo"] = true;
                 if ($all_values) {
                   foreach ($all_values as $v) {
                      $id=$v["optionID"];
                      if ($id) { 
                        $params["sConfigurator"][$cnt]["values"][$id]["selectable"] = true;
                      }
                   }
                 }
              } 
              //if group with single option
              if (
                 ($params["sConfiguratorSettings"]["type"] == 0) 
                 && (!$params["sConfigurator"][$cnt]["pseudo"])
                 && ($all_values) 
                 && (count($all_values) < 2)
              )
              {
                $params["sConfigurator"][$cnt]["hidden"] = true;
              }

              $data = Shopware()->Db()->fetchAll("SELECT * FROM s_article_configurator_groups_attributes WHERE groupID = ?", [$grp["groupID"]]);
              if (count($data) > 0) {
                if ($params["sConfigurator"][$cnt]["hidden"]) {
                  $data[0]["cf_workflowlabel"]="";
                }
                $params["sConfigurator"][$cnt]["group_attributes"]=$data[0];              
                $cur_workflow=$data[0]["cf_workflowlabel"];
                $cur_subgroupid=$data[0]["cf_subgroupid"];
                if ((!isset($cur_subgroupid)) || (empty($cur_subgroupid)) || ($cur_subgroupid==0)) {
                  if ((!isset($cur_workflow)) || (empty($cur_workflow)) || ($cur_workflow=="")) {
                    $cur_workflow="default";
                  }
                  if (!in_array($cur_workflow, $workflow)) {
                     $workflow[]=$cur_workflow;
                  }
                }
              }
              if ($all_values) {  
                foreach ($all_values as $v) {
                  $id=$v["optionID"];
                  if ($id) {
                    $vdata = Shopware()->Db()->fetchAll("SELECT * FROM s_article_configurator_options_attributes WHERE optionID = ?", [$id]);
                    if (count($vdata) > 0) {
                      $params["sConfigurator"][$cnt]["values"][$id]["option_attributes"]=$vdata[0];
                      $mediaid = $vdata[0]["cf_mediaid"];
                      if (($mediaid) && (!empty($mediaid))) {
                        $controller = $this;
                        $media_data = $controller->getMediaInfoById($mediaid);
                        $params["sConfigurator"][$cnt]["values"][$id]["media_data"] = $media_data;
                      }
                      $mediaid = $vdata[0]["cf_designmediaid"];
                      if (($mediaid) && (!empty($mediaid))) {
                        $controller = $this;
                        $media_data = $controller->getMediaInfoById($mediaid);
                        $params["sConfigurator"][$cnt]["values"][$id]["design_media_data"] = $media_data;
                      }
                    }
                  }
                }  
              }  
           }
           $cnt+=1;
        }
      }
      if (!count($workflow)>0) {
        $workflow[]="default";
      }
      $params["sWorkflow"] = $workflow;
      $params["isCF"] = true;
      if (!empty($this->cf_template_mode)) {
         $params["template"] = ($this->cf_template_orig).($this->cf_template_mode).".tpl";
      } //else {
      //   $params["template"] = ($this->cf_template_orig)."_ajax.tpl";
      //}        
    }
    $args->setReturn($params);
}


public function CreateAttributeTables()
{
    $sqlQuery = "
      CREATE TABLE IF NOT EXISTS s_article_configurator_groups_attributes (
      id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
      groupID INT(11) UNSIGNED
      )
      ";
    Shopware()->Db()->query($sqlQuery);
    $sqlQuery = "
      CREATE TABLE IF NOT EXISTS s_article_configurator_options_attributes (
      id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
      optionID INT(11) UNSIGNED
      )
      ";
    Shopware()->Db()->query($sqlQuery);
}

public function DropAttributeTables()
{
  if (!$this->full_clear_on_uninstall) {   
    $tname = "webiprog_tmp_groups_attr_v".$this->db_table_version;
    $sqlQuery = "CREATE TABLE ".$tname." LIKE s_article_configurator_groups_attributes"; 
    Shopware()->Db()->query($sqlQuery);
    $sqlQuery = "INSERT ".$tname." SELECT * FROM s_article_configurator_groups_attributes";
    Shopware()->Db()->query($sqlQuery);
    $tname = "webiprog_tmp_options_attr_v".$this->db_table_version;
    $sqlQuery = "CREATE TABLE ".$tname." LIKE s_article_configurator_options_attributes"; 
    Shopware()->Db()->query($sqlQuery);
    $sqlQuery = "INSERT ".$tname." SELECT * FROM s_article_configurator_options_attributes";
    Shopware()->Db()->query($sqlQuery);
  } 
  $sqlQuery = "DROP TABLE IF EXISTS s_article_configurator_groups_attributes, s_article_configurator_options_attributes";
  Shopware()->Db()->query($sqlQuery); 
}

public function onTableMappingConstruct(Enlight_Event_EventArgs $args)
{
    $newService = new Shopware\Plugins\CardFormular\resources\TableMappingEx(Shopware()->Container()->get('dbal_connection'));
    Shopware()->Container()->set('shopware_attribute.table_mapping', $newService);
}


public function onBackendArticlePostDispatch(Enlight_Event_EventArgs $args)
{   
    $controller = $args->getSubject();
    $view = $controller->View();
    $request = $controller->Request();
    $view->addTemplateDir(__DIR__ . '/Views');
    if ($request->getActionName() == 'load') {
        $view->extendsTemplate('backend/cardformular_extend_article/controller/variant.js');
        $view->extendsTemplate('backend/cardformular_extend_article/controller/main.js');
        $view->extendsTemplate('backend/cardformular_extend_article/view/variant/configurator/group_edit.js');  
        $view->extendsTemplate('backend/cardformular_extend_article/view/variant/configurator/option_edit.js');
        $view->extendsTemplate('backend/cardformular_extend_article/view/variant/configurator/price_variation_rule.js');
        $view->extendsTemplate('backend/cardformular_extend_article/view/variant/list.js');
        $view->extendsTemplate('backend/cardformular_extend_article/model/configurator_option.js');
    }
}

public function onBackendBasePostDispatch(Enlight_Event_EventArgs $args)
{   
    $controller = $args->getSubject();
    $view = $controller->View();
    $request = $controller->Request();
    $view->addTemplateDir(__DIR__ . '/Views');
    if ($request->getActionName() == 'index') {
        $view->extendsTemplate('backend/cardformular_extend_base/attribute/Shopware.attribute.Form.js');
    }
}

public function beforeCreateConfiguratorVariantsAction(Enlight_Event_EventArgs $args)
{
    $controller = $args->getSubject();
    $groups = $controller->Request()->getParam('groups');
    $cnt = 0;
    foreach ($groups as $group) {
      if (($group["id"]) && ($this->isPseudoGroup($group["id"]))) {
        $groups[$cnt]['active'] = 0;
      } 
      $cnt += 1; 
    }  
    $controller->Request()->setParam('groups', $groups);
}  

public function afterBackendArticleLoadStoresAction(Enlight_Event_EventArgs $args)
{
    $controller = $args->getSubject();
    $view = $controller->View();

    $data = $view->getAssign('data');
    $templates = $data['templates'];

    $groups = $data['configuratorGroups'];    
    $cnt = 0;
    $s = '[pseudo]';
    foreach ($groups as $group) {
      if ($this->isPseudoGroup($data['configuratorGroups'][$cnt]['id'])) {
        if (strpos($data['configuratorGroups'][$cnt]['description'], $s) === false) {
          $data['configuratorGroups'][$cnt]['description']= $data['configuratorGroups'][$cnt]['description'].$s;
        }
      }
      $cnt += 1;
    }

    $templates[] = array(
      'id'  => $this->cf_template,
      'name'  => $this->cf_template_name, 
    );

    $data['templates'] = $templates;
    $view->assign('data', $data);
}

public function CreateForm()
{
  $form = $this->Form();

  $booleanArray = array(
    array(0, 'No'),
    array(1, 'Yes'),
  );
  $errorMessagesArray = array(
  array(0, 'An Karte anpassen'),
  array(1, 'Im Original drucken'),
  );

  $form->setElement('select', 'cf_show_markup',
    array(
      'label'     => 'Show markup box?',
      'scope'     => \Shopware\Models\Config\Element::SCOPE_SHOP,
      'required'  => false,
      'store'     => $booleanArray,
    )
  );

  $form->setElement('text', 'cf_max_string_length',
    array(
      'label'     => 'The maximum length of the line descriptions',
      'required'  => false,
      'value'     => 100,
    )
  );

  // FTP settings
  $form->setElement('text', 'cf_remote_ftp_address',
    array(
      'label'     => 'Remote FTP server address',
      'required'  => false,
    )
  );
  $form->setElement('text', 'cf_remote_ftp_username',
    array(
      'label'     => 'Remote FTP server username',
      'required'  => false,
    )
  );
  $form->setElement('text', 'cf_remote_ftp_password',
    array(
      'label'     => 'Remote FTP server password',
      'required'  => false,
    )
  );
  $form->setElement('text', 'cf_remote_ftp_path',
    array(
      'label'     => 'Remote FTP server path',
      'required'  => false,
    )
  );

  // Files settings
  $form->setElement('text', 'cf_files_make_time',
    array(
      'label'     => 'Preparation time',
      'required'  => false,
    )
  );
  $form->setElement('text', 'cf_files_upload_size',
    array(
      'label'     => 'Maximum file size',
      'required'  => false,
    )
  );
  $form->setElement('select', 'cf_files_upload_preview',
    array(
      'label'     => 'Show previews of the uploaded file',
      'scope'     => \Shopware\Models\Config\Element::SCOPE_SHOP,
      'required'  => false,
      'store'     => $booleanArray,
    )
  );
  $form->setElement('text', 'cf_files_types',
    array(
      'label'     => 'What types of files will be supported',
      'required'  => false,
    )
  );
  $form->setElement('color', 'cf_files_error_color',
    array(
      'label' => 'Error field color', 
      'value' => '#FF0000',
    )
  );
  $form->setElement('text', 'cf_files_error_message',
    array(
      'label'     => 'Show message if upload error',
      'required'  => false,
    )
  );
  $form->setElement('text', 'cf_files_optimal_size',
    array(
      'label'     => 'The optimal size of the file to print in mm or pixels',
      'required'  => false,
    )
  );
  $form->setElement('select', 'cf_files_upload_size_message',
    array(
      'label'     => 'If the downloaded file is different from the specified dimensions',
      'scope'     => \Shopware\Models\Config\Element::SCOPE_SHOP,
      'required'  => true,
      'store'     => $errorMessagesArray,
    )
  );
}

public function DropArticleTemplates()
{
    $sql = 'UPDATE `s_articles` SET `template`="" WHERE `template`="'.$this->cf_template.'"';
    Shopware()->Db()->query($sql);
}

public function ftpMkDir($conn_id, $path) 
{ 
  $dir=split("/", $path); 
  $path=""; 
  $ret = true; 
   
  for($i = 0; $i < count($dir); $i++) { 
    $path .= "/" . $dir[$i]; 
    if(!@ftp_chdir($conn_id, $path)) {
      @ftp_chdir($conn_id, "/");
      if(!@ftp_mkdir($conn_id, $path)) { 
        $ret = false;
        break;
      }
    }
  }
  return $ret;
}

public function ftpUpload($fileData)
{
  $config = $this->Config()->toArray();
//print_r('<pre>config: ');print_r($config);print_r('</pre>');
//print_r('<pre>fileData: ');print_r($fileData);print_r('</pre>');

  $file = $fileData['tmp_name'];
  $remote_file = $fileData['name'];

  // set up basic connection
  $conn_id = ftp_connect($config['cf_remote_ftp_address']);

  // login with username and password
  $login_result = ftp_login($conn_id, $config['cf_remote_ftp_username'], $config['cf_remote_ftp_password']);
  $path = $config['cf_remote_ftp_path'] . DS . rand(111111, 999999) . DS;
  $returnPath = $config['cf_remote_ftp_address'] . DS . $path . $remote_file;

  $createDir = $this->ftpMkDir($conn_id, $path);
//print_r('<pre>createDir: ');print_r($createDir);print_r('</pre>');

  // upload a file
  if (ftp_put($conn_id, $path . $remote_file, $file, FTP_ASCII)) {
    ftp_close($conn_id);
    return $returnPath;
  } else {
    ftp_close($conn_id);
    return 0;
  }
}

public function onFrontendDetailPostDispatch(Enlight_Event_EventArgs $args)
{
    $controller = $args->getSubject();
    $view = $controller->View();

    $sArticle = $view->getAssign('sArticle');
    $sql = 'SELECT configurator_set_id FROM s_articles WHERE id = ' . $sArticle['articleID'];
    $configuratorSetId = Shopware()->Db()->fetchOne($sql);

    $markups = $this->mainController->getMarkupPrice($sArticle, $configuratorSetId);

    $view->addTemplateDir($this->Path() . 'Views/');

    $view->assign('cf_show_markup', $this->Config()->get('cf_show_markup', 0));
    $view->assign('cf_markups', $markups);

    if($sArticle['template'] == $this->cf_template) {
      $view->extendsTemplate('frontend/detail/hidden.tpl');
      $view->extendsTemplate('frontend/detail/'.$this->cf_template);
      $view->extendsTemplate('frontend/detail/scripts.tpl');
      $view->extendsTemplate('frontend/detail/styles.tpl');
    }
}

 public function getMediaInfoById($id)
    {
        $image = array();
        $imageData = array();
        $sql = "SELECT * FROM s_media WHERE id = ".$id;
        $image = Shopware()->Db()->fetchRow($sql);
        if (!$image) {
            return $imageData;
        }
        if (empty($image["path"])) {
            return $imageData;
        }
        $mediaService = Shopware()->Container()->get('shopware_media.media_service');
        $imageData['src']['original'] = $mediaService->getUrl('media/image/' . $image["path"]);
        $imageData["res"]["original"]["width"] = $image["width"];
        $imageData["res"]["original"]["height"] = $image["height"];
        $imageData["res"]["original"]["size"] = $image["file_size"];
        $imageData["res"]["description"] = $image["description"];
        $imageData["extension"] = $image["extension"];
        $imageData["name"] = $image["name"];
        $imageData["id"] = $image["id"];
        return $imageData;
    }

}

/*
error_log('variable');
ob_start();
print_r($variable);
$contents = ob_get_contents();
ob_end_clean();
error_log($contents);
*/