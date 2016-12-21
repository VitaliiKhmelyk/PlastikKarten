function setQtyTextInputVal(is_forced) {    
    var obj = document.getElementById("qty");
    if (obj) {
      var min_val = parseInt(obj.min);    
      var max_val = parseInt(obj.max);
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
}

function setCountInputEvents() {
  var srcObj = document.getElementById("qty");
  if (srcObj) {    
    srcObj.onkeyup = function(){setQtyTextInputVal(false);};
    srcObj.oninput = function(){setQtyTextInputVal(false);};
    srcObj.onchange = function(){setQtyTextInputVal(true);};
    srcObj.onkeydown = function(event){
     var x = event.which || event.keyCode;
     return  ( event.ctrlKey || event.altKey 
                              || (47<x && x<58 && event.shiftKey==false) 
                              || (95<x && x<106)
                              || (x==8) || (x==9) 
                              || (x>34 && x<40) 
                              || (x==46) )
    };
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

function saveCustomParamsStatus(group_id, option_id) {
  var i, j, optns, grp_id, opt_id, srcObj, srcObjParent, newval;
  var prefix='custom';
  for (i = 0; i < aGroupsDataArray.length; i++) {
    grp_id = aGroupsDataArray[i][0];
    if (((group_id) && (group_id == grp_id)) || (!group_id)) {
      srcObjParent = document.getElementById(prefix + 'group['+ grp_id + ']'); 
      if (srcObjParent) {
        aGroupsDataArray[i][1]=srcObjParent.value;
      } else {
        aGroupsDataArray[i][1]="";
      } 
      optns = aGroupsDataArray[i][2];
      for (j = 0; j < optns.length; j++) {
        opt_id = optns[j][0];
        if (((option_id) && (option_id == opt_id)) || (!option_id)) {
          srcObj = document.getElementById(prefix + 'group['+ grp_id + ']['+opt_id+']');       
          if (srcObj) {
            aGroupsDataArray[i][2][j][1]=srcObj.value;
            newval = srcObj.value;
            if ((srcObj.getAttribute("type") == "radio") && !(srcObj.checked)) {
              newval = "";
            }
            if ((newval!="") && (aGroupsDataArray[i][1]==""))  {
              aGroupsDataArray[i][1] = srcObj.value;
            }
          } 
        }    
      }  
    }
  }  
  //console.log(aGroupsDataArray);
}

// function loadCustomParamsStatus() {
//   var i, j, optns, grp_id, opt_id, srcObj, srcObjParent;
//   var prefix='custom';
//   var event = new Event('change');
//   for (i = 0; i < aGroupsDataArray.length; i++) {
//     grp_id = aGroupsDataArray[i][0];
//     n = prefix + 'group['+ grp_id + ']';
//     srcObjParent = document.getElementById(n); 
//     if (srcObjParent) {          
//       if (srcObjParent.tagName && srcObjParent.tagName.toLowerCase() == "select") {
//          for (j = 0; j < srcObjParent.options.length; j++) {
//             if (srcObjParent.options[j].value == aGroupsDataArray[i][1]) { 
//                srcObjParent.options[j].selected = true;   
//                srcObjParent.dispatchEvent(event);            
//                break;          
//             }    
//          }
//       } else {
//         srcObjParent.value = aGroupsDataArray[i][1];  
//       }
//     } 
//     optns = aGroupsDataArray[i][2];
//     for (j = 0; j < optns.length; j++) {
//       opt_id = optns[j][0];
//       srcObj = document.getElementById(prefix + 'group['+ grp_id + ']['+opt_id+']');       
//       if (srcObj) {      
//         if (srcObj.getAttribute("type") == "radio") {
//             srcObj.checked = (aGroupsDataArray[i][1] == srcObj.value);
//         } else {
//           srcObj.value = aGroupsDataArray[i][2][j][1]; 
//         }
//       }     
//     }  
//   }  
// }

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

function openModalInfo(title_str, content_str) {
	$.modal.open('<div style="padding:0 20px 20px 20px"><div style="width:100%"><h2>'+title_str+'</h2></div><div style="width:100%">'+content_str+'</div></div>', { title: title_str});
}

function setLoadingMode() {
  $('.cf_ajax_container_price_value').html($('.cf_ajax_container_loading').html());  
  $('.price--discount-icon').hide();  
  $('.entry--content').html("");
}

function replaceActiveContent(response) {
var container,
    divContent= [ 
               'cf_ajax_container_common_info',
               'cf_ajax_container_price',
               'cf_ajax_container_product_txt',
               'cf_ajax_container_buy',
               'cf_ajax_container_market_price',
               'cf_ajax_container_order_info',
               'cf_ajax_container_count'
            ];  
  $.each( divContent, function( i, val ) {
     container = response.find('.'+val);
     $('.'+val).html(container.html());
  });
  container = response.find('.buybox--inner');
  $('.buybox--inner').attr('itemtype',container.attr('itemtype'));
  container = response.find('.cf_ajax_container_link1');
  $('.content--link.link--contact').attr('href',container.html()); 
  for (var i = 0; i < aGroupsDataArray.length; i++) {
    container = response.find('.cf_ajax_container_group_'+aGroupsDataArray[i][0]);
    $('.cf_ajax_container_group_'+aGroupsDataArray[i][0]).html(container.html());
  }
}  

if (isCardFormular) {

//console.log('card formular detected!');  

executeSetSubgroupParentObj();
setWorkflowStage();
setCountInputEvents();


$.overridePlugin('swAjaxVariant', {
    requestData: function(values, pushState) {
            var me = this,           
                flag_md5_obj = '.cf_ajax_container_rand',
                flag_obj = '.cf_ajax_container_flag';           
            
            setLoadingMode();

            if (($(flag_obj)) && ($(flag_obj).val()!="") && ($(flag_obj).val()!="0"))  {
               $(flag_md5_obj).val("");
               //console.log('waiting for completion!');
               return;
            }
            //console.log('ajax started');

            var flag_md5 = $(flag_md5_obj).val();
            $(flag_obj).val(flag_md5);            

            $.loadingIndicator.open({
                closeOnClick: false,
                delay: 5000
            });

            var stateObj = me._createHistoryStateObject();

            $.publish('plugin/swAjaxVariant/onBeforeRequestData', [ me, values, stateObj.location ]);

            values.template = 'ajax';
            values.templatemode = '_ajax';

            if(stateObj.params.hasOwnProperty('c')) {
                values.c = stateObj.params.c;
            }

            

            $.ajax({
                url: stateObj.location,
                data: values,
                method: 'GET',
                success: function(response) {
                    
                    if ($(flag_md5_obj).val() != flag_md5) {
                      //console.log('form has been changed!');
                      return;
                    }

                    var $response = $($.parseHTML(response)),
                        $container,
                        ordernumber;

                    // Replace the content
                    replaceActiveContent($response);
                                       
                    // Get the ordernumber for the url
                    ordernumber = $.trim(me.$el.find(me.opts.orderNumberSelector).text());

                    //Plugin developers should subscribe to this event to update their plugins accordingly
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
                   $(flag_obj).val(""); 
                   //console.log('ajax finished');
                   if ($(flag_md5_obj).val()=="") {
                      $(flag_md5_obj).val(Math.floor(Math.random()*1000000000));
                      //console.log('ajax recall'); 
                      $.loadingIndicator.close();
                      $(".call_ajax_repaint").trigger( "click" );
                   }
                   $.loadingIndicator.close();
                }
            });
        }
});

$.subscribe('plugin/swAjaxVariant/onBeforeRequestData', function(me, values, location) {    
    //console.log('onBeforeRequestData');  
    saveCustomParamsStatus();
});

$.subscribe('plugin/swAjaxVariant/onRequestData', function(me, response, values, location) {    
    //console.log('onRequestData');  
    setCountInputEvents();
    saveCustomParamsStatus();
});

};
