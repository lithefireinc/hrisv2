 <script type="text/javascript">
 Ext.namespace("referredby");
 referredby.app = function()
 {
 	return{
 		init: function()
 		{
 			ExtCommon.util.init();
 			ExtCommon.util.quickTips();
 			this.getGrid();
 		},
 		getGrid: function()
 		{
 			ExtCommon.util.renderSearchField('searchby');

 			var Objstore = new Ext.data.Store({
 						proxy: new Ext.data.HttpProxy({
 							url: "functions/getReferredby.php",
 							method: "POST"
 							}),
 						reader: new Ext.data.JsonReader({
 								root: "data",
 								id: "id",
 								totalProperty: "totalCount",
 								fields: [
 											{ name: "id"},
 											{ name: "description"}
 										]
 						}),
 						remoteSort: true,
 						baseParams: {start: 0, limit: 25}
 					});


 			var grid = new Ext.grid.GridPanel({
 				id: 'referredbygrid',
 				height: 300,
 				width: 900,
 				border: true,
 				ds: Objstore,
 				cm:  new Ext.grid.ColumnModel(
 						[
 						  { header: "Id", dataIndex: "id", width: 100, sortable: true},
 						  { header: "Referred by", width: 300, sortable: true, dataIndex: "description" }
 						]
 				),
 				sm: new Ext.grid.RowSelectionModel({singleSelect:true}),
 	        	loadMask: true,
 	        	bbar:
 	        		new Ext.PagingToolbar({
 		        		autoShow: true,
 				        pageSize: 25,
 				        store: Objstore,
 				        displayInfo: true,
 				        displayMsg: 'Displaying Results {0} - {1} of {2}',
 				        emptyMsg: "No Data Found."
 				    }),
 				tbar: [
 					    {
 					     	xtype: 'tbfill'
 					 	},{
 					     	xtype: 'tbbutton',
 					     	text: 'ADD',
							icon: '/sms/images/icons/application_add.png',
 							cls:'x-btn-text-icon',

 					     	handler: referredby.app.Add

 					 	},'-',{
 					     	xtype: 'tbbutton',
 					     	text: 'EDIT',
							icon: '/sms/images/icons/application_edit.png',
 							cls:'x-btn-text-icon',

 					     	handler: referredby.app.Edit

 					 	},'-',{
 					     	xtype: 'tbbutton',
 					     	text: 'DELETE',
							icon: '/sms/images/icons/application_delete.png',
 							cls:'x-btn-text-icon',

 					     	handler: referredby.app.Delete

 					 	}
 	    			 ]
 	    	});
 	    	
 			referredby.app.Grid = grid;
 			referredby.app.Grid.getStore().load({params:{start: 0, limit: 25}});

 			//var msgbx = Ext.MessageBox.wait("Redirecting to main page. . .","Status");
 			<?php include('../functions/menu.php');?>

 			var _window = new Ext.Panel({
 		        title: 'Referred by',
 		        width: '100%',
 		        height:420,
 		        renderTo: 'mainBody',
 		        draggable: false,
 		        layout: 'fit',
 		        items: [referredby.app.Grid],
 		        resizable: false

 			    /*listeners : {
 				    	  close: function(p){
 					    	  window.location="../"
 					      }
 			       	} */
 	        });

 	        _window.render();


 		},
 			setForm: function(){
 		    var form = new Ext.form.FormPanel({
 		        labelWidth: 150,
 		        url:"functions/createReferredby.php",
 		        method: 'POST',
 		        defaultType: 'textfield',
 		        frame: true,

 		        items: [ {
 					xtype:'fieldset',
 					title:'Fields w/ Asterisks are required.',
 					width:370,
 					height:60,
 					items:[{
 					xtype:'textfield',
 		            fieldLabel: 'Referred by*',
                     maxLength:50,
                     autoCreate : {tag: "input", type: "text", size: "20", autocomplete: "off", maxlength: "50"},
 		            name: 'description',
 		            allowBlank:false,
 		            maxLength:50,
 		            anchor:'90%',  // anchor width by percentage
 		            id: 'description'
 		        }

 		        ]
 					}
 		        ]
 		    });

 		    referredby.app.Form = form;
 		},
 		Add: function(){

 			referredby.app.setForm();

 		  	var _window;

 		    _window = new Ext.Window({
 		        title: 'New Referred by',
 		        width: 410,
 		        height:170,
 		        layout: 'fit',
 		        plain:true,
 		        modal: true,
 		        bodyStyle:'padding:5px;',
 		        buttonAlign:'center',
 		        items: referredby.app.Form,
 		        buttons: [{
 		         	text: 'Save',
                                icon: '/sms/images/icons/disk.png',  cls:'x-btn-text-icon',

 	                handler: function () {
 			            if(ExtCommon.util.validateFormFields(referredby.app.Form)){//check if all forms are filled up

 		                referredby.app.Form.getForm().submit({
 			                success: function(f,action){
                 		    	Ext.MessageBox.alert('Status', action.result.data);
                  		    	 Ext.Msg.show({
  								     title: 'Status',
 								     msg: action.result.data,
  								     buttons: Ext.Msg.OK,
  								     icon: 'icon'
  								 });
 				                ExtCommon.util.refreshGrid(referredby.app.Grid.getId());
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
 		            text: 'Cancel',
                            icon: '/sms/images/icons/cancel.png', cls:'x-btn-text-icon',

 		            handler: function(){
 			            _window.destroy();
 		            }
 		        }]
 		    });
 		  	_window.show();
 		},
 		Edit: function(){


 			if(ExtCommon.util.validateSelectionGrid(referredby.app.Grid.getId())){//check if user has selected an item in the grid
 			var sm = referredby.app.Grid.getSelectionModel();
 			var id = sm.getSelected().data.id;

 			referredby.app.setForm();
 		    _window = new Ext.Window({
 		        title: 'Update Referred by',
 		        width: 410,
 		        height:160,
 		        layout: 'fit',
 		        plain:true,
 		        modal: true,
 		        bodyStyle:'padding:5px;',
 		        buttonAlign:'center',
 		        items: referredby.app.Form,
 		        buttons: [{
 		         	text: 'Save',
                                icon: '/sms/images/icons/disk.png',  cls:'x-btn-text-icon',

 		            handler: function () {
 			            if(ExtCommon.util.validateFormFields(referredby.app.Form)){//check if all forms are filled up
 		                referredby.app.Form.getForm().submit({
 			                url: "functions/updateReferredby.php",
 			                params: {id: id},
 			                method: 'POST',
 			                success: function(f,action){
                 		    	Ext.MessageBox.alert('Status', action.result.data);
 				                ExtCommon.util.refreshGrid(referredby.app.Grid.getId());
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
 			                waitMsg: 'Updating Data...'
 		                });
 	                }else return;
 		            }
 		        },{
 		            text: 'Cancel',
                            icon: '/sms/images/icons/cancel.png', cls:'x-btn-text-icon',

 		            handler: function(){
 			            _window.destroy();
 		            }
 		        }]
 		    });


 		  	Ext.Ajax.request({
				url: "functions/loadReferredby.php",
				params:{ id: id},
				method: "POST",
				timeout:300000000,
                success: function(responseObj){
    		    	var response = Ext.decode(responseObj.responseText);
			if(response.success == true)
			{
			Ext.getCmp("description").setValue(response.description);
			_window.show();
				return;

			}
			else if(response.success == false)
			{


				return;
			}
				},
                failure: function(f,a){
					Ext.Msg.show({
						title: 'Error Alert',
						msg: "There was an error encountered. Please contact your administrator",
						icon: Ext.Msg.ERROR,
						buttons: Ext.Msg.OK
					});
                },
                waitMsg: 'Please Wait...'
			});


 		  /*	referredby.app.Form.getForm().load({
 				url: "functions/loadreferredby.php",
 				method: 'POST',
 				params: {id: id},
 				timeout: 300000,
 				waitMsg:'Loading...',
 				success: function(form, action){
 			 		//Ext.get('referredby').dom.value  = action.result.data.referredby;
 			 		//Ext.get('toId').dom.value  = action.result.data.ToId;
 			 		Ext.getCmp('referredby').setValue(action.result.data.referredby);
 			 		//Ext.getCmp('receiverId').setRawValue(action.result.data.receiver_name);
 				},
 				failure: function(form, action) {
         					Ext.Msg.show({
 									title: 'Error Alert',
 									msg: "A connection to the server could not be established",
 									icon: Ext.Msg.ERROR,
 									buttons: Ext.Msg.OK,
 									fn: function(){ _window.close(); }
 								});
     			}
 			});*/
 			}else return;
 		},
		Delete: function(){


			if(ExtCommon.util.validateSelectionGrid(referredby.app.Grid.getId())){//check if user has selected an item in the grid
			var sm = referredby.app.Grid.getSelectionModel();
			var id = sm.getSelected().data.id;
			Ext.Msg.show({
   			title:'Delete',
  			msg: 'Are you sure you want to delete this record?',
   			buttons: Ext.Msg.OKCANCEL,
   			fn: function(btn, text){
   			if (btn == 'ok'){

   			Ext.Ajax.request({
							url: "functions/deleteReferredby.php",
							params:{ id: id},
							method: "POST",
							timeout:300000000,
			                success: function(responseObj){
                		    	var response = Ext.decode(responseObj.responseText);
						if(response.success == true)
						{
							Ext.Msg.show({
								title: 'Status',
								msg: "Record deleted successfully",
								icon: Ext.Msg.INFO,
								buttons: Ext.Msg.OK
							});
							referredby.app.Grid.getStore().load({params:{start:0, limit: 25}});

							return;

						}
						else if(response.success == false)
						{
							Ext.Msg.show({
								title: 'Error!',
								msg: "There was an error encountered in deleting the record. Please try again",
								icon: Ext.Msg.ERROR,
								buttons: Ext.Msg.OK
							});

							return;
						}
							},
			                failure: function(f,a){
								Ext.Msg.show({
									title: 'Error Alert',
									msg: "There was an error encountered in deleting the record. Please try again",
									icon: Ext.Msg.ERROR,
									buttons: Ext.Msg.OK
								});
			                },
			                waitMsg: 'Deleting Data...'
						});
   			}
   			},

   			icon: Ext.MessageBox.QUESTION
			});

	                }else return;


		}<?php include('../links.php')?>
 	}

 }();

 Ext.onReady(referredby.app.init, referredby.app);

</script>

<div id="mainBody">
</div>
