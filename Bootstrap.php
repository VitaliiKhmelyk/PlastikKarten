<?php

class Shopware_Plugins_Backend_CardFormular_Bootstrap extends Shopware_Components_Plugin_Bootstrap
{

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
      'copyright' => 'Copyright © 2016, WebiProg',
      'support' => 'info@webiprog.com',
      'link' => 'http://www.webiprog.com'
    );
    
    return $info;
  }

private $clearCache = array(
        'template', 'backend', 'proxy'
    );

private $cf_template = "card_formular.tpl";  
private $cf_template_name = 'PlastikKarten';

private $cf_prefix = 'cf';

private $dbeConfGroupFilelds = array(
        array('name'=>'grouptype', 
              'type'=>'combobox', 
              'column_type'=>'mediumtext',
              'label'=>'Group type', 
              'help'=>'Sets type of elements inside option group for frontend', 
              'support'=>'', 
              'data'=>'[{"key":"SelectBox","value":"Selector"},{"key":"RadioBox","value":"Radio buttons"},{"key":"TextFields","value":"Text inputs"},{"key":"FrontBackSide","value":"Surface design"},{"key":"Upload","value":"Upload"},{"key":"Container","value":"Container"}]'
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
              'help'=>'Allows to ignore current group during variants generation (for Selector and Radio buttons only)', 
              'support'=>'', 
              'data'=>''
              )             
    );

private $dbeConfOptionFilelds = array(       
        array('name'=>'optioninfo', 
              'type'=>'string', 
              'column_type'=>'varchar(500)',
              'label'=>'Option info', 
              'help'=>'Sets additional information for the option', 
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
         array('name'=>'subgroupid', 
              'type'=>'integer', 
              'column_type'=>'int(11)',
              'label'=>'Subgroup ID', 
              'help'=>'Sets subgroup ID to build dependencies with other groups', 
              'support'=>'', 
              'data'=>''
              )
    );

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

}

public function registerNamespaces()
{
   $this->Application()->Loader()->registerNamespace('Shopware\Plugins\CardFormular', $this->Path() );
   $this->Application()->Loader()->registerNamespace('Shopware\Plugins\CardFormular\resources', ($this->Path())."\resources" );
   //$this->Application()->Loader()->registerNamespace('Shopware\Plugins\CardFormular\resources', ($this->Path())."\Controllers" );
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
    //Shopware()->Models()->addAttribute($tname, 'media', 'id', 'INT(11)');
    $metaDataCacheDoctrine = Shopware()->Models()->getConfiguration()->getMetadataCacheImpl();
    $metaDataCacheDoctrine->deleteAll();    
    Shopware()->Models()->generateAttributeModels(array('s_article_configurator_groups_attributes', 's_article_configurator_options_attributes'));
}

public function unInstallConfiguratorAttributes()
{
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
    //Shopware()->Models()->removeAttribute($tname, 'media', 'id');
    $metaDataCacheDoctrine = $this->getEntityManager()->getConfiguration()->getMetadataCacheImpl();
    $metaDataCacheDoctrine->deleteAll();    
    $this->getEntityManager()->generateAttributeModels('s_article_configurator_groups_attributes', 's_article_configurator_options_attributes'); 
}

public function CreateEvents()
{
  $this->subscribeEvent('Enlight_Bootstrap_InitResource_shopware_attribute.table_mapping', 'onTableMappingConstruct');
  
  $this->subscribeEvent('Enlight_Controller_Action_PostDispatchSecure_Backend_Base','onBackendBasePostDispatch');
  $this->subscribeEvent('Enlight_Controller_Action_PostDispatchSecure_Backend_Article','onBackendArticlePostDispatch');
  $this->subscribeEvent('Enlight_Controller_Action_PostDispatch_Frontend_Detail','onFrontendDetailPostDispatch');

  $this->subscribeEvent('sArticles::sGetArticleById::after', 'onArticleGetProduct');

  $this->subscribeEvent('Shopware_Controllers_Backend_Article::loadStoresAction::after', 'afterBackendArticleLoadStoresAction');
  $this->subscribeEvent('Shopware_Controllers_Backend_Article::createConfiguratorVariantsAction::before', 'beforeCreateConfiguratorVariantsAction');

  $this->subscribeEvent('Legacy_Struct_Converter_Convert_Configurator_Set', 'onFilterConvertConfiguratorStruct');
  $this->subscribeEvent('Legacy_Struct_Converter_Convert_Configurator_Price', 'onFilterConvertConfiguratorPrice');
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

public function checkConfiguratorSetSelectionSpecified(StoreFrontBundle\Struct\Configurator\Set $set) {
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
                if ((!isset($cur_workflow)) || (empty($cur_workflow)) || ($cur_workflow=="")) {
                  $cur_workflow="default";
                }
                if (!in_array($cur_workflow, $workflow)) {
                   $workflow[]=$cur_workflow;
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
      $params["sWorkflow"]=$workflow;
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
        $view->extendsTemplate('backend/cardformular_extend_article/view/variant/configurator/group_edit.js');  
        $view->extendsTemplate('backend/cardformular_extend_article/view/variant/configurator/option_edit.js');
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

    $markups = $this->getMarkupPrice($sArticle, $configuratorSetId);

    $view->addTemplateDir($this->Path() . 'Views/');

    $view->assign('cf_show_markup', $this->Config()->get('cf_show_markup', 0));
    $view->assign('cf_markups', $markups);

    if($sArticle['template'] == $this->cf_template) {
      $view->extendsTemplate('frontend/detail/hidden.tpl');
      $view->extendsTemplate('frontend/detail/'.$this->cf_template);
      $view->extendsTemplate('frontend/detail/scripts.tpl');
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