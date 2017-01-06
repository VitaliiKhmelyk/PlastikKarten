/**
 * aGroupsDataArray indexes:
 * 0 = group id
 * 1 = group value
 * 2 = options array
 * 3 = true if pseudo group
 * 4 = true if disabled group
 * 5 = group type (string)
 * 6 = true if group has single value (selectbox or radiobox)
 * 7 = parent subgroup id (>0 if current group is subgroup) 
 * 8 = current visible and enabled status (actual for pseudo groups)
 *
 * options indexes:
 * 0 = option id
 * 1 = option value
 * 2 = subgroup id (>0 if contains subgroup)
*/

const GRP_IDX = 0;
const GRP_VALUE = 1;
const GRP_OPTIONS = 2;
const GRP_PSEUDO = 3;
const GRP_DISABLED = 4;
const GRP_TYPE = 5;
const GRP_SINGLE_VAL = 6;
const GRP_SUBID = 7;
const GRP_VISIBLE = 8;

const OPT_IDX = 0;
const OPT_VALUE = 1;
const OPT_SUBID = 2;
const OPT_DESIGN_ORIG = 3;
const OPT_DESIGN_DATA = 4;

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
 srcObj = document.getElementById("workflow_stage_cnt_"+idx.toString()); 
 if ((srcObj) && (srcObj.classList.contains('is--disabled'))) {
   return;
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
        if ((srcObj) && (!(srcObj.classList.contains('is--disabled')))) {
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
    if ((srcObj) && (!(srcObj.classList.contains('is--disabled')))) {
      srcObj.classList.remove('is--secondary');
      srcObj.classList.add('is--primary');
    }  
    srcObj = document.getElementById("cf_custom_workflow_stage"); 
    if (srcObj) {
      srcObj.value = idx;
    }   
 }
}  

function checkWorkflowStage() {
  var idx, stage_empty, srcObj;
  if (typeof(aWorkflowDataArray) !== 'undefined')  {
    srcObj = document.getElementById("cf_custom_workflow_stage"); 
    if (srcObj) {
     idx = parseInt(srcObj.value);
    } else {
     idx = 0;
    }
    for (var i = 0; i < aWorkflowDataArray.length; i++) {
      stage_empty = true;
      for (var j = 0; j < aWorkflowDataArray[i].length; j++) {
        if (stage_empty) {
          for (var k = 0; k < aGroupsDataArray.length; k++) {
             if (aGroupsDataArray[k][GRP_IDX]==aWorkflowDataArray[i][j]) {
               stage_empty = (aGroupsDataArray[k][GRP_DISABLED])&&(!(aGroupsDataArray[k][GRP_PSEUDO])); 
               break; 
             }
          }
        }
      }
      srcObj = document.getElementById("workflow_stage_cnt_"+i.toString()); 
      if (srcObj) {
        if (stage_empty) {
         if (i > 0) {  
           srcObj.classList.remove('is--primary');
           srcObj.classList.remove('is--secondary');
           srcObj.classList.add('is--disabled');
           if (i==idx) {
              setWorkflowStage(0);
           }
         }
        } else {  
           srcObj.classList.remove('is--disabled');
           if (i==idx) {
              srcObj.classList.add('is--primary');
           } else {
              srcObj.classList.add('is--secondary');
           }
        }    
      } 
    }
  }
}  

function hideUnusedPseudoSubgroupValues(subgroup_id) {
  var i, j, optns;
  for (i = 0; i < aGroupsDataArray.length; i++) {
    if ((aGroupsDataArray[i][GRP_SUBID]==subgroup_id)&&(aGroupsDataArray[i][GRP_PSEUDO])) {
       aGroupsUnusedArray.push(aGroupsDataArray[i][GRP_IDX]);
       optns = aGroupsDataArray[i][GRP_OPTIONS];
       for (j = 0; j < optns.length; j++) { 
         if (optns[j][OPT_SUBID]>0) {
           hideUnusedPseudoSubgroupValues(optns[j][OPT_SUBID]);
         }  
       }        
    } 
  }  
}  

