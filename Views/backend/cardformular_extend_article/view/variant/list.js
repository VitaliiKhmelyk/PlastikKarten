// File location: CardFormular/Views/backend/cardformular_extend_article/view/variant/list.js
//{block name="backend/article/view/variant/list" append}

Ext.define('Shopware.apps.CardformularExtendArticle.view.variant.list', {

    override: 'Shopware.apps.Article.view.variant.List',

    createDynamicColumns: function() {
        var me = this,
            result = me.callParent(arguments);
        var new_result = [];
        for (var i = 0, l = result.length; i < l; i++) {
          if  (!result[i]['configuratorGroup']['data']["pseudo"]) {
            new_result.push(result[i]);             
          }
        } 
        return new_result; 
    },    

});

//{/block}


