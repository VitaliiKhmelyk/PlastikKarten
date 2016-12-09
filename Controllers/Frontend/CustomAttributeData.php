<?php

class Shopware_Controllers_Frontend_CustomAttributeData extends Enlight_Controller_Action
{
    public function indexAction()
    {
      return;
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
        // if (empty($image['extension'])) {
        //     $image['extension'] = 'jpg';
        // }
        $mediaService = Shopware()->Container()->get('shopware_media.media_service');
        //$imageData['src']['original'] = $mediaService->getUrl('media/image/' . $image["path"] . "." . $image["extension"]);
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