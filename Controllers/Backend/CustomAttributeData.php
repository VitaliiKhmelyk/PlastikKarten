<?php

class Shopware_Controllers_Backend_CustomAttributeData extends Enlight_Controller_Action
{
    public function indexAction()
    {
      return;
    }

    public function loadMediaDataAction()
    {
        try {
            $tablename = $this->Request()->getParam('_table');
            $optionid = $this->Request()->getParam('_foreignKey');
            $fieldname = $this->Request()->getParam('_foreignField');           	
			$sql = "SELECT cf_mediaid FROM ".$tablename." WHERE ".$fieldname." = ".$optionid;
            $id = Shopware()->Db()->fetchOne($sql);
            if ($id) {
            	die(json_encode(array('success' => true, 'data' => $id)));
            } else {
            	die(json_encode(array('success' => false)));
            }
        } catch (Exception $e) {
            die(json_encode(array('success' => false, 'message' => $e->getMessage())));
        }
    }

    public function saveMediaDataAction()
    {
       try {
            $tablename = $this->Request()->getParam('_table');
            $optionid = $this->Request()->getParam('_foreignKey');
            $fieldname = $this->Request()->getParam('_foreignField');
            $mediaid = $this->Request()->getParam('_mediaId');
            $media_empty = (empty($mediaid) || is_null($mediaid));
            $sql = "SELECT id FROM ".$tablename." WHERE ".$fieldname." = ".$optionid;
            $id = Shopware()->Db()->fetchOne($sql);
            if ($id) {
            	if ($media_empty) {
					$sql = "UPDATE ".$tablename." SET cf_mediaid = null WHERE id = ".$id;
            	} else {
            		$sql = "UPDATE ".$tablename." SET cf_mediaid = ".$mediaid." WHERE id = ".$id;
            	}
            	Shopware()->Db()->query($sql);
            } else {
            	if (!$media_empty) {
            		$sql = "INSERT INTO ".$tablename." (".$fieldname.", cf_mediaid)  VALUES (".$optionid.", ".$mediaid.")";
            		Shopware()->Db()->query($sql);
            	}
            }
            die(json_encode(array('success' => true)));
        } catch (Exception $e) {
           die(json_encode(array('success' => false, 'message' => $e->getMessage())));
        } 
    } 

}