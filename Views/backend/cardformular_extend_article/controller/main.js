// File location: CardFormular/Views/backend/cardformular_extend_article/controller/main.js
//{block name="backend/article/controller/main" append}

Ext.define('Shopware.apps.CardformularExtendArticle.controller.Main', {

    /**
     * Defines an override applied to a class.
     * @string
     */
    override: 'Shopware.apps.Article.controller.Main',

    prepareAssociationStores: function(data) {
      var me = this,
          res = me.callParent(arguments);
      var sSkip='[pseudo]';
      var s;
      var isPseudo;
      res['configuratorGroups'].each(function(group) {
              s = group.get("description");
              isPseudo = (s.indexOf(sSkip)!=-1);
              group.set('pseudo', isPseudo);   
              if (isPseudo) {
                group.set("description", s.replace(sSkip, ''));
              }        
      });
      return res;
    },  

});

//{/block}