function hideUnusedPseudoValues() {
  var i, j, optns, data, srcObj, isOn; 
  aGroupsUnusedArray = [];
  for (i = 0; i < aGroupsDataArray.length; i++) {
   if ((aGroupsDataArray[i][GRP_SINGLE_VAL])||(aGroupsDataArray[i][GRP_DISABLED])) { //radio or select or disabled
     optns = aGroupsDataArray[i][GRP_OPTIONS];
     for (j = 0; j < optns.length; j++) {
       if (((aGroupsDataArray[i][GRP_DISABLED])||((aGroupsDataArray[i][GRP_VALUE]+'')!=(optns[j][OPT_IDX]+''))) && (optns[j][OPT_SUBID]>0)) {
         hideUnusedPseudoSubgroupValues(optns[j][OPT_SUBID]);
       }
     } 
   }
  }
  for (i = 0; i < aGroupsDataArray.length; i++) {  
    isOn = 1;
    for (j = 0; j < aGroupsUnusedArray.length; j++) {
       if (aGroupsDataArray[i][GRP_IDX]==aGroupsUnusedArray[j]) {
         isOn = 0;
         break; 
       }
    }
    aGroupsDataArray[i][GRP_VISIBLE]=(isOn==1);
    srcObj = document.getElementById('pseudo-' +aGroupsUnusedArray[j]);
    if (srcObj) {
      data = JSON.parse(srcObj.value);
      if (data.length>2) {
        data[2] = isOn;
        srcObj.value=JSON.stringify(data); 
      }
    } 
  }  
}

function drawDesignSurface() {
  var i, id = -1;
  for (i = 0; i < aGroupsDataArray.length; i++) {
    if (aGroupsDataArray[i][GRP_TYPE]=="DesignCanvas") {
      id = i;
      break;
    }
  }  
  if (id < 0) { return; }
}

function setSubgroupState(idx) {
  var j, b, prefix,
  group_val = parseInt(aGroupsDataArray[idx][GRP_VALUE]), 
  optns = aGroupsDataArray[idx][GRP_OPTIONS];  
  for (j = 0; j < optns.length; j++) {   
    b = (optns[j][OPT_IDX]!=group_val);
    prefix = '.cf_ajax_container_group_'+aGroupsDataArray[idx][GRP_IDX]+'_'+optns[j][OPT_IDX];
    if (aGroupsDataArray[idx][GRP_TYPE]=="SelectBox") {
      setObjDisplayStyle(prefix+'.cf_ajax_type_selectbox_sub',b);
    } 
    if (aGroupsDataArray[idx][GRP_TYPE]=="RadioBox") {  
      setObjDisplayStyle(prefix+'.cf_ajax_type_radio_sub',b);
    }  
  }
}  

