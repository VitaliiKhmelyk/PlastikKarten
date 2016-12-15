// File location: CardFormular/Views/backend/cardformular_extend_article/view/variant/configurator/group_edit.js
//{block name="backend/article/view/variant/configurator/group_edit" append}

Ext.define('Shopware.apps.CardformularExtendArticle.view.variant.configurator.GroupEdit', {

    /**
     * Defines an override applied to a class.
     * @string
     */
    override: 'Shopware.apps.Article.view.variant.configurator.GroupEdit',
   
    width:800,
    height:740,

    /**
     * Overrides the createItems function of the overridden ExtJs object  
     * and inserts new columns
     * @return
     */
    createItems: function() {
        var me = this,
            result = me.callParent(arguments);

        me.attributeForm = Ext.create('Shopware.attribute.Form', {
            table: 's_article_configurator_groups_attributes',
            allowTranslation: false,
            translationForm: null
        });

        me.attributeForm.on('config-loaded', function() {
           if (me.record) { me.attributeForm.loadAttribute(me.record.get('id')); }
        }, me.attributeForm, { single: true });

        result[0].add(me.attributeForm);      
        
        return result;
    }

});

//{/block}

