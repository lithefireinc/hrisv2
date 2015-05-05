  <script type="text/javascript" src="/js/ext34/examples/ux/ux-all.js"></script>
 <link rel="stylesheet" type="text/css" href="/js/ext34/examples/ux/css/ux-all.css" />
 <script type="text/javascript">
 Ext.namespace("hrisv2_my_whereabouts");
 hrisv2_my_whereabouts.app = function()
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
 			//Ext.QuickTips.init();

 			var Objstore = new Ext.data.Store({
 						proxy: new Ext.data.HttpProxy({
 							url: "<?php echo site_url("admin/getLeaves"); ?>",
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
                                                        {name: 'employee_name'},
                                                        {name: 'employee_id'},
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
 						{ header: "Employee Id", dataIndex: "employee_id", width: 100, sortable: true},
 												  { header: "Employee Name", dataIndex: "employee_name", width: 250, sortable: true},

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
                                                                 hrisv2_my_whereabouts.app.Grid.getStore().setBaseParam("date_from", this.getRawValue());          
                                                              },
                                                              blur: function(){
                                                                           
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
                                                                   hrisv2_my_whereabouts.app.Grid.getStore().setBaseParam("date_to", this.getRawValue());                
                                                              },
                                                              blur: function(){
                                                                           
                                                                  }
                                                            }
                                                      },
                                                      '-', '   ',
                                                      {xtype: 'tbbutton', 
                                                      text: 'Refresh Grid', 
                                                      handler: function(){
                                                      	hrisv2_my_whereabouts.app.Grid.getStore().load({params:{start: 0, limit: 25}});
                                                      	}
                                                    },
 					    {
 					     	xtype: 'tbfill'
 					 	},
                                                
                                                {
                                                    xtype: 'tbbutton',
                                                    text: 'VIEW',
                                                    icon: '/images/icons/application_form_magnify.png',
                                                    handler: hrisv2_my_whereabouts.app.view_application
                                                }, '-',
                                                {
                                                    xtype: 'tbbutton',
                                                    text: 'VOID',
                                                    icon: '/images/icons/application_form_delete.png',
                                                    handler: function(){
                                                                 if(ExtCommon.util.validateSelectionGrid(hrisv2_my_whereabouts.app.Grid.getId())){
                                                                var sm = hrisv2_my_whereabouts.app.Grid.getSelectionModel();
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
                                                                                                hrisv2_my_whereabouts.app.Grid.getStore().load({params:{start:0, limit: 25}});

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

 			hrisv2_my_whereabouts.app.Grid = grid;
 			hrisv2_my_whereabouts.app.Grid.getStore().load({params:{start: 0, limit: 25}});



var _window = new Ext.Panel({
 		        title: 'Leaves',
 		        width: '100%',
 		        height:450,
 		        renderTo: 'mainBody',
 		        draggable: false,
 		        layout: 'fit',
 		        items: [hrisv2_my_whereabouts.app.Grid],
 		        resizable: false

 			    /*listeners : {
 				    	  close: function(p){
 					    	  window.location="../"
 					      }
 			       	} */
 	        }).render();


 		},
        timeFormat: function(val){

			var fmtVal;

			switch(val){
				case "ABSENT"	: 	fmtVal = '<span style="color: red; font-weight: bold;">'+val+'</span>'; break;
			 	case "LEAVE"	:  	fmtVal = '<span style="color: green; font-weight: bold;">'+val+'</span>'; break;
			 	case "SUSPENDED": 	fmtVal = '<span style="color: red; font-weight: bold;">'+val+'</span>'; break;
			 	case "CLIENT SCHEDULE"	: 	fmtVal = '<span style="color: green; font-weight: bold;">'+val+'</span>'; break;
				case "HOLIDAY" : fmtVal = '<span style="color: green; font-weight: bold;">'+val+'</span>'; break;
				case "REST DAY"	: 	fmtVal = '<span style="color: green; font-weight: bold;">'+val+'</span>'; break;
				case "Y"	: 	fmtVal = '<span style="color: red; font-weight: bold;">'+val+'</span>'; break;
				case "FORCE LEAVE"	: 	fmtVal = '<span style="color: orange; font-weight: bold;">'+val+'</span>'; break;
				default: fmtVal = val;
			}

			return fmtVal;
		},
		view_application: function(){
                if(ExtCommon.util.validateSelectionGrid(hrisv2_my_whereabouts.app.Grid.getId())){//check if user has selected an item in the grid
			var sm = hrisv2_my_whereabouts.app.Grid.getSelectionModel();
			var id = sm.getSelected().data.audit_id;
                        var pk = sm.getSelected().data.application_pk;
                        var app_type = sm.getSelected().data.app_type;
						if(pk == 0){
						Ext.Msg.show({title: 'Error Alert',	msg:"No details available for this entry", icon: Ext.Msg.ERROR,buttons: Ext.Msg.OK});
						return;
						}
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
                                autoScroll      :       true,
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



					switch(app_type){
						case "OT":
							

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
										/*'<tr style="background: #d8f1dc;">',
											'<td style="padding: 4px;text-align:right;" >Type:</td>',
											'<td style="padding: 4px;" >{ot_type}</td>',
										'</tr>',
										'<tr style="background: #d8f1dc;">',
											'<td style="padding: 4px;text-align:right;" valign="top" >Barcode Logs:</td>',
											'<td style="padding: 4px;" valign="top" >{barcodes}</td>',
										'</tr>',*/
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

						break;
						
						case "TITO":
							

							 var data = action.result.data;
							 var tplApplicationDetails = new Ext.XTemplate(
									'<br />',
									'<p>',
                                                                        '<tpl for="data">',
										'<table width="520" style="background: #fff;padding: 4px;border: solid 1px #5aa865;font-size: 10pt">',
										'<tr style="background: #5aa865;">',
											'<td colspan="2" style="color:#fff;font-weight:bold;padding: 4px;">Application Details</td>',
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
										
										/*'<tr style="background: #d8f1dc;">',
											'<td style="padding: 4px;text-align:right;" >Type:</td>',
											'<td style="padding: 4px;" >{ot_type}</td>',
										'</tr>',
										'<tr style="background: #d8f1dc;">',
											'<td style="padding: 4px;text-align:right;" valign="top" >Barcode Logs:</td>',
											'<td style="padding: 4px;" valign="top" >{barcodes}</td>',
										'</tr>',*/
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

						break;

                                                /*Client Schedule*/

                                                case "Client Schedule":


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

						break;

						/*
						 * 	for leave requests
						 */

						case "Leave":
                               

							 var data = action.result.data;
							 var tplApplicationDetails = new Ext.XTemplate(
									'<br />',
									'<p>',
                                                                        '<tpl for="data">',
										'<table width="520" style="background: #fff;padding: 4px;border: solid 1px #5aa865;font-size: 10pt">',
										'<tr style="background: #5aa865;">',
											'<td colspan="2" style="color:#fff;font-weight:bold;padding: 4px;">Application Details</td>',
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
											'<td style="padding: 4px;text-align:right;" >No of Days:</td>',
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

						break;
					}

					openWin.show();
				},
				failure: function(form, action){
					Ext.Msg.show({title: 'Error Alert',	msg:action.result.data, icon: Ext.Msg.ERROR,buttons: Ext.Msg.OK});
					openWin.destroy();
				}
			});

                                var openWin = new Ext.Window({
					title		: "View details",
					width		: 560,
					height		: 420,
					bodyStyle	:'padding:5px;',
					plain		: true,
					modal		: true,
					layout		: 'fit',
					items		: [ fPanel],
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
		}//end of functions
 	}

 }();

 Ext.onReady(hrisv2_my_whereabouts.app.init, hrisv2_my_whereabouts.app);

</script>

<div id="mainBody">
</div>