function saveCustomParamsStatus(group_id, option_id) {
  var i, j, optns, grp_id, opt_id, srcObj, srcObjParent, newval, arrayGrps,  arrayOpt, arrayOpts;
  var prefix='custom';
  var need_check_pseudo=false;
  for (i = 0; i < aGroupsDataArray.length; i++) {
    grp_id = aGroupsDataArray[i][GRP_IDX];
    if (((group_id) && (group_id == grp_id)) || (!group_id)) {
      need_check_pseudo = (need_check_pseudo || aGroupsDataArray[i][GRP_SINGLE_VAL]);
      srcObjParent = document.getElementById(prefix + 'group['+ grp_id + ']'); 
      if (!srcObjParent) {
        srcObjParent = document.getElementById('group['+ grp_id + ']'); 
      }  
      if (srcObjParent) {
        aGroupsDataArray[i][GRP_VALUE]=srcObjParent.value;
      } else {
        aGroupsDataArray[i][GRP_VALUE]="";        
      }
      arrayOpts = new Array(); 
      optns = aGroupsDataArray[i][GRP_OPTIONS];
      for (j = 0; j < optns.length; j++) {        
        opt_id = optns[j][OPT_IDX];        
        if (((option_id) && (option_id == opt_id)) || (!option_id)) {
          srcObj = document.getElementById(prefix + 'group['+ grp_id + ']['+opt_id+']');       
          if (!srcObj) {
            srcObj = document.getElementById('group['+ grp_id + ']['+opt_id+']');    
          }
          if (srcObj) {
            aGroupsDataArray[i][GRP_OPTIONS][j][OPT_VALUE]=srcObj.value;
            newval = srcObj.value;
            if ((srcObj.getAttribute("type") == "radio") && !(srcObj.checked)) {
              newval = "";
            }
            if ((newval!="") && (aGroupsDataArray[i][1]==""))  {
              aGroupsDataArray[i][GRP_VALUE] = srcObj.value;
            }
          } 
        }
        arrayOpt = new Array(); 
        arrayOpt.push(opt_id);
        arrayOpt.push(aGroupsDataArray[i][GRP_OPTIONS][j][OPT_VALUE]);
        arrayOpts.push(arrayOpt);    
      }
      srcObj = document.getElementById('pseudo-' + grp_id);
      if (srcObj) {
        arrayGrps = new Array();
        arrayGrps.push(aGroupsDataArray[i][GRP_TYPE]);
        if (aGroupsDataArray[i][GRP_SINGLE_VAL]) {
            arrayGrps.push(aGroupsDataArray[i][GRP_VALUE]);
        } else {
            arrayGrps.push(arrayOpts);
        }
        arrayGrps.push(1);
        srcObj.value =JSON.stringify(arrayGrps);
        //console.log(srcObj.value);
      }
      if (group_id) {
        setSubgroupState(i);
      }  
    }    
  }
  if (need_check_pseudo) {
    hideUnusedPseudoValues();
  }
  drawDesignSurface();
  //console.log(aGroupsDataArray);
}

