<script type="text/javascript">
 Ext.namespace("my_approver");
 my_approver.app = function()
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
 							url: "<?php echo site_url('apps/getPendingApplications')?>",
 							method: "POST"
 							}),
 						reader: new Ext.data.JsonReader({
 								root: "data",
 								id: "id",
 								totalProperty: "totalCount",
 								fields: [
 											{ name: "apps_pk"},
 											{ name: "date_requested"},
                                                                                        {name: "app_type_id"},
                                                                                        {name: "app_type"},
                                                                                        {name: "emp_name"},
                                                                                        {name: "audit_id"}
 										]
 						}),
 						remoteSort: true,
 						baseParams: {start: 0, limit: 25}
 					});


 			var grid = new Ext.grid.GridPanel({
 				id: 'my_approvergrid',
 				height: 300,
 				width: 900,
 				border: true,
 				ds: Objstore,
 				cm:  new Ext.grid.ColumnModel(
 						[
 						  { header: "Date Filed", width: 120, sortable: true, dataIndex: "date_requested" },
	  					  { header: "Employee Name", width: 200, sortable: true, dataIndex: "emp_name" },
	  					  { header: "Application Type", width: 150, sortable: true, dataIndex: "app_type" }
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
 					     	text: 'VIEW APPLICATION',
							icon: '/images/icons/application_form_magnify.png',
 							cls:'x-btn-text-icon',

 					     	handler: my_approver.app.view_application

 					 	}
 	    			 ]
 	    	});

 			my_approver.app.Grid = grid;
 			my_approver.app.Grid.getStore().load({params:{start: 0, limit: 25}});

 

 			var _window = new Ext.Panel({
 		        title: 'My Approval',
 		        width: '100%',
 		        height:420,
 		        renderTo: 'mainBody',
 		        draggable: false,
 		        layout: 'fit',
 		        items: [my_approver.app.Grid],
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
 		        url:"functions/createmy_approver.php",
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

 		    my_approver.app.Form = form;
 		},
 		Add: function(){

 			my_approver.app.setForm();

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
 		        items: my_approver.app.Form,
 		        buttons: [{
 		         	text: 'Save',
                                icon: '/sms/images/icons/disk.png',  cls:'x-btn-text-icon',

 	                handler: function () {
 			            if(ExtCommon.util.validateFormFields(my_approver.app.Form)){//check if all forms are filled up

 		                my_approver.app.Form.getForm().submit({
 			                success: function(f,action){
                 		    	Ext.MessageBox.alert('Status', action.result.data);
                  		    	 Ext.Msg.show({
  								     title: 'Status',
 								     msg: action.result.data,
  								     buttons: Ext.Msg.OK,
  								     icon: 'icon'
  								 });
 				                ExtCommon.util.refreshGrid(my_approver.app.Grid.getId());
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


 			if(ExtCommon.util.validateSelectionGrid(my_approver.app.Grid.getId())){//check if user has selected an item in the grid
 			var sm = my_approver.app.Grid.getSelectionModel();
 			var id = sm.getSelected().data.apps_pk;

 			my_approver.app.setForm();
 		    _window = new Ext.Window({
 		        title: 'Update Referred by',
 		        width: 410,
 		        height:160,
 		        layout: 'fit',
 		        plain:true,
 		        modal: true,
 		        bodyStyle:'padding:5px;',
 		        buttonAlign:'center',
 		        items: my_approver.app.Form,
 		        buttons: [{
 		         	text: 'Save',
                                icon: '/sms/images/icons/disk.png',  cls:'x-btn-text-icon',

 		            handler: function () {
 			            if(ExtCommon.util.validateFormFields(my_approver.app.Form)){//check if all forms are filled up
 		                my_approver.app.Form.getForm().submit({
 			                url: "functions/updatemy_approver.php",
 			                params: {id: id},
 			                method: 'POST',
 			                success: function(f,action){
                 		    	Ext.MessageBox.alert('Status', action.result.data);
 				                ExtCommon.util.refreshGrid(my_approver.app.Grid.getId());
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
				url: "functions/loadmy_approver.php",
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


 		  /*	my_approver.app.Form.getForm().load({
 				url: "functions/loadmy_approver.php",
 				method: 'POST',
 				params: {id: id},
 				timeout: 300000,
 				waitMsg:'Loading...',
 				success: function(form, action){
 			 		//Ext.get('my_approver').dom.value  = action.result.data.my_approver;
 			 		//Ext.get('toId').dom.value  = action.result.data.ToId;
 			 		Ext.getCmp('my_approver').setValue(action.result.data.my_approver);
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


			if(ExtCommon.util.validateSelectionGrid(my_approver.app.Grid.getId())){//check if user has selected an item in the grid
			var sm = my_approver.app.Grid.getSelectionModel();
			var id = sm.getSelected().data.apps_pk;
			Ext.Msg.show({
   			title:'Delete',
  			msg: 'Are you sure you want to delete this record?',
   			buttons: Ext.Msg.OKCANCEL,
   			fn: function(btn, text){
   			if (btn == 'ok'){

   			Ext.Ajax.request({
							url: "functions/deletemy_approver.php",
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
							my_approver.app.Grid.getStore().load({params:{start:0, limit: 25}});

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


		},
                view_application: function(){
                if(ExtCommon.util.validateSelectionGrid(my_approver.app.Grid.getId())){//check if user has selected an item in the grid
			var sm = my_approver.app.Grid.getSelectionModel();
			var id = sm.getSelected().data.audit_id;
                        var pk = sm.getSelected().data.apps_pk;
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
				title       : 'Application Approvers',
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
                                autoScroll      :       true,
                                bodyStyle	:	'padding:0 0 0',
                                width		: 	595,
                                height		: 	550,
                                        autoScroll	:	true,
                                items		:
                                [ 	
					fPanelHtml,
		        	{ xtype: 'textarea', id: 'txtreason', name: 'reason', anchor:'90%', fieldLabel: 'Comment', allowBlank: false }
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
									'<table width="520"  style="background: #fff;padding: 4px;border:solid 1px #ff6666; font-size: 10pt">',
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
								'</p>'
							);

					var approver_details = action.result.approver_details;
				    if(approver_details != null && typeof(approver_details) != "undefined")
						Ext.getCmp("_panelHtml").html = tplApplicationApprovers.applyTemplate(action.result);

				/*
				 *  DETAILS FOR APPROVAL HERE PER APPLICATION TYPE
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

						break;
						
						/*Trainings*/

                                                case "Training":


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

						break;

						/*
						 * 	for leave requests
						 */

						case "Leave":
                                                        /*
							 var dataLeave = action.result.leave_credits;
							 var tplLeaveCredits = new Ext.XTemplate(
									'<br />',
									'<p>',
										'<table width="520" style="background: #fff;padding: 4px;border: solid 1px #5aa865">',
											'<tr style="background: #5aa865;">',
												'<td colspan="3" style="color:#fff;font-weight:bold;padding: 4px;">{leave_title}</td>',
											'</tr>',
											'<tr style="background: #d8f1dc;">',
												'<td style="padding: 4px;text-align:center; font-weight: bold" >&nbsp;</td>',
												'<td style="padding: 4px;text-align:center; font-weight: bold" >Used</td>',
												'<td style="padding: 4px;text-align:center; font-weight: bold;" >Remaining</td>',
											'</tr>',
											'<tr style="background: #d8f1dc;" >',
												'<td style="padding: 4px;text-align:left;" width="180px">Leave Credits:</td>',
												'<td style="padding: 4px;text-align: center; " >{leave_used}</td>',
												'<td style="padding: 4px;text-align: center; " >{leave_remaining}</td>',
											'</tr>',
											'<tr style="background: #d8f1dc;" >',
												'<td style="padding: 4px;text-align:left;" width="180px">Emergency Leave Credits:</td>',
												'<td style="padding: 4px;text-align: center;" >{floating_leave_used}</td>',
												'<td style="padding: 4px;text-align: center;" >{floating_remaining}</td>',
											'</tr>',
											'<tr style="background: #d8f1dc;" >',
												'<td style="padding: 4px;text-align:left;" width="180px">Solo Leave Credits:</td>',
												'<td style="padding: 4px;text-align: center;" >{solo_leave_used}</td>',
												'<td style="padding: 4px;text-align: center;" >{solo_leave_remaining}</td>',
											'</tr>',
										'</table>',
									'</p>',
									'<br />'
							    );*/

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
					title		: "Application Approval",
					width		: 900,
					height		: 520,
					bodyStyle	:'padding:5px;',
					plain		: true,
					modal		: true,
					layout		: 'border',
					items		: [ fPanel, ApplicationApprovers ],
					autoScroll: true,
					buttons: [
							{
								text: 'Approve',
                                                                icon: '/images/icons/tick.png',
								handler: function(){
                                                                    if(ExtCommon.util.validateFormFields(ApplicationDetailsPanel)){
                                                                        ApplicationDetailsPanel.getForm().submit({
                                                                            url: "<?php echo site_url("apps/approveApplication")?>",
                                                                            method: "POST",
                                                                            params: {audit_id: id},
                                                                            success: function(f,action){
                                                                            Ext.MessageBox.alert('Status', action.result.data);
                                                                             Ext.Msg.show({
                                                                                                         title: 'Status',
                                                                                                         msg: action.result.data,
                                                                                                         buttons: Ext.Msg.OK,
                                                                                                         icon: Ext.Msg.INFO
                                                                                                     });
                                                                                    ExtCommon.util.refreshGrid(my_approver.app.Grid.getId());
                                                                                    openWin.destroy();
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
							{
								text: 'Deny',
                                                                icon: '/images/icons/cross.png',
								handler: function(){
                                                                    if(ExtCommon.util.validateFormFields(ApplicationDetailsPanel)){
                                                                        ApplicationDetailsPanel.getForm().submit({
                                                                            url: "<?php echo site_url("apps/denyApplication")?>",
                                                                            method: "POST",
                                                                            params: {audit_id: id},
                                                                            success: function(f,action){
                                                                            Ext.MessageBox.alert('Status', action.result.data);
                                                                             Ext.Msg.show({
                                                                                                         title: 'Status',
                                                                                                         msg: action.result.data,
                                                                                                         buttons: Ext.Msg.OK,
                                                                                                         icon: Ext.Msg.INFO
                                                                                                     });
                                                                                    ExtCommon.util.refreshGrid(my_approver.app.Grid.getId());
                                                                                    openWin.destroy();
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
							{
								text: 'Cancel',
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

 Ext.onReady(my_approver.app.init, my_approver.app);

</script>
<div class="mainBody" id="mainBody" >
</div>