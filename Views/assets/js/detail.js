function setQtyTextInputVal(obj, is_forced, min_val, max_val) {
    var new_val = "";
    if ((obj.value == "") && !(is_forced))  {
    	new_val = min_val;
    } else {
    	new_val = Math.min(Math.max(obj.value - 0, min_val), max_val);
    	obj.value = new_val;
    }
    var elem = document.getElementById('sQuantity');
    if (elem) {
      elem.value = new_val;
    }
}

function setWorkflowStage(stage_idx) {
 var prefix = "workflow_stage_";
 var idx = 0;
 var srcObj;
 if ((stage_idx) || (stage_idx==0))  {
   idx = stage_idx
 } else {
   srcObj = document.getElementById("cf_custom_workflow_stage"); 
   if (srcObj) {
     idx = parseInt(srcObj.value);
   }
 }
 var maxVobj = document.getElementById(prefix + "cnt_");
 if ((maxVobj)&&(parseInt(maxVobj.value)>1)) {
    var maxV = parseInt(maxVobj.value);    
    var i;   
    var j;
    for (i = 0; i < maxV; i++) {
      if (i != idx) {
        srcObj = document.getElementsByClassName(prefix+i.toString());
        for (j = 0; j < srcObj.length; j++) {
            srcObj[j].style.display = 'none';
        }  
        srcObj = document.getElementById(prefix+"cnt_"+i.toString()); 
        if (srcObj) {
            srcObj.classList.remove('is--primary');
            srcObj.classList.add('is--secondary');
        } 
      }      
    }  
    srcObj = document.getElementsByClassName(prefix+idx.toString());
    for (j = 0; j < srcObj.length; j++) {
      srcObj[j].style.display = 'block';
    } 
    srcObj = document.getElementById(prefix+"cnt_"+idx.toString()); 
    if (srcObj) {
      srcObj.classList.remove('is--secondary');
      srcObj.classList.add('is--primary');
    }  
    srcObj = document.getElementById("cf_custom_workflow_stage"); 
    if (srcObj) {
      srcObj.value = idx;
    }   
 }
}    

function setSubgroupParentObj(id) {
   var srcObj = document.getElementsByClassName("child_subgroup_"+id+"_container");
   var targetObj = document.getElementById("parent_subgroup_"+id+"_container");
   if ((srcObj) && (targetObj)) {
        var i;
        for (i = 0; i < srcObj.length; i++) {
            targetObj.appendChild(srcObj[i]);
            srcObj[i].style.display = 'inline';
        }    
      
   }
}

function executeSetSubgroupParentObj() {
 if (typeof(aSubGroupsArray) !== 'undefined')  {
  for (var i = 0; i < aSubGroupsArray.length; i++) {
    setSubgroupParentObj(aSubGroupsArray[i]);
  }
 } 
}    

executeSetSubgroupParentObj();
setWorkflowStage();

function openModalInfo(title_str, content_str) {
	$.modal.open('<div style="padding:0 20px 20px 20px"><div style="width:100%"><h2>'+title_str+'</h2></div><div style="width:100%">'+content_str+'</div></div>', { title: title_str});
}

