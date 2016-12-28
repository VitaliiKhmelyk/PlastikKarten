// File location: CardFormular/Views/backend/cardformular_extend_article/view/variant/configurator/price_variation_rule.js
//{block name="backend/article/view/variant/configurator/price_variation_rule" append}

Ext.define('Shopware.apps.CardformularExtendArticle.view.variant.configurator.PriceVariationRule', {

    /**
     * Defines an override applied to a class.
     * @string
     */
    override: 'Shopware.apps.Article.view.variant.configurator.PriceVariationRule',
   

    /**
     * Overrides the createItems function of the overridden ExtJs object  
     * and inserts new columns
     * @return
     */

    refreshConfiguratorTree: function(configuratorGroupStore) {
        var me = this;
        me.callParent(arguments);
        if (configuratorGroupStore) {
          configuratorGroupStore.each( function(group) {
            if ((group.get('active')) && (group.get('pseudo'))) {
               var rootNode = me.configuratorTree.getRootNode();
               var n = rootNode.findChild("text", group.get('name'), true);
               if (n) { rootNode.removeChild(n); }
            }   
          });
        }
    },

});

//{/block}