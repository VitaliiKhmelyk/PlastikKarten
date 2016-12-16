<?php

use Shopware\Components\CSRFWhitelistAware;

class Shopware_Controllers_Frontend_CardFormularUpload extends Enlight_Controller_Action implements CSRFWhitelistAware
{
	private $plugin;

	const SNIPPET_FILESUPLOADERROR = [
		'namespace' => 'CardFormular',
		'name' => 'FilesUploadError',
		'default' => 'Error loading file'
	];
	const SNIPPET_FILESUPLOADSTORAGEERROR = [
		'namespace' => 'CardFormular',
		'name' => 'FilesUploadStorageError',
		'default' => 'Error loading file to the storage'
	];
	const SNIPPET_FILESUPLOADTYPEERROR = [
		'namespace' => 'CardFormular',
		'name' => 'FilesUploadTypeError',
		'default' => 'Unsupported file type'
	];
	const SNIPPET_FILESUPLOADSIZEERROR = [
		'namespace' => 'CardFormular',
		'name' => 'FilesUploadSizeError',
		'default' => 'The file is too large'
	];
	const SNIPPET_FILESUPLOADGLOBALERROR = [
		'namespace' => 'CardFormular',
		'name' => 'FilesUploadGlobalError',
		'default' => 'No print file has been uploaded, so this page will not be printed'
	];
	const SNIPPET_FILESUPLOADERRORREASON = [
		'namespace' => 'CardFormular',
		'name' => 'FilesUploadErrorReason',
		'default' => 'Reason'
	];
	const SNIPPET_FILESUPLOADCOMPLETE = [
		'namespace' => 'backend/performance/main',
		'name' => 'request/done_message',
		'default' => 'Operation complete'
	];

	public function getWhitelistedCSRFActions()
    {
        return [
            'upload'
        ];
    }
    
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
error_log('function: ');
error_log(__METHOD__);
		$sOption = $this->Request()->getParam('sOption');
		$sFile = $this->Request()->get('file-'.$sOption);

		$this->Front()->Plugins()->Json()->setRenderer(false);

		$filesUploadTypes					= $this->plugin->Config()->get('cf_files_types', '');
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

/*error_log('_FILES');
ob_start();
print_r($_FILES);
$contents = ob_get_contents();
ob_end_clean();
error_log($contents);*/
		if($_FILES['file-'.$sOption]['error'] !== UPLOAD_ERR_OK) {
			// error upload file from form
			$responce = array(
				'complete'				=> 1,
				'success'				=> false,
				'message'				=> $this->getSnippet(self::SNIPPET_FILESUPLOADGLOBALERROR),
				'reason'				=> $this->getSnippet(self::SNIPPET_FILESUPLOADERRORREASON) . ': ' . $this->getSnippet(self::SNIPPET_FILESUPLOADERROR),
            );
        } else {
			// success upload file from form
			$sFile = $_FILES['file-'.$sOption];
error_log('sFile');
ob_start();
print_r($sFile);
$contents = ob_get_contents();
ob_end_clean();
error_log($contents);
			if($sFile['size'] > $maxUploadSize) {
				// fail check file size
				$responce = array(
					'complete'				=> 1,
					'success'				=> false,
					'message'				=> $this->getSnippet(self::SNIPPET_FILESUPLOADGLOBALERROR),
					'reason'				=> $this->getSnippet(self::SNIPPET_FILESUPLOADERRORREASON) . ': ' . $this->getSnippet(self::SNIPPET_FILESUPLOADSTORAGEERROR),
				);
			} else {
				$finfo = finfo_open(FILEINFO_MIME_TYPE);

				if(!$finfo) {
					// fail prepare file mime type
					$responce = array(
						'complete'				=> 1,
						'success'				=> false,
						'message'				=> $this->getSnippet(self::SNIPPET_FILESUPLOADGLOBALERROR),
						'reason'				=> $this->getSnippet(self::SNIPPET_FILESUPLOADERRORREASON) . ': ' . $this->getSnippet(self::SNIPPET_FILESUPLOADSTORAGEERROR),
					);
				} else {
					$mime = finfo_file($finfo, $sFile['tmp_name']);
error_log('mime');
ob_start();
print_r($mime);
$contents = ob_get_contents();
ob_end_clean();
error_log($contents);
					$types = explode(',', $filesUploadTypes);
					$mimeTest = false;
					for($i = 0; $i < count($types); $i++) {
						if(stripos($mime, $types[$i])) {
							$mimeTest = true;
							break;
						}
					}

					if(!$mimeTest) {
						// fail check file mime type
						$responce = array(
							'complete'				=> 1,
							'success'				=> false,
							'message'				=> $this->getSnippet(self::SNIPPET_FILESUPLOADGLOBALERROR),
							'reason'				=> $this->getSnippet(self::SNIPPET_FILESUPLOADERRORREASON) . ': ' . $this->getSnippet(self::SNIPPET_FILESUPLOADTYPEERROR),
						);
					} else {
						$uploadResult = $this->plugin->ftpUpload($sFile);
						if(!$uploadResult) {
							// fail upload file to ftp
							$responce = array(
								'complete'				=> 1,
								'success'				=> false,
								'message'				=> $this->getSnippet(self::SNIPPET_FILESUPLOADGLOBALERROR),
								'reason'				=> $this->getSnippet(self::SNIPPET_FILESUPLOADERRORREASON) . ': ' . $this->getSnippet(self::SNIPPET_FILESUPLOADTYPEERROR),
							);
						} else {
							$responce = array(
								'complete'				=> 1,
								'success'				=> true,
								'message'				=> $this->getSnippet(self::SNIPPET_FILESUPLOADCOMPLETE),
								'src'					=> $uploadResult,
							);
						}
					}
				}
			}
		}

		echo json_encode($responce);
	}

    private function getSnippet(array $snippet)
    {
        return Shopware()->Snippets()->getNamespace($snippet['namespace'])->get($snippet['name'], $snippet['default'], true);
    }
}