$.subscribe('plugin/swAjaxVariant/onRequestData', function(me, response, values, location) {
	executeSetSubgroupParentObj();
    setWorkflowStage();
    StateManager.addPlugin('select:not([data-no-fancy-select="true"])', 'swSelectboxReplacement')
		.addPlugin('*[data-image-slider="true"]', 'swImageSlider', { touchControls: true })
		.addPlugin('.product--image-zoom', 'swImageZoom', 'xl')
		.addPlugin('*[data-image-gallery="true"]', 'swImageGallery')
		.addPlugin('*[data-add-article="true"]', 'swAddArticle')
		.addPlugin('*[data-modalbox="true"]', 'swModalbox')

		// **** added for cf-template by vk ****
		// Detail page tab menus
		.addPlugin('.product--rating-link, .link--publish-comment', 'swScrollAnimate', {
			scrollTarget: '.tab-menu--product'
		})
		.addPlugin('.tab-menu--product', 'swTabMenu', ['s', 'm', 'l', 'xl'])
		.addPlugin('.tab-menu--cross-selling', 'swTabMenu', ['m', 'l', 'xl'])
		.addPlugin('.tab-menu--product .tab--container', 'swOffcanvasButton', {
			titleSelector: '.tab--title',
			previewSelector: '.tab--preview',
			contentSelector: '.tab--content'
		}, ['xs'])
		.addPlugin('.tab-menu--cross-selling .tab--header', 'swCollapsePanel', {
			'contentSiblingSelector': '.tab--content'
		}, ['xs', 's'])
		.addPlugin('body', 'swAjaxProductNavigation')
		.addPlugin('*[data-collapse-panel="true"]', 'swCollapsePanel')
		.addPlugin('*[data-range-slider="true"]', 'swRangeSlider')
		.addPlugin('*[data-auto-submit="true"]', 'swAutoSubmit')
		.addPlugin('*[data-drop-down-menu="true"]', 'swDropdownMenu')
		.addPlugin('*[data-newsletter="true"]', 'swNewsletter')
		.addPlugin('*[data-pseudo-text="true"]', 'swPseudoText')
		.addPlugin('*[data-preloader-button="true"]', 'swPreloaderButton')
		.addPlugin('*[data-filter-type]', 'swFilterComponent')
		.addPlugin('*[data-listing-actions="true"]', 'swListingActions')
		.addPlugin('*[data-scroll="true"]', 'swScrollAnimate')
		.addPlugin('*[data-ajax-wishlist="true"]', 'swAjaxWishlist')
		.addPlugin('*[data-image-gallery="true"]', 'swImageGallery');  
	// **** end of vk ****
});
/*
$.overridePlugin('swAjaxVariant', {
    requestData: function(values, pushState) {
            var me = this,
                stateObj = me._createHistoryStateObject();

            $.loadingIndicator.open({
                closeOnClick: false,
                delay: 100
            });

            $.publish('plugin/swAjaxVariant/onBeforeRequestData', [ me, values, stateObj.location ]);

            values += '&template=ajax';

            if(stateObj.params.hasOwnProperty('c')) {
                values += '&c=' + stateObj.params.c;
            }

            $.ajax({
                url: stateObj.location,
                data: values,
                method: 'GET',
                success: function(response) {
                    var $response = $($.parseHTML(response)),
                        $productDetails,
                        $productDescription,
                        ordernumber;

                    // Replace the content
                    $productDetails = $response.find(me.opts.productDetailsSelector);
                    $(me.opts.productDetailsSelector).html($productDetails.html());

                    // Replace the description box
                    $productDescription = $response.find(me.opts.productDetailsDescriptionSelector);
                    $(me.opts.productDetailsDescriptionSelector).html($productDescription.html());

                    // Get the ordernumber for the url
                    ordernumber = $.trim(me.$el.find(me.opts.orderNumberSelector).text());

                    StateManager.addPlugin('select:not([data-no-fancy-select="true"])', 'swSelectboxReplacement')
                        .addPlugin('*[data-image-slider="true"]', 'swImageSlider', { touchControls: true })
                        .addPlugin('.product--image-zoom', 'swImageZoom', 'xl')
                        .addPlugin('*[data-image-gallery="true"]', 'swImageGallery')
                        .addPlugin('*[data-add-article="true"]', 'swAddArticle')
                        .addPlugin('*[data-modalbox="true"]', 'swModalbox')

                        // **** added for cf-template by vk ****
                        // Detail page tab menus
                        .addPlugin('.product--rating-link, .link--publish-comment', 'swScrollAnimate', {
                            scrollTarget: '.tab-menu--product'
                        })
                        .addPlugin('.tab-menu--product', 'swTabMenu', ['s', 'm', 'l', 'xl'])
                        .addPlugin('.tab-menu--cross-selling', 'swTabMenu', ['m', 'l', 'xl'])
                        .addPlugin('.tab-menu--product .tab--container', 'swOffcanvasButton', {
                            titleSelector: '.tab--title',
                            previewSelector: '.tab--preview',
                            contentSelector: '.tab--content'
                        }, ['xs'])
                        .addPlugin('.tab-menu--cross-selling .tab--header', 'swCollapsePanel', {
                            'contentSiblingSelector': '.tab--content'
                        }, ['xs', 's'])
                        .addPlugin('body', 'swAjaxProductNavigation')
                        .addPlugin('*[data-collapse-panel="true"]', 'swCollapsePanel')
                        .addPlugin('*[data-range-slider="true"]', 'swRangeSlider')
                        .addPlugin('*[data-auto-submit="true"]', 'swAutoSubmit')
                        .addPlugin('*[data-drop-down-menu="true"]', 'swDropdownMenu')
                        .addPlugin('*[data-newsletter="true"]', 'swNewsletter')
                        .addPlugin('*[data-pseudo-text="true"]', 'swPseudoText')
                        .addPlugin('*[data-preloader-button="true"]', 'swPreloaderButton')
                        .addPlugin('*[data-filter-type]', 'swFilterComponent')
                        .addPlugin('*[data-listing-actions="true"]', 'swListingActions')
                        .addPlugin('*[data-scroll="true"]', 'swScrollAnimate')
                        .addPlugin('*[data-ajax-wishlist="true"]', 'swAjaxWishlist')
                        .addPlugin('*[data-image-gallery="true"]', 'swImageGallery');  
                        // **** end of vk ****


                    // Plugin developers should subscribe to this event to update their plugins accordingly
                    $.publish('plugin/swAjaxVariant/onRequestData', [ me, response, values, stateObj.location ]);

                    if(pushState && me.hasHistorySupport) {
                        var location = stateObj.location + '?number=' + ordernumber;

                        if(stateObj.params.hasOwnProperty('c')) {
                            location += '&c=' + stateObj.params.c;
                        }

                        window.history.pushState(stateObj.state, stateObj.title, location);
                    }
                },
                complete: function() {
                    $.loadingIndicator.close();
                }
            });
        }
});
*/
