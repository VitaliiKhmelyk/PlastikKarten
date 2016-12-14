<form class="form upload-modal-form" action="{url controller=CardFormularUpload action=upload sOption=$sOption}" id="upload-form-{$sOption}" method="post" name="upload-form-{$sOption}" enctype="multipart/form-data" novalidate="">
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
