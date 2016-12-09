// File location: CardFormular/Views/backend/cardformular_extend_article/view/variant/configurator/option_edit.js
//{block name="backend/article/view/variant/configurator/option_edit" append}

Ext.define('Shopware.apps.CardformularExtendArticle.view.variant.configurator.OptionEdit', {

    /**
     * Defines an override applied to a class.
     * @string
     */
    override: 'Shopware.apps.Article.view.variant.configurator.OptionEdit',

    width:600,
    height:500,

    /**
     * Overrides the createItems function of the overridden ExtJs object
     * and inserts new columns
     * @return
     */
    createItems: function() {
        var me = this,
            result = me.callParent(arguments);

        result[0].add(me.mediaIdField = Ext.create('Shopware.form.field.Media', {
            name: 'cfMediaid',
            fieldLabel: '{s name="OptionsMedia" namespace="CardFormular"}Media source{/s}'
        }));

        me.attributeForm = Ext.create('Shopware.CardformularExtendBase.attribute.Form', {
            table: 's_article_configurator_options_attributes',
            allowTranslation: false,
            translationForm: null
        });

        me.attributeForm.on('config-loaded', function() {
           if (me.record) { 
              me.attributeForm.loadAttribute(me.record.get('id')); 
              me.attributeForm.loadMediaAttribute(me.record.get('id'), 'optionID', me.mediaIdField); 
           }
        }, me.attributeForm, { single: true });

        result[0].add(me.attributeForm);      

        return result;
    }

});

//{/block}

