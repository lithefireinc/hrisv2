,
apply_cs: function(){


                        var reader = new Ext.data.JsonReader({
                                                idProperty: 'id',
						fields: [
									{ name: "member_name" },

									{ name: "description"},
                                                                        {name: 'id'}

								]
						});

                                        Objstore = new Ext.data.GroupingStore({
                                            reader		: 	reader,
                                            data		: 	new Array(),
                                            sortInfo	:	{field: 'id', direction: "ASC"},
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
                        Ext.getCmp('contact_person_id').setValue("");
                        Ext.getCmp('contact_person_id').setRawValue("");

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

                }

