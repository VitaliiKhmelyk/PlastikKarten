{if !empty($group_info)}
	<p class="modal--size-table link-show-tooltip" data-content="" data-modalbox="true" data-targetSelector="a" data-width="640" data-height="480" data-mode="ajax">
		<a href="javascript:void(0);" 	onclick="openModalInfo('{$sConfigurator.groupname}','{$group_info}')"	title="{s name='ShowTooltip' namespace='CardFormular'}{/s}">
		  	<i class="icon--info2" ></i>&nbsp;{s name='ShowTooltip' namespace='CardFormular'}{/s}
		</a>
	</p>
{else}
   <div class="product--data-spacer-cf"></div>	
{/if}