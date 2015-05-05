<script type="text/javascript">
 Ext.namespace("requests");
 requests.app = function()
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
 							url: "<?php echo site_url("leaves/getLeaves"); ?>",
 							method: "POST"
 							}),
 						reader: new Ext.data.JsonReader({
 								root: "data",
 								id: "id",
 								totalProperty: "totalCount",
 								fields: [
 							{ name: "date_requested"},
							{ name: "date_from"},
							{name: "date_to"},
							{name: "status"},
                                                        {name: "no_days"},
                                                        {name: "reason"},
                                                        {name: 'id'},
                                                        {name: 'app_type'},
                                                        {name: 'leave_type'},
                                                        {name: 'audit_id'}
 										]
 						}),
 						remoteSort: true,
 						baseParams: {start: 0, limit: 25}
 					});


 			var grid = new Ext.grid.GridPanel({
 				id: 'leavegrid',
 				height: 422,
 				width: '100%',
 				border: true,
 				ds: Objstore,
 				cm:  new Ext.grid.ColumnModel(
 						[

 						  { header: "Date Filed", width: 120, sortable: true, dataIndex: "date_requested" },
	  					  { header: "Date From", width: 120, sortable: true, dataIndex: "date_from" },
	  					  { header: "Date To", width: 120, sortable: true, dataIndex: "date_to" },
                                                  { header: "No. of Days", width: 120, sortable: true, dataIndex: "no_days" },
                                                  { header: "Leave Type", width: 200, sortable: true, dataIndex: "leave_type" },
		  				{ header: "Status", width: 150, sortable: true, dataIndex: "status", renderer: this.statusFormat }
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
                                                {
                                                    xtype: 'tbbutton',
                                                    text: 'APPLY',
                                                    icon: '/images/icons/application_form_add.png',
                                                    handler: requests.app.applyLeave
                                                }, '-',
                                                {
                                                    xtype: 'tbbutton',
                                                    text: 'VIEW',
                                                    icon: '/images/icons/application_form_magnify.png',
                                                    handler: requests.app.view_application
                                                }, '-',
                                                {
                                                    xtype: 'tbbutton',
                                                    text: 'VOID',
                                                    icon: '/images/icons/application_form_delete.png',
                                                    handler: function(){
                                                                 if(ExtCommon.util.validateSelectionGrid(requests.app.Grid.getId())){
                                                                var sm = requests.app.Grid.getSelectionModel();
                                                                var id = sm.getSelected().data.audit_id;
                                                                Ext.Msg.show({
                                                                title:'Delete',
                                                                msg: 'Are you sure you want to cancel this application?',
                                                                buttons: Ext.Msg.OKCANCEL,
                                                                fn: function(btn, text){
                                                                if (btn == 'ok'){

                                                                Ext.Ajax.request({
                                                                                                url: "<?php echo site_url("apps/voidApplication")?>",
                                                                                                params:{ audit_id: id},
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
                                                                                                requests.app.Grid.getStore().load({params:{start:0, limit: 25}});

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
                                                }
 	    			 ]
 	    	});

 			requests.app.Grid = grid;
 			requests.app.Grid.getStore().load({params:{start: 0, limit: 25}});

 			var overtimeStore = new Ext.data.Store({
					proxy: new Ext.data.HttpProxy({
                                        url: "<?=  site_url("overtime/getOT")?>",
						method: "POST"
						}),
					reader: new Ext.data.JsonReader({
							root: "data",
							id: "id",
							totalProperty: "totalCount",
							fields: [
                                                                    { name: "id", mapping: "id" },
													{ name: "date_requested"},
													{ name: "date_from"},
													{ name: "date_to"},
													{ name: "no_hours"},
													
													{ name: "status"},
                                                        {name: 'id'},
                                                        {name: 'app_type'},
                                                        {name: 'audit_id'}
									]
					}),
					remoteSort: true,
					baseParams: {start: 0, limit: 25}
				});


		var overtimeGrid = new Ext.grid.GridPanel({
			id: 'overtimeGrid',
			height: 422,
			width: '100%',
			border: true,
			ds: overtimeStore,
			cm:  new Ext.grid.ColumnModel(
					[

					  { header: "Date Filed", width: 140, sortable: true, locked:true, dataIndex: "date_requested", resizable: true },
								  { header: "Date From", width: 140, sortable: true, locked:true, dataIndex: "date_from", resizable: true  },
								  { header: "Date To", width: 140, sortable: true, locked:true, dataIndex: "date_to", resizable: true  },
								  { header: "No of Hours", width: 120, sortable: true, locked:true, dataIndex: "no_hours", resizable: true  },
								  { header: "Status", width: 100, sortable: true, locked:true, dataIndex: "status", renderer: this.statusFormat, resizable: true  }
					]
			),
			sm: new Ext.grid.RowSelectionModel({singleSelect:true}),
     	loadMask: true,
     	bbar:
     		new Ext.PagingToolbar({
	        		autoShow: true,
			        pageSize: 25,
			        store: overtimeStore,
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
		},'   ', new Ext.app.SearchField({ store: overtimeStore, width:250}),
				    {
 					     	xtype: 'tbfill'
 					 	},
                                                {
                                                    xtype: 'tbbutton',
                                                    text: 'APPLY',
                                                    icon: '/images/icons/application_form_add.png',
                                                    handler: requests.app.apply_ot
                                                }, '-',
                                                {
                                                    xtype: 'tbbutton',
                                                    text: 'VIEW',
                                                    icon: '/images/icons/application_form_magnify.png',
                                                    handler: requests.app.view_ot
                                                }, '-',
                                                {
                                                    xtype: 'tbbutton',
                                                    text: 'VOID',
                                                    icon: '/images/icons/application_form_delete.png',
                                                    handler: function(){
                                                                 if(ExtCommon.util.validateSelectionGrid(requests.app.overtimeGrid.getId())){
                                                                var sm = requests.app.overtimeGrid.getSelectionModel();
                                                                var id = sm.getSelected().data.audit_id;
                                                                Ext.Msg.show({
                                                                title:'Delete',
                                                                msg: 'Are you sure you want to cancel this application?',
                                                                buttons: Ext.Msg.OKCANCEL,
                                                                fn: function(btn, text){
                                                                if (btn == 'ok'){

                                                                Ext.Ajax.request({
                                                                                                url: "<?php echo site_url("apps/voidApplication")?>",
                                                                                                params:{ audit_id: id},
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
                                                                                                requests.app.overtimeGrid.getStore().load({params:{start:0, limit: 25}});

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
                                                }
 			 ]
 	});

		requests.app.overtimeGrid = overtimeGrid;
		requests.app.overtimeGrid.getStore().load({params:{start: 0, limit: 25}});

                var clientScheduleStore = new Ext.data.Store({
					proxy: new Ext.data.HttpProxy({
                                        url: "<?=  site_url("overtime/getCS")?>",
						method: "POST"
						}),
					reader: new Ext.data.JsonReader({
							root: "data",
							id: "id",
							totalProperty: "totalCount",
							fields: [
                                                                    { name: "id", mapping: "id" },
													{ name: "date_requested"},
													{ name: "client"},
													{ name: "purpose"},
													{ name: "date_scheduled"},

													{ name: "time_in"},
                                                                                                        {name: 'time_out'},
                                                                                                        {name : "status"},
                                                                                                        {name : "agenda"},
                                                                                                        {name : "app_type"},
                                                                                                        {name : "audit_id"}
									]
					}),
					remoteSort: true,
					baseParams: {start: 0, limit: 25}
				});


		var clientScheduleGrid = new Ext.grid.GridPanel({
			id: 'clientScheduleGrid',
			height: 422,
			width: '100%',
			border: true,
			ds: clientScheduleStore,
			cm:  new Ext.grid.ColumnModel(
					[

					  { header: "Date Filed", width: 140, sortable: true, locked:true, dataIndex: "date_requested", resizable: true },
								  { header: "Client/Supplier", width: 220, sortable: true, locked:true, dataIndex: "client", resizable: true  },
								  { header: "Purpose", width: 140, sortable: true, locked:true, dataIndex: "purpose", resizable: true  },
								  { header: "Date Schedule", width: 120, sortable: true, locked:true, dataIndex: "date_scheduled", resizable: true  },
								  { header: "Time-In", width: 100, sortable: true, locked:true, dataIndex: "time_in", resizable: true  },
                                                                  { header: "Time-Out", width: 100, sortable: true, locked:true, dataIndex: "time_out", resizable: true  },
                                                                  { header: "Agenda", width: 200, sortable: true, locked:true, dataIndex: "agenda", resizable: true  },
                                                                  { header: "Status", width: 120, sortable: true, locked:true, dataIndex: "status", renderer: this.statusFormat, resizable: true  }
					]
			),
			sm: new Ext.grid.RowSelectionModel({singleSelect:true}),
     	loadMask: true,
     	bbar:
     		new Ext.PagingToolbar({
	        		autoShow: true,
			        pageSize: 25,
			        store: clientScheduleStore,
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
		},'   ', new Ext.app.SearchField({ store: clientScheduleStore, width:250}),
				    {
 					     	xtype: 'tbfill'
 					 	},
                                                {
                                                    xtype: 'tbbutton',
                                                    text: 'APPLY',
                                                    icon: '/images/icons/application_form_add.png',
                                                    handler: requests.app.apply_cs
                                                }, '-',
                                                {
                                                    xtype: 'tbbutton',
                                                    text: 'VIEW',
                                                    icon: '/images/icons/application_form_magnify.png',
                                                    handler: requests.app.view_cs
                                                }, '-',
                                                {
                                                    xtype: 'tbbutton',
                                                    text: 'VOID',
                                                    icon: '/images/icons/application_form_delete.png',
                                                    handler: function(){
                                                                 if(ExtCommon.util.validateSelectionGrid(requests.app.clientScheduleGrid.getId())){
                                                                var sm = requests.app.clientScheduleGrid.getSelectionModel();
                                                                var id = sm.getSelected().data.audit_id;
                                                                Ext.Msg.show({
                                                                title:'Delete',
                                                                msg: 'Are you sure you want to cancel this application?',
                                                                buttons: Ext.Msg.OKCANCEL,
                                                                fn: function(btn, text){
                                                                if (btn == 'ok'){

                                                                Ext.Ajax.request({
                                                                                                url: "<?php echo site_url("apps/voidApplication")?>",
                                                                                                params:{ audit_id: id},
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
                                                                                                requests.app.clientScheduleGrid.getStore().load({params:{start:0, limit: 25}});

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
                                                }
 			 ]
 	});

		requests.app.clientScheduleGrid = clientScheduleGrid;
		requests.app.clientScheduleGrid.getStore().load({params:{start: 0, limit: 25}});
		
		var trainingStore = new Ext.data.Store({
					proxy: new Ext.data.HttpProxy({
                                        url: "<?=  site_url("overtime/getTraining")?>",
						method: "POST"
						}),
					reader: new Ext.data.JsonReader({
							root: "data",
							id: "id",
							totalProperty: "totalCount",
							fields: [
                                                    { name: "id" },
													{ name: "date_requested"},
													{ name: "client"},
													{ name: "purpose"},
													{ name: "date_start"},
													{ name: "date_end"},
													{ name: "start_time"},
                                                    {name: 'end_time'},
                                                    {name : "status"},
                                                    { name: "training_type"},
                                                    {name : "details"},
                                                    { name: "title"},
                                                    {name : "app_type"},
                                                    {name : "audit_id"}
									]
					}),
					remoteSort: true,
					baseParams: {start: 0, limit: 25}
				});


		var trainingGrid = new Ext.grid.GridPanel({
			id: 'trainingGrid',
			height: 422,
			width: '100%',
			border: true,
			ds: trainingStore,
			cm:  new Ext.grid.ColumnModel(
					[

					  { header: "Date Filed", width: 140, sortable: true, locked:true, dataIndex: "date_requested", resizable: true },
								  { header: "Client/Supplier", width: 220, sortable: true, locked:true, dataIndex: "client", resizable: true  },
								  { header: "Training Type", width: 140, sortable: true, locked:true, dataIndex: "training_type", resizable: true  },
								  { header: "Title", width: 140, sortable: true, locked:true, dataIndex: "title", resizable: true  },
								  { header: "Details", width: 140, sortable: true, locked:true, dataIndex: "details", resizable: true  },
								  { header: "Date Start", width: 120, sortable: true, locked:true, dataIndex: "date_start", resizable: true  },
								  { header: "Date End", width: 120, sortable: true, locked:true, dataIndex: "date_end", resizable: true  },
								  { header: "Start Time", width: 100, sortable: true, locked:true, dataIndex: "start_time", resizable: true  },
                                                                  { header: "End Time", width: 100, sortable: true, locked:true, dataIndex: "end_time", resizable: true  },
                                                                  { header: "Status", width: 120, sortable: true, locked:true, dataIndex: "status", renderer: this.statusFormat, resizable: true  }
					]
			),
			sm: new Ext.grid.RowSelectionModel({singleSelect:true}),
     	loadMask: true,
     	bbar:
     		new Ext.PagingToolbar({
	        		autoShow: true,
			        pageSize: 25,
			        store: trainingStore,
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
		},'   ', new Ext.app.SearchField({ store: trainingStore, width:250}),
				    {
 					     	xtype: 'tbfill'
 					 	},
                                                {
                                                    xtype: 'tbbutton',
                                                    text: 'APPLY',
                                                    icon: '/images/icons/application_form_add.png',
                                                    handler: requests.app.apply_training
                                                }, '-',
                                                {
                                                    xtype: 'tbbutton',
                                                    text: 'VIEW',
                                                    icon: '/images/icons/application_form_magnify.png',
                                                    handler: requests.app.view_training
                                                }, '-',
                                                {
                                                    xtype: 'tbbutton',
                                                    text: 'VOID',
                                                    icon: '/images/icons/application_form_delete.png',
                                                    handler: function(){
                                                                 if(ExtCommon.util.validateSelectionGrid(requests.app.trainingGrid.getId())){
                                                                var sm = requests.app.trainingGrid.getSelectionModel();
                                                                var id = sm.getSelected().data.audit_id;
                                                                Ext.Msg.show({
                                                                title:'Delete',
                                                                msg: 'Are you sure you want to cancel this application?',
                                                                buttons: Ext.Msg.OKCANCEL,
                                                                fn: function(btn, text){
                                                                if (btn == 'ok'){

                                                                Ext.Ajax.request({
                                                                                                url: "<?php echo site_url("apps/voidApplication")?>",
                                                                                                params:{ audit_id: id},
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
                                                                                                requests.app.trainingGrid.getStore().load({params:{start:0, limit: 25}});

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
                                                }
 			 ]
 	});

		requests.app.trainingGrid = trainingGrid;
		requests.app.trainingGrid.getStore().load({params:{start: 0, limit: 25}});
		
		var titoStore = new Ext.data.Store({
					proxy: new Ext.data.HttpProxy({
                                        url: "<?=  site_url("overtime/getTITO")?>",
						method: "POST"
						}),
					reader: new Ext.data.JsonReader({
							root: "data",
							id: "id",
							totalProperty: "totalCount",
							fields: [
                                                                    { name: "id", mapping: "id" },
                                                                    { name: "employee_id" },
													{ name: "date_requested"},
													{ name: "date_time_in"},
													{ name: "date_time_out"},
													{ name: "time_in"},
													{ name: "time_out"},
													{ name: "status"},
                                                      //  {name: 'id'},
                                                        {name: 'app_type'},
                                                        {name: 'audit_id'}
									]
					}),
					remoteSort: true,
					baseParams: {start: 0, limit: 25}
				});


		var titoGrid = new Ext.grid.GridPanel({
			id: 'titoGrid',
			height: 422,
			width: '100%',
			border: true,
			ds: titoStore,
			cm:  new Ext.grid.ColumnModel(
					[

					  { header: "Date Filed", width: 140, sortable: true, locked:true, dataIndex: "date_requested", resizable: true },
								  { header: "Date Time In", width: 140, sortable: true, locked:true, dataIndex: "date_time_in", resizable: true  },
								  { header: "Time In", width: 140, sortable: true, locked:true, dataIndex: "time_in", resizable: true  },
								  { header: "Date Time Out", width: 140, sortable: true, locked:true, dataIndex: "date_time_out", resizable: true  },
								  { header: "Time Out", width: 140, sortable: true, locked:true, dataIndex: "time_out", resizable: true  },
								  { header: "Status", width: 100, sortable: true, locked:true, dataIndex: "status", renderer: this.statusFormat, resizable: true  }
					]
			),
			sm: new Ext.grid.RowSelectionModel({singleSelect:true}),
     	loadMask: true,
     	bbar:
     		new Ext.PagingToolbar({
	        		autoShow: true,
			        pageSize: 25,
			        store: titoStore,
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
		},'   ', new Ext.app.SearchField({ store: titoStore, width:250}),
				    {
 					     	xtype: 'tbfill'
 					 	},
                                                {
                                                    xtype: 'tbbutton',
                                                    text: 'APPLY',
                                                    icon: '/images/icons/application_form_add.png',
                                                    handler: requests.app.apply_tito
                                                }, '-',
                                                {
                                                    xtype: 'tbbutton',
                                                    text: 'VIEW',
                                                    icon: '/images/icons/application_form_magnify.png',
                                                    handler: requests.app.view_tito
                                                }, '-',
                                                {
                                                    xtype: 'tbbutton',
                                                    text: 'VOID',
                                                    icon: '/images/icons/application_form_delete.png',
                                                    handler: function(){
                                                                 if(ExtCommon.util.validateSelectionGrid(requests.app.titoGrid.getId())){
                                                                var sm = requests.app.titoGrid.getSelectionModel();
                                                                var id = sm.getSelected().data.audit_id;
                                                                Ext.Msg.show({
                                                                title:'Delete',
                                                                msg: 'Are you sure you want to cancel this application?',
                                                                buttons: Ext.Msg.OKCANCEL,
                                                                fn: function(btn, text){
                                                                if (btn == 'ok'){

                                                                Ext.Ajax.request({
                                                                                                url: "<?php echo site_url("apps/voidApplication")?>",
                                                                                                params:{ audit_id: id},
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
                                                                                                requests.app.titoGrid.getStore().load({params:{start:0, limit: 25}});

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
                                                }
 			 ]
 	});

		requests.app.titoGrid = titoGrid;
		requests.app.titoGrid.getStore().load({params:{start: 0, limit: 25}});


var forceLeaveStore = new Ext.data.Store({
 						proxy: new Ext.data.HttpProxy({
 							url: "<?php echo site_url("user/getForceLeave")?>",
 							method: "POST"
 							}),
 						reader: new Ext.data.JsonReader({
 								root: "data",
 								id: "id",
 								totalProperty: "totalCount",
 								fields: [
 											{ name: "id"},
 											{ name: "employee_id"},
                                                                                        { name: "employee_name"},
                                                                                        { name: "date_requested"},
                                                                                        { name: "reason"},
                                                                                        { name: "no_days"},
                                                                                        { name: "date_from"},
                                                                                        { name: "date_to"},
                                                                                        { name: "status"},
                                                                                        { name: "requested_by"}
 										]
 						}),
 						remoteSort: true,
 						baseParams: {start: 0, limit: 25}
 					});


 			var forceLeaveGrid = new Ext.grid.GridPanel({
 				id: 'forceLeaveGrid',
 				height: 422,
 				width: '100%',
 				border: true,
 				ds: forceLeaveStore,
 				cm:  new Ext.grid.ColumnModel(
 						[
 						  { header: "Id", dataIndex: "id", width: 100, sortable: true},
 						  { header: "Employee Name", width: 200, sortable: true, dataIndex: "employee_name" },
                                                  { header: "Date Filed", dataIndex: "date_requested", width: 100, sortable: true},
                                                  { header: "Date From", dataIndex: "date_from", width: 100, sortable: true},
                                                  { header: "Date To", dataIndex: "date_to", width: 100, sortable: true},
                                                  { header: "No. of Days", dataIndex: "no_days", width: 100, sortable: true},
                                                  { header: "Reason", dataIndex: "reason", width: 150, sortable: true},
                                                  { header: "Filed by", dataIndex: "requested_by", width: 150, sortable: true},
                                                  { header: "Status", dataIndex: "status", width: 120, renderer: this.statusFormat, sortable: true}
 						]
 				),
 				sm: new Ext.grid.RowSelectionModel({singleSelect:true}),
 	        	loadMask: true,
 	        	bbar:
 	        		new Ext.PagingToolbar({
 		        		autoShow: true,
 				        pageSize: 25,
 				        store: forceLeaveStore,
 				        displayInfo: true,
 				        displayMsg: 'Displaying Results {0} - {1} of {2}',
 				        emptyMsg: "No Data Found."
 				    }),
 				tbar: [
 					    {
 					     	xtype: 'tbfill'
 					 	},{
 					     	xtype: 'tbbutton',
 					     	text: 'VIEW',
							icon: '/images/icons/application_edit.png',
 							cls:'x-btn-text-icon',

 					     	handler: requests.app.viewForceLeave

 					 	}
 	    			 ]
 	    	});

 			requests.app.forceLeaveGrid = forceLeaveGrid;
 			requests.app.forceLeaveGrid.getStore().load({params:{start: 0, limit: 25}});


 			var tabs = new Ext.TabPanel({
		        renderTo: 'mainBody',
		        width:'100%',
		        activeTab: 0,
		        frame:true,
		        height: 450,
                       // layout: 'fit',
		        //defaults:{autoHeight: true},
		        items:[
		            {title: 'Leave Applications', items: requests.app.Grid},
		            {title: 'Overtime Applications', items: requests.app.overtimeGrid},
                            {title: 'Client Schedule', items: requests.app.clientScheduleGrid},
                            {title: 'Training', items: requests.app.trainingGrid},
                            {title: 'TITO', items: requests.app.titoGrid},
                            {title: 'Force Leave', items: requests.app.forceLeaveGrid}
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

                        var reader = new Ext.data.JsonReader({
                                                idProperty: 'id',
						fields: [
									{ name: "member_name" },

									{ name: "description"},
                                                                        {name: 'id'},
                                                                        {name: 'app_tree_details_id'}

								]
						});

                                        Objstore = new Ext.data.GroupingStore({
                                            reader		: 	reader,
                                            data		: 	new Array(),
                                            remoteSort  : true,
                                            sortInfo	:	{field: 'app_tree_details_id', direction: "ASC"},
                                            groupField	:	'description'
                                        });

                                        var gridApprovers = new Ext.grid.GridPanel({
						id		: 	'LeaveApprovers',
						loadMask	: 	true,
						border		: 	false,
                                                store		: 	Objstore,
						columns	:
								[
                                                                { header: "id", width: 150, sortable: false, locked:true, dataIndex: "id", hidden: true },
    							  { header: "Group Name", width: 150, sortable: false, locked:true, dataIndex: "description" },
								  { header: "Approver's Name", width: 200, sortable: false, locked:true, dataIndex: "member_name" }
								],
                                        view: new Ext.grid.GroupingView({
                                         forceFit:true,
                                         enableNoGroups	: true,
                                         groupText:'{text}'
                                        })
                                        });

                                var LeaveApprovers = new Ext.Panel({
                                    title       : 'Approvers',
                                    iconCls		: 'icon_appgroup',
                                    region      : 'east',
                                    split       : true,
                                    width       : 380,
                                    collapsible : true,
                                    layout		: "fit",
                                    margins     : '3 0 3 3',
                                    cmargins    : '3 3 3 3',
                                    items: [ gridApprovers ]
                                });

                                var form = new Ext.form.FormPanel({
                                        labelWidth: 75,
                                        url: "<?php echo site_url("leaves/applyLeave")?>",
                                        method: 'POST',
                                        frame: true,
                                        items: [LeaveCredits,
                                        {
                                           xtype: 'fieldset',
                                           title : 'Leave Details',
                                           height : 'auto',
                                           items  : [
		               		
                                        requests.app.leaveTypeCombo(),
                                        requests.app.callLogCombo(),
                                                      {
                                                            xtype: 'datefield',
                                                            name: 'date_from',
                                                            id: 'date_from',
                                                            format: 'Y-m-d',
                                                            fieldLabel: 'Date From',
                                                            allowBlank: false,
                                                            anchor: '55%',
                                                            vtype: 'daterange',
                                                            endDateField: 'date_to',
                                                            listeners:{
                                                                change: function(){
				                		requests.app.setNoOfDays();
                                                            },
                                                                blur: function(){
					                  	requests.app.setNoOfDays();
                                                            }
                                                            }
                                                       },
                                              
                                                      {
                                                            xtype: 'datefield',
                                                            name: 'date_to',
                                                            id: 'date_to',
                                                            format: 'Y-m-d',
                                                            fieldLabel: 'Date To',
                                                            allowBlank: false,
                                                            anchor: '55%',
                                                            vtype: 'daterange',
                                                            startDateField: 'date_from',
                                                            listeners:{
                                                                change: function(){
				                		requests.app.setNoOfDays();
                                                            },
                                                                blur: function(){
					                  	requests.app.setNoOfDays();
                                                            }
                                                            }
                                                       },
                                                       new Ext.form.ComboBox({
                                                            fieldLabel: 'Portion',
                                                            hiddenName:'portion_hdn',
                                                            id: 'leave_portion',
                                                            name: 'portion',
                                                            allowBlank: false,
                                                                        //store: Objstore,
                                                            typeAhead: true,
                                                            triggerAction: 'all',
                                                            selectOnFocus:true,

                                                            store: new Ext.data.SimpleStore({
                                                                         id:0
                                                                        ,fields:
                                                                            [
                                                                             'myId',   //numeric value is the key
                                                                             'myText' //the text value is the value

                                                                            ]


                                                                         , data: [['WHOLE DAY', 'WHOLE DAY'], ['FIRST HALF', 'FIRST HALF'], ['SECOND HALF', 'SECOND HALF']]

                                                                }),
                                                                    valueField:'myId',
                                                                    displayField:'myText',
                                                                    mode:'local',
                                                                    anchor:'55%',
                                                                    listeners:{
                                                                        change: function(){
                                                                        requests.app.setNoOfDaysByPortion();
                                                                    },
                                                                        blur: function(){
                                                                        requests.app.setNoOfDaysByPortion();
                                                                    }
                                                                    }

                                                        }),
		               		{ xtype: 'textfield',
                                          name: 'no_of_days',
                                          id: 'no_of_days',
                                          anchor:'55%',
                                          fieldLabel: 'No. of Days',
                                          readOnly: true,
                                          allowBlank: true
                                        },
					{ xtype: 'textarea', 
                                          id: 'txtreason',
                                          name: 'reason',
                                          anchor:'93%',
                                          fieldLabel: 'Reason',
                                          allowBlank: false,
                                          maxLength: '128'
                                        },
                                        {
                                        	xtype: 'hidden',
                                        	id: 'vacation_leave',
                                        	name: 'vacation_leave'
                                        },
                                        {
                                        	xtype: 'hidden',
                                        	id: 'sick_leave',
                                        	name: 'sick_leave'
                                        },
                                        {
                                        	xtype: 'hidden',
                                        	id: 'emergency_leave',
                                        	name: 'emergency_leave'
                                        },
                                        {
                                        	xtype: 'hidden',
                                        	id: 'maternity_leave',
                                        	name: 'maternity_leave'
                                        },
                                        {
                                        	xtype: 'hidden',
                                        	id: 'paternity_leave',
                                        	name: 'paternity_leave'
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
					height: 625,
					bodyStyle:'padding:5px;',
					plain: true,
					modal: true,
					layout: 'border',
					items: [ fPanel, LeaveApprovers ],
					buttons: [
							{
							  text: 'Save',
                                                          icon: '/images/icons/disk.png',
                                                          handler: function () {
                                                                        if(ExtCommon.util.validateFormFields(form)){//check if all forms are filled up

                                                                    form.getForm().submit({
                                                                            success: function(f,action){
                                                                            Ext.MessageBox.alert('Status', action.result.data);
                                                                             Ext.Msg.show({
                                                                                                         title: 'Status',
                                                                                                         msg: action.result.data,
                                                                                                         buttons: Ext.Msg.OK,
                                                                                                         icon: Ext.Msg.INFO
                                                                                                     });
                                                                                    ExtCommon.util.refreshGrid(requests.app.Grid.getId());
                                                                                    applyWinView.destroy();
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
							},
							{ text: 'Cancel',
                                                        icon: '/images/icons/cancel.png',
                                                            disabled: false,
                                                            handler: function(){
                                                                applyWinView.destroy();
                                                            }
                                                        }
						]
			});

                        form.form.load({
							url:"<?php echo site_url("apps/checkEmployeeFlow"); ?>",
                                                        params: {type: 2},
							waitMsg:'Loading...',
							success: function(f,a){
                                                            Ext.getCmp('LeaveApprovers').getStore().loadData(a.result.approvers);
                                                            var data = a.result;
                                                            var tplApplicationDetails = new Ext.XTemplate(
								'<br />',
								'<p>',
                                                                '<tpl for="data">',
									'<table width="100%" style="background: #fff;padding: 4px;border: solid 1px #5aa865;font-size: 10pt">',
									
										'<tr style="background: #5aa865;">',
											'<td colspan="3" style="color:#fff;font-weight:bold;padding: 4px;">{leave_title}</td>',
										'</tr>',
										'<tr style="background: #d8f1dc;">',
											'<td style="padding: 4px;text-align:center; font-weight: bold" >&nbsp;</td>',
											'<td style="padding: 4px;text-align:center; font-weight: bold" >Used</td>',
											'<td style="padding: 4px;text-align:center; font-weight: bold;" >Remaining</td>',
										'</tr>',
										'<tr style="background: #d8f1dc;" >',
											'<td style="padding: 4px;text-align:left" width="180px">Vacation Leave Credits:</td>',
											'<td style="padding: 4px;text-align: center; " >{vacation_leave_used}</td>',
											'<td style="padding: 4px;text-align: center; " >{vacation_leave}</td>',
										'</tr>',
                                                                                '<tr style="background: #d8f1dc;" >',
											'<td style="padding: 4px;text-align:left" width="180px">Sick Leave Credits:</td>',
											'<td style="padding: 4px;text-align: center; " >{sick_leave_used}</td>',
											'<td style="padding: 4px;text-align: center; " >{sick_leave}</td>',
										'</tr>',
										'<tr style="background: #d8f1dc;" >',
											'<td style="padding: 4px;text-align:left;" width="180px">Emergency Leave Credits:</td>',
											'<td style="padding: 4px;text-align: center;" >{emergency_leave_used}</td>',
											'<td style="padding: 4px;text-align: center;" >{emergency_leave}</td>',
										'</tr>',
										'<tr style="background: #5aa865;">',
											'<td colspan="3" style="color:#fff;font-weight:bold;padding: 4px;">Unpaid Leaves</td>',
										'</tr>',
										'<tr style="background: #d8f1dc;">',
											'<td style="padding: 4px;text-align:center; font-weight: bold" >&nbsp;</td>',
											'<td style="padding: 4px;text-align:center; font-weight: bold" colspan="2">Used</td>',
											//'<td style="padding: 4px;text-align:center; font-weight: bold;" >Remaining</td>',
										'</tr>',
										'<tr style="background: #d8f1dc;" >',
											'<td style="padding: 4px;text-align:left" width="180px">Vacation Leave Credits:</td>',
											'<td style="padding: 4px;text-align: center; " colspan="2">{unpaid_vacation_leave_used}</td>',
											//'<td style="padding: 4px;text-align: center; " >{unpaid_vacation_leave}</td>',
										'</tr>',
                                                                                '<tr style="background: #d8f1dc;" >',
											'<td style="padding: 4px;text-align:left" width="180px">Sick Leave Credits:</td>',
											'<td style="padding: 4px;text-align: center; " colspan="2">{unpaid_sick_leave_used}</td>',
											//'<td style="padding: 4px;text-align: center; " >{unpaid_sick_leave}</td>',
										'</tr>',
									'</table>',
                                                                        '</tpl>',
								'</p>',
								'<br />'
                                                                );

                                                            var approver_details = a.result.data.approver_details;
                                                            if(approver_details != null && typeof(approver_details) != "undefined")
                                                                    Ext.getCmp("panel_leave_credits").html = tplApplicationApprovers.applyTemplate(a.result.data);

                                                            Ext.getCmp("panel_leave_credits").html += tplApplicationDetails.applyTemplate(data);

                                                            applyWinView.show();
							},
                                                        failure: function(f,a){
 								Ext.Msg.show({
 									title: 'Error Alert',
 									msg: a.result.msg,
 									icon: Ext.Msg.ERROR,
 									buttons: Ext.Msg.OK
 								});
                                                        }

						});
                        //applyWinView.show();
                },
                setNoOfDays: function(){

                        var portion = Ext.getCmp("leave_portion").getValue();

                        if(portion == 'FIRST HALF' || portion == 'SECOND HALF'){
                            requests.app.setNoOfDaysByPortion();
                            return;
                        }

			obj 	 = Ext.getCmp('no_of_days');
			objdate1 = Ext.getCmp("date_from").getRawValue();
			objdate2 = Ext.getCmp("date_to").getRawValue();
                        //alert(objdate1);
			if(objdate1 != "" || objdate2 != "")
			{
				ddate1 = new Date();
				ddate2 = new Date();
				diff = new Date();

				if(String(objdate1).indexOf("-") != -1)
					arDate1 = objdate1.split("-");
				else
					arDate1 = objdate1.split("/");

				if(String(objdate2).indexOf("-") != -1)
					arDate2 = objdate2.split("-");
				else
					arDate2 = objdate2.split("/");

				ddate1temp = new Date(arDate1[0], arDate1[1]-1, arDate1[2]);
				ddate1.setTime(ddate1temp.getTime());

				ddate2temp = new Date(arDate2[0], arDate2[1]-1, arDate2[2]);
				ddate2.setTime(ddate2temp.getTime());

				//sets difference date to difference of first date and second date
				diff.setTime(Math.abs(ddate1.getTime() - ddate2.getTime()));
				timediff = diff.getTime();

				weeks = Math.floor(timediff / (1000 * 60 * 60 * 24 * 7));
				timediff -= weeks * (1000 * 60 * 60 * 24 * 7);

				days = Math.floor(timediff / (1000 * 60 * 60 * 24));
				timediff -= days * (1000 * 60 * 60 * 24);

				totaldays = (weeks*7) + days;

				if(!isNaN(Number(totaldays)))
				{
					dispvalue = (ddate1.getTime() > ddate2.getTime() ? "-"+(requests.app.round(totaldays)+1) : (requests.app.round(totaldays)+1));
					obj.setValue((String(dispvalue).indexOf(".") == -1 ? (dispvalue)+".0" : dispvalue));
				}
				else
					obj.setValue("");
			}
			else
			{
				obj.setValue("");
			}
		},
                round: function(number,X){
			X = (!X ? 2 : X);
			return Math.round(number*Math.pow(10,X))/Math.pow(10,X);
		},
                setNoOfDaysByPortion: function(){

			if(Ext.getCmp("leave_portion").getValue() != "WHOLE DAY")
			{
				Ext.getCmp('no_of_days').setValue('0.5');
				Ext.getCmp("date_to").setValue(Ext.getCmp("date_from").getValue());
			}
			else
				requests.app.setNoOfDays();
		},
                statusFormat: function(val){

			var fmtVal;

			switch(val){
				case "Approved"	: 	fmtVal = '<span style="color: blue; font-weight: bold;">'+val+'</span>'; break;
			 	case "Denied"	:  	fmtVal = '<span style="color: red; font-weight: bold;">'+val+'</span>'; break;
			 	case "Cancelled": 	fmtVal = '<span style="color: red; font-weight: bold;">'+val+'</span>'; break;
			 	case "Pending"	: 	fmtVal = '<span style="color: green; font-weight: bold;">'+val+'</span>'; break;
				case "Recalled" : fmtVal = '<span style="color: red; font-weight: bold;">'+val+'</span>'; break;
				case "System Void"	: 	fmtVal = '<span style="color: green; font-weight: bold;">'+val+'</span>'; break;

			}

			return fmtVal;
		},
                view_application: function(){
                if(ExtCommon.util.validateSelectionGrid(requests.app.Grid.getId())){//check if user has selected an item in the grid
			var sm = requests.app.Grid.getSelectionModel();
			var id = sm.getSelected().data.audit_id;
                        var pk = sm.getSelected().data.id;
                        var app_type = sm.getSelected().data.app_type;

                        var reader = new Ext.data.JsonReader({
                                                idProperty: 'id',
						fields: [
									{ name: "member_name" },

									{ name: "description"},
                                                                        {name: 'id'}

								]
						});

			/*
			*	SET STORE FOR THE GROUPING GRID
			*/

                                        Objstore = new Ext.data.GroupingStore({
                                            reader		: 	reader,
                                            data		: 	new Array(),
                                            sortInfo	:	{field: 'id', direction: "ASC"},
                                            groupField	:	'description'
                                        });

                                        var gridApprovers = new Ext.grid.GridPanel({
						id		: 	'ApplicationApprovers',
						loadMask	: 	true,
						border		: 	false,
                                                store		: 	Objstore,
						columns	:
								[
                                                                { header: "id", width: 150, sortable: false, locked:true, dataIndex: "id", hidden: true },
    							  { header: "Group Name", width: 150, sortable: false, locked:true, dataIndex: "description" },
								  { header: "Approver's Name", width: 200, sortable: false, locked:true, dataIndex: "member_name" }
								],
                                        view: new Ext.grid.GroupingView({
                                         forceFit:true,
                                         enableNoGroups	: true,
                                         groupText:'{text}'
                                        })
                                        });

                                var ApplicationApprovers = new Ext.Panel({
				title       : 'Approvers',
				iconCls		: 'icon_appgroup',
                                region      : 'east',
                                split       : true,
                                width       : 280,
                                collapsible : true,
                                layout		: "fit",
                                margins     : '3 0 3 3',
                                cmargins    : '3 3 3 3',
                                items: [ gridApprovers ]
                                    });

                                    var fPanelHtml = new Ext.Panel({
                                        id		: "_panelHtml",
                                        border	: 	false,
                                        width	: 580,
                                        margins : 	'1 1 1 0',
                                        html	: ""
                                    });

                                var ApplicationDetailsPanel = new Ext.FormPanel({
				id			: 	"_applicationDetailsPanel",
		 		border		: 	false,
                                frame		:	true,
                                bodyStyle	:	'padding:0 0 0',
                                width		: 	595,
                                height		: 	550,
                                        autoScroll	:	true,
                                items		:
                                [
					fPanelHtml
                                ]
                                });

                                var fPanel = new Ext.Panel({
				border	: 	false,
			 	region  : 	'center',
			 	width	: 	520,
                                margins : 	'1 1 1 0',
                                items	: 	[ApplicationDetailsPanel]
                                });

                                ApplicationDetailsPanel.form.load({
				url		:	"<?php echo site_url('apps/viewApplication') ?>",
				method	: 	'POST',
				params	: 	{ id: id, app_type: app_type, pk: pk },
				waitMsg	:	'Loading...',
				success	: 	function(form, action){

				/*
				 * fill-in the approver list
				 */

					Ext.getCmp('ApplicationApprovers').getStore().loadData(action.result.approvers);


				/*
				 *  DETAILS FOR APPROVAL HERE PER APPLICATION TYPE
				 */

						var tplApplicationApprovers = new Ext.XTemplate(
								'<br />',
								'<p>',
									'<table width="520"  style="background: #fff;padding: 4px;border:solid 1px #ff6666;font-size: 10pt">',
							    		'<tr style="background: #ff6666;">',
							    			'<td colspan="4" style="color:#fff;font-weight:bold;padding: 4px;">Approvers Details</td>',
							    		'</tr>',

							    		'<tr style="background: #ffbec2;">',
							    			'<td style="padding: 4px;font-weight:bold" >Approvers Name</td>',
							    			'<td style="padding: 4px;font-weight:bold" >Action Date</td>',
							    			'<td style="padding: 4px;font-weight:bold" >Status</td>',
							    			'<td style="padding: 4px;font-weight:bold" >Remarks</td>',
							    		'</tr>',
							    		'<tpl for="approver_details">',
								    		'<tr >',
								    			'<td style="padding: 4px;font-weight:bold; background: #ffbec2;" >{approver}</td>',
								    			'<td style="padding: 4px;font-weight:bold; background: #ffd9d9" >{action_timestamp}</td>',
								    			'<td style="padding: 4px;font-weight:bold; background: #ffd9d9" >{status}</td>',
								    			'<td style="padding: 4px;font-weight:bold; background: #ffd9d9" >{remarks}</td>',
								    		'</tr>',
								    	'</tpl>',
							    	'</table>',
								'</p>',
								'<br />',
								'<br />'
							);

					var approver_details = action.result.approver_details;
				    if(approver_details != null && typeof(approver_details) != "undefined")
						Ext.getCmp("_panelHtml").html = tplApplicationApprovers.applyTemplate(action.result);

				/*
				 *  DETAILS FOR APPROVAL HERE PER APPLICATION TYPE
				 */

					
							 var data = action.result.data;
							 var tplApplicationDetails = new Ext.XTemplate(
									'<br />',
									'<p>',
                                                                        '<tpl for="data">',
										'<table width="520" style="background: #fff;padding: 4px;border: solid 1px #5aa865;font-size: 10pt">',
										'<tr style="background: #5aa865;">',
											'<td colspan="2" style="color:#fff;font-weight:bold;padding: 4px;">Application Details</td>',
										'</tr>',
										'<tr style="background: #d8f1dc;">',
											'<td style="padding: 4px;text-align:right;" >Employee Name:</td>',
											'<td style="padding: 4px;" >{employee_name}</td>',
										'</tr>',
										'<tr style="background: #d8f1dc;" >',
											'<td style="padding: 4px;text-align:right;" width="180px">Date Filed:</td>',
											'<td style="padding: 4px;" >{date_requested}</td>',
										'</tr>',
										'<tr style="background: #d8f1dc;">',
											'<td style="padding: 4px;text-align:right;" >From:</td>',
											'<td style="padding: 4px;" >{date_from}</td>',
										'</tr>',
										'<tr style="background: #d8f1dc;">',
											'<td style="padding: 4px;text-align:right;" >To:</td>',
											'<td style="padding: 4px;" >{date_to}</td>',
										'</tr>',
										'<tr style="background: #d8f1dc;">',
											'<td style="padding: 4px;text-align:right;" >No. of Days:</td>',
											'<td style="padding: 4px;" >{no_days}</td>',
										'</tr>',
                                                                                '<tr style="background: #d8f1dc;">',
											'<td style="padding: 4px;text-align:right;" >Leave Type:</td>',
											'<td style="padding: 4px;" >{leave_type}</td>',
										'</tr>',
										'<tr style="background: #d8f1dc;">',
											'<td style="padding: 4px;text-align:right;" >Reason:</td>',
											'<td style="padding: 4px;" >{reason}</td>',
										'</tr>',
										'</table>',
                                                                                '</tpl>',
									'</p>',
									'<br />');

							 //Ext.getCmp("_panelHtml").html += tplLeaveCredits.applyTemplate(dataLeave);
							 Ext.getCmp("_panelHtml").html += tplApplicationDetails.applyTemplate(action.result);
							// Ext.getCmp("gridLastFiled").getStore().loadData(action.result.last3filed);

						
					openWin.show();
				},
				failure: function(form, action){
					Ext.Msg.show({title: 'Error Alert',	msg:action.result.data, icon: Ext.Msg.ERROR,buttons: Ext.Msg.OK});
					openWin.destroy();
				}
			});

                                var openWin = new Ext.Window({
					title		: "Application Approval",
					width		: 900,
					height		: 520,
					bodyStyle	:'padding:5px;',
					plain		: true,
					modal		: true,
					layout		: 'border',
					items		: [ fPanel, ApplicationApprovers ],
					autoScroll: true,
                                        buttonAlign: 'center',
					buttons: [
							{
								text: 'Close',
                                                                icon: '/images/icons/cancel.png',
								disabled: false,
								handler: function(){
                                                                openWin.destroy();
                                                                }
                                                }
						],
						listeners: {
							show: function(obj) {
									obj.doLayout();
									//fPanel.doLayout();
							}

						}
			});

                }else return;

                },
                apply_ot: function(){
                    

                        var reader = new Ext.data.JsonReader({
                                                idProperty: 'id',
						fields: [
									{ name: "member_name" },

									{ name: "description"},
                                                                        {name: 'id'},
                                                                        {name: 'app_tree_details_id'}

								]
						});

                                        Objstore = new Ext.data.GroupingStore({
                                            reader		: 	reader,
                                            data		: 	new Array(),
                                            remoteSort  : true,
                                            sortInfo	:	{field: 'app_tree_details_id', direction: "ASC"},
                                            groupField	:	'description'
                                        });

                                        var gridApprovers = new Ext.grid.GridPanel({
						id		: 	'LeaveApprovers',
						loadMask	: 	true,
						border		: 	false,
                                                store		: 	Objstore,
						columns	:
								[
                                                                { header: "id", width: 150, sortable: false, locked:true, dataIndex: "id", hidden: true },
    							  { header: "Group Name", width: 150, sortable: false, locked:true, dataIndex: "description" },
								  { header: "Approver's Name", width: 200, sortable: false, locked:true, dataIndex: "member_name" }
								],
                                        view: new Ext.grid.GroupingView({
                                         forceFit:true,
                                         enableNoGroups	: true,
                                         groupText:'{text}'
                                        })
                                        });

                                var LeaveApprovers = new Ext.Panel({
                                    title       : 'Approvers',
                                    iconCls		: 'icon_appgroup',
                                    region      : 'east',
                                    split       : true,
                                    width       : 380,
                                    collapsible : true,
                                    layout		: "fit",
                                    margins     : '3 0 3 3',
                                    cmargins    : '3 3 3 3',
                                    items: [ gridApprovers ]
                                });

                                var form = new Ext.form.FormPanel({
                                        labelWidth: 75,
                                        url: "<?php echo site_url("overtime/applyOT")?>",
                                        method: 'POST',
                                        frame: true,
                                        items: [
                                        {
                                           xtype: 'fieldset',
                                           title : 'Overtime Information',
                                           height : 'auto',
                                           items  : [
                                                       {
		            layout:'column',
		            items:[{
		                columnWidth:.5,
		                layout: 'form',
		                items: [
		                	{
                                                            xtype: 'datefield',
                                                            name: 'date_from',
                                                            id: 'date_from',
                                                            format: 'Y-m-d',
                                                            fieldLabel: 'From',
                                                            allowBlank: false,
                                                            anchor: '90%',
                                                            vtype: 'daterange',
                                                            endDateField: 'date_to',
                                                            listeners:{
                                                              change: function(){
                                                                            requests.app.setNoOfHours();
                                                              },
                                                              blur: function(){
                                                                            requests.app.setNoOfHours();
                                                                  }
                                                            }
                                                       },

                                                      {
                                                            xtype: 'datefield',
                                                            name: 'date_to',
                                                            id: 'date_to',
                                                            format: 'Y-m-d',
                                                            fieldLabel: 'To',
                                                            allowBlank: false,
                                                            anchor: '90%',
                                                            vtype: 'daterange',
                                                            startDateField: 'date_from',
                                                            listeners:{
                                                              change: function(){
                                                                            requests.app.setNoOfHours();
                                                              },
                                                              blur: function(){
                                                                            requests.app.setNoOfHours();
                                                                  }
                                                            }
                                                       },
		                	{ xtype:'textfield', id: 'txtNoOfHours', name: 'no_of_hours', anchor:'70%', fieldLabel: 'No of Hours', readOnly: true, allowBlank: false }

		                ]
	            	},
	            	{
		            	columnWidth: .5,
		            	labelWidth: 4,
		                layout: 'form',
		                items: [
		                	{
			                	xtype:'textfield', id: 'txtOTTimeFrom', name: 'time_from',
			                	labelSeparator: "", vtypeText: "(hh:mm:ss)",
                                                
			                	emptyText: 'hh:mm:ss', allowBlank: false,
			                	listeners:{
			                	  change: function(){
				                		requests.app.setNoOfHours();
				                  },
				                  blur: function(){
					                  	requests.app.setNoOfHours();
					              }
			                	}
			                },
		                	{
			                	xtype:'textfield', id: 'txtOTTimeTo', name: 'time_to',
			                	labelSeparator: "", vtypeText: "(hh:mm:ss)",
                                                
			                	emptyText: 'hh:mm:ss', allowBlank: false,
			                	listeners:{
			                	  change: function(){
				                		requests.app.setNoOfHours();
				                  },
				                  blur: function(){
					                  	requests.app.setNoOfHours();
					              }
			                	}
			                },
		                	{ xtype:'label', text: "" }
		                ]
		            }]
		        },
					{ xtype: 'textarea',
                                          id: 'txtreason',
                                          name: 'reason',
                                          anchor:'90%',
                                          fieldLabel: 'Reason',
                                          allowBlank: false,
                                          maxLength: '128'
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
					title: "Overtime Application",
					width: 900,
					height: 270,
					bodyStyle:'padding:5px;',
					plain: true,
					modal: true,
					layout: 'border',
					items: [ fPanel, LeaveApprovers ],
					buttons: [
							{
							  text: 'Save',
                                                          icon: '/images/icons/disk.png',
                                                          handler: function () {
                                                                        if(ExtCommon.util.validateFormFields(form)){//check if all forms are filled up

                                                                    form.getForm().submit({
                                                                            success: function(f,action){
                                                                            Ext.MessageBox.alert('Status', action.result.data);
                                                                             Ext.Msg.show({
                                                                                                         title: 'Status',
                                                                                                         msg: action.result.data,
                                                                                                         buttons: Ext.Msg.OK,
                                                                                                         icon: Ext.Msg.INFO
                                                                                                     });
                                                                                    ExtCommon.util.refreshGrid(requests.app.overtimeGrid.getId());
                                                                                    applyWinView.destroy();
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
							},
							{ text: 'Cancel',
                                                        icon: '/images/icons/cancel.png',
                                                            disabled: false,
                                                            handler: function(){
                                                                applyWinView.destroy();
                                                            }
                                                        }
						]
			});

                        form.form.load({
							url:"<?php echo site_url("apps/checkEmployeeFlow"); ?>",
                                                        params: {type: 1},
							waitMsg:'Loading...',
							success: function(f,a){
                                                            Ext.getCmp('LeaveApprovers').getStore().loadData(a.result.approvers);

                                                            applyWinView.show();
							},
                                                        failure: function(f,a){
 								Ext.Msg.show({
 									title: 'Error Alert',
 									msg: a.result.msg,
 									icon: Ext.Msg.ERROR,
 									buttons: Ext.Msg.OK
 								});
                                                        }

						});
                        //applyWinView.show();
                },
                apply_tito: function(){
                    

                        var reader = new Ext.data.JsonReader({
                                                idProperty: 'id',
						fields: [
									{ name: "member_name" },

									{ name: "description"},
                                                                        {name: 'id'},
                                                                        {name: 'app_tree_details_id'}

								]
						});

                                        Objstore = new Ext.data.GroupingStore({
                                            reader		: 	reader,
                                            data		: 	new Array(),
                                            remoteSort  : true,
                                            sortInfo	:	{field: 'app_tree_details_id', direction: "ASC"},
                                            groupField	:	'description'
                                        });

                                        var gridApprovers = new Ext.grid.GridPanel({
						id		: 	'LeaveApprovers',
						loadMask	: 	true,
						border		: 	false,
                                                store		: 	Objstore,
						columns	:
								[
                                                                { header: "id", width: 150, sortable: false, locked:true, dataIndex: "id", hidden: true },
    							  { header: "Group Name", width: 150, sortable: false, locked:true, dataIndex: "description" },
								  { header: "Approver's Name", width: 200, sortable: false, locked:true, dataIndex: "member_name" }
								],
                                        view: new Ext.grid.GroupingView({
                                         forceFit:true,
                                         enableNoGroups	: true,
                                         groupText:'{text}'
                                        })
                                        });

                                var LeaveApprovers = new Ext.Panel({
                                    title       : 'Approvers',
                                    iconCls		: 'icon_appgroup',
                                    region      : 'east',
                                    split       : true,
                                    width       : 380,
                                    collapsible : true,
                                    layout		: "fit",
                                    margins     : '3 0 3 3',
                                    cmargins    : '3 3 3 3',
                                    items: [ gridApprovers ]
                                });

                                var form = new Ext.form.FormPanel({
                                        labelWidth: 75,
                                        url: "<?php echo site_url("overtime/applyTITO")?>",
                                        method: 'POST',
                                        frame: true,
                                        items: [
                                        {
                                           xtype: 'fieldset',
                                           title : 'TITO Information',
                                           height : 'auto',
                                           items  : [
                                                       {
		            layout:'column',
		            items:[{
		                columnWidth:.5,
		                layout: 'form',
		                items: [
		                	{
                                                            xtype: 'datefield',
                                                            name: 'date_time_in',
                                                            id: 'date_time_in',
                                                            format: 'Y-m-d',
                                                            fieldLabel: 'From',
                                                            allowBlank: false,
                                                            anchor: '90%',
                                                            vtype: 'daterange',
                                                            endDateField: 'date_time_out'
                                                       },

                                                      {
                                                            xtype: 'datefield',
                                                            name: 'date_time_out',
                                                            id: 'date_time_out',
                                                            format: 'Y-m-d',
                                                            fieldLabel: 'To',
                                                            allowBlank: false,
                                                            anchor: '90%',
                                                            vtype: 'daterange',
                                                            startDateField: 'date_time_in'
                                                       }

		                ]
	            	},
	            	{
		            	columnWidth: .5,
		            	labelWidth: 4,
		                layout: 'form',
		                items: [
		                	{
                                                           xtype: 'timefield',
                                                            name: 'time_in',
                                                            id: 'time_in',
                                                            allowBlank: false,
                                                            minValue: '00:00:00',
                                                            maxValue: '23:00:00',
                                                            //value: '08:00:00',
                                                            increment: 30,
                                                            format: 'H:i:s',
                                                            anchor: '80%',
                                                            vtype: 'timerange',
                                                            endTimeField: 'time_out'
                                                        },
		                	{
                                                           
                                                           xtype: 'timefield',
                                                            name: 'time_out',
                                                            id: 'time_out',
                                                            allowBlank: false,
                                                            minValue: '00:00:00',
                                                            maxValue: '23:00:00',
                                                            //value: '08:00:00',
                                                            increment: 30,
                                                            format: 'H:i:s',
                                                            anchor: '80%',
                                                            vtype: 'timerange',
                                                            startTimeField: 'time_in'
                                                        },
		                	{ xtype:'label', text: "" }
		                ]
		            }]
		        },
					{ xtype: 'textarea',
                                          id: 'txtreason',
                                          name: 'reason',
                                          anchor:'90%',
                                          fieldLabel: 'Reason',
                                          allowBlank: false,
                                          maxLength: '128',
                                          height: 90
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
					title: "TITO Application",
					width: 900,
					height: 270,
					bodyStyle:'padding:5px;',
					plain: true,
					modal: true,
					layout: 'border',
					items: [ fPanel, LeaveApprovers ],
					buttons: [
							{
							  text: 'Save',
                                                          icon: '/images/icons/disk.png',
                                                          handler: function () {
                                                                        if(ExtCommon.util.validateFormFields(form)){//check if all forms are filled up

                                                                    form.getForm().submit({
                                                                            success: function(f,action){
                                                                            Ext.MessageBox.alert('Status', action.result.data);
                                                                             Ext.Msg.show({
                                                                                                         title: 'Status',
                                                                                                         msg: action.result.data,
                                                                                                         buttons: Ext.Msg.OK,
                                                                                                         icon: Ext.Msg.INFO
                                                                                                     });
                                                                                    ExtCommon.util.refreshGrid(requests.app.titoGrid.getId());
                                                                                    applyWinView.destroy();
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
							},
							{ text: 'Cancel',
                                                        icon: '/images/icons/cancel.png',
                                                            disabled: false,
                                                            handler: function(){
                                                                applyWinView.destroy();
                                                            }
                                                        }
						]
			});

                        form.form.load({
							url:"<?php echo site_url("apps/checkEmployeeFlow"); ?>",
                                                        params: {type: 5},
							waitMsg:'Loading...',
							success: function(f,a){
                                                            Ext.getCmp('LeaveApprovers').getStore().loadData(a.result.approvers);

                                                            applyWinView.show();
							},
                                                        failure: function(f,a){
 								Ext.Msg.show({
 									title: 'Error Alert',
 									msg: a.result.msg,
 									icon: Ext.Msg.ERROR,
 									buttons: Ext.Msg.OK
 								});
                                                        }

						});
                        //applyWinView.show();
                },
                setNoOfHours: function(){

			var objt1 = Ext.getCmp('txtOTTimeFrom').getValue();
			var objt2 = Ext.getCmp('txtOTTimeTo').getValue();
			var objhrs = Ext.getCmp('txtNoOfHours');

                        

			if(objt1 != "" && objt2 != "")
			{
                            
                            
				try
				{
					var objd1 = Ext.getCmp('date_from').getRawValue();
					var objd2 = Ext.getCmp('date_to').getRawValue();

					var date1 = new Date();
					var date2 = new Date();
					var diff = new Date();

					date1temp = new Date(requests.app.getDateString(objd1+" "+objt1));
					date1.setTime(date1temp.getTime());

					date2temp = new Date(requests.app.getDateString(objd2+" "+objt2));
					date2.setTime(date2temp.getTime());

                                        if(date2.getTime() <= date1.getTime()){
                                            Ext.getCmp('txtOTTimeTo').markInvalid("Time to should be greater than time from");
                                            objhrs.setValue("");
                                            return false;
                                        }

               

					if(date1.toLocaleString() == "Invalid Date" && date2.toLocaleString() == "Invalid Date")
					{
						objhrs.setValue("");
						return false;
					}

					// sets difference date to difference of first date and second date
					diff.setTime(Math.abs(date1.getTime() - date2.getTime()));
					var timediff = diff.getTime();

                

					weeks = Math.floor(timediff / (1000 * 60 * 60 * 24 * 7));
					timediff -= weeks * (1000 * 60 * 60 * 24 * 7);

					days = Math.floor(timediff / (1000 * 60 * 60 * 24));
					timediff -= days * (1000 * 60 * 60 * 24);

					hours = Math.floor(timediff / (1000 * 60 * 60));
					timediff -= hours * (1000 * 60 * 60);

					mins = Math.floor(timediff / (1000 * 60));
					timediff -= mins * (1000 * 60);

					secs = Math.floor(timediff / 1000);
					timediff -= secs * 1000;

					totalhrs = (weeks * 168) + (days * 24) + hours + (mins/60) + (secs/3600);

					dispvalue = (date1.getTime() > date2.getTime() ? "-"+ requests.app.round(totalhrs) : requests.app.round(totalhrs));

					if(isNaN(dispvalue))
						objhrs.setValue("");
					else
						objhrs.setValue((String(dispvalue).indexOf(".") == -1 ? dispvalue+".0" : dispvalue));
				}catch(e){ alert(e.message); }
			}
			else
			{
				objhrs.setValue("");
			}

		},
                getDateString: function(date){

			arDate = date.split(" ");
			_date = arDate[0].split("-");
			return requests.app.getMonthName(_date[1])+" "+_date[2]+", "+_date[0]+" "+arDate[1];

		},
                getMonthName: function(iMonth){
			arMonth = new Array("January","February","March","April","May","June","July","August","September","October","November","December");
			return arMonth[parseInt(iMonth, 10)-1];
		},
                view_ot: function(){
                if(ExtCommon.util.validateSelectionGrid(requests.app.overtimeGrid.getId())){//check if user has selected an item in the grid
			var sm = requests.app.overtimeGrid.getSelectionModel();
			var id = sm.getSelected().data.audit_id;
                        var pk = sm.getSelected().data.id;
                        var app_type = sm.getSelected().data.app_type;

                        var reader = new Ext.data.JsonReader({
                                                idProperty: 'id',
						fields: [
									{ name: "member_name" },

									{ name: "description"},
                                                                        {name: 'id'}

								]
						});

			/*
			*	SET STORE FOR THE GROUPING GRID
			*/

                                        Objstore = new Ext.data.GroupingStore({
                                            reader		: 	reader,
                                            data		: 	new Array(),
                                            sortInfo	:	{field: 'id', direction: "ASC"},
                                            groupField	:	'description'
                                        });

                                        var gridApprovers = new Ext.grid.GridPanel({
						id		: 	'ApplicationApprovers',
						loadMask	: 	true,
						border		: 	false,
                                                store		: 	Objstore,
						columns	:
								[
                                                                { header: "id", width: 150, sortable: false, locked:true, dataIndex: "id", hidden: true },
    							  { header: "Group Name", width: 150, sortable: false, locked:true, dataIndex: "description" },
								  { header: "Approver's Name", width: 200, sortable: false, locked:true, dataIndex: "member_name" }
								],
                                        view: new Ext.grid.GroupingView({
                                         forceFit:true,
                                         enableNoGroups	: true,
                                         groupText:'{text}'
                                        })
                                        });

                                var ApplicationApprovers = new Ext.Panel({
				title       : 'Approvers',
				iconCls		: 'icon_appgroup',
                                region      : 'east',
                                split       : true,
                                width       : 280,
                                collapsible : true,
                                layout		: "fit",
                                margins     : '3 0 3 3',
                                cmargins    : '3 3 3 3',
                                items: [ gridApprovers ]
                                    });

                                    var fPanelHtml = new Ext.Panel({
                                        id		: "_panelHtml",
                                        border	: 	false,
                                        width	: 580,
                                        margins : 	'1 1 1 0',
                                        html	: ""
                                    });

                                var ApplicationDetailsPanel = new Ext.FormPanel({
				id			: 	"_applicationDetailsPanel",
		 		border		: 	false,
                                frame		:	true,
                                bodyStyle	:	'padding:0 0 0',
                                width		: 	595,
                                height		: 	550,
                                        autoScroll	:	true,
                                items		:
                                [
					fPanelHtml
                                ]
                                });

                                var fPanel = new Ext.Panel({
				border	: 	false,
			 	region  : 	'center',
			 	width	: 	520,
                                margins : 	'1 1 1 0',
                                items	: 	[ApplicationDetailsPanel]
                                });

                                ApplicationDetailsPanel.form.load({
				url		:	"<?php echo site_url('apps/viewApplication') ?>",
				method	: 	'POST',
				params	: 	{ id: id, app_type: app_type, pk: pk },
				waitMsg	:	'Loading...',
				success	: 	function(form, action){

				/*
				 * fill-in the approver list
				 */

					Ext.getCmp('ApplicationApprovers').getStore().loadData(action.result.approvers);


				/*
				 *  DETAILS FOR APPROVAL HERE PER APPLICATION TYPE
				 */

						var tplApplicationApprovers = new Ext.XTemplate(
								'<br />',
								'<p>',
									'<table width="520"  style="background: #fff;padding: 4px;border:solid 1px #ff6666;font-size: 10pt">',
							    		'<tr style="background: #ff6666;">',
							    			'<td colspan="4" style="color:#fff;font-weight:bold;padding: 4px;">Approvers Details</td>',
							    		'</tr>',

							    		'<tr style="background: #ffbec2;">',
							    			'<td style="padding: 4px;font-weight:bold" >Approvers Name</td>',
							    			'<td style="padding: 4px;font-weight:bold" >Action Date</td>',
							    			'<td style="padding: 4px;font-weight:bold" >Status</td>',
							    			'<td style="padding: 4px;font-weight:bold" >Remarks</td>',
							    		'</tr>',
							    		'<tpl for="approver_details">',
								    		'<tr >',
								    			'<td style="padding: 4px;font-weight:bold; background: #ffbec2;" >{approver}</td>',
								    			'<td style="padding: 4px;font-weight:bold; background: #ffd9d9" >{action_timestamp}</td>',
								    			'<td style="padding: 4px;font-weight:bold; background: #ffd9d9" >{status}</td>',
								    			'<td style="padding: 4px;font-weight:bold; background: #ffd9d9" >{remarks}</td>',
								    		'</tr>',
								    	'</tpl>',
							    	'</table>',
								'</p>',
								'<br />',
								'<br />'
							);

					var approver_details = action.result.approver_details;
				    if(approver_details != null && typeof(approver_details) != "undefined")
						Ext.getCmp("_panelHtml").html = tplApplicationApprovers.applyTemplate(action.result);

				/*
				 *  DETAILS FOR APPROVAL HERE PER APPLICATION TYPE
				 */



							 var data = action.result.data;
							 var tplApplicationDetails = new Ext.XTemplate(
									'<br />',
									'<p>',
                                                                        '<tpl for="data">',
										'<table width="520" style="background: #fff;padding: 4px;border: solid 1px #5aa865;font-size: 10pt">',
										'<tr style="background: #5aa865;">',
											'<td colspan="2" style="color:#fff;font-weight:bold;padding: 4px;">Application Details</td>',
										'</tr>',
										'<tr style="background: #d8f1dc;">',
											'<td style="padding: 4px;text-align:right;" >Employee Name:</td>',
											'<td style="padding: 4px;" >{employee_name}</td>',
										'</tr>',
										'<tr style="background: #d8f1dc;" >',
											'<td style="padding: 4px;text-align:right;" width="180px">Date Filed:</td>',
											'<td style="padding: 4px;" >{date_requested}</td>',
										'</tr>',
										'<tr style="background: #d8f1dc;">',
											'<td style="padding: 4px;text-align:right;" >From:</td>',
											'<td style="padding: 4px;" >{date_from}</td>',
										'</tr>',
										'<tr style="background: #d8f1dc;">',
											'<td style="padding: 4px;text-align:right;" >To:</td>',
											'<td style="padding: 4px;" >{date_to}</td>',
										'</tr>',
										'<tr style="background: #d8f1dc;">',
											'<td style="padding: 4px;text-align:right;" >No of Hours:</td>',
											'<td style="padding: 4px;" >{no_hours}</td>',
										'</tr>',
										'<tr style="background: #d8f1dc;">',
											'<td style="padding: 4px;text-align:right;" >Reason:</td>',
											'<td style="padding: 4px;" >{reason}</td>',
										'</tr>',
										'</table>',
                                                                                '</tpl>',
									'</p>',
									'<br />'
							    );

							Ext.getCmp("_panelHtml").html += tplApplicationDetails.applyTemplate(action.result);

					openWin.show();
				},
				failure: function(form, action){
					Ext.Msg.show({title: 'Error Alert',	msg:action.result.data, icon: Ext.Msg.ERROR,buttons: Ext.Msg.OK});
					openWin.destroy();
				}
			});

                                var openWin = new Ext.Window({
					title		: "Application Approval",
					width		: 900,
					height		: 520,
					bodyStyle	:'padding:5px;',
					plain		: true,
					modal		: true,
					layout		: 'border',
					items		: [ fPanel, ApplicationApprovers ],
					autoScroll: true,
                                        buttonAlign: 'center',
					buttons: [
							{
								text: 'Close',
                                                                icon: '/images/icons/cancel.png',
								disabled: false,
								handler: function(){
                                                                openWin.destroy();
                                                                }
                                                }
						],
						listeners: {
							show: function(obj) {
									obj.doLayout();
									//fPanel.doLayout();
							}

						}
			});

                }else return;

                },
                
        leaveTypeCombo: function(){

		return {
			xtype:'combo',
			id:'leave_type_id',
			hiddenName: 'leave_type',
			hiddenId: 'leave_type',
			name: 'leave_type_id',
			valueField: 'id',
			displayField: 'name',
			//width: 100,
			anchor: '55%',
			triggerAction: 'all',
			minChars: 2,
			forceSelection: true,
			enableKeyEvents: true,
			pageSize: 10,
			resizable: true,
			readOnly: false,
			minListWidth: 300,
			allowBlank: false,
			store: new Ext.data.JsonStore({
			id: 'idsocombo',
			root: 'data',
			totalProperty: 'totalCount',
			fields:[{name: 'id'}, {name: 'name'}],
			url: "<?php echo site_url("apps/getLeaveTypeCombo"); ?>",
			baseParams: {start: 0, limit: 10}

			}),
			listeners: {
			beforequery: function(){
				Ext.get('call_log_id').dom.value = '';
				Ext.getCmp('call_log').setRawValue("");
				this.store.load();
			},
			select: function (combo, record, index){
			this.setRawValue(record.get('name'));
			Ext.get(this.hiddenName).dom.value  = record.get('id');
			//Ext.getCmp('call_log').getStore().setBaseParam("type", record.get('id'));
			if(record.get('id') == '2' || record.get('id') == '3' || record.get('id') == '5'){
				Ext.getCmp('call_log').enable();
			}else{
				Ext.getCmp('call_log').disable();
				Ext.getCmp('date_from').setReadOnly(false);
			Ext.getCmp('date_to').setReadOnly(false);
			Ext.getCmp('leave_portion').setReadOnly(false);
			Ext.getCmp('no_of_days').setReadOnly(false);
			Ext.getCmp('txtreason').setReadOnly(false);
			
			Ext.getCmp('call_log').disable();
				Ext.getCmp('date_from').reset();
			Ext.getCmp('date_to').reset();
			Ext.getCmp('leave_portion').reset();
			Ext.getCmp('no_of_days').reset();
			Ext.getCmp('txtreason').reset();
			}
			},
			blur: function(){
			var val = this.getRawValue();
			this.setRawValue.defer(1, this, [val]);
			this.validate();
			},
			render: function() {
			this.el.set({qtip: 'Type at least ' + this.minChars + ' characters to search for a leave type'});

			},
			keypress: {buffer: 100, fn: function() {
			Ext.get(this.hiddenName).dom.value  = '';
			if(!this.getRawValue()){
			this.doQuery('', true);
			}
			}}
			},
			fieldLabel: 'Leave Type'

			}
	},
	callLogCombo: function(){

		return {
			xtype:'combo',
			id:'call_log',
			hiddenName: 'call_log_id',
			hiddenId: 'call_log_id',
			name: 'call_log',
			valueField: 'id',
			displayField: 'name',
			//width: 100,
			anchor: '55%',
			triggerAction: 'all',
			minChars: 2,
			forceSelection: true,
			enableKeyEvents: true,
			pageSize: 10,
			resizable: true,
			readOnly: false,
			minListWidth: 300,
			allowBlank: true,
			disabled: true,
			store: new Ext.data.JsonStore({
			id: 'idsocombo',
			root: 'data',
			totalProperty: 'totalCount',
			fields:[{name: 'id'}, {name: 'name'}, {name: 'date_from'}, {name: 'date_to'}, {name: 'portion'}, {name: 'no_days'}, {name: 'reason'}],
			url: "<?php echo site_url("user/callLogCombo"); ?>",
			baseParams: {start: 0, limit: 10}

			}),
			listeners: {
			beforequery: function()
			{
					if (Ext.get("leave_type").dom.value == "")
							return false;

				    this.store.baseParams = {type: Ext.get("leave_type").dom.value};

			            var o = {start: 0, limit:10};
			            this.store.baseParams = this.store.baseParams || {};
			            this.store.baseParams[this.paramName] = '';
			            this.store.load({params:o, timeout: 300000});
			},
			select: function (combo, record, index){
			this.setRawValue(record.get('name'));
			Ext.get(this.hiddenName).dom.value = record.get('id');
			Ext.getCmp('date_from').setValue(record.get('date_from'));
			Ext.getCmp('date_to').setValue(record.get('date_to'));
			Ext.getCmp('leave_portion').setValue(record.get('portion'));
			Ext.getCmp('no_of_days').setValue(record.get('no_days'));
			Ext.getCmp('txtreason').setValue(record.get('reason'));
			
			Ext.getCmp('date_from').setReadOnly(true);
			Ext.getCmp('date_to').setReadOnly(true);
			Ext.getCmp('leave_portion').setReadOnly(true);
			Ext.getCmp('no_of_days').setReadOnly(true);
			Ext.getCmp('txtreason').setReadOnly(true);

			},
			blur: function(){
			var val = this.getRawValue();
			this.setRawValue.defer(1, this, [val]);
			this.validate();
			},
			render: function() {
			this.el.set({qtip: 'Type at least ' + this.minChars + ' characters to search for a school'});

			},
			keypress: {buffer: 100, fn: function() {
			Ext.get(this.hiddenName).dom.value  = '';
			if(!this.getRawValue()){
			this.doQuery('', true);
			}
			}}
			},
			fieldLabel: 'Call Log'

			}
	}
        ,
apply_cs: function(){


                        var reader = new Ext.data.JsonReader({
                                                idProperty: 'id',
						fields: [
									{ name: "member_name" },

									{ name: "description"},
                                                                        {name: 'id'},
                                                                        {name: 'app_tree_details_id'}

								]
						});

                                        Objstore = new Ext.data.GroupingStore({
                                            reader		: 	reader,
                                            data		: 	new Array(),
                                            remoteSort  : true,
                                            sortInfo	:	{field: 'app_tree_details_id', direction: "ASC"},
                                            groupField	:	'description'
                                        });

                                        var gridApprovers = new Ext.grid.GridPanel({
						id		: 	'LeaveApprovers',
						loadMask	: 	true,
						border		: 	false,
                                                store		: 	Objstore,
						columns	:
								[
                                                                { header: "id", width: 150, sortable: false, locked:true, dataIndex: "id", hidden: true },
    							  { header: "Group Name", width: 150, sortable: false, locked:true, dataIndex: "description" },
								  { header: "Approver's Name", width: 200, sortable: false, locked:true, dataIndex: "member_name" }
								],
                                        view: new Ext.grid.GroupingView({
                                         forceFit:true,
                                         enableNoGroups	: true,
                                         groupText:'{text}'
                                        })
                                        });

                                var LeaveApprovers = new Ext.Panel({
                                    title       : 'Approvers',
                                    iconCls		: 'icon_appgroup',
                                    region      : 'east',
                                    split       : true,
                                    width       : 380,
                                    collapsible : true,
                                    layout		: "fit",
                                    margins     : '3 0 3 3',
                                    cmargins    : '3 3 3 3',
                                    items: [ gridApprovers ]
                                });

                                var form = new Ext.form.FormPanel({
                                        labelWidth: 120,
                                        url: "<?php echo site_url("overtime/applyCS")?>",
                                        method: 'POST',
                                        frame: true,
                                        items: [
                                        {
                                           xtype: 'fieldset',
                                           title : 'Client Schedule Information',
                                           height : 'auto',
                                           items  : [requests.app.purposeCombo(),
                                               {
                                                            xtype: 'datefield',
                                                            name: 'date_scheduled',
                                                            id: 'date_scheduled',
                                                            format: 'Y-m-d',
                                                            fieldLabel: 'Date Scheduled*',
                                                            allowBlank: false,
                                                            anchor: '90%',
                                                            listeners:{
                                                            }
                                                       },
                                                       {
                                                            xtype: 'timefield',
                                                            fieldLabel: 'Time-In*',
                                                            name: 'time_in',
                                                            id: 'time_in',
                                                            allowBlank: false,
                                                            minValue: '00:00:00',
                                                            maxValue: '23:00:00',
                                                            //value: '08:00:00',
                                                            increment: 30,
                                                            format: 'H:i:s',
                                                            anchor: '90%',
                                                            vtype: 'timerange',
                                                            endTimeField: 'time_out'
                                                        },
                                                        {
                                                            xtype: 'timefield',
                                                            fieldLabel: 'Time-Out*',
                                                            name: 'time_out',
                                                            id: 'time_out',
                                                            allowBlank: false,
                                                            minValue: '00:00:00',
                                                            maxValue: '23:00:00',
                                                            //value: '08:00:00',
                                                            increment: 30,
                                                            format: 'H:i:s',
                                                            anchor: '90%',
                                                            vtype: 'timerange',
                                                            startTimeField: 'time_in'
                                                        },
                                                        new Ext.form.ComboBox(
			      				   {
			       		            fieldLabel: 'Type*',
			       	   	         store: new Ext.data.SimpleStore(
			       		            {
			       		               fields: ['field', 'value'],
			       		               data : [['Client', 'Client'],['Supplier', 'Supplier']]
			          		         }),
                                                    valueField:'field',
			       		            displayField:'value',
			          		    name: 'type',
			       		            id: 'type',
			       		            mode: 'local',
                                                    anchor: '90%',
			       		            triggerAction: 'all',
			          		    selectOnFocus: true,
                                                    allowBlank: false,
			       		            forceSelection:true,
			       		            tabIndex: 0,
                                                    listeners: {
			      					select: function (combo, record, index){
			      						//Ext.get('client_hdn').dom.value = '';
			      						Ext.getCmp('client_id').setValue("");
                                                                        Ext.getCmp('client_id').setRawValue("");
			      					}
						        	}
			          		      }),
				    requests.app.clientCombo(),
                                    {
                                                            xtype: 'textarea',
                                                            fieldLabel: 'Address',
                                                            name: 'address',
                                                            id: 'address',
                                                            allowBlank: false,
                                                            readOnly: true,
                                                            anchor: '90%'
                                     },
                                     requests.app.contactCombo(),
                                     {
                                                            xtype: 'textarea',
                                                            fieldLabel: 'Reason*',
                                                            name: 'reason',
                                                            id: 'reason',
                                                            allowBlank: false,
                                                            anchor: '90%'
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
					title: "Client Schedule Application",
					width: 900,
					height: 450,
					bodyStyle:'padding:5px;',
					plain: true,
					modal: true,
					layout: 'border',
					items: [ fPanel, LeaveApprovers ],
					buttons: [
							{
							  text: 'Save',
                                                          icon: '/images/icons/disk.png',
                                                          handler: function () {
                                                                        if(ExtCommon.util.validateFormFields(form)){//check if all forms are filled up

                                                                    form.getForm().submit({
                                                                            success: function(f,action){
                                                                            Ext.MessageBox.alert('Status', action.result.data);
                                                                             Ext.Msg.show({
                                                                                                         title: 'Status',
                                                                                                         msg: action.result.data,
                                                                                                         buttons: Ext.Msg.OK,
                                                                                                         icon: Ext.Msg.INFO
                                                                                                     });
                                                                                    ExtCommon.util.refreshGrid(requests.app.clientScheduleGrid.getId());
                                                                                    applyWinView.destroy();
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
							},
							{ text: 'Cancel',
                                                        icon: '/images/icons/cancel.png',
                                                            disabled: false,
                                                            handler: function(){
                                                                applyWinView.destroy();
                                                            }
                                                        }
						]
			});

                        form.form.load({
							url:"<?php echo site_url("apps/checkEmployeeFlow"); ?>",
                                                        params: {type: 4},
							waitMsg:'Loading...',
							success: function(f,a){
                                                            Ext.getCmp('LeaveApprovers').getStore().loadData(a.result.approvers);

                                                            applyWinView.show();
							},
                                                        failure: function(f,a){
 								Ext.Msg.show({
 									title: 'Error Alert',
 									msg: a.result.msg,
 									icon: Ext.Msg.ERROR,
 									buttons: Ext.Msg.OK
 								});
                                                        }

						});
                        //applyWinView.show();
},
apply_training: function(){


                        var reader = new Ext.data.JsonReader({
                                                idProperty: 'id',
						fields: [
									{ name: "member_name" },

									{ name: "description"},
                                                                        {name: 'id'},
                                                                        {name: 'app_tree_details_id'}

								]
						});

                                        Objstore = new Ext.data.GroupingStore({
                                            reader		: 	reader,
                                            data		: 	new Array(),
                                            remoteSort  : true,
                                            sortInfo	:	{field: 'app_tree_details_id', direction: "ASC"},
                                            groupField	:	'description'
                                        });

                                        var gridApprovers = new Ext.grid.GridPanel({
						id		: 	'LeaveApprovers',
						loadMask	: 	true,
						border		: 	false,
                                                store		: 	Objstore,
						columns	:
								[
                                                                { header: "id", width: 150, sortable: false, locked:true, dataIndex: "id", hidden: true },
    							  { header: "Group Name", width: 150, sortable: false, locked:true, dataIndex: "description" },
								  { header: "Approver's Name", width: 200, sortable: false, locked:true, dataIndex: "member_name" }
								],
                                        view: new Ext.grid.GroupingView({
                                         forceFit:true,
                                         enableNoGroups	: true,
                                         groupText:'{text}'
                                        })
                                        });

                                var LeaveApprovers = new Ext.Panel({
                                    title       : 'Approvers',
                                    iconCls		: 'icon_appgroup',
                                    region      : 'east',
                                    split       : true,
                                    width       : 380,
                                    collapsible : true,
                                    layout		: "fit",
                                    margins     : '3 0 3 3',
                                    cmargins    : '3 3 3 3',
                                    items: [ gridApprovers ]
                                });

                                var form = new Ext.form.FormPanel({
                                        labelWidth: 120,
                                        url: "<?php echo site_url("overtime/applyTraining")?>",
                                        method: 'POST',
                                        frame: true,
                                        items: [
                                        {
                                           xtype: 'fieldset',
                                           title : 'Training/Seminar Information',
                                           height : 'auto',
                                           items  : [requests.app.trainingTypeCombo(),
                                               {                       xtype: 'datefield',
		 	 			fieldLabel: 'Date Start*',
		 	 			name: 'date_start',
		 	 			id: 'date_start',
		 	 			allowBlank: false,
		 	 			format: 'Y-m-d',
		 	 			//value: new Date(),
		 	 			anchor: '90%',
                                                vtype: 'daterange',
                                                endDateField: 'date_end'

		 	 			      },
                        {                       xtype: 'datefield',
		 	 			fieldLabel: 'Date End*',
		 	 			name: 'date_end',
		 	 			id: 'date_end',
		 	 			//allowBlank: false,
		 	 			format: 'Y-m-d',
		 	 			//value: new Date(),
		 	 			anchor: '90%',
                                                vtype: 'daterange',
                                                startDateField: 'date_start'

		 	 			      },
                                                       {
                                                            xtype: 'timefield',
                                                            fieldLabel: 'Time-In*',
                                                            name: 'time_in',
                                                            id: 'time_in',
                                                            allowBlank: false,
                                                            minValue: '00:00:00',
                                                            maxValue: '23:00:00',
                                                            //value: '08:00:00',
                                                            increment: 30,
                                                            format: 'H:i:s',
                                                            anchor: '90%',
                                                            vtype: 'timerange',
                                                            endTimeField: 'time_out'
                                                        },
                                                        {
                                                            xtype: 'timefield',
                                                            fieldLabel: 'Time-Out*',
                                                            name: 'time_out',
                                                            id: 'time_out',
                                                            allowBlank: false,
                                                            minValue: '00:00:00',
                                                            maxValue: '23:00:00',
                                                            //value: '08:00:00',
                                                            increment: 30,
                                                            format: 'H:i:s',
                                                            anchor: '90%',
                                                            vtype: 'timerange',
                                                            startTimeField: 'time_in'
                                                        },
                                                        new Ext.form.ComboBox(
			      				   {
			       		            fieldLabel: 'Type*',
			       	   	         store: new Ext.data.SimpleStore(
			       		            {
			       		               fields: ['field', 'value'],
			       		               data : [['Client', 'Client'],['Supplier', 'Supplier']]
			          		         }),
                                                    valueField:'field',
			       		            displayField:'value',
			          		    name: 'type',
			       		            id: 'type',
			       		            mode: 'local',
                                                    anchor: '90%',
			       		            triggerAction: 'all',
			          		    selectOnFocus: true,
                                                    allowBlank: false,
			       		            forceSelection:true,
			       		            tabIndex: 0,
                                                    listeners: {
			      					select: function (combo, record, index){
			      						//Ext.get('client_hdn').dom.value = '';
			      						Ext.getCmp('client_id').setValue("");
                                                                        Ext.getCmp('client_id').setRawValue("");
			      					}
						        	}
			          		      }),
				    requests.app.clientCombo(),
                                    {
                                                            xtype: 'textarea',
                                                            fieldLabel: 'Address',
                                                            name: 'address',
                                                            id: 'address',
                                                            allowBlank: false,
                                                            anchor: '90%'
                                     },
                                     {
                                                            xtype: 'textfield',
                                                            fieldLabel: 'Title*',
                                                            name: 'title',
                                                            id: 'title',
                                                            allowBlank: false,
                                                            anchor: '90%'
                                     },
                                     {
                                                            xtype: 'textarea',
                                                            fieldLabel: 'Details*',
                                                            name: 'details',
                                                            id: 'details',
                                                            allowBlank: false,
                                                            anchor: '90%'
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
					title: "Training/Seminar Application",
					width: 900,
					height: 480,
					bodyStyle:'padding:5px;',
					plain: true,
					modal: true,
					layout: 'border',
					items: [ fPanel, LeaveApprovers ],
					buttons: [
							{
							  text: 'Save',
                                                          icon: '/images/icons/disk.png',
                                                          handler: function () {
                                                                        if(ExtCommon.util.validateFormFields(form)){//check if all forms are filled up

                                                                    form.getForm().submit({
                                                                            success: function(f,action){
                                                                            Ext.MessageBox.alert('Status', action.result.data);
                                                                             Ext.Msg.show({
                                                                                                         title: 'Status',
                                                                                                         msg: action.result.data,
                                                                                                         buttons: Ext.Msg.OK,
                                                                                                         icon: Ext.Msg.INFO
                                                                                                     });
                                                                                    ExtCommon.util.refreshGrid(requests.app.trainingGrid.getId());
                                                                                    applyWinView.destroy();
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
							},
							{ text: 'Cancel',
                                                        icon: '/images/icons/cancel.png',
                                                            disabled: false,
                                                            handler: function(){
                                                                applyWinView.destroy();
                                                            }
                                                        }
						]
			});

                        form.form.load({
							url:"<?php echo site_url("apps/checkEmployeeFlow"); ?>",
                                                        params: {type: 6},
							waitMsg:'Loading...',
							success: function(f,a){
                                                            Ext.getCmp('LeaveApprovers').getStore().loadData(a.result.approvers);

                                                            applyWinView.show();
							},
                                                        failure: function(f,a){
 								Ext.Msg.show({
 									title: 'Error Alert',
 									msg: a.result.msg,
 									icon: Ext.Msg.ERROR,
 									buttons: Ext.Msg.OK
 								});
                                                        }

						});
                        //applyWinView.show();
},
purposeCombo: function(){

		return {
			xtype:'combo',
			id:'purpose_id',
			//hiddenName: 'COURIDNO',
			name: 'purpose_id',
			valueField: 'id',
			displayField: 'name',
			//width: 100,
			anchor: '90%',
			triggerAction: 'all',
			minChars: 2,
			forceSelection: true,
			enableKeyEvents: true,
			pageSize: 10,
			resizable: true,
			readOnly: false,
			minListWidth: 300,
			allowBlank: false,
			store: new Ext.data.JsonStore({
			id: 'idsocombo',
			root: 'data',
			totalProperty: 'totalCount',
			fields:[{name: 'id'}, {name: 'name'}],
			url: "<?php echo site_url("apps/getPurposeCombo"); ?>",
			baseParams: {start: 0, limit: 10}

			}),
			listeners: {
			select: function (combo, record, index){
			this.setRawValue(record.get('name'));
			Ext.getCmp(this.id).setValue(record.get('name'));

			},
			blur: function(){
			var val = this.getRawValue();
			this.setRawValue.defer(1, this, [val]);
			this.validate();
			},
			render: function() {
			this.el.set({qtip: 'Type at least ' + this.minChars + ' characters to search for a school'});

			},
			keypress: {buffer: 100, fn: function() {
			//Ext.get(this.hiddenName).dom.value  = '';
			if(!this.getRawValue()){
			this.doQuery('', true);
			}
			}}
			},
			fieldLabel: 'Purpose'

			}
	},
clientCombo: function(){

		return {
			xtype:'combo',
			id:'client_id',
			//hiddenName: 'COURIDNO',
			name: 'client_id',
			valueField: 'id',
			displayField: 'name',
			//width: 100,
			anchor: '90%',
			triggerAction: 'all',
			minChars: 2,
			forceSelection: true,
			enableKeyEvents: true,
			pageSize: 10,
			resizable: true,
			readOnly: false,
			minListWidth: 300,
			allowBlank: false,
			store: new Ext.data.JsonStore({
			id: 'idsocombo',
			root: 'data',
			totalProperty: 'totalCount',
			fields:[{name: 'id'}, {name: 'name'}, {name: 'address'}],
			url: "<?php echo site_url("apps/getClientCombo"); ?>",
			baseParams: {start: 0, limit: 10}

			}),
			listeners: {
                        beforequery: function()
			{
			if (Ext.getCmp('type').getValue() == "")
			return false;

			this.store.baseParams = {id: Ext.getCmp('type').getValue()};

	           	var o = {start: 0, limit: 10};
                        this.store.load({params:o, timeout: 300000});
	            	this.store.baseParams = this.store.baseParams || {};
	           	this.store.baseParams[this.paramName] = '';
                        

			},
			select: function (combo, record, index){
			this.setRawValue(record.get('name'));
			Ext.getCmp(this.id).setValue(record.get('name'));
                        Ext.getCmp('address').setValue(record.get('address'));
                        if(Ext.get('contact_person') !== null){
                        Ext.getCmp('contact_person_id').setValue("");
                        Ext.getCmp('contact_person_id').setRawValue("");
                       }

			},
			blur: function(){
			var val = this.getRawValue();
			this.setRawValue.defer(1, this, [val]);
			this.validate();
			},
			render: function() {
			this.el.set({qtip: 'Type at least ' + this.minChars + ' characters to search for a school'});

			},
			keypress: {buffer: 100, fn: function() {
			//Ext.get(this.hiddenName).dom.value  = '';
			if(!this.getRawValue()){
			this.doQuery('', true);
			}
			}}
			},
			fieldLabel: 'Company Name/Supplier'

			}
	},
       contactCombo: function(){

			return {
			xtype:'combo',
			id:'contact_person_id',
			name: 'contact_person_id',
			hiddenId:'contact_person',
			hiddenName: 'contact_person',
			valueField: 'id',
			displayField: 'name',
			//width: 100,
			anchor: '90%',
			triggerAction: 'all',

			minChars: 2,
			forceSelection: true,
			enableKeyEvents: true,
			pageSize: 10,
			resizable: true,
			readOnly: false,
			minListWidth: 300,
			allowBlank: true,
			store: new Ext.data.JsonStore({
			id: 'idsocombo',
			root: 'data',
			totalProperty: 'totalCount',
			fields:[{name: 'id'}, {name: 'name'}],
			url: "<?php echo site_url("apps/getContactCombo"); ?>",
			baseParams: {start: 0, limit: 10}

			}),
			listeners: {
			beforequery: function()
			{
			if(Ext.get('client_id').getValue() == "")
			return false;

			this.store.baseParams = {name: Ext.getCmp('client_id').getValue(), type: Ext.getCmp('type').getValue()};

			var o = {start: 0, limit:10};
			this.store.baseParams = this.store.baseParams || {};
			this.store.baseParams[this.paramName] = '';
			this.store.load({params:o, timeout: 300000});
			},
			select: function (combo, record, index){
			this.setRawValue(record.get('name'));
			Ext.getCmp(this.id).setValue(record.get('name'));


			},
			blur: function(){
			var val = this.getRawValue();
			this.setRawValue.defer(1, this, [val]);
			this.validate();
			},
			render: function() {
			this.el.set({qtip: 'Type at least ' + this.minChars + ' characters to search for a school'});

			},
			keypress: {buffer: 100, fn: function() {
			//Ext.get(this.hiddenName).dom.value  = '';
			if(!this.getRawValue()){
			this.doQuery('', true);
			}
			}}
			},
			fieldLabel: 'Contact Person*'

			}
			},
view_cs: function(){
                if(ExtCommon.util.validateSelectionGrid(requests.app.clientScheduleGrid.getId())){//check if user has selected an item in the grid
			var sm = requests.app.clientScheduleGrid.getSelectionModel();
			var id = sm.getSelected().data.audit_id;
                        var pk = sm.getSelected().data.id;
                        var app_type = sm.getSelected().data.app_type;

                        var reader = new Ext.data.JsonReader({
                                                idProperty: 'id',
						fields: [
									{ name: "member_name" },

									{ name: "description"},
                                                                        {name: 'id'}

								]
						});

			/*
			*	SET STORE FOR THE GROUPING GRID
			*/

                                        Objstore = new Ext.data.GroupingStore({
                                            reader		: 	reader,
                                            data		: 	new Array(),
                                            sortInfo	:	{field: 'id', direction: "ASC"},
                                            groupField	:	'description'
                                        });

                                        var gridApprovers = new Ext.grid.GridPanel({
						id		: 	'ApplicationApprovers',
						loadMask	: 	true,
						border		: 	false,
                                                store		: 	Objstore,
						columns	:
								[
                                                                { header: "id", width: 150, sortable: false, locked:true, dataIndex: "id", hidden: true },
    							  { header: "Group Name", width: 150, sortable: false, locked:true, dataIndex: "description" },
								  { header: "Approver's Name", width: 200, sortable: false, locked:true, dataIndex: "member_name" }
								],
                                        view: new Ext.grid.GroupingView({
                                         forceFit:true,
                                         enableNoGroups	: true,
                                         groupText:'{text}'
                                        })
                                        });

                                var ApplicationApprovers = new Ext.Panel({
				title       : 'Approvers',
				iconCls		: 'icon_appgroup',
                                region      : 'east',
                                split       : true,
                                width       : 280,
                                collapsible : true,
                                layout		: "fit",
                                margins     : '3 0 3 3',
                                cmargins    : '3 3 3 3',
                                items: [ gridApprovers ]
                                    });

                                    var fPanelHtml = new Ext.Panel({
                                        id		: "_panelHtml",
                                        border	: 	false,
                                        width	: '100%',
                                        margins : 	'1 1 1 0',
                                        html	: ""
                                    });

                                var ApplicationDetailsPanel = new Ext.FormPanel({
				id			: 	"_applicationDetailsPanel",
		 		border		: 	false,
                                frame		:	true,
                                bodyStyle	:	'padding:0 0 0',
                                width		: 	'100%',
                                height		: 	'100%',
                                        
                                items		:
                                [
					fPanelHtml
                                ]
                                });

                                var fPanel = new Ext.Panel({
				border	: 	false,
			 	region  : 	'center',
                                autoScroll	:	true,
			 	width	: 	'100%',
                                margins : 	'1 1 1 0',
                                items	: 	[ApplicationDetailsPanel]
                                });

                                ApplicationDetailsPanel.form.load({
				url		:	"<?php echo site_url('apps/viewApplication') ?>",
				method	: 	'POST',
				params	: 	{ id: id, app_type: app_type, pk: pk },
				waitMsg	:	'Loading...',
				success	: 	function(form, action){

				/*
				 * fill-in the approver list
				 */

					Ext.getCmp('ApplicationApprovers').getStore().loadData(action.result.approvers);


				/*
				 *  DETAILS FOR APPROVAL HERE PER APPLICATION TYPE
				 */

						var tplApplicationApprovers = new Ext.XTemplate(
								'<br />',
								'<p>',
									'<table width="520"  style="background: #fff;padding: 4px;border:solid 1px #ff6666;font-size: 10pt">',
							    		'<tr style="background: #ff6666;">',
							    			'<td colspan="4" style="color:#fff;font-weight:bold;padding: 4px;">Approvers Details</td>',
							    		'</tr>',

							    		'<tr style="background: #ffbec2;">',
							    			'<td style="padding: 4px;font-weight:bold" >Approvers Name</td>',
							    			'<td style="padding: 4px;font-weight:bold" >Action Date</td>',
							    			'<td style="padding: 4px;font-weight:bold" >Status</td>',
							    			'<td style="padding: 4px;font-weight:bold" >Remarks</td>',
							    		'</tr>',
							    		'<tpl for="approver_details">',
								    		'<tr >',
								    			'<td style="padding: 4px;font-weight:bold; background: #ffbec2;" >{approver}</td>',
								    			'<td style="padding: 4px;font-weight:bold; background: #ffd9d9" >{action_timestamp}</td>',
								    			'<td style="padding: 4px;font-weight:bold; background: #ffd9d9" >{status}</td>',
								    			'<td style="padding: 4px;font-weight:bold; background: #ffd9d9" >{remarks}</td>',
								    		'</tr>',
								    	'</tpl>',
							    	'</table>',
								'</p>',
								'<br />',
								'<br />'
							);

					var approver_details = action.result.approver_details;
				    if(approver_details != null && typeof(approver_details) != "undefined")
						Ext.getCmp("_panelHtml").html = tplApplicationApprovers.applyTemplate(action.result);

				/*
				 *  DETAILS FOR APPROVAL HERE PER APPLICATION TYPE
				 */



							 var data = action.result.data;
							 var tplApplicationDetails = new Ext.XTemplate(
									'<br />',
									'<p>',
                                                                        '<tpl for="data">',
										'<table width="520" style="background: #fff;padding: 4px;border: solid 1px #5aa865;font-size: 10pt">',
										'<tr style="background: #5aa865;">',
											'<td colspan="2" style="color:#fff;font-weight:bold;padding: 4px;">Application Details</td>',
										'</tr>',
										'<tr style="background: #d8f1dc;">',
											'<td style="padding: 4px;text-align:right;" >Employee Name:</td>',
											'<td style="padding: 4px;" >{employee_name}</td>',
										'</tr>',
                                                                                '<tr style="background: #d8f1dc;">',
											'<td style="padding: 4px;text-align:right;" >Purpose:</td>',
											'<td style="padding: 4px;" >{purpose}</td>',
										'</tr>',
										'<tr style="background: #d8f1dc;" >',
											'<td style="padding: 4px;text-align:right;" width="180px">Date Filed:</td>',
											'<td style="padding: 4px;" >{date_requested}</td>',
										'</tr>',
										'<tr style="background: #d8f1dc;">',
											'<td style="padding: 4px;text-align:right;" >Date Scheduled:</td>',
											'<td style="padding: 4px;" >{date_scheduled}</td>',
										'</tr>',
										'<tr style="background: #d8f1dc;">',
											'<td style="padding: 4px;text-align:right;" >Time In:</td>',
											'<td style="padding: 4px;" >{time_in}</td>',
										'</tr>',
                                                                                '<tr style="background: #d8f1dc;">',
											'<td style="padding: 4px;text-align:right;" >Time Out:</td>',
											'<td style="padding: 4px;" >{time_out}</td>',
										'</tr>',
                                                                                '<tr style="background: #d8f1dc;">',
											'<td style="padding: 4px;text-align:right;" >Type:</td>',
											'<td style="padding: 4px;" >{type}</td>',
										'</tr>',
                                                                                '<tr style="background: #d8f1dc;">',
											'<td style="padding: 4px;text-align:right;" >Client/Supplier Name:</td>',
											'<td style="padding: 4px;" >{client}</td>',
										'</tr>',
                                                                                '<tr style="background: #d8f1dc;">',
											'<td style="padding: 4px;text-align:right;" >Contact Person:</td>',
											'<td style="padding: 4px;" >{contact}</td>',
										'</tr>',
										/*'<tr style="background: #d8f1dc;">',
											'<td style="padding: 4px;text-align:right;" >Type:</td>',
											'<td style="padding: 4px;" >{ot_type}</td>',
										'</tr>',
										'<tr style="background: #d8f1dc;">',
											'<td style="padding: 4px;text-align:right;" valign="top" >Barcode Logs:</td>',
											'<td style="padding: 4px;" valign="top" >{barcodes}</td>',
										'</tr>',*/
										'<tr style="background: #d8f1dc;">',
											'<td style="padding: 4px;text-align:right;" >Agenda:</td>',
											'<td style="padding: 4px;" >{agenda}</td>',
										'</tr>',
										'</table>',
                                                                                '</tpl>',
									'</p>',
									'<br />'
							    );

							Ext.getCmp("_panelHtml").html += tplApplicationDetails.applyTemplate(action.result);

					openWin.show();
				},
				failure: function(form, action){
					Ext.Msg.show({title: 'Error Alert',	msg:action.result.data, icon: Ext.Msg.ERROR,buttons: Ext.Msg.OK});
					openWin.destroy();
				}
			});

                                var openWin = new Ext.Window({
					title		: "View Application",
					width		: 900,
					height		: 422,
					bodyStyle	:'padding:5px;',
					plain		: true,
					modal		: true,
					layout		: 'border',
					items		: [ fPanel, ApplicationApprovers ],
					autoScroll: true,
                                        buttonAlign: 'center',
					buttons: [
							{
								text: 'Close',
                                                                icon: '/images/icons/cancel.png',
								disabled: false,
								handler: function(){
                                                                openWin.destroy();
                                                                }
                                                }
						],
						listeners: {
							show: function(obj) {
									obj.doLayout();
									//fPanel.doLayout();
							}

						}
			});

                }else return;

                },
                
                view_training: function(){
                if(ExtCommon.util.validateSelectionGrid(requests.app.trainingGrid.getId())){//check if user has selected an item in the grid
			var sm = requests.app.trainingGrid.getSelectionModel();
			var id = sm.getSelected().data.audit_id;
                        var pk = sm.getSelected().data.id;
                        var app_type = sm.getSelected().data.app_type;

                        var reader = new Ext.data.JsonReader({
                                                idProperty: 'id',
						fields: [
									{ name: "member_name" },

									{ name: "description"},
                                                                        {name: 'id'}

								]
						});

			/*
			*	SET STORE FOR THE GROUPING GRID
			*/

                                        Objstore = new Ext.data.GroupingStore({
                                            reader		: 	reader,
                                            data		: 	new Array(),
                                            sortInfo	:	{field: 'id', direction: "ASC"},
                                            groupField	:	'description'
                                        });

                                        var gridApprovers = new Ext.grid.GridPanel({
						id		: 	'ApplicationApprovers',
						loadMask	: 	true,
						border		: 	false,
                                                store		: 	Objstore,
						columns	:
								[
                                                                { header: "id", width: 150, sortable: false, locked:true, dataIndex: "id", hidden: true },
    							  { header: "Group Name", width: 150, sortable: false, locked:true, dataIndex: "description" },
								  { header: "Approver's Name", width: 200, sortable: false, locked:true, dataIndex: "member_name" }
								],
                                        view: new Ext.grid.GroupingView({
                                         forceFit:true,
                                         enableNoGroups	: true,
                                         groupText:'{text}'
                                        })
                                        });

                                var ApplicationApprovers = new Ext.Panel({
				title       : 'Approvers',
				iconCls		: 'icon_appgroup',
                                region      : 'east',
                                split       : true,
                                width       : 280,
                                collapsible : true,
                                layout		: "fit",
                                margins     : '3 0 3 3',
                                cmargins    : '3 3 3 3',
                                items: [ gridApprovers ]
                                    });

                                    var fPanelHtml = new Ext.Panel({
                                        id		: "_panelHtml",
                                        border	: 	false,
                                        width	: '100%',
                                        margins : 	'1 1 1 0',
                                        html	: ""
                                    });

                                var ApplicationDetailsPanel = new Ext.FormPanel({
				id			: 	"_applicationDetailsPanel",
		 		border		: 	false,
                                frame		:	true,
                                bodyStyle	:	'padding:0 0 0',
                                width		: 	'100%',
                                height		: 	'100%',
                                        
                                items		:
                                [
					fPanelHtml
                                ]
                                });

                                var fPanel = new Ext.Panel({
				border	: 	false,
			 	region  : 	'center',
                                autoScroll	:	true,
			 	width	: 	'100%',
                                margins : 	'1 1 1 0',
                                items	: 	[ApplicationDetailsPanel]
                                });

                                ApplicationDetailsPanel.form.load({
				url		:	"<?php echo site_url('apps/viewApplication') ?>",
				method	: 	'POST',
				params	: 	{ id: id, app_type: app_type, pk: pk },
				waitMsg	:	'Loading...',
				success	: 	function(form, action){

				/*
				 * fill-in the approver list
				 */

					Ext.getCmp('ApplicationApprovers').getStore().loadData(action.result.approvers);


				/*
				 *  DETAILS FOR APPROVAL HERE PER APPLICATION TYPE
				 */

						var tplApplicationApprovers = new Ext.XTemplate(
								'<br />',
								'<p>',
									'<table width="520"  style="background: #fff;padding: 4px;border:solid 1px #ff6666;font-size: 10pt">',
							    		'<tr style="background: #ff6666;">',
							    			'<td colspan="4" style="color:#fff;font-weight:bold;padding: 4px;">Approvers Details</td>',
							    		'</tr>',

							    		'<tr style="background: #ffbec2;">',
							    			'<td style="padding: 4px;font-weight:bold" >Approvers Name</td>',
							    			'<td style="padding: 4px;font-weight:bold" >Action Date</td>',
							    			'<td style="padding: 4px;font-weight:bold" >Status</td>',
							    			'<td style="padding: 4px;font-weight:bold" >Remarks</td>',
							    		'</tr>',
							    		'<tpl for="approver_details">',
								    		'<tr >',
								    			'<td style="padding: 4px;font-weight:bold; background: #ffbec2;" >{approver}</td>',
								    			'<td style="padding: 4px;font-weight:bold; background: #ffd9d9" >{action_timestamp}</td>',
								    			'<td style="padding: 4px;font-weight:bold; background: #ffd9d9" >{status}</td>',
								    			'<td style="padding: 4px;font-weight:bold; background: #ffd9d9" >{remarks}</td>',
								    		'</tr>',
								    	'</tpl>',
							    	'</table>',
								'</p>',
								'<br />',
								'<br />'
							);

					var approver_details = action.result.approver_details;
				    if(approver_details != null && typeof(approver_details) != "undefined")
						Ext.getCmp("_panelHtml").html = tplApplicationApprovers.applyTemplate(action.result);

				/*
				 *  DETAILS FOR APPROVAL HERE PER APPLICATION TYPE
				 */



							 var data = action.result.data;
							 var tplApplicationDetails = new Ext.XTemplate(
									'<br />',
									'<p>',
                                                                        '<tpl for="data">',
										'<table width="520" style="background: #fff;padding: 4px;border: solid 1px #5aa865;font-size: 10pt">',
										'<tr style="background: #5aa865;">',
											'<td colspan="2" style="color:#fff;font-weight:bold;padding: 4px;">Application Details</td>',
										'</tr>',
										'<tr style="background: #d8f1dc;">',
											'<td style="padding: 4px;text-align:right;" >Employee Name:</td>',
											'<td style="padding: 4px;" >{employee_name}</td>',
										'</tr>',
                                                                                '<tr style="background: #d8f1dc;">',
											'<td style="padding: 4px;text-align:right;" >Training Type:</td>',
											'<td style="padding: 4px;" >{training_type}</td>',
										'</tr>',
										'<tr style="background: #d8f1dc;" >',
											'<td style="padding: 4px;text-align:right;" width="180px">Date Filed:</td>',
											'<td style="padding: 4px;" >{date_requested}</td>',
										'</tr>',
										'<tr style="background: #d8f1dc;">',
											'<td style="padding: 4px;text-align:right;" >Date Start:</td>',
											'<td style="padding: 4px;" >{date_start}</td>',
										'</tr>',
										'<tr style="background: #d8f1dc;">',
											'<td style="padding: 4px;text-align:right;" >Date End:</td>',
											'<td style="padding: 4px;" >{date_end}</td>',
										'</tr>',
										'<tr style="background: #d8f1dc;">',
											'<td style="padding: 4px;text-align:right;" >Time Start:</td>',
											'<td style="padding: 4px;" >{start_time}</td>',
										'</tr>',
                                                                                '<tr style="background: #d8f1dc;">',
											'<td style="padding: 4px;text-align:right;" >Time End:</td>',
											'<td style="padding: 4px;" >{end_time}</td>',
										'</tr>',
                                                                                '<tr style="background: #d8f1dc;">',
											'<td style="padding: 4px;text-align:right;" >Type:</td>',
											'<td style="padding: 4px;" >{type}</td>',
										'</tr>',
                                                                                '<tr style="background: #d8f1dc;">',
											'<td style="padding: 4px;text-align:right;" >Client/Supplier Name:</td>',
											'<td style="padding: 4px;" >{client}</td>',
										'</tr>',
                                                                              
										/*'<tr style="background: #d8f1dc;">',
											'<td style="padding: 4px;text-align:right;" >Type:</td>',
											'<td style="padding: 4px;" >{ot_type}</td>',
										'</tr>',
										'<tr style="background: #d8f1dc;">',
											'<td style="padding: 4px;text-align:right;" valign="top" >Barcode Logs:</td>',
											'<td style="padding: 4px;" valign="top" >{barcodes}</td>',
										'</tr>',*/
										'<tr style="background: #d8f1dc;">',
											'<td style="padding: 4px;text-align:right;" >Title:</td>',
											'<td style="padding: 4px;" >{title}</td>',
										'</tr>',
										'<tr style="background: #d8f1dc;">',
											'<td style="padding: 4px;text-align:right;" >Details:</td>',
											'<td style="padding: 4px;" >{details}</td>',
										'</tr>',
										'</table>',
                                                                                '</tpl>',
									'</p>',
									'<br />'
							    );

							Ext.getCmp("_panelHtml").html += tplApplicationDetails.applyTemplate(action.result);

					openWin.show();
				},
				failure: function(form, action){
					Ext.Msg.show({title: 'Error Alert',	msg:action.result.data, icon: Ext.Msg.ERROR,buttons: Ext.Msg.OK});
					openWin.destroy();
				}
			});

                                var openWin = new Ext.Window({
					title		: "View Application",
					width		: 900,
					height		: 422,
					bodyStyle	:'padding:5px;',
					plain		: true,
					modal		: true,
					layout		: 'border',
					items		: [ fPanel, ApplicationApprovers ],
					autoScroll: true,
                                        buttonAlign: 'center',
					buttons: [
							{
								text: 'Close',
                                                                icon: '/images/icons/cancel.png',
								disabled: false,
								handler: function(){
                                                                openWin.destroy();
                                                                }
                                                }
						],
						listeners: {
							show: function(obj) {
									obj.doLayout();
									//fPanel.doLayout();
							}

						}
			});

                }else return;

                },
                view_tito: function(){
                if(ExtCommon.util.validateSelectionGrid(requests.app.titoGrid.getId())){//check if user has selected an item in the grid
			var sm = requests.app.titoGrid.getSelectionModel();
			var id = sm.getSelected().data.audit_id;
                        var pk = sm.getSelected().data.id;
                        var app_type = sm.getSelected().data.app_type;

                        var reader = new Ext.data.JsonReader({
                                                idProperty: 'id',
						fields: [
									{ name: "member_name" },

									{ name: "description"},
                                                                        {name: 'id'}

								]
						});

			/*
			*	SET STORE FOR THE GROUPING GRID
			*/

                                        Objstore = new Ext.data.GroupingStore({
                                            reader		: 	reader,
                                            data		: 	new Array(),
                                            sortInfo	:	{field: 'id', direction: "ASC"},
                                            groupField	:	'description'
                                        });

                                        var gridApprovers = new Ext.grid.GridPanel({
						id		: 	'ApplicationApprovers',
						loadMask	: 	true,
						border		: 	false,
                                                store		: 	Objstore,
						columns	:
								[
                                                                { header: "id", width: 150, sortable: false, locked:true, dataIndex: "id", hidden: true },
    							  { header: "Group Name", width: 150, sortable: false, locked:true, dataIndex: "description" },
								  { header: "Approver's Name", width: 200, sortable: false, locked:true, dataIndex: "member_name" }
								],
                                        view: new Ext.grid.GroupingView({
                                         forceFit:true,
                                         enableNoGroups	: true,
                                         groupText:'{text}'
                                        })
                                        });

                                var ApplicationApprovers = new Ext.Panel({
				title       : 'Approvers',
				iconCls		: 'icon_appgroup',
                                region      : 'east',
                                split       : true,
                                width       : 280,
                                collapsible : true,
                                layout		: "fit",
                                margins     : '3 0 3 3',
                                cmargins    : '3 3 3 3',
                                items: [ gridApprovers ]
                                    });

                                    var fPanelHtml = new Ext.Panel({
                                        id		: "_panelHtml",
                                        border	: 	false,
                                        width	: 580,
                                        margins : 	'1 1 1 0',
                                        html	: ""
                                    });

                                var ApplicationDetailsPanel = new Ext.FormPanel({
				id			: 	"_applicationDetailsPanel",
		 		border		: 	false,
                                frame		:	true,
                                bodyStyle	:	'padding:0 0 0',
                                width		: 	595,
                                height		: 	550,
                                        autoScroll	:	true,
                                items		:
                                [
					fPanelHtml
                                ]
                                });

                                var fPanel = new Ext.Panel({
				border	: 	false,
			 	region  : 	'center',
			 	width	: 	520,
                                margins : 	'1 1 1 0',
                                items	: 	[ApplicationDetailsPanel]
                                });

                                ApplicationDetailsPanel.form.load({
				url		:	"<?php echo site_url('apps/viewApplication') ?>",
				method	: 	'POST',
				params	: 	{ id: id, app_type: app_type, pk: pk },
				waitMsg	:	'Loading...',
				success	: 	function(form, action){

				/*
				 * fill-in the approver list
				 */

					Ext.getCmp('ApplicationApprovers').getStore().loadData(action.result.approvers);


				/*
				 *  DETAILS FOR APPROVAL HERE PER APPLICATION TYPE
				 */

						var tplApplicationApprovers = new Ext.XTemplate(
								'<br />',
								'<p>',
									'<table width="520"  style="background: #fff;padding: 4px;border:solid 1px #ff6666;font-size: 10pt">',
							    		'<tr style="background: #ff6666;">',
							    			'<td colspan="4" style="color:#fff;font-weight:bold;padding: 4px;">Approvers Details</td>',
							    		'</tr>',

							    		'<tr style="background: #ffbec2;">',
							    			'<td style="padding: 4px;font-weight:bold" >Approvers Name</td>',
							    			'<td style="padding: 4px;font-weight:bold" >Action Date</td>',
							    			'<td style="padding: 4px;font-weight:bold" >Status</td>',
							    			'<td style="padding: 4px;font-weight:bold" >Remarks</td>',
							    		'</tr>',
							    		'<tpl for="approver_details">',
								    		'<tr >',
								    			'<td style="padding: 4px;font-weight:bold; background: #ffbec2;" >{approver}</td>',
								    			'<td style="padding: 4px;font-weight:bold; background: #ffd9d9" >{action_timestamp}</td>',
								    			'<td style="padding: 4px;font-weight:bold; background: #ffd9d9" >{status}</td>',
								    			'<td style="padding: 4px;font-weight:bold; background: #ffd9d9" >{remarks}</td>',
								    		'</tr>',
								    	'</tpl>',
							    	'</table>',
								'</p>',
								'<br />',
								'<br />'
							);

					var approver_details = action.result.approver_details;
				    if(approver_details != null && typeof(approver_details) != "undefined")
						Ext.getCmp("_panelHtml").html = tplApplicationApprovers.applyTemplate(action.result);

				/*
				 *  DETAILS FOR APPROVAL HERE PER APPLICATION TYPE
				 */



							 var data = action.result.data;
							 var tplApplicationDetails = new Ext.XTemplate(
									'<br />',
									'<p>',
                                                                        '<tpl for="data">',
										'<table width="520" style="background: #fff;padding: 4px;border: solid 1px #5aa865;font-size: 10pt">',
										'<tr style="background: #5aa865;">',
											'<td colspan="2" style="color:#fff;font-weight:bold;padding: 4px;">Application Details</td>',
										'</tr>',
										'<tr style="background: #d8f1dc;">',
											'<td style="padding: 4px;text-align:right;" >Employee Name:</td>',
											'<td style="padding: 4px;" >{employee_name}</td>',
										'</tr>',
										'<tr style="background: #d8f1dc;" >',
											'<td style="padding: 4px;text-align:right;" width="180px">Date Filed:</td>',
											'<td style="padding: 4px;" >{date_requested}</td>',
										'</tr>',
										'<tr style="background: #d8f1dc;">',
											'<td style="padding: 4px;text-align:right;" >From:</td>',
											'<td style="padding: 4px;" >{date_time_in} {time_in}</td>',
										'</tr>',
										'<tr style="background: #d8f1dc;">',
											'<td style="padding: 4px;text-align:right;" >To:</td>',
											'<td style="padding: 4px;" >{date_time_out} {time_out}</td>',
										'</tr>',
										'<tr style="background: #d8f1dc;">',
											'<td style="padding: 4px;text-align:right;" >Reason:</td>',
											'<td style="padding: 4px;" >{reason}</td>',
										'</tr>',
										'</table>',
                                                                                '</tpl>',
									'</p>',
									'<br />'
							    );

							Ext.getCmp("_panelHtml").html += tplApplicationDetails.applyTemplate(action.result);

					openWin.show();
				},
				failure: function(form, action){
					Ext.Msg.show({title: 'Error Alert',	msg:action.result.data, icon: Ext.Msg.ERROR,buttons: Ext.Msg.OK});
					openWin.destroy();
				}
			});

                                var openWin = new Ext.Window({
					title		: "Application Approval",
					width		: 900,
					height		: 520,
					bodyStyle	:'padding:5px;',
					plain		: true,
					modal		: true,
					layout		: 'border',
					items		: [ fPanel, ApplicationApprovers ],
					autoScroll: true,
                                        buttonAlign: 'center',
					buttons: [
							{
								text: 'Close',
                                                                icon: '/images/icons/cancel.png',
								disabled: false,
								handler: function(){
                                                                openWin.destroy();
                                                                }
                                                }
						],
						listeners: {
							show: function(obj) {
									obj.doLayout();
									//fPanel.doLayout();
							}

						}
			});

                }else return;

                },
                viewForceLeave: function(){
                if(ExtCommon.util.validateSelectionGrid(requests.app.forceLeaveGrid.getId())){//check if user has selected an item in the grid
			var sm = requests.app.forceLeaveGrid.getSelectionModel();

                        var id = sm.getSelected().data.id;
  



                                    var fPanelHtml = new Ext.Panel({
                                        id		: "_panelHtml",
                                        border	: 	false,
                                        width	: 580,
                                        margins : 	'1 1 1 0',
                                        html	: ""
                                    });

                                var ApplicationDetailsPanel = new Ext.FormPanel({
				id			: 	"_applicationDetailsPanel",
		 		border		: 	false,
                                frame		:	true,
                                bodyStyle	:	'padding:0 0 0',
                                width		: 	595,
                                height		: 	550,
                                        autoScroll	:	true,
                                items		:
                                [
					fPanelHtml
                                ]
                                });

                                var fPanel = new Ext.Panel({
				border	: 	false,
			 	region  : 	'center',
			 	width	: 	520,
                                margins : 	'1 1 1 0',
                                items	: 	[ApplicationDetailsPanel]
                                });

                                ApplicationDetailsPanel.form.load({
				url		:	"<?php echo site_url('admin/viewForceLeave') ?>",
				method	: 	'POST',
				params	: 	{ id: id },
				waitMsg	:	'Loading...',
				success	: 	function(form, action){

			


				

				/*
				 *  DETAILS FOR APPROVAL HERE PER APPLICATION TYPE
				 */

					
							 var data = action.result.data;
							 var tplApplicationDetails = new Ext.XTemplate(
									'<br />',
									'<p>',
                                                                        '<tpl for="data">',
										'<table width="520" style="background: #fff;padding: 4px;border: solid 1px #5aa865;font-size: 10pt">',
										'<tr style="background: #5aa865;">',
											'<td colspan="2" style="color:#fff;font-weight:bold;padding: 4px;">Application Details</td>',
										'</tr>',
										'<tr style="background: #d8f1dc;">',
											'<td style="padding: 4px;text-align:right;" >Employee Name:</td>',
											'<td style="padding: 4px;" >{employee_name}</td>',
										'</tr>',
										'<tr style="background: #d8f1dc;" >',
											'<td style="padding: 4px;text-align:right;" width="180px">Date Filed:</td>',
											'<td style="padding: 4px;" >{date_requested}</td>',
										'</tr>',
										'<tr style="background: #d8f1dc;">',
											'<td style="padding: 4px;text-align:right;" >From:</td>',
											'<td style="padding: 4px;" >{date_from}</td>',
										'</tr>',
										'<tr style="background: #d8f1dc;">',
											'<td style="padding: 4px;text-align:right;" >To:</td>',
											'<td style="padding: 4px;" >{date_to}</td>',
										'</tr>',
										'<tr style="background: #d8f1dc;">',
											'<td style="padding: 4px;text-align:right;" >No. of Days:</td>',
											'<td style="padding: 4px;" >{no_days}</td>',
										'</tr>',
                                                                                '<tr style="background: #d8f1dc;">',
											'<td style="padding: 4px;text-align:right;" >Leave Type:</td>',
											'<td style="padding: 4px;" >{leave_type}</td>',
										'</tr>',
										'<tr style="background: #d8f1dc;">',
											'<td style="padding: 4px;text-align:right;" >Reason:</td>',
											'<td style="padding: 4px;" >{reason}</td>',
										'</tr>',
										'</table>',
                                                                                '</tpl>',
									'</p>',
									'<br />');

							 //Ext.getCmp("_panelHtml").html += tplLeaveCredits.applyTemplate(dataLeave);
							 Ext.getCmp("_panelHtml").html += tplApplicationDetails.applyTemplate(action.result);
							// Ext.getCmp("gridLastFiled").getStore().loadData(action.result.last3filed);

						
					openWin.show();
				},
				failure: function(form, action){
					Ext.Msg.show({title: 'Error Alert',	msg:action.result.data, icon: Ext.Msg.ERROR,buttons: Ext.Msg.OK});
					openWin.destroy();
				}
			});

                                var openWin = new Ext.Window({
					title		: "View Force Leave",
					width		: 550,
					height		: 340,
					bodyStyle	:'padding:5px;',
					plain		: true,
					modal		: true,
					layout		: 'border',
					items		: [ fPanel ],
					autoScroll: true,
                                        buttonAlign: 'center',
					buttons: [
							{
								text: 'Close',
                                                                icon: '/images/icons/cancel.png',
								disabled: false,
								handler: function(){
                                                                openWin.destroy();
                                                                }
                                                }
						],
						listeners: {
							show: function(obj) {
									obj.doLayout();
									//fPanel.doLayout();
							}

						}
			});

                }else return;

                },
                trainingTypeCombo: function(){

		return {
			xtype:'combo',
			id:'training_type',
			hiddenName: 'training_type_id',
			hiddenId: 'training_type_id',
			name: 'training_type_name',
			valueField: 'id',
			displayField: 'name',
			//width: 100,
			anchor: '90%',
			triggerAction: 'all',
			minChars: 2,
			forceSelection: true,
			enableKeyEvents: true,
			pageSize: 10,
			resizable: true,
			readOnly: false,
			minListWidth: 300,
			allowBlank: false,
			store: new Ext.data.JsonStore({
			id: 'idsocombo',
			root: 'data',
			totalProperty: 'totalCount',
			fields:[{name: 'id', type:'int', mapping:'id'}, {name: 'name', type:'string', mapping: 'name'}, {name: 'fax', type:'string', mapping: 'fax'}],
			url: "<?php echo site_url("hr/getTrainingType"); ?>",
			baseParams: {start: 0, limit: 10}

			}),
			listeners: {
			select: function (combo, record, index){
			this.setRawValue(record.get('name'));
			Ext.get(this.hiddenName).dom.value  = record.get('id');

			},
			blur: function(){
			var val = this.getRawValue();
			this.setRawValue.defer(1, this, [val]);
			this.validate();
			},
			render: function() {
			this.el.set({qtip: 'Type at least ' + this.minChars + ' characters to search for an employee status'});

			},
			keypress: {buffer: 100, fn: function() {
			Ext.get(this.hiddenName).dom.value  = '';
			if(!this.getRawValue()){
			this.doQuery('', true);
			}
			}}
			},
			fieldLabel: 'Training Type*'

			}
	}//end of functions
 	}

 }();

 Ext.onReady(requests.app.init, requests.app);

</script>

<div class="mainBody" id="mainBody" >
</div>