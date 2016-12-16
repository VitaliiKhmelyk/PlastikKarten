// File location: CardFormular/Views/backend/cardformular_extend_article/view/variant/list.js
//{block name="backend/article/view/variant/list" append}

Ext.define('Shopware.apps.CardformularExtendArticle.view.variant.list', {

    override: 'Shopware.apps.Article.view.variant.List',

    hideDynamicColumns: function(ids) {
      //console.log(ids);  
    },    

    createDynamicColumns: function() {
        var me = this,
            ids = [],
            excl_ids = [],            
            result = me.callParent(arguments);
        for (var i = 0, l = result.length; i < l; i++) {
           ids.push(result[i]['configuratorGroup']['data']["id"]);   
        }    
        Ext.Ajax.request({
            url: '{url controller=CustomAttributeData action=removePseudoGroups}',
            method: 'POST',
            async: false,   //change later
            cache: false,
            timeout: 20000,
            params: {
                ids : Ext.encode(ids)
            },
            success: function(response) {
                var res = Ext.decode(response.responseText);
                excl_ids = res['data'];
                me.hideDynamicColumns(excl_ids);
            }
        });
        if (excl_ids.length > 0) {
            var new_result = [];
            for (var i = 0, l = result.length; i < l; i++) {
               if (!Ext.Array.contains(excl_ids,result[i]['configuratorGroup']['data']["id"])) {
                 new_result.push(result[i]);
               }           
            }    
            return new_result;    
       } else {
         return result;    
       } 
    },    

});

//{/block}


