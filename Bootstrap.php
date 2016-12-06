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
              'data'=>'[{"key":"0","value":"SelectBox"},{"key":"1","value":"RadioBox"},{"key":"2","value":"TextFields"},{"key":"3","value":"FrontBackSide"}]'
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
              'label'=>'Attribute info', 
              'help'=>'Sets additional attribute information for frontend', 
              'support'=>'Set additional information', 
              'data'=>''
              ),
        array('name'=>'optionparent', 
              'type'=>'string', 
              'column_type'=>'varchar(500)',
              'label'=>'Parent group name', 
              'help'=>'Define if attribute is separate options group', 
              'support'=>'Set existing group name', 
              'data'=>'',
              'non_translatable'=>true 
              )
    );

public function install()
{
  try {
    //$this->CreateEvents();
    $this->CreateAttributeTables();
    $this->installConfiguratorAttributes();
    
    $this->subscribeEvent('Enlight_Bootstrap_InitResource_shopware_attribute.table_mapping', 'onTableMappingConstruct');
    $this->subscribeEvent('Enlight_Controller_Action_PostDispatchSecure_Backend_Article','onBackendArticlePostDispatch');

    return true; //array('success' => true, 'invalidateCache' => $this->clearCache);

  } catch (Exception $exception) {
    $this->uninstall();
    throw new Exception($exception->getMessage());
  }
}

public function uninstall()
{
    $this->unInstallConfiguratorAttributes();
    $this->DropAttributeTables();
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
      if ($cur_entry['non_translatable']) {$translatable = 0;} else {$translatable = 1;}
      $sqlQuery = "INSERT INTO s_attribute_configuration(table_name, column_name, column_type, position, translatable, display_in_backend, custom, help_text, support_text, label, entity, array_store) VALUES (?,?,?,?,?,1,1,?,?,?,?,?)";
        Shopware()->Db()->query($sqlQuery,[$tname,$cname,$cur_entry["type"],$c,$translatable,$cur_entry["help"],$cur_entry["support"],$cur_entry["label"],"",$cur_entry["data"]]); 
      $c+=1;  
    }  
    $all_entries = $this->dbeConfOptionFilelds;
    $tname='s_article_configurator_options_attributes';
    $c=1;
    foreach ($all_entries as $cur_entry) {
      Shopware()->Models()->addAttribute(
        $tname, 
        $this->cf_prefix,
        $cur_entry['name'],
        $cur_entry['column_type']);
      $cname=($this->cf_prefix).'_'.$cur_entry['name'];
      if ($cur_entry['non_translatable']) {$translatable = 0;} else {$translatable = 1;}
      $sqlQuery = "INSERT INTO s_attribute_configuration(table_name, column_name, column_type, position, translatable, display_in_backend, custom, help_text, support_text, label, entity, array_store) VALUES (?,?,?,?,?,1,1,?,?,?,?,?)";
        Shopware()->Db()->query($sqlQuery,[$tname,$cname,$cur_entry["type"],$c,$translatable,$cur_entry["help"],$cur_entry["support"],$cur_entry["label"],"",$cur_entry["data"]]); 
      $c+=1; 
    }  
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
    $metaDataCacheDoctrine = $this->getEntityManager()->getConfiguration()->getMetadataCacheImpl();
    $metaDataCacheDoctrine->deleteAll();    
    $this->getEntityManager()->generateAttributeModels('s_article_configurator_groups_attributes', 's_article_configurator_options_attributes'); 
}

public function CreateEvents()
{
  $this->subscribeEvent('Enlight_Bootstrap_InitResource_shopware_attribute.table_mapping', 'onTableMappingConstruct');
  $this->subscribeEvent('Enlight_Controller_Action_PostDispatchSecure_Backend_Article','onBackendArticlePostDispatch');
}

public function onCustomerPostDispatch(Enlight_Event_EventArgs $args)
{
    /** @var \Enlight_Controller_Action $controller */
    $controller = $args->getSubject();
    $view = $controller->View();
    $request = $controller->Request();

    $view->addTemplateDir(__DIR__ . '/Views');

    if ($request->getActionName() == 'load') {
        $view->extendsTemplate('backend/swag_extend_customer/view/detail/billing.js');
    }
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

      error_log('----done!');
      ob_start();
      print_r($request->getActionName());
      $contents = ob_get_contents();
      ob_end_clean();
      error_log($contents);


      $view->extendsTemplate('backend/cardformular_extend_article/view/variant/configurator/group_edit.js');  
    }

}


}