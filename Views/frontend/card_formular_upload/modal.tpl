<div id="message-upload-form-{$sOption}" class="is--hidden error-message" style="background: {$filesUploadErrorBackgroundColor};"></div>
<form
	class="form upload-modal-form"
	action="{url controller=CardFormularUpload action=upload sOption=$sOption}"
	id="upload-form-{$sOption}"
	method="post"
	name="upload-form-{$sOption}"
	enctype="multipart/form-data"
	onsubmit="return validate{$sOption}({$maxUploadSize})"
	data-mode="ajax"
	>
	<div class="class_uploadtable form-top-aligned form-top-aligned container-normal" style="">

		{if $filesUploadSize neq ''}
			<div class="fieldcontainer currentPage currentPageActive">
				{s name="FilesMaxUploadMessage" namespace="CardFormular"}{/s}: {$filesUploadSize}
			</div>
		{/if}
		{if $filesUploadTypes neq ''}
			<div class="fieldcontainer currentPage currentPageActive">
				{s name="FilesUploadTypesMessage" namespace="CardFormular"}{/s}: {$filesUploadTypes}
			</div>
		{else}
			<div class="fieldcontainer currentPage currentPageActive">
				{s name="FilesUploadTypesMessage" namespace="CardFormular"}{/s}: {s name="FilesUploadTypesAll" namespace="CardFormular"}{/s}
			</div>
		{/if}
		{if $filesUploadOptimalSize}
			<div class="fieldcontainer currentPage currentPageActive">
				{s name="FilesUploadOptimalSizeMessage" namespace="CardFormular"}{/s}: {$filesUploadOptimalSize}
			</div>
		{/if}
		<div id="fieldcontainer" class="fieldcontainer currentPage currentPageActive">
			<div id="row4" class="fieldtype-6-9 row row-fluid currentPage currentPageActive">
				<div class="subitem col-lg-12 rowup col-md-12 col-sm-12">
					<label class="class-label class-fieldname" id="id-{$sOption}-title" for="id-{$sOption}-control">{s name="FilesUploadPreviewMessage" namespace="CardFormular"}{/s}</label>
				</div>
			</div>
			<div id="rowsec4" class="fieldcontainer rowdown currentPage currentPageActive">
				<div class="row rowdownsmall align-left">
					<input type="hidden" id="errorflagrow4-{$sOption}" value="">
					<span class="btn fileinput-button" id="fileinput-button-{$sOption}">
						{s name="FilesUploadMessage" namespace="CardFormular"}{/s}
						<input class="low_zindex_upload_button no-validation-error" id="fileupload-{$sOption}" type="file" name="file-{$sOption}">
					</span>
					<div id="newfilesattached-{$sOption}" class="files"></div>
				</div>
				<div class="clear"></div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="subitem col-lg-12 col-md-12 col-sm-12">
		<div id="checkout_form_con"></div>
	</div>
</div>
<!-- Buttons begin here -->
<div id="fieldcontainer-buttons" class="fieldcontainer thebuttons">
	<div class="row currentPage currentPageActive">
		<div class="subitem col-lg-12 rowup align-left col-md-12 col-sm-12">
			<button onclick="/*this.style.display='none'; insertPleaseWaitDiv(this,'Please wait...'); (function(self) { UploadManager.setSubmitOriginButton( self ); var hiddenInput = document.createElement('input'); $(hiddenInput).attr('name', this.name || '').attr('value', this.value || '').attr('type', 'hidden'); $(self.form).append(hiddenInput); })(this); */" type="submit" class="no-default-image btn submit-btn formdefaultbut ripple-effect" id="id-{$sOption}-button-send" value="{s name='SupportActionSubmit' namespace='frontend/forms/elements'}{/s}">{s name='SupportActionSubmit' namespace='frontend/forms/elements'}{/s}</button>
		</div>
	</div>
</div> 
<div class="clear"></div>
</form>

<script type="text/javascript">
function validate{$sOption}(max_img_size) {
	var input_size = $('#fileupload-{$sOption}')[0].files[0].size;
	// check for browser support (may need to be modified)
	if(input_size > max_img_size) {
		var message = '{s name="FilesMaxUploadMessage" namespace="CardFormular"}{/s}' + ': ' + (max_img_size/1024/1024) + 'MB';
		$('#message-upload-form-{$sOption}').html(message).removeClass('is--hidden');
		return false;
	}

	var $form = $('#upload-form-{$sOption}'),
		formUrl = $form.attr('action'),
		file = $('#fileupload-{$sOption}')[0].files[0];
		data = new FormData();
console.log(file);

	data.append('file-{$sOption}', file, file.name);
console.log(data);
console.log(data.get('file-{$sOption}'));

/*	var serData = {};
	$.each($form.serializeArray(), function(i, data) {
		serData[data.name] = data.value;
	});*/

	var xhr = new XMLHttpRequest();     
	xhr.open('POST', formUrl, true);  
	xhr.send(data);
	xhr.onload = function () {                  
		if (xhr.status === 200) {
console.log('Ok');
console.log($.parseJSON(xhr.response));
		} else {
console.log('Fail');
console.log($.parseJSON(xhr.response));
		}
	}
  
/*	$.ajax({
		url: formUrl,
		//dataType: 'json',
		data: serData,
		async: true,
		method: 'POST',
 
		success: function(data) {
			console.log(data);
			$('#message-upload-form-{$sOption}').html(data).removeClass('is--hidden');
		},
		error: function(e,a,b) {
			console.log('error');
			console.log(e,a,b);
		}
	});*/

	return false;
}
</script>
