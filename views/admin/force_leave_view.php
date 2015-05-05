 <script type="text/javascript">
 Ext.namespace("hrisv2_force_leave");
 hrisv2_force_leave.app = function()
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
 							url: "<?php echo site_url("admin/getForceLeave")?>",
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
                                                                                        { name: "no_days"},
                                                                                        { name: "date_from"},
                                                                                        { name: "date_to"},
                                                                                        { name: "reason"},
                                                                                        { name: "status"},
                                                                                        { name: "requested_by"}
 										]
 						}),
 						remoteSort: true,
 						baseParams: {start: 0, limit: 25}
 					});


 			var Grid = new Ext.grid.GridPanel({
 				id: 'notificationgrid',
 				height: 422,
 				width: '100%',
 				border: true,
 				ds: Objstore,
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
							icon: '/images/icons/application_add.png',
 							cls:'x-btn-text-icon',

 					     	handler: hrisv2_force_leave.app.forceLeaveApply

 					 	},'-',{
 					     	xtype: 'tbbutton',
 					     	text: 'VIEW',
							icon: '/images/icons/application_edit.png',
 							cls:'x-btn-text-icon',

 					     	handler: hrisv2_force_leave.app.view_application

 					 	},'-',{
                                                   xtype: 'tbbutton',
                                                    text: 'VOID',
                                                    icon: '/images/icons/application_form_delete.png',
                                                    handler: function(){
                                                                 if(ExtCommon.util.validateSelectionGrid(hrisv2_force_leave.app.Grid.getId())){
                                                                var sm = hrisv2_force_leave.app.Grid.getSelectionModel();
                                                                var id = sm.getSelected().data.id;
                                                                Ext.Msg.show({
                                                                title:'Void Force Leave',
                                                                msg: 'Are you sure you want to cancel this application?',
                                                                buttons: Ext.Msg.OKCANCEL,
                                                                fn: function(btn, text){
                                                                if (btn == 'ok'){

                                                                Ext.Ajax.request({
                                                                                                url: "<?php echo site_url("admin/voidForceLeave")?>",
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
                                                                                                hrisv2_force_leave.app.Grid.getStore().load({params:{start:0, limit: 25}});

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

 			hrisv2_force_leave.app.Grid = Grid;
 			hrisv2_force_leave.app.Grid.getStore().load({params:{start: 0, limit: 25}});

                        

var _window = new Ext.Panel({
 		        title: 'Force Leave',
 		        width: '100%',
 		        height:450,
 		        renderTo: 'mainBody',
 		        draggable: false,
 		        layout: 'fit',
 		        items: [hrisv2_force_leave.app.Grid],
 		        resizable: false

 			    /*listeners : {
 				    	  close: function(p){
 					    	  window.location="../"
 					      }
 			       	} */
 	        }).render();


 		},
 			notificationSetForm: function(){
 		    var form = new Ext.form.FormPanel({
 		        labelWidth: 100,
 		        url:"<?=site_url("admin/applyForceLeave")?>",
 		        method: 'POST',
 		        defaultType: 'textfield',
 		        frame: true,

 		        items: [ {
 					xtype:'fieldset',
 					title:'Force Leave details',
 					width:'100%',
 					height:'auto',
 					items:[
                                            hrisv2_force_leave.app.employeeAllCombo(),
                                             hrisv2_force_leave.app.leaveTypeCombo(),

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
				                		hrisv2_force_leave.app.setNoOfDays();
                                                            },
                                                                blur: function(){
					                  	hrisv2_force_leave.app.setNoOfDays();
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
				                		hrisv2_force_leave.app.setNoOfDays();
                                                            },
                                                                blur: function(){
					                  	hrisv2_force_leave.app.setNoOfDays();
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
                                                                        hrisv2_force_leave.app.setNoOfDaysByPortion();
                                                                    },
                                                                        blur: function(){
                                                                        hrisv2_force_leave.app.setNoOfDaysByPortion();
                                                                    }
                                                                    }

                                                        }),
		               		{ xtype: 'textfield',
                                          name: 'no_days',
                                          id: 'no_days',
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
                                        }

 		        ]
 					}
 		        ]
 		    });


 		    hrisv2_force_leave.app.Form = form;
 		},
                employeeAllCombo: function(){

		return {
			xtype:'combo',
			id:'employee_combo',
			hiddenName: 'employee_id',
                        hiddenId: 'employee_id',
			name: 'employee_combo',
			valueField: 'id',
			displayField: 'name',
			//width: 100,
			anchor: '93%',
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
			url: "<?php echo site_url("admin/employeeAllCombo"); ?>",
			baseParams: {start: 0, limit: 10}

			}),
			listeners: {
			select: function (combo, record, index){

                        Ext.get(this.hiddenName).dom.value  = record.get('id');
			this.setRawValue(record.get('name'));
			//Ext.getCmp(this.id).setValue(record.get('name'));

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
			fieldLabel: 'Employee'

			}
	},
 		forceLeaveApply: function(){

 			hrisv2_force_leave.app.notificationSetForm();

 		  	var _window;

 		    _window = new Ext.Window({
 		        title: 'New Force Leave',
 		        width: 450,
 		        height:350,
 		        layout: 'fit',
 		        plain:true,
 		        modal: true,
 		        bodyStyle:'padding:5px;',
 		        buttonAlign:'center',
 		        items: hrisv2_force_leave.app.Form,
 		        buttons: [{
 		         	text: 'Save',
                                icon: '/images/icons/disk.png',  cls:'x-btn-text-icon',

 	                handler: function () {
 			            if(ExtCommon.util.validateFormFields(hrisv2_force_leave.app.Form)){//check if all forms are filled up

 		                hrisv2_force_leave.app.Form.getForm().submit({
 			                success: function(f,action){
                 		    	Ext.MessageBox.alert('Status', action.result.data);
                  		    	 Ext.Msg.show({
  								     title: 'Status',
 								     msg: action.result.data,
  								     buttons: Ext.Msg.OK,
  								     icon: 'icon'
  								 });
 				                ExtCommon.util.refreshGrid(hrisv2_force_leave.app.Grid.getId());
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
                            icon: '/images/icons/cancel.png', cls:'x-btn-text-icon',

 		            handler: function(){
 			            _window.destroy();
 		            }
 		        }]
 		    });
 		  	_window.show();
 		},
 		notificationEdit: function(){


 			if(ExtCommon.util.validateSelectionGrid(hrisv2_force_leave.app.Grid.getId())){//check if user has selected an item in the grid
 			var sm = hrisv2_force_leave.app.Grid.getSelectionModel();
 			var id = sm.getSelected().data.id;

 			hrisv2_force_leave.app.notificationSetForm();
 		    _window = new Ext.Window({
 		        title: 'Update Force Leave',
 		        width: 450,
 		        height:250,
 		        layout: 'fit',
 		        plain:true,
 		        modal: true,
 		        bodyStyle:'padding:5px;',
 		        buttonAlign:'center',
 		        items: hrisv2_force_leave.app.Form,
 		        buttons: [{
 		         	text: 'Save',
                                icon: '/images/icons/disk.png',  cls:'x-btn-text-icon',

 		            handler: function () {
 			            if(ExtCommon.util.validateFormFields(hrisv2_force_leave.app.Form)){//check if all forms are filled up
 		                hrisv2_force_leave.app.Form.getForm().submit({
 			                url: "<?=site_url("admin/updateForceLeave")?>",
 			                params: {id: id},
 			                method: 'POST',
 			                success: function(f,action){
                 		    	Ext.MessageBox.alert('Status', action.result.data);
 				                ExtCommon.util.refreshGrid(hrisv2_force_leave.app.Grid.getId());
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




 		  	hrisv2_force_leave.app.Form.getForm().load({
 				url: "<?=site_url("admin/loadNotification")?>",
 				method: 'POST',
 				params: {id: id},
 				timeout: 300000,
 				waitMsg:'Loading...',
 				success: function(form, action){


                                    _window.show();
                                    Ext.getCmp('employee_combo').setRawValue(action.result.data.employee_name);


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
		notificationDelete: function(){


			if(ExtCommon.util.validateSelectionGrid(hrisv2_force_leave.app.Grid.getId())){//check if user has selected an item in the grid
			var sm = hrisv2_force_leave.app.Grid.getSelectionModel();
			var id = sm.getSelected().data.id;
			Ext.Msg.show({
   			title:'Delete',
  			msg: 'Are you sure you want to delete this record?',
   			buttons: Ext.Msg.OKCANCEL,
   			fn: function(btn, text){
   			if (btn == 'ok'){

   			Ext.Ajax.request({
							url: "<?=site_url("admin/deleteNotification")?>",
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
							hrisv2_force_leave.app.Grid.getStore().load({params:{start:0, limit: 25}});

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
                callLogTypeCombo: function(){

		return {
			xtype:'combo',
			id:'call_log_type',
			hiddenName: 'call_log_type_id',
                        hiddenId: 'call_log_type_id',
			name: 'call_log_type',
			valueField: 'id',
			displayField: 'name',
			//width: 100,
			anchor: '93%',
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
			url: "<?php echo site_url("admin/callLogTypeCombo"); ?>",
			baseParams: {start: 0, limit: 10}

			}),
			listeners: {
			select: function (combo, record, index){

                        Ext.get(this.hiddenName).dom.value  = record.get('id');
			this.setRawValue(record.get('name'));
			//Ext.getCmp(this.id).setValue(record.get('name'));

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
			fieldLabel: 'Call Log Type'

			}
	},
        employeeCombo: function(){

		return {
			xtype:'combo',
			id:'employee_combo',
			hiddenName: 'employee_id',
                        hiddenId: 'employee_id',
			name: 'employee_combo',
			valueField: 'id',
			displayField: 'name',
			//width: 100,
			anchor: '93%',
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
			url: "<?php echo site_url("admin/employeeCombo"); ?>",
			baseParams: {start: 0, limit: 10}

			}),
			listeners: {
			select: function (combo, record, index){

                        Ext.get(this.hiddenName).dom.value  = record.get('id');
			this.setRawValue(record.get('name'));
			//Ext.getCmp(this.id).setValue(record.get('name'));

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
			fieldLabel: 'Employee'

			}
	},
        setNoOfDays: function(){

                        var portion = Ext.getCmp("leave_portion").getValue();

                        if(portion == 'FIRST HALF' || portion == 'SECOND HALF'){
                            hrisv2_force_leave.app.setNoOfDaysByPortion();
                            return;
                        }

			obj 	 = Ext.getCmp('no_days');
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
					dispvalue = (ddate1.getTime() > ddate2.getTime() ? "-"+(hrisv2_force_leave.app.round(totaldays)+1) : (hrisv2_force_leave.app.round(totaldays)+1));
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
				Ext.getCmp('no_days').setValue('0.5');
				Ext.getCmp("date_to").setValue(Ext.getCmp("date_from").getValue());
			}
			else
				hrisv2_force_leave.app.setNoOfDays();
		},
                leaveFormat: function(val){

			var fmtVal;

			switch(val){
				case "1"	: 	fmtVal = '<span style="color: blue; font-weight: bold;">Yes</span>'; break;
			 	case "0"	:  	fmtVal = '<span style="color: red; font-weight: bold;">No</span>'; break;

			}

			return fmtVal;
		},
		setNoOfDaysByPortion: function(){

			if(Ext.getCmp("leave_portion").getValue() != "WHOLE DAY")
			{
				Ext.getCmp('no_of_days').setValue('0.5');
				Ext.getCmp("date_to").setValue(Ext.getCmp("date_from").getValue());
			}
			else
				hrisv2_force_leave.app.setNoOfDays();
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
                if(ExtCommon.util.validateSelectionGrid(hrisv2_force_leave.app.Grid.getId())){//check if user has selected an item in the grid
			var sm = hrisv2_force_leave.app.Grid.getSelectionModel();

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
			anchor: '93%',
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
			url: "<?php echo site_url("admin/leaveTypeCombo"); ?>",
			baseParams: {start: 0, limit: 10}

			}),
			listeners: {
			beforequery: function(){
			
			},
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
			this.el.set({qtip: 'Type at least ' + this.minChars + ' characters to search for a school'});

			},
			keypress: {buffer: 100, fn: function() {
			Ext.get(this.hiddenName).dom.value  = '';
			if(!this.getRawValue()){
			this.doQuery('', true);
			}
			}}
			},
			fieldLabel: 'Leave Type*'

			}
	}//end of functions
 	}

 }();

 Ext.onReady(hrisv2_force_leave.app.init, hrisv2_force_leave.app);

</script>

<div id="mainBody">
</div>
