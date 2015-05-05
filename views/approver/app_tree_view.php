,
addAppTree: function(){
approver.app.SetForm();

 		  	var _window;

 		    _window = new Ext.Window({
 		        title: 'New App Tree',
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
                                        params: {table: "tbl_app_tree"},
 			                success: function(f,action){
                 		    	Ext.MessageBox.alert('Status', action.result.data);
                  		    	 Ext.Msg.show({
  								     title: 'Status',
 								     msg: action.result.data,
  								     buttons: Ext.Msg.OK,
  								     icon: Ext.Msg.INFO
  								 });
 				                ExtCommon.util.refreshGrid(approver.app.appTreeGrid.getId());
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
showAppTreeDetailsGrid: function(){
if(ExtCommon.util.validateSelectionGrid(approver.app.appTreeGrid.getId())){//check if user has selected an item in the grid
 			var sm = approver.app.appTreeGrid.getSelectionModel();
 			var id = sm.getSelected().data.id;
                var appTreeDetailsStore = new Ext.data.Store({
 						proxy: new Ext.data.HttpProxy({
 							url: "<?php echo site_url("approver/getAppTreeDetails"); ?>",
 							method: "POST"
 							}),
 						reader: new Ext.data.JsonReader({
 								root: "data",
 								id: "id",
 								totalProperty: "totalCount",
 								fields: [
 							{ name: "id"},
                                                        { name: "app_group"},
                                                        { name: "app_group_id"},
                                                        { name: "app_tree_id"},
                                                        { name: "parent"},
                                                        { name: "parent_name"}
 										]
 						}),
 						remoteSort: true,
 						baseParams: {start: 0, limit: 25, app_tree_id: id}
 					});


 			var appTreeDetailsGrid = new Ext.grid.GridPanel({
 				id: 'appTreeDetailsGrid',
 				height: 422,
 				width: '100%',
 				border: true,
 				ds: appTreeDetailsStore,
 				cm:  new Ext.grid.ColumnModel(
 						[

 						  { header: "Approver Group", width: 250, sortable: true, dataIndex: "app_group" },
	  					  { header: "Parent", width: 250, sortable: true, dataIndex: "parent_name" }
 						]
 				),
 				sm: new Ext.grid.RowSelectionModel({singleSelect:true}),
 	        	loadMask: true,
 	        	bbar:
 	        		new Ext.PagingToolbar({
 		        		autoShow: true,
 				        pageSize: 25,
 				        store: appTreeDetailsStore,
 				        displayInfo: true,
 				        displayMsg: 'Displaying Results {0} - {1} of {2}',
 				        emptyMsg: "No Data Found."
 				    }),
 				tbar: [new Ext.form.ComboBox({
                    fieldLabel: 'Search',
                    hiddenName:'searchby-form',
                    id: 'searchby',
					//store: appGroupMembersStore,
                    typeAhead: true,
                    triggerAction: 'all',
                    emptyText:'Search By...',
                    selectOnFocus:true,

                    store: new Ext.data.SimpleStore({
				         id:0
				        ,fields:
				            [
				             'myId',   //numeric value is the key
				             'myText' //the text value is the value

				            ]


				         , data: [['id', 'ID'], ['sd', 'Short Description'], ['ld', 'Long Description']]

			        }),
				    valueField:'myId',
				    displayField:'myText',
				    mode:'local',
                    width:100,
                    hidden: true

                }), {
					xtype:'tbtext',
					text:'Search:'
				},'   ', new Ext.app.SearchField({ store: appTreeDetailsStore, width:250}),
 					    {
 					     	xtype: 'tbfill'
 					 	},
                                                {
                                                    xtype: 'tbbutton',
                                                    text: 'ADD',
                                                    icon: '/images/icons/chart_bar_add.png',  cls:'x-btn-text-icon',
                                                    handler: approver.app.addAppTreeDetail
                                                }, '-',
                                                {
                                                    xtype: 'tbbutton',
                                                    text: 'DELETE',
                                                    icon: '/images/icons/chart_bar_delete.png',  cls:'x-btn-text-icon',
                                                    handler: approver.app.deleteAppTreeDetail
                                                }
 	    			 ]
 	    	});
                approver.app.appTreeDetailsGrid = appTreeDetailsGrid;
                approver.app.appTreeDetailsGrid.getStore().load();

                var _window = new Ext.Window({
 		        title: 'App Tree Details',
 		        width: 800,
 		        height:400,
 		        layout: 'fit',
 		        plain:true,
 		        modal: true,
 		        bodyStyle:'padding:5px;',
 		        buttonAlign:'center',
 		        items: appTreeDetailsGrid
 		    }).show();


                }else return;
},
appTreeDetailsSetForm: function(){
                    var sm = approver.app.appTreeGrid.getSelectionModel();
                    var id = sm.getSelected().data.id;
 		    var form = new Ext.form.FormPanel({
 		        labelWidth: 150,
 		        url:"<?php echo site_url("approver/insertAppTreeDetail"); ?>",
 		        method: 'POST',
 		        defaultType: 'textfield',
 		        frame: true,
 		        height: 'auto',

 		        items: [ {
 					xtype:'fieldset',
 					title:'Fields w/ Asterisks are required.',
 					width:370,
 					height:'auto',
 					items:[approver.app.appGroupCombo(), approver.app.parentCombo(id)

 		        ]
 					}
 		        ]
 		    });

 		    approver.app.appTreeDetailForm = form;
 		},
addAppTreeDetail: function(){
approver.app.appTreeDetailsSetForm();

 		  	var _window;

 		    _window = new Ext.Window({
 		        title: 'New Employee Group Member',
 		        width: 410,
 		        height:220,
 		        layout: 'fit',
 		        plain:true,
 		        modal: true,
 		        bodyStyle:'padding:5px;',
 		        buttonAlign:'center',
 		        items: approver.app.appTreeDetailForm,
 		        buttons: [{
 		         	text: 'Save',
 		         	icon: '/images/icons/disk.png',
 	                handler: function () {
 			            if(ExtCommon.util.validateFormFields(approver.app.appTreeDetailForm)){//check if all forms are filled up
                                var sm = approver.app.appTreeGrid.getSelectionModel();
                                var id = sm.getSelected().data.id;

 		                approver.app.appTreeDetailForm.getForm().submit({
                                        params: {app_tree_id: id},
 			                success: function(f,action){
                 		    	Ext.MessageBox.alert('Status', action.result.data);
                  		    	 Ext.Msg.show({
  								     title: 'Status',
 								     msg: action.result.data,
  								     buttons: Ext.Msg.OK,
  								     icon: Ext.Msg.INFO
  								 });
 				                ExtCommon.util.refreshGrid(approver.app.appTreeDetailsGrid.getId());
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
		deleteAppTreeDetail: function(){


			if(ExtCommon.util.validateSelectionGrid(approver.app.appTreeDetailsGrid.getId())){//check if user has selected an item in the grid
			var sm = approver.app.appTreeDetailsGrid.getSelectionModel();
			var id = sm.getSelected().data.id;
			var app_tree_id = sm.getSelected().data.app_tree_id;
			var app_group_id = sm.getSelected().data.app_group_id;

			Ext.Msg.show({
   			title:'Delete',
  			msg: 'Are you sure you want to delete this record?',
   			buttons: Ext.Msg.OKCANCEL,
   			fn: function(btn, text){
   			if (btn == 'ok'){

   			Ext.Ajax.request({
                            url: "<?=  site_url("approver/deleteAppTreeDetail")?>",
							params:{ id: id, app_tree_id: app_tree_id, app_group_id: app_group_id},
							method: "POST",
							timeout:300000000,
			                success: function(responseObj){
                		    	var response = Ext.decode(responseObj.responseText);
						if(response.success == true)
						{
							Ext.Msg.show({
								title: 'Status',
								msg: response.data,
								icon: Ext.Msg.INFO,
								buttons: Ext.Msg.OK
							});
							approver.app.appTreeDetailsGrid.getStore().load({params:{start:0, limit: 25}});

							return;

						}
						else if(response.success == false)
						{
							Ext.Msg.show({
								title: 'Error!',
								msg: response.data,
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


		}