,
SetForm: function(){
 		    var form = new Ext.form.FormPanel({
 		        labelWidth: 150,
 		        url:"<?php echo site_url("approver/insertRecord"); ?>",
 		        method: 'POST',
 		        defaultType: 'textfield',
 		        frame: true,
 		        height: 'auto',

 		        items: [ {
 					xtype:'fieldset',
 					title:'Fields w/ Asterisks are required.',
 					width:370,
 					height:80,
 					items:[{
 					xtype:'textfield',
                                        fieldLabel: 'Description*',
                                        maxLength:128,
                                        name: 'description',
                                        allowBlank:false,
                                        anchor:'90%',  // anchor width by percentage
                                        id: 'description'
 		        }

 		        ]
 					}
 		        ]
 		    });

 		    approver.app.Form = form;
 		},
addAppGroup: function(){
approver.app.SetForm();

 		  	var _window;

 		    _window = new Ext.Window({
 		        title: 'New Approver Group',
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
                                        params: {table: "tbl_app_group"},
 			                success: function(f,action){
                 		    	Ext.MessageBox.alert('Status', action.result.data);
                  		    	 Ext.Msg.show({
  								     title: 'Status',
 								     msg: action.result.data,
  								     buttons: Ext.Msg.OK,
  								     icon: Ext.Msg.INFO
  								 });
 				                ExtCommon.util.refreshGrid(approver.app.appGroupGrid.getId());
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
showAppGroupMembersGrid: function(){
if(ExtCommon.util.validateSelectionGrid(approver.app.appGroupGrid.getId())){//check if user has selected an item in the grid
 			var sm = approver.app.appGroupGrid.getSelectionModel();
 			var id = sm.getSelected().data.id;
                var appGroupMembersStore = new Ext.data.Store({
 						proxy: new Ext.data.HttpProxy({
 							url: "<?php echo site_url("approver/getAppGroupMembers"); ?>",
 							method: "POST"
 							}),
 						reader: new Ext.data.JsonReader({
 								root: "data",
 								id: "id",
 								totalProperty: "totalCount",
 								fields: [
 							{ name: "id"},
                                                        { name: "employee_id"},
                                                        { name: "emp_name"},
                                                        { name: "start_date"},
                                                        { name: "end_date"},
                                                        { name: "app_group_id"},
 										]
 						}),
 						remoteSort: true,
 						baseParams: {start: 0, limit: 25, app_group_id: id}
 					});


 			var appGroupMembersGrid = new Ext.grid.GridPanel({
 				id: 'appGroupMembersGrid',
 				height: 422,
 				width: '100%',
 				border: true,
 				ds: appGroupMembersStore,
 				cm:  new Ext.grid.ColumnModel(
 						[

 						  { header: "Employee Id", width: 100, sortable: true, dataIndex: "employee_id" },
	  					  { header: "Name", width: 250, sortable: true, dataIndex: "emp_name" },
                                                  { header: "Start Date", width: 100, sortable: true, dataIndex: "start_date" },
                                                  { header: "End Date", width: 100, sortable: true, dataIndex: "end_date" }
 						]
 				),
 				sm: new Ext.grid.RowSelectionModel({singleSelect:true}),
 	        	loadMask: true,
 	        	bbar:
 	        		new Ext.PagingToolbar({
 		        		autoShow: true,
 				        pageSize: 25,
 				        store: appGroupMembersStore,
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
				},'   ', new Ext.app.SearchField({ store: appGroupMembersStore, width:250}),
 					    {
 					     	xtype: 'tbfill'
 					 	},
                                                {
                                                    xtype: 'tbbutton',
                                                    text: 'ADD',
                                                    icon: '/images/icons/group_add.png',  cls:'x-btn-text-icon',
                                                    handler: approver.app.addAppGroupMember
                                                }, '-',
                                                {
                                                    xtype: 'tbbutton',
                                                    text: 'EXPIRE',
                                                    icon: '/images/icons/group_delete.png',  cls:'x-btn-text-icon',
                                                    handler: approver.app.expireAppGroupMember
                                                }
 	    			 ]
 	    	});
                approver.app.appGroupMembersGrid = appGroupMembersGrid;
                approver.app.appGroupMembersGrid.getStore().load();

                var _window = new Ext.Window({
 		        title: 'Approver Group Members',
 		        width: 800,
 		        height:400,
 		        layout: 'fit',
 		        plain:true,
 		        modal: true,
 		        bodyStyle:'padding:5px;',
 		        buttonAlign:'center',
 		        items: appGroupMembersGrid
 		    }).show();


                }else return;
},
appGroupMemberSetForm: function(){
 		    var form = new Ext.form.FormPanel({
 		        labelWidth: 150,
 		        url:"<?php echo site_url("approver/insertAppGroupMember"); ?>",
 		        method: 'POST',
 		        defaultType: 'textfield',
 		        frame: true,
 		        height: 'auto',

 		        items: [ {
 					xtype:'fieldset',
 					title:'Fields w/ Asterisks are required.',
 					width:370,
 					height:80,
 					items:[approver.app.userCombo()

 		        ]
 					}
 		        ]
 		    });

 		    approver.app.appGroupMemberForm = form;
 		},
addAppGroupMember: function(){
approver.app.appGroupMemberSetForm();

 		  	var _window;

 		    _window = new Ext.Window({
 		        title: 'New Approver Group',
 		        width: 410,
 		        height:180,
 		        layout: 'fit',
 		        plain:true,
 		        modal: true,
 		        bodyStyle:'padding:5px;',
 		        buttonAlign:'center',
 		        items: approver.app.appGroupMemberForm,
 		        buttons: [{
 		         	text: 'Save',
 		         	icon: '/images/icons/disk.png',
 	                handler: function () {
 			            if(ExtCommon.util.validateFormFields(approver.app.appGroupMemberForm)){//check if all forms are filled up
                                var sm = approver.app.appGroupGrid.getSelectionModel();
                                var id = sm.getSelected().data.id;

 		                approver.app.appGroupMemberForm.getForm().submit({
                                        params: {app_group_id: id},
 			                success: function(f,action){
                 		    	Ext.MessageBox.alert('Status', action.result.data);
                  		    	 Ext.Msg.show({
  								     title: 'Status',
 								     msg: action.result.data,
  								     buttons: Ext.Msg.OK,
  								     icon: Ext.Msg.INFO
  								 });
 				                ExtCommon.util.refreshGrid(approver.app.appGroupMembersGrid.getId());
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
editAppGroup: function(){


 			if(ExtCommon.util.validateSelectionGrid(approver.app.appGroupGrid.getId())){//check if user has selected an item in the grid
 			var sm = approver.app.appGroupGrid.getSelectionModel();
 			var id = sm.getSelected().data.id;

 			approver.app.SetForm();
 		    _window = new Ext.Window({
 		        title: 'Update Holiday',
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
                                icon: '/images/icons/disk.png',  cls:'x-btn-text-icon',

 		            handler: function () {
 			            if(ExtCommon.util.validateFormFields(approver.app.Form)){//check if all forms are filled up
 		                approver.app.Form.getForm().submit({
 			                url: "<?=site_url("approver/updateAppGroup")?>",
 			                params: {id: id},
 			                method: 'POST',
 			                success: function(f,action){
                 		    	Ext.MessageBox.alert('Status', action.result.data);
 				                ExtCommon.util.refreshGrid(approver.app.appGroupGrid.getId());
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
                            icon: '/images/icons/cancel.png', cls:'x-btn-text-icon',

 		            handler: function(){
 			            _window.destroy();
 		            }
 		        }]
 		    });




 		  	approver.app.Form.getForm().load({
 				url: "<?=site_url("approver/loadAppGroup")?>",
 				method: 'POST',
 				params: {id: id},
 				timeout: 300000,
 				waitMsg:'Loading...',
 				success: function(form, action){


                                    _window.show();



 				},
 				failure: function(form, action) {
         					Ext.Msg.show({
 									title: 'Error Alert',
 									msg: "A connection to the server could not be established",
 									icon: Ext.Msg.ERROR,
 									buttons: Ext.Msg.OK,
 									fn: function(){ _window.destroy(); }
 								});
     			}
 			});
 			}else return;
 		},
		expireAppGroupMember: function(){


			if(ExtCommon.util.validateSelectionGrid(approver.app.appGroupMembersGrid.getId())){//check if user has selected an item in the grid
			var sm = approver.app.appGroupMembersGrid.getSelectionModel();
			var id = sm.getSelected().data.id;

			Ext.Msg.show({
   			title:'Delete',
  			msg: 'Are you sure you want remove this member of the approver group?',
   			buttons: Ext.Msg.OKCANCEL,
   			fn: function(btn, text){
   			if (btn == 'ok'){

   			Ext.Ajax.request({
                            url: "<?=  site_url("approver/expireAppGroupMember")?>",
							params:{ id: id},
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
							approver.app.appGroupMembersGrid.getStore().load({params:{start:0, limit: 25}});

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