// File location: CardFormular/Views/backend/cardformular_extend_base\attribute\Shopware.attribute.Form.js
//{block name="backend/base/attribute/form" append}

Ext.define('Shopware.CardformularExtendBase.attribute.Form', {
    extend: 'Shopware.attribute.Form',

    loadMediaAttribute: function(foreignKey, foreignField, target_obj) {
        var me = this;
        me.load({
            url: '{url controller=CustomAttributeData action=loadMediaData}',
            params: {
                _foreignKey: foreignKey,
                _foreignField: foreignField,
                _table: me.table
            },
            success: function(response, opts) {               
              if ( (opts) && (opts.result) && (opts.result["data"]) && (opts.result["data"]!='') ) {
              	target_obj.setValue(opts.result["data"]); 
              } else {
				target_obj.removeMedia(); 
              };
            },
            failure: function(response) {                            
               target_obj.removeMedia(); 
            }
        });
    },

    saveMediaAttribute: function(foreignKey,  foreignField, id) {
        var me = this;
        me.submit({
            url: '{url controller=CustomAttributeData action=saveMediaData}',
            params: {
                _table: me.table,
                _foreignKey: foreignKey,
                _foreignField: foreignField,
                _mediaId: id
            },
            success: function(response, opts) {                
            },
            failure: function(response) {                
            }
        });        
    }    
});

//{/block}