function setDefaultDesignParams() {
  var i, j, optns, obj, data;
  for (i = 0; i < aGroupsDataArray.length; i++) {
    optns = aGroupsDataArray[i][GRP_OPTIONS];
    for (j = 0; j < optns.length; j++) { 
      data=[];
      if (optns[j][OPT_DESIGN_ORIG]!='') {
        obj=JSON.parse(optns[j][OPT_DESIGN_ORIG]);
        //console.log(obj);
      }
      optns[j][OPT_DESIGN_DATA]=JSON.stringify(data);
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

function openModalInfo(title_str, content_str) {
	$.modal.open('<div style="padding:0 20px 20px 20px"><div style="width:100%"><h2>'+title_str+'</h2></div><div style="width:100%">'+content_str+'</div></div>', { title: title_str});
}

function setLoadingMode() {
  $('.cf_ajax_container_price_value').html($('.cf_ajax_container_loading').html());  
  $('.price--discount-icon').hide();  
  $('.entry--content').html("");
}

function setObjDisplayStyle(ident, off_condition) {
  var s;
  $(ident).each( function() {
  if (off_condition) {s='none';} else {s='';}
     this.style.display = s;
  });
}

function replaceActiveContent(response) {  
var container, container2, optns, data, data_arr, s, prefix, cnt,
    divContent= [ 
               'cf_ajax_container_common_info',
               'cf_ajax_container_price',
               'cf_ajax_container_product_txt',
               'cf_ajax_container_buy',
               'cf_ajax_container_market_price',
               'cf_ajax_container_order_info',
               'cf_ajax_container_count',
               'cf_ajax_container_comments'
            ];  
  //fixed divs
  $.each( divContent, function( i, val ) {
     container = response.find('.'+val);
     if (container.length > 0 ) { 
       $('.'+val).html(container.html());
     } else {
       $('.'+val).html(''); 
     }
  });
  //itemtype for buybox
  container = response.find('.buybox--inner');
  $('.buybox--inner').attr('itemtype',container.attr('itemtype'));
  //link for description
  container = response.find('.cf_ajax_container_link1');
  $('.content--link.link--contact').attr('href',container.html()); 
  //groups
  for (var i = 0; i < aGroupsDataArray.length; i++) {
    cnt=0;
    //combobox
    prefix = '.cf_ajax_container_group_'+aGroupsDataArray[i][GRP_IDX];
    container = response.find(prefix+'.cf_ajax_type_selectbox');   
    if (container.length > 0) { 
       container2 = $(prefix+'.cf_ajax_type_selectbox');
       if (container2.length > 0) { 
           container2.html(container.html());
       } 
      container = response.find(prefix+'.cf_data');  
      if (container.length > 0) {  
        data_arr=(container.html()).split(";");
        for (var j = 0; j < data_arr.length; j++) {
           data_arr[j]=(data_arr[j]).split(",");
           data_arr[j][0]=parseInt(data_arr[j][0]);
           if ((j > 0) && (data_arr[j][0] > 0) && (data_arr[j][1]!='0')) { cnt += 1;} ;
        }  
        aGroupsDataArray[i][GRP_DISABLED]=(data_arr[0][0]!=0);      
        setObjDisplayStyle(prefix+'_na',(cnt > 0));
        optns = aGroupsDataArray[i][GRP_OPTIONS];
        for (var j = 0; j < optns.length; j++) {
           data=[];
           for (var k = 1; k < data_arr.length; k++) {
              if (data_arr[k][0]==optns[j][OPT_IDX]) {
                data=data_arr[k];
                break;
              }
           }
           if (data.length > 0 ) {  
              setObjDisplayStyle(prefix+'_'+optns[j][OPT_IDX]+'.cf_ajax_type_selectbox_sub',((data[1]=='0') || (data_arr[0][0]==1) || (data[2]=='0')));
           } 
        }
      }
    } else {
       //radiobox
       container = response.find(prefix+'.cf_ajax_type_radio');
       if (container.length > 0 ) { 
         data_arr=(container.html()).split(";");
         for (var j = 0; j < data_arr.length; j++) {
           data_arr[j]=(data_arr[j]).split(",");
           data_arr[j][0]=parseInt(data_arr[j][0]);
           if ((j > 0) && (data_arr[j][0] > 0) && (data_arr[j][1]!='0')) { cnt += 1;} ;
         } 
         aGroupsDataArray[i][GRP_DISABLED]=(data_arr[0][0]!=0); 
         container = $(prefix);
         if (container.length > 0 ) {                  
            s='disabled_object';
            if (data_arr[0][0]==0) {
              container.removeClass(s);
            } else {
              container.addClass(s);  
            }
         }
         setObjDisplayStyle(prefix+'_na',(cnt > 0));
         optns = aGroupsDataArray[i][GRP_OPTIONS];
         for (var j = 0; j < optns.length; j++) {
            s=prefix+'_'+optns[j][OPT_IDX];
            setObjDisplayStyle(s+'.cf_ajax_type_radio_markup',(data_arr[0][1]=='0'));
            data=[];
            for (var k = 1; k < data_arr.length; k++) {
              if (data_arr[k][0]==optns[j][OPT_IDX]) {
                data=data_arr[k];
                break;
              }
            } 
            if (data.length > 0 ) {               
               setObjDisplayStyle(s+'.cf_ajax_type_radio',(data[1]=='0'));
               setObjDisplayStyle(s+'.cf_ajax_type_radio_sub',((data[1]=='0') || (data_arr[0][0]==1) || (data[2]=='0')));
               $(s+'.cf_ajax_type_radio_btn').each( function() {
                   this.checked = ((data[1]=='1') && (data[2]=='1'));
               });
               container = $(s+'.cf_data_markup');
               if (container.length > 0 ) {                  
                 container.html(data[3]);
               }
            }  
         }  
       }  
    }
  }
}  

if (isCardFormular) {

//console.log('card formular detected!'); 
setDefaultDesignParams();
executeSetSubgroupParentObj();
saveCustomParamsStatus();
checkWorkflowStage();
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
                    StateManager.addPlugin('select:not([data-no-fancy-select="true"])', 'swSelectboxReplacement')
                        .addPlugin('*[data-add-article="true"]', 'swAddArticle')
                        .addPlugin('*[data-modalbox="true"]', 'swModalbox');
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

$.subscribe('plugin/swAjaxVariant/onRequestData', function(me, response, values, location) {    
    //console.log('onRequestData');  
    setCountInputEvents();
    saveCustomParamsStatus();
    checkWorkflowStage();
});

};
