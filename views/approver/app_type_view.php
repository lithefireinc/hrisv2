,
addAppType: function(){
approver.app.SetForm();

 		  	var _window;

 		    _window = new Ext.Window({
 		        title: 'New Application Type',
 		        width: 410,
 		        height:180,
 		        layout: 'fit',
 		        plain:true,
 		        modal: true,
 		        bodyStyle:'padding:5px;',
 		        buttonAlign:'center',
 		        items: approver.app.Form,
 		        buttons: [{
 		         	text: 'Save',
 		         	icon: '/images/icons/disk.png',
 	                handler: function () {
 			            if(ExtCommon.util.validateFormFields(approver.app.Form)){//check if all forms are filled up

 		                approver.app.Form.getForm().submit({
                                        params: {table: "tbl_app_type"},
 			                success: function(f,action){
                 		    	Ext.MessageBox.alert('Status', action.result.data);
                  		    	 Ext.Msg.show({
  								     title: 'Status',
 								     msg: action.result.data,
  								     buttons: Ext.Msg.OK,
  								     icon: Ext.Msg.INFO
  								 });
 				                ExtCommon.util.refreshGrid(approver.app.appTypeGrid.getId());
 				                _window.destroy();
 			                },
 			                failure: function(f,a){
 								Ext.Msg.show({
 									title: 'Error Alert',
 									msg: a.result.data,
 									icon: Ext.Msg.ERROR,
 									buttons: Ext.Msg.OK
 								});
 			                },
 			                waitMsg: 'Saving Data...'
 		                });
 	                }else return;
 	                }
 	            },{
 		            text: 'Cancel', icon: '/images/icons/cancel.png',
 		            handler: function(){
 			            _window.destroy();
 		            }
 		        }]
 		    });
 		  	_window.show();
},
userCombo: function(){

			return {
				xtype:'combo',
				id:'usercombo',
				hiddenName: 'username',
				name: 'usercombo3',
				valueField: 'id',
				displayField: 'name',
				anchor: '90%',
				triggerAction: 'all',
				minChars: 2,
				forceSelection: true,
				enableKeyEvents: true,
				pageSize: 10,
				resizable: true,
				readOnly: false,
				minListWidth: 250,
				allowBlank: false,
				store: new Ext.data.JsonStore({
					id: 'idUserCombo',
					root: 'data',
					totalProperty: 'totalCount',
					fields:[{name: 'id', type:'int', mapping:'id'}, {name: 'name', type:'string', mapping: 'username'}],
					url: "<?php echo site_url("approver/getUsers"); ?>",
					baseParams: {start: 0, limit: 10}

				}),
				listeners: {
					select: function (combo, record, index){
						this.setRawValue(record.get('name'));
						//Ext.get(this.hiddenName).dom.value  = record.get('id');
						var id = record.get('id');
					},
					blur: function(){
						var val = this.getRawValue();
						this.setRawValue.defer(1, this, [val]);
						this.validate();
					},
					render: function() {
						this.el.set({qtip: 'Type at least ' + this.minChars + ' characters to search for a username'});

					},
					keypress: {buffer: 100, fn: function() {
						//Ext.get(this.hiddenName).dom.value  = '';
						if(!this.getRawValue()){
							this.doQuery('', true);
						}
					}}
				},
				fieldLabel: 'Username*'

			}
			},
appGroupCombo: function(){

			return {
				xtype:'combo',
				id:'appgroupcombo_id',
				hiddenName: 'appgroup',
				name: 'appgroupcombo_name',
				valueField: 'id',
				displayField: 'name',
				anchor: '90%',
				triggerAction: 'all',
				minChars: 2,
				forceSelection: true,
				enableKeyEvents: true,
				pageSize: 10,
				resizable: true,
				readOnly: false,
				minListWidth: 250,
				allowBlank: false,
				store: new Ext.data.JsonStore({
					id: 'idUserCombo',
					root: 'data',
					totalProperty: 'totalCount',
					fields:[{name: 'id'}, {name: 'name'}],
					url: "<?php echo site_url("approver/getAppGroup"); ?>",
					baseParams: {start: 0, limit: 10}

				}),
				listeners: {
					select: function (combo, record, index){
						this.setRawValue(record.get('name'));
						//Ext.get(this.hiddenName).dom.value  = record.get('id');
						var id = record.get('id');
					},
					blur: function(){
						var val = this.getRawValue();
						this.setRawValue.defer(1, this, [val]);
						this.validate();
					},
					render: function() {
						this.el.set({qtip: 'Type at least ' + this.minChars + ' characters to search for a username'});

					},
					keypress: {buffer: 100, fn: function() {
						//Ext.get(this.hiddenName).dom.value  = '';
						if(!this.getRawValue()){
							this.doQuery('', true);
						}
					}}
				},
				fieldLabel: 'Approver Group*'

			}
			},
                        parentCombo: function(id){

			return {
				xtype:'combo',
				id:'parent_id',
				hiddenName: 'parent',
				name: 'parent_name',
				valueField: 'id',
				displayField: 'name',
				anchor: '90%',
				triggerAction: 'all',
				minChars: 2,
				forceSelection: true,
				enableKeyEvents: true,
				pageSize: 10,
				resizable: true,
				readOnly: false,
				minListWidth: 250,
				allowBlank: true,
				store: new Ext.data.JsonStore({
					id: 'idUserCombo',
					root: 'data',
					totalProperty: 'totalCount',
					fields:[{name: 'id'}, {name: 'name'}],
					url: "<?php echo site_url("approver/getAppGroupParent"); ?>",
					baseParams: {start: 0, limit: 10, app_tree_id: id}

				}),
				listeners: {
					select: function (combo, record, index){
						this.setRawValue(record.get('name'));
						//Ext.get(this.hiddenName).dom.value  = record.get('id');
						var id = record.get('id');
					},
					blur: function(){
						var val = this.getRawValue();
						this.setRawValue.defer(1, this, [val]);
						this.validate();
					},
					render: function() {
						this.el.set({qtip: 'Type at least ' + this.minChars + ' characters to search for a username'});

					},
					keypress: {buffer: 100, fn: function() {
						//Ext.get(this.hiddenName).dom.value  = '';
						if(!this.getRawValue()){
							this.doQuery('', true);
						}
					}}
				},
				fieldLabel: 'Parent*'

			}
			}//end of functions
}

 }();

Ext.onReady(approver.app.init, approver.app);

</script>

<div class="mainBody" id="mainBody" >
</div>