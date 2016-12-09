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

private $cf_prefix = 'cf';

private $dbeConfGroupFilelds = array(
        array('name'=>'grouptype', 
              'type'=>'combobox', 
              'column_type'=>'mediumtext',
              'label'=>'Group type', 
              'help'=>'Sets type of elements inside option group for frontend', 
              'support'=>'Select type of option group elements', 
              'data'=>'[{"key":"SelectBox","value":"SelectBox"},{"key":"RadioBox","value":"RadioBox"},{"key":"TextFields","value":"TextFields"},{"key":"FrontBackSide","value":"FrontBackSide"}]'
              ),        
        array('name'=>'groupstage', 
              'type'=>'integer', 
              'column_type'=>'int(11)',
              'label'=>'Group stage', 
              'help'=>'Sets stage for the group in frontend workflow', 
              'support'=>'Set group stage', 
              'data'=>''
              ),
        array('name'=>'groupinfo', 
              'type'=>'html', 
              'column_type'=>'mediumtext',
              'label'=>'Group info', 
              'help'=>'Sets additional information for the group', 
              'support'=>'Set additional information', 
              'data'=>''
              )
    );

private $dbeConfOptionFilelds = array(       
        array('name'=>'optioninfo', 
              'type'=>'string', 
              'column_type'=>'varchar(500)',
              'label'=>'Option info', 
              'help'=>'Sets additional information for the option', 
              'support'=>'Set additional information', 
              'data'=>''
              ),
        array('name'=>'subgroup', 
              'type'=>'string', 
              'column_type'=>'varchar(500)',
              'label'=>'Subgroup Tag', 
              'help'=>'Sets option name to create subgroup', 
              'support'=>'Set subgroup name', 
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
      Shopware()->Models()->addAttribute(
        $tname, 
        $this->cf_prefix,
        $cur_entry['name'],
        $cur_entry['column_type']);
      $cname=($this->cf_prefix).'_'.$cur_entry['name'];
      $sqlQuery = "INSERT INTO s_attribute_configuration(table_name, column_name, column_type, position, translatable, display_in_backend, custom, help_text, support_text, label, entity, array_store) VALUES (?,?,?,?,1,1,0,?,?,?,?,?)";
        Shopware()->Db()->query($sqlQuery,[$tname,$cname,$cur_entry["type"],$c,$cur_entry["help"],$cur_entry["support"],$cur_entry["label"],"",$cur_entry["data"]]); 
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
  $this->subscribeEvent('sArticles::sGetArticleById::after', 'onArticleGetProduct');
  $this->subscribeEvent('Enlight_Controller_Action_PostDispatch_Frontend_Detail','onFrontendDetailPostDispatch');
  $this->subscribeEvent('Shopware_Controllers_Backend_Article::loadStoresAction::after', 'afterBackendArticleLoadStoresAction');
}

public function onArticleGetProduct(Enlight_Event_EventArgs $args) {
    $params = $args->getReturn();
    $all_groups=$params["sConfigurator"];
    if ($all_groups) {
      $cnt=0;
      foreach ($all_groups as $grp) {
         if ($grp["groupID"]) {
            $data = Shopware()->Db()->fetchAll("SELECT * FROM s_article_configurator_groups_attributes WHERE groupID = ?", [$grp["groupID"]]);
            if (count($data) > 0) {
              $params["sConfigurator"][$cnt]["group_attributes"]=$data[0];
            }
            $all_values=$grp["values"];
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

public function afterBackendArticleLoadStoresAction(Enlight_Event_EventArgs $args)
{
    $controller = $args->getSubject();
    $view = $controller->View();

    $data = $view->getAssign('data');
    $templates = $data['templates'];

    $templates[] = array(
      'id'  => 'card_formular.tpl',
      'name'  => 'CardFormular',
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

  $form->setElement('select', 'cf_show_markup',
    array(
      'label'     => 'Show markup box?',
      'scope'     => \Shopware\Models\Config\Element::SCOPE_SHOP,
      'required'  => false,
      'store'     => $booleanArray,
    )
  );

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
}

public function DropArticleTemplates()
{
    $sql = 'UPDATE `s_articles` SET `template`="" WHERE `template`="card_formular.tpl"';
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

  $file = 'somefile.txt';
  $remote_file = 'readme.txt';

  // set up basic connection
  $conn_id = ftp_connect($config['cf_remote_ftp_address']);

  // login with username and password
  $login_result = ftp_login($conn_id, $config['cf_remote_ftp_username'], $config['cf_remote_ftp_password']);
  $path = $config['cf_remote_ftp_path'] . DS . rand(111111, 999999) . DS;

  $createDir = $this->ftpMkDir($conn_id, $path);
//print_r('<pre>createDir: ');print_r($createDir);print_r('</pre>');

  // upload a file
  if (ftp_put($conn_id, $path . $remote_file, $file, FTP_ASCII)) {
    ftp_close($conn_id);
    return 1;
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

  if($sArticle['template'] == 'card_formular.tpl') {
    $view->extendsTemplate('frontend/detail/card_formular.tpl');
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