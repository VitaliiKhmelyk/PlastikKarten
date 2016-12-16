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

    public function changeConfiguratorGroupPositionAction()
    {
        $data = $this->Request()->getParam('data');
        $positions = json_decode($data); 
        $cnt = 0;
        foreach ($positions as $obj) {   
            $a =  (array) $obj;
            $cnt += 1;
            try {
               $sql = "UPDATE s_article_configurator_groups SET position = ".$a["position"]." WHERE id = ".$a["groupId"];
               Shopware()->Db()->query($sql);
            } catch (Exception $e) {
               die(json_encode(array('success' => false, 'message' => $e->getMessage())));
            }     
        }   
        die(json_encode(array('success' => ($cnt > 0))));
    }
    
    public function changeConfiguratorOptionPositionAction()
    {
        $data = $this->Request()->getParam('data');
        $positions = json_decode($data); 
        $cnt = 0;
        foreach ($positions as $obj) {   
            $a =  (array) $obj;
            $cnt += 1;
            try {
               $sql = "UPDATE s_article_configurator_options SET position = ".$a["position"]." WHERE id = ".$a["optionId"];
               Shopware()->Db()->query($sql);
            } catch (Exception $e) {
               die(json_encode(array('success' => false, 'message' => $e->getMessage())));
            }     
        }   
        die(json_encode(array('success' => ($cnt > 0))));
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

    public function removePseudoGroupsAction()
    {
       $data = $this->Request()->getParam('ids');
       $groups = json_decode($data);  
       $result = [];     
       foreach ($groups as $id) {
          if ($this->isPseudoGroup($id)) {
            $result[] = $id;
          }
       }        
       die(json_encode(array('success' => true, 'data' => $result)));
    }

}