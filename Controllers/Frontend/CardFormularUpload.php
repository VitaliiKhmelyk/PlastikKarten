<?php

class Shopware_Controllers_Frontend_CardFormularUpload extends Enlight_Controller_Action
{
	private $plugin;

	public function init()
	{
        $this->plugin = Shopware()->Container()->get('plugins')->Backend()->CardFormular();
	}

    public function indexAction()
    {
      return;
    }

    public function modalAction()
    {
		$sOption = (int) $this->Request()->getParam('sOption');

		$filesMakeTime						= $this->plugin->Config()->get('cf_files_make_time', '');
		$filesUploadSize					= $this->plugin->Config()->get('cf_files_upload_size', '');
		$filesUploadPreview					= $this->plugin->Config()->get('cf_files_upload_preview', 0);
		$filesUploadTypes					= $this->plugin->Config()->get('cf_files_types', '');
		$filesUploadErrorBackgroundColor	= $this->plugin->Config()->get('cf_files_error_color', '#FF0000');
		$filesUploadErrorMessage			= $this->plugin->Config()->get('cf_files_error_message', '');
		$filesUploadOptimalSize				= $this->plugin->Config()->get('cf_files_optimal_size', '');
		$filesUploadSizeMessage				= $this->plugin->Config()->get('cf_files_upload_size_message', '');

		if(stripos($filesUploadSize, 'gb')) {
			$maxUploadSize = (int)$filesUploadSize * 1024 * 1024 * 1024;
		} elseif(stripos($filesUploadSize, 'mb')) {
			$maxUploadSize = (int)$filesUploadSize * 1024 * 1024;
		} elseif(stripos($filesUploadSize, 'kb')) {
			$maxUploadSize = (int)$filesUploadSize * 1024;
		} else {
			$maxUploadSize = (int)$filesUploadSize;
		}

		if($maxUploadSize == 0) {
			$maxUploadSize = 50 * 1024;
		}


/*        $templateId = (int) $this->Request()->getParam('templateId');
        $template = $this->Request()->getParam("template");
        $background = $this->Request()->getParam('background');

        $sql = 'SELECT * FROM s_card_template WHERE id=' . $templateId;
        //$ex_template = Shopware()->Db()->fetchRow($sql, array(1));
        $ex_template = Shopware()->Db()->fetchOne($sql);
        $ex_background = @getimagesize($background);

        $fileurl = '';

        if (!$ex_background) {
            $directory = Shopware()->DocPath('media_card');
            if (!file_exists($directory)) mkdir($directory, 0777);
            $background = str_replace('data:image/jpeg;base64,','', $background);
            $background = str_replace('data:image/png;base64,','', $background);
            $background = str_replace(' ', '+', $background);
            
            $filename = md5(mt_rand()) . '.jpg';
            file_put_contents($directory . $filename, base64_decode($background));
            $fileurl = "fotodecke/media/card/" . $filename;
        }
        
        if (!$templateId) {
            $sql = "INSERT INTO s_card_template SET template = '" . $template . "', background='" . $fileurl . "'";
        }
        else {
            if (!$ex_background) $sql = "UPDATE s_card_template SET template = '" . $template . "', background='" . $fileurl . "' WHERE id=" . $templateId;
            else             $sql = "UPDATE s_card_template SET template = '" . $template . "' WHERE id=" . $templateId;
        }

        Shopware()->Db()->query($sql);

		$this->saveCardTemplateToArticleAction();
*/
        $this->View()->assign('sOption', $sOption);
        $this->View()->assign('filesUploadSize', $filesUploadSize);
        $this->View()->assign('maxUploadSize', $maxUploadSize);
        $this->View()->assign('filesUploadTypes', $filesUploadTypes);
        $this->View()->assign('filesUploadErrorBackgroundColor', $filesUploadErrorBackgroundColor);
        $this->View()->assign('filesUploadErrorMessage', $filesUploadErrorMessage);
        $this->View()->assign('filesUploadOptimalSize', $filesUploadOptimalSize);
        $this->View()->assign('filesUploadSizeMessage', $filesUploadSizeMessage);
    }

    public function uploadAction()
    {
	}
}
