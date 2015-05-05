<script type="text/javascript">
 Ext.namespace("approver");
 approver.app = function()
 {
 	return{
 		init: function()
 		{
 			ExtCommon.util.init();
 			ExtCommon.util.quickTips();
                        ExtCommon.util.validations();
 			this.getGrid();
 		},
 		getGrid: function()
 		{
ExtCommon.util.renderSearchField('searchby');

 			var Objstore = new Ext.data.Store({
 						proxy: new Ext.data.HttpProxy({
 							url: "<?php echo site_url("approver/getRecords"); ?>",
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
 						baseParams: {start: 0, limit: 25, table: "tbl_app_type"}
 					});


 			var grid = new Ext.grid.GridPanel({
 				id: 'leavegrid',
 				height: 422,
 				width: '100%',
 				border: true,
 				ds: Objstore,
 				cm:  new Ext.grid.ColumnModel(
 						[

 						  { header: "Id", width: 100, sortable: true, dataIndex: "id" },
	  					  { header: "Description", width: 250, sortable: true, dataIndex: "description" }
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
 				tbar: [new Ext.form.ComboBox({
                    fieldLabel: 'Search',
                    hiddenName:'searchby-form',
                    id: 'searchby',
					//store: Objstore,
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
				},'   ', new Ext.app.SearchField({ store: Objstore, width:250}),
 					    {
 					     	xtype: 'tbfill'
 					 	},
                                            /*    {
                                                    xtype: 'tbbutton',
                                                    text: 'ADD',
                                                    icon: '/images/icons/application_add.png',  cls:'x-btn-text-icon',
                                                    handler: approver.app.addAppType
                                                }, '-',
                                                {
                                                    xtype: 'tbbutton',
                                                    text: 'EDIT',
                                                    handler: approver.app.editAppType
                                                }, '-',
                                                {
                                                    xtype: 'tbbutton',
                                                    text: 'DELETE',
                                                    handler: approver.app.deleteAppType
                                                }*/
 	    			 ]
 	    	});

 			approver.app.appTypeGrid = grid;
 			approver.app.appTypeGrid.getStore().load({params:{start: 0, limit: 25}});

 			var appGroupStore = new Ext.data.Store({
					proxy: new Ext.data.HttpProxy({
						url: "<?php echo site_url("approver/getRecords"); ?>",
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
					baseParams: {start: 0, limit: 25, table: "tbl_app_group"}
				});


		var appGroupGrid = new Ext.grid.GridPanel({
			id: 'appGroupGrid',
			height: 422,
			width: '100%',
			border: true,
			ds: appGroupStore,
			cm:  new Ext.grid.ColumnModel(
					[

					  { header: "Id", width: 100, sortable: true, dataIndex: "id" },
	  					  { header: "Description", width: 250, sortable: true, dataIndex: "description" }
					]
			),
		sm: new Ext.grid.RowSelectionModel({singleSelect:true}),
                loadMask: true,
                bbar:
     		new Ext.PagingToolbar({
	        		autoShow: true,
			        pageSize: 25,
			        store: appGroupStore,
			        displayInfo: true,
			        displayMsg: 'Displaying Results {0} - {1} of {2}',
			        emptyMsg: "No Data Found."
			    }),
		tbar: [new Ext.form.ComboBox({
                fieldLabel: 'Search',
                hiddenName:'searchby-form',
                id: 'searchby',
			//store: Objstore,
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
		},'   ', new Ext.app.SearchField({ store: appGroupStore, width:250}),
				    {
				     	xtype: 'tbfill'
				 	},
                                        {
                                                    xtype: 'tbbutton',
                                                    text: 'ADD',
                                                    icon: '/images/icons/application_add.png',  cls:'x-btn-text-icon',
                                                    handler: approver.app.addAppGroup
                                                }, '-',
                                                {
                                                    xtype: 'tbbutton',
                                                    text: 'EDIT',
                                                    icon: '/images/icons/application_edit.png',  cls:'x-btn-text-icon',
                                                    handler: approver.app.editAppGroup
                                                }, '-',
                                                {
                                                    xtype: 'tbbutton',
                                                    text: 'MEMBERS',
                                                    icon: '/images/icons/group.png',  cls:'x-btn-text-icon',
                                                    handler: approver.app.showAppGroupMembersGrid
                                                }
 			 ]
 	});

		approver.app.appGroupGrid = appGroupGrid;
		approver.app.appGroupGrid.getStore().load({params:{start: 0, limit: 25}});

                var empGroupStore = new Ext.data.Store({
					proxy: new Ext.data.HttpProxy({
						url: "<?php echo site_url("approver/getRecords"); ?>",
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
					baseParams: {start: 0, limit: 25, table: "tbl_employee_group"}
				});


		var empGroupGrid = new Ext.grid.GridPanel({
			id: 'empGroupGrid',
			height: 422,
			width: '100%',
			border: true,
			ds: empGroupStore,
			cm:  new Ext.grid.ColumnModel(
					[

					  { header: "Id", width: 100, sortable: true, dataIndex: "id" },
	  					  { header: "Description", width: 250, sortable: true, dataIndex: "description" }
					]
			),
		sm: new Ext.grid.RowSelectionModel({singleSelect:true}),
                loadMask: true,
                bbar:
     		new Ext.PagingToolbar({
	        		autoShow: true,
			        pageSize: 25,
			        store: empGroupStore,
			        displayInfo: true,
			        displayMsg: 'Displaying Results {0} - {1} of {2}',
			        emptyMsg: "No Data Found."
			    }),
		tbar: [new Ext.form.ComboBox({
                fieldLabel: 'Search',
                hiddenName:'searchby-form',
                id: 'searchby',
			//store: Objstore,
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
		},'   ', new Ext.app.SearchField({ store: empGroupStore, width:250}),
				    {
				     	xtype: 'tbfill'
				 	},
                                        {
                                                    xtype: 'tbbutton',
                                                    text: 'ADD',
                                                    icon: '/images/icons/application_add.png',  cls:'x-btn-text-icon',
                                                    handler: approver.app.addEmpGroup
                                                }, '-',
                                                {
                                                    xtype: 'tbbutton',
                                                    text: 'EDIT',
                                                    icon: '/images/icons/application_edit.png',  cls:'x-btn-text-icon',
                                                    handler: approver.app.editEmpGroup
                                                }, '-',
                                                {
                                                    xtype: 'tbbutton',
                                                    text: 'MEMBERS',
                                                    icon: '/images/icons/group.png',  cls:'x-btn-text-icon',
                                                    handler: approver.app.showEmpGroupMembersGrid
                                                }, '-',
                                                {
                                                    xtype: 'tbbutton',
                                                    text: 'APPROVAL FLOW',
                                                    icon: '/images/icons/chart_organisation.png',  cls:'x-btn-text-icon',
                                                    handler: approver.app.showApprovalFlowGrid
                                                }
 			 ]
 	});

		approver.app.empGroupGrid = empGroupGrid;
		approver.app.empGroupGrid.getStore().load({params:{start: 0, limit: 25}});

                var appTreeStore = new Ext.data.Store({
					proxy: new Ext.data.HttpProxy({
						url: "<?php echo site_url("approver/getRecords"); ?>",
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
					baseParams: {start: 0, limit: 25, table: "tbl_app_tree"}
				});


		var appTreeGrid = new Ext.grid.GridPanel({
			id: 'appTreeGrid',
			height: 422,
			width: '100%',
			border: true,
			ds: appTreeStore,
			cm:  new Ext.grid.ColumnModel(
					[

					  { header: "Id", width: 100, sortable: true, dataIndex: "id" },
	  					  { header: "Description", width: 250, sortable: true, dataIndex: "description" }
					]
			),
		sm: new Ext.grid.RowSelectionModel({singleSelect:true}),
                loadMask: true,
                bbar:
     		new Ext.PagingToolbar({
	        		autoShow: true,
			        pageSize: 25,
			        store:appTreeStore,
			        displayInfo: true,
			        displayMsg: 'Displaying Results {0} - {1} of {2}',
			        emptyMsg: "No Data Found."
			    }),
		tbar: [new Ext.form.ComboBox({
                fieldLabel: 'Search',
                hiddenName:'searchby-form',
                id: 'searchby',
			//store: Objstore,
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
		},'   ', new Ext.app.SearchField({ store: appTreeStore, width:250}),
				    {
				     	xtype: 'tbfill'
				 	},
                                        {
                                                    xtype: 'tbbutton',
                                                    text: 'ADD',
                                                    icon: '/images/icons/application_add.png',  cls:'x-btn-text-icon',
                                                    handler: approver.app.addAppTree
                                                }, '-',
                                                {
                                                    xtype: 'tbbutton',
                                                    text: 'EDIT',
                                                    icon: '/images/icons/application_edit.png',  cls:'x-btn-text-icon',
                                                    handler: approver.app.editAppTree
                                                }, '-',
                                                {
                                                    xtype: 'tbbutton',
                                                    text: 'DETAILS',
                                                    icon: '/images/icons/chart_bar.png',  cls:'x-btn-text-icon',
                                                    handler: approver.app.showAppTreeDetailsGrid
                                                }
 			 ]
 	});

		approver.app.appTreeGrid = appTreeGrid;
		approver.app.appTreeGrid.getStore().load({params:{start: 0, limit: 25}});



 			var tabs = new Ext.TabPanel({
		        renderTo: 'mainBody',
		        width:'100%',
		        activeTab: 0,
		        frame:true,
		        height: 450,
                       // layout: 'fit',
		        //defaults:{autoHeight: true},
		        items:[
		            {title: 'Application Type', items: approver.app.appTypeGrid},
		            {title: 'Approver Assignment', items: approver.app.appGroupGrid},
                            {title: 'Employee Assignment', items: [approver.app.empGroupGrid]},
                            {title: 'Approver Setup', items: [approver.app.appTreeGrid]}
		        ]
		    }).render();






 		},
                applyLeave: function(){
                    var LeaveCredits = new Ext.Panel({
				id			: 'panel_leave_credits',
				iconCls		: 'icon_appgroup',
                                split       : true,
                                width       : "100%",
                                layout		: "fit",
                                margins     : '3 0 3 3',
                                html		: ""
			});

                    var form = new Ext.form.FormPanel({
                        labelWidth: 75,
                        url: "<?php echo site_url("leaves/applyLeave")?>",
                        method: 'POST',
                        frame: true,
                        items: [
		        {
                               xtype: 'fieldset',
		               title : 'Leave Information',
		               height : 180,
		               items  : [
		               		{
                                          layout: 'column',
                                          width: 'auto',
                                          items: [
                                              {
                                                  columnWidth: '.5',
                                                  layout: 'form',
                                                  items: [
                                                      {
                                                            xtype: 'datefield',
                                                            name: 'date_from',
                                                            id: 'date_from',
                                                            format: 'Y-m-d',
                                                            fieldLabel: 'From',
                                                            anchor: '93%',
                                                            vtype: 'daterange',
                                                            endDateField: 'date_to'
                                                       }
                                                  ]
                                              },
                                              {
                                                  columnWidth: '.5',
                                                  layout: 'form',
                                                  items: [
                                                      {
                                                            xtype: 'datefield',
                                                            name: 'date_to',
                                                            id: 'date_to',
                                                            format: 'Y-m-d',
                                                            fieldLabel: 'To',
                                                            anchor: '93%',
                                                            vtype: 'daterange',
                                                            startDateField: 'date_from'
                                                       }
                                                  ]
                                              }
                                          ]
                                        },
		               		{ xtype: 'textfield',
                                          name: 'no_of_days',
                                          id: 'no_of_days',
                                          anchor:'25%',
                                          fieldLabel: 'No of Days',
                                          readOnly: true,
                                          allowBlank: true
                                        },
					{ xtype: 'textarea',
                                          id: 'txtreason',
                                          name: 'reason',
                                          anchor:'90%',
                                          fieldLabel: 'Reason',
                                          allowBlank: false
                                        }
		             	 ]
		              }
		        ]
                    });

                    var fPanel = new Ext.Panel({
				border: false,
			 	region  : 'center',
			 	width: 500,
                                margins : '1 1 1 0',
                                items	: [ form ]
			});

			var applyWinView = new Ext.Window({
					title: "Leave Application",
					width: 900,
					height: 520,
					bodyStyle:'padding:5px;',
					plain: true,
					modal: true,
					layout: 'border',
					items: [ fPanel ],
					buttons: [
							{
							  text: 'Save',
							  icon: '/images/icons/disk.png',
							},
							{ text: 'Cancel', icon: '/images/icons/cancel.png',
                                                            disabled: false,
                                                            handler: function(){
                                                                applyWinView.destroy();
                                                            }
                                                        }
						]
			});
                        applyWinView.show();
                }//end of functions
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
,
addEmpGroup: function(){
approver.app.SetForm();

 		  	var _window;

 		    _window = new Ext.Window({
 		        title: 'New Employee Group',
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
                                        params: {table: "tbl_employee_group"},
 			                success: function(f,action){
                 		    	Ext.MessageBox.alert('Status', action.result.data);
                  		    	 Ext.Msg.show({
  								     title: 'Status',
 								     msg: action.result.data,
  								     buttons: Ext.Msg.OK,
  								     icon: Ext.Msg.INFO
  								 });
 				                ExtCommon.util.refreshGrid(approver.app.empGroupGrid.getId());
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
showEmpGroupMembersGrid: function(){
if(ExtCommon.util.validateSelectionGrid(approver.app.empGroupGrid.getId())){//check if user has selected an item in the grid
 			var sm = approver.app.empGroupGrid.getSelectionModel();
 			var id = sm.getSelected().data.id;
                var empGroupMembersStore = new Ext.data.Store({
 						proxy: new Ext.data.HttpProxy({
 							url: "<?php echo site_url("approver/getEmpGroupMembers"); ?>",
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
                                                        { name: "emp_group_id"}
 										]
 						}),
 						remoteSort: true,
 						baseParams: {start: 0, limit: 25, emp_group_id: id}
 					});


 			var empGroupMembersGrid = new Ext.grid.GridPanel({
 				id: 'empGroupMembersGrid',
 				height: 422,
 				width: '100%',
 				border: true,
 				ds: empGroupMembersStore,
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
 				        store: empGroupMembersStore,
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
				},'   ', new Ext.app.SearchField({ store: empGroupMembersStore, width:250}),
 					    {
 					     	xtype: 'tbfill'
 					 	},
                                                {
                                                    xtype: 'tbbutton',
                                                    text: 'ADD',
                                                    icon: '/images/icons/group_add.png',  cls:'x-btn-text-icon',
                                                    handler: approver.app.addEmpGroupMember
                                                }, '-',
                                                {
                                                    xtype: 'tbbutton',
                                                    text: 'EXPIRE',
                                                    icon: '/images/icons/group_delete.png',  cls:'x-btn-text-icon',
                                                    handler: approver.app.expireEmpGroupMember
                                                }
 	    			 ]
 	    	});
                approver.app.empGroupMembersGrid = empGroupMembersGrid;
                approver.app.empGroupMembersGrid.getStore().load();

                var _window = new Ext.Window({
 		        title: 'Employee Group Members',
 		        width: 800,
 		        height:400,
 		        layout: 'fit',
 		        plain:true,
 		        modal: true,
 		        bodyStyle:'padding:5px;',
 		        buttonAlign:'center',
 		        items: empGroupMembersGrid
 		    }).show();


                }else return;
},
empGroupMemberSetForm: function(){
 		    var form = new Ext.form.FormPanel({
 		        labelWidth: 150,
 		        url:"<?php echo site_url("approver/insertEmpGroupMember"); ?>",
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

 		    approver.app.empGroupMemberForm = form;
 		},
addEmpGroupMember: function(){
approver.app.empGroupMemberSetForm();

 		  	var _window;

 		    _window = new Ext.Window({
 		        title: 'New Employee Group Member',
 		        width: 410,
 		        height:180,
 		        layout: 'fit',
 		        plain:true,
 		        modal: true,
 		        bodyStyle:'padding:5px;',
 		        buttonAlign:'center',
 		        items: approver.app.empGroupMemberForm,
 		        buttons: [{
 		         	text: 'Save',
 		         	icon: '/images/icons/disk.png',
 	                handler: function () {
 			            if(ExtCommon.util.validateFormFields(approver.app.empGroupMemberForm)){//check if all forms are filled up
                                var sm = approver.app.empGroupGrid.getSelectionModel();
                                var id = sm.getSelected().data.id;

 		                approver.app.empGroupMemberForm.getForm().submit({
                                        params: {emp_group_id: id},
 			                success: function(f,action){
                 		    	Ext.MessageBox.alert('Status', action.result.data);
                  		    	 Ext.Msg.show({
  								     title: 'Status',
 								     msg: action.result.data,
  								     buttons: Ext.Msg.OK,
  								     icon: Ext.Msg.INFO
  								 });
 				                ExtCommon.util.refreshGrid(approver.app.empGroupMembersGrid.getId());
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
showApprovalFlowGrid: function(){
if(ExtCommon.util.validateSelectionGrid(approver.app.empGroupGrid.getId())){//check if user has selected an item in the grid
 			var sm = approver.app.empGroupGrid.getSelectionModel();
 			var id = sm.getSelected().data.id;
                var appFlowStore = new Ext.data.Store({
 						proxy: new Ext.data.HttpProxy({
 							url: "<?php echo site_url("approver/getAppFlow"); ?>",
 							method: "POST"
 							}),
 						reader: new Ext.data.JsonReader({
 								root: "data",
 								id: "id",
 								totalProperty: "totalCount",
 								fields: [
 							{ name: "id"},
                                                        { name: "app_type_id"},
                                                        { name: "app_tree_id"},
                                                        { name: "app_type"},
                                                        { name: "app_tree"}
 										]
 						}),
 						remoteSort: true,
 						baseParams: {start: 0, limit: 25, emp_group_id: id}
 					});


 			var appFlowGrid = new Ext.grid.GridPanel({
 				id: 'appFlowGrid',
 				height: 422,
 				width: '100%',
 				border: true,
 				ds: appFlowStore,
 				cm:  new Ext.grid.ColumnModel(
 						[
                                                  { header: "Application Type", width: 200, sortable: true, dataIndex: "app_type" },
                                                  { header: "Application Tree Flow", width: 200, sortable: true, dataIndex: "app_tree" }
 						]
 				),
 				sm: new Ext.grid.RowSelectionModel({singleSelect:true}),
 	        	loadMask: true,
 	        	bbar:
 	        		new Ext.PagingToolbar({
 		        		autoShow: true,
 				        pageSize: 25,
 				        store: appFlowStore,
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
				},'   ', new Ext.app.SearchField({ store: appFlowStore, width:250}),
 					    {
 					     	xtype: 'tbfill'
 					 	},
                                                {
                                                    xtype: 'tbbutton',
                                                    text: 'ADD',
                                                    icon: '/images/icons/chart_organisation_add.png',  cls:'x-btn-text-icon',
                                                    handler: approver.app.addAppFlow
                                                },/* '-',
                                                {
                                                    xtype: 'tbbutton',
                                                    text: 'EDIT',
                                                    handler: approver.app.editAppFlow
                                                },*/ '-',
                                                {
                                                    xtype: 'tbbutton',
                                                    text: 'DELETE',
                                                    icon: '/images/icons/chart_organisation_delete.png',  cls:'x-btn-text-icon',
                                                    handler: approver.app.deleteAppFlow
                                                }
 	    			 ]
 	    	});
                approver.app.appFlowGrid = appFlowGrid;
                approver.app.appFlowGrid.getStore().load();

                var _window = new Ext.Window({
 		        title: 'Approval Flow',
 		        width: 800,
 		        height:400,
 		        layout: 'fit',
 		        plain:true,
 		        modal: true,
 		        bodyStyle:'padding:5px;',
 		        buttonAlign:'center',
 		        items: approver.app.appFlowGrid
 		    }).show();


                }else return;
},
appFlowSetForm: function(){

 		    var form = new Ext.form.FormPanel({
 		        labelWidth: 150,
 		        url:"<?php echo site_url("approver/insertAppFlow"); ?>",
 		        method: 'POST',
 		        defaultType: 'textfield',
 		        frame: true,
 		        height: 'auto',

 		        items: [ {
 					xtype:'fieldset',
 					title:'Fields w/ Asterisks are required.',
 					width:370,
 					height:'auto',
 					items:[approver.app.appTypeCombo(), approver.app.appTreeCombo()

 		        ]
 					}
 		        ]
 		    });

 		    approver.app.appFlowForm = form;
},
addAppFlow: function(){
approver.app.appFlowSetForm();

 		  	var _window;

 		    _window = new Ext.Window({
 		        title: 'New Approval Flow',
 		        width: 410,
 		        height:220,
 		        layout: 'fit',
 		        plain:true,
 		        modal: true,
 		        bodyStyle:'padding:5px;',
 		        buttonAlign:'center',
 		        items: approver.app.appFlowForm,
 		        buttons: [{
 		         	text: 'Save',
 		         	icon: '/images/icons/disk.png',
 	                handler: function () {
 			            if(ExtCommon.util.validateFormFields(approver.app.appFlowForm)){//check if all forms are filled up
                                var sm = approver.app.empGroupGrid.getSelectionModel();
                                var id = sm.getSelected().data.id;

 		                approver.app.appFlowForm.getForm().submit({
                                        params: {emp_group_id: id},
 			                success: function(f,action){
                 		    	Ext.MessageBox.alert('Status', action.result.data);
                  		    	 Ext.Msg.show({
  								     title: 'Status',
 								     msg: action.result.data,
  								     buttons: Ext.Msg.OK,
  								     icon: Ext.Msg.INFO
  								 });
 				                ExtCommon.util.refreshGrid(approver.app.appFlowGrid.getId());
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
appTypeCombo: function(){

			return {
				xtype:'combo',
				id:'app_type_id',
				hiddenName: 'app_type',
				name: 'app_type_name',
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
					url: "<?php echo site_url("approver/getAppType"); ?>",
					baseParams: {start: 0, limit: 10, app_tree_id: id}

				}),
				listeners: {
					select: function (combo, record, index){
						this.setRawValue(record.get('name'));
                                                this.setValue(record.get('name'));
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
				fieldLabel: 'Application Type*'

			}
},
appTreeCombo: function(){

			return {
				xtype:'combo',
				id:'app_tree_id',
				hiddenName: 'app_tree',
				name: 'app_tree_name',
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
					url: "<?php echo site_url("approver/getAppTree"); ?>",
					baseParams: {start: 0, limit: 10, app_tree_id: id}

				}),
				listeners: {
					select: function (combo, record, index){
						this.setRawValue(record.get('name'));
                                                this.setValue(record.get('name'));
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
				fieldLabel: 'Application Tree*'

			}
},
editEmpGroup: function(){


 			if(ExtCommon.util.validateSelectionGrid(approver.app.empGroupGrid.getId())){//check if user has selected an item in the grid
 			var sm = approver.app.empGroupGrid.getSelectionModel();
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
 			                url: "<?=site_url("approver/updateEmpGroup")?>",
 			                params: {id: id},
 			                method: 'POST',
 			                success: function(f,action){
                 		    	Ext.MessageBox.alert('Status', action.result.data);
 				                ExtCommon.util.refreshGrid(approver.app.empGroupGrid.getId());
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
 				url: "<?=site_url("approver/loadEmpGroup")?>",
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
		expireEmpGroupMember: function(){


			if(ExtCommon.util.validateSelectionGrid(approver.app.empGroupMembersGrid.getId())){//check if user has selected an item in the grid
			var sm = approver.app.empGroupMembersGrid.getSelectionModel();
			var id = sm.getSelected().data.id;

			Ext.Msg.show({
   			title:'Delete',
  			msg: 'Are you sure you want remove this member of the approver group?',
   			buttons: Ext.Msg.OKCANCEL,
   			fn: function(btn, text){
   			if (btn == 'ok'){

   			Ext.Ajax.request({
                            url: "<?=  site_url("approver/expireEmpGroupMember")?>",
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
							approver.app.empGroupMembersGrid.getStore().load({params:{start:0, limit: 25}});

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


		},
		deleteAppFlow: function(){


			if(ExtCommon.util.validateSelectionGrid(approver.app.appFlowGrid.getId())){//check if user has selected an item in the grid
			var sm = approver.app.appFlowGrid.getSelectionModel();
			var id = sm.getSelected().data.id;

			Ext.Msg.show({
   			title:'Delete',
  			msg: 'Are you sure you want remove this member of the approver group?',
   			buttons: Ext.Msg.OKCANCEL,
   			fn: function(btn, text){
   			if (btn == 'ok'){

   			Ext.Ajax.request({
                            url: "<?=  site_url("approver/deleteAppFlow")?>",
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
							approver.app.appFlowGrid.getStore().load({params:{start:0, limit: 25}});

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