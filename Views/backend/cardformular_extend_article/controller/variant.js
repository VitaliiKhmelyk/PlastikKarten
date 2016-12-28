// File location: CardFormular/Views/backend/cardformular_extend_article/controller/variant.js
//{block name="backend/article/controller/variant" append}

Ext.define('Shopware.apps.CardformularExtendArticle.controller.Variant', {

    /**
     * Defines an override applied to a class.
     * @string
     */
    override: 'Shopware.apps.Article.controller.Variant',    

    onSaveAttrCallback: function(group_store) {      
      Ext.Ajax.request({
           url: '{url controller=CustomAttributeData action=getGroupsPseudoStatus}',
           method: 'POST',
           cache: false,
           success: function(response) {
                var res = Ext.decode(response.responseText);                
                var data = res['data'];   
                group_store.each(function(item) {
                  var new_val= Ext.Array.contains(data, (item.get('id')).toString());
                  item['data']["pseudo"] = new_val;
                });
           }
      });      
    },

    onSaveGroup: function(group, form, window) {
        var me = this,
            group_store = me.subApplication.articleWindow.configuratorGroupStore;
        if (!form.getForm().isValid()) {
            return;
        }                 
        window.attributeForm.saveAttribute(group.get('id'), me.onSaveAttrCallback(group_store)); 
        me.callParent(arguments); 
    },

    // onSaveGroup: function(group, form, window) {
    //     var me = this,group_store = me.subApplication.articleWindow.configuratorGroupStore;
    //     if (!form.getForm().isValid()) {
    //         return;
    //     }
    //     form.getForm().updateRecord(group);        
    //     var name = group.get('name');
    //     group.save({
    //         success: function(record, operation) {                
    //             window.attributeForm.saveAttribute(group.get('id'), me.onSaveAttrCallback(group_store));              
    //             window.destroy();
    //             var message = Ext.String.format(me.snippets.success.groupSave, name);
    //             Shopware.Notification.createGrowlMessage(me.snippets.success.title, message, me.snippets.growlMessage);
    //             me.getConfiguratorGroupListing().reconfigure(me.getConfiguratorGroupListing().getStore());
    //         },
    //         failure: function(record, operation) {
    //             window.destroy();
    //             var rawData = record.getProxy().getReader().rawData,
    //                 message = rawData.message;

    //             if (Ext.isString(message) && message.length > 0) {
    //                 message = Ext.String.format(me.snippets.failure.groupSave, name) + '<br>' + message;
    //             } else {
    //                 message = Ext.String.format(me.snippets.failure.groupSave, name) + '<br>' + me.snippets.failure.noMoreInformation;
    //             }
    //             Shopware.Notification.createGrowlMessage(me.snippets.failure.title, message, me.snippets.growlMessage);
    //         }
    //     });
    // },

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

    onGroupDropped: function(source, target) {
       var me = this;           
       me.callParent(arguments);
       var groupListing = me.getConfiguratorGroupListing();
       var orderedItems = [];
           groupListing.getStore().each(function(item) {
               orderedItems.push({
                    position: item.get('position'),
                    groupId: item.get('id')
                });
       });
       Ext.Ajax.request({
            url: '{url controller=CustomAttributeData action=changeConfiguratorGroupPosition}',
            method: 'POST',
            params: {
                data : Ext.encode(orderedItems)
            },
            success: function() {
                Shopware.Notification.createGrowlMessage(me.snippets.success.title, Ext.String.format(me.snippets.success.groupSave, ""), me.snippets.growlMessage);
            },
            failure: function(message) {
                var message0 = Ext.String.format(me.snippets.failure.groupSave, "") + '<br>';
                if (Ext.isString(message) && message.length > 0) {
                    message = message0 + message;
                } else {
                    message = message0 + me.snippets.failure.noMoreInformation;
                }
                Shopware.Notification.createGrowlMessage(me.snippets.failure.title, message, me.snippets.growlMessage);
            }
        });
    }, 

    onOptionDropped: function(source, target) {
       var me = this;           
       me.callParent(arguments);
       var optionListing = me.getConfiguratorOptionListing();
       var orderedItems = [];
           optionListing.getStore().each(function(item) {
               orderedItems.push({
                    position: item.get('position'),
                    optionId: item.get('id')
                });
       });
       Ext.Ajax.request({
            url: '{url controller=CustomAttributeData action=changeConfiguratorOptionPosition}',
            method: 'POST',
            params: {
                data : Ext.encode(orderedItems)
            },
            success: function() {
                Shopware.Notification.createGrowlMessage(me.snippets.success.title, Ext.String.format(me.snippets.success.optionSave, ""), me.snippets.growlMessage);
            },
            failure: function(message) {
                var message0 = Ext.String.format(me.snippets.failure.optionSave, "") + '<br>';                
                if (Ext.isString(message) && message.length > 0) {
                    message = message0 + message;
                } else {
                    message = message0 + me.snippets.failure.noMoreInformation;
                }
                Shopware.Notification.createGrowlMessage(me.snippets.failure.title, message, me.snippets.growlMessage);
            }
        });    

    },    

});

//{/block}