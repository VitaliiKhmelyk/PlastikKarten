// File location: CardFormular/Views/backend/cardformular_extend_article/controller/variant.js
//{block name="backend/article/controller/variant" append}

Ext.define('Shopware.apps.CardformularExtendArticle.controller.Variant', {

    /**
     * Defines an override applied to a class.
     * @string
     */
    override: 'Shopware.apps.Article.controller.Variant',

    onSaveGroup: function(group, form, window) {
        var me = this;
        if (!form.getForm().isValid()) {
            return;
        }
        form.getForm().updateRecord(group);        
        var name = group.get('name');
        group.save({
            success: function(record, operation) {                
                window.attributeForm.saveAttribute(record.get('id'));                
                window.destroy();
                var message = Ext.String.format(me.snippets.success.groupSave, name);
                Shopware.Notification.createGrowlMessage(me.snippets.success.title, message, me.snippets.growlMessage);
                me.getConfiguratorGroupListing().reconfigure(me.getConfiguratorGroupListing().getStore());
            },
            failure: function(record, operation) {
                window.destroy();
                var rawData = record.getProxy().getReader().rawData,
                    message = rawData.message;

                if (Ext.isString(message) && message.length > 0) {
                    message = Ext.String.format(me.snippets.failure.groupSave, name) + '<br>' + message;
                } else {
                    message = Ext.String.format(me.snippets.failure.groupSave, name) + '<br>' + me.snippets.failure.noMoreInformation;
                }
                Shopware.Notification.createGrowlMessage(me.snippets.failure.title, message, me.snippets.growlMessage);
            }
        });
    },

    onSaveOption: function(option, form, window) {
        var me = this;
        if (!form.getForm().isValid()) {
            return;
        }
        form.getForm().updateRecord(option);        
        var name = option.get('name');
        option.save({
            success: function(record, operation) {
                window.attributeForm.saveAttribute(record.get('id'));
                window.attributeForm.saveMediaAttribute(record.get('id'), 'optionID', record.get('cfMediaid'));
                window.destroy();
                var message = Ext.String.format(me.snippets.success.optionSave, name);
                Shopware.Notification.createGrowlMessage(me.snippets.success.title, message, me.snippets.growlMessage);
                me.getConfiguratorOptionListing().reconfigure(me.getConfiguratorOptionListing().getStore());
            },
            failure: function(record, operation) {
                window.destroy();
                var rawData = record.getProxy().getReader().rawData,
                    message = rawData.message;

                if (Ext.isString(message) && message.length > 0) {
                    message = Ext.String.format(me.snippets.failure.optionSave, name) + '<br>' + message;
                } else {
                    message = Ext.String.format(me.snippets.failure.optionSave, name) + '<br>' + me.snippets.failure.noMoreInformation;
                }
                Shopware.Notification.createGrowlMessage(me.snippets.failure.title, message, me.snippets.growlMessage);
            }
        });
    },

});

//{/block}