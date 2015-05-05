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
 							url: "<?php echo site_url("admin/getWhereabouts")?>",
 							method: "POST"
 							}),
 						reader: new Ext.data.JsonReader({
 								root: "data",
 								id: "id",
 								totalProperty: "totalCount",
 								fields: [
 											{ name: "id"},
 											{ name: "dtr_date"},
                                                                                        { name: "time_in"},
                                                                                        { name: "time_out"},
                                                                                        { name: "details"},
                                                                                        { name: "employee_id"},
 											{name: "name"},
 											{ name: "restday"},
                                                                                        { name: "is_leave"},
                                                                                        { name: "client_schedule"},
                                                                                        { name: "audit_id"},
                                                                                        { name: "application_pk"},
                                                                                        { name: "app_type"}
 										
 										]
 						}),
 						remoteSort: true,
 						baseParams: {start: 0, limit: 25}
 					});
		

 			var Grid = new Ext.grid.GridPanel({
 				id: 'hrisv2_my_whereaboutsgrid',
 				height: 422,
 				width: '100%',
 			//	plugins: expander,
 				border: true,
 				ds: Objstore,
 				cm:  new Ext.grid.ColumnModel(
 						[
 												//  Ext.grid.gridNumberer(),
 											//	expander,
 											new Ext.grid.RowNumberer(),
 												  { header: "Employee Id", dataIndex: "employee_id", width: 150, sortable: true},
 												  { header: "Employee Name", dataIndex: "name", width: 250, sortable: true},
                                                  { header: "DTR Date", dataIndex: "dtr_date", width: 150, sortable: true},
                                                  { header: "Time-In", dataIndex: "time_in", width: 150, sortable: true, renderer: this.timeFormat},
                                                  { header: "Time-Out", dataIndex: "time_out", width: 150, sortable: true, renderer: this.timeFormat},
                                                  { header: "Restday", dataIndex: "restday", width: 75, sortable: true, renderer: this.timeFormat},
                                                  { header: "Leave", dataIndex: "is_leave", width: 75, sortable: true, renderer: this.timeFormat},
                                                  { header: "Client Schedule", dataIndex: "client_schedule", width: 100, sortable: true, renderer: this.timeFormat}
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
 				new Ext.form.ComboBox({
                    fieldLabel: 'Search',
                    hiddenName:'searchby-form',
                    id: 'searchby',
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
                                                      text: 'Refresh Date Filter', 
                                                      handler: function(){
                                                      	hrisv2_my_whereabouts.app.Grid.getStore().load({params:{start: 0, limit: 25}});
                                                      	}
                                                    },
                                {
 					     	xtype: 'tbfill'
 					 	},
 					 	{
 					     	xtype: 'tbbutton',
 					     	text: 'VIEW DETAILS',
							icon: '/images/icons/application_form_magnify.png',
 							cls:'x-btn-text-icon',

 					     	handler: hrisv2_my_whereabouts.app.view_application

 					 	}

 	    			 ]
 	    	});

 			hrisv2_my_whereabouts.app.Grid = Grid;
 			hrisv2_my_whereabouts.app.Grid.getStore().load({params:{start: 0, limit: 25}});



var _window = new Ext.Panel({
 		        title: 'Whereabouts',
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
			 	case "VACATION LEAVE"	:  	fmtVal = '<span style="color: green; font-weight: bold;">'+val+'</span>'; break;
			 	case "SICK LEAVE"	:  	fmtVal = '<span style="color: green; font-weight: bold;">'+val+'</span>'; break;
			 	case "EMERGENCY LEAVE"	:  	fmtVal = '<span style="color: green; font-weight: bold;">'+val+'</span>'; break;
			 	case "OFFSET LEAVE"	:  	fmtVal = '<span style="color: orange; font-weight: bold;">'+val+'</span>'; break;
			 	case "UNPAID VACATION LEAVE"	:  	fmtVal = '<span style="color: green; font-weight: bold;">'+val+'</span>'; break;
			 	case "UNPAID SICK LEAVE"	:  	fmtVal = '<span style="color: green; font-weight: bold;">'+val+'</span>'; break;
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

                }//end of functions
 	}

 }();

 Ext.onReady(hrisv2_my_whereabouts.app.init, hrisv2_my_whereabouts.app);

</script>

<div id="mainBody">
</div>