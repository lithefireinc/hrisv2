 <script type="text/javascript">
 Ext.namespace("hrisv2_company_setup");
 hrisv2_company_setup.app = function()
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
 							url: "<?php echo site_url("admin/getCompanySetup")?>",
 							method: "POST"
 							}),
 						reader: new Ext.data.JsonReader({
 								root: "data",
 								id: "id",
 								totalProperty: "totalCount",
 								fields: [
 											{ name: "id"},
                                                                                        
                                                                                        { name: "time_in"},
                                                                                        { name: "time_out"},
                                                                                        { name: "sick_leave_grace_period"},
                                                                                        { name: "vacation_leave_grace_period"},
                                                                                        { name: "vl_limit"},
                                                                                        { name: "sl_limit"},
                                                                                        { name: "el_limit"},
                                                                                        { name: "ml_limit"},
                                                                                        { name: "pl_limit"}
 										]
 						}),
 						remoteSort: true,
 						baseParams: {start: 0, limit: 25}
 					});
			var fm = Ext.form;

 			var grid = new Ext.grid.EditorGridPanel({
 				id: 'hrisv2_company_setupgrid',
 				height: 422,
 				width: '100%',
 				clicksToEdit: 1,
 				border: true,
 				ds: Objstore,
 				cm:  new Ext.grid.ColumnModel(
 						[
                                                  { header: "Employee Time-In", dataIndex: "time_in", width: 150, sortable: true, editor: new fm.TimeField({
                                                           
                                                            name: 'time_in',
                                                            id: 'time_in',
                                                            allowBlank: false,
                                                            minValue: '00:00:00',
                                                            maxValue: '23:00:00',
                                                            //value: '08:00:00',
                                                            increment: 30,
                                                            format: 'H:i:s',
                                                            anchor: '90%'
                                                         //   vtype: 'timerange',
                                                          //  endTimeField: 'time_out'
                                                        })},
                                                 /* { header: "Employee Time-Out", dataIndex: "time_out", width: 150, sortable: true, editor: new fm.TimeField({
                                                           
                                                           
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
                                                        })},*/
                                                  { header: "SL Grace Period", dataIndex: "sick_leave_grace_period", width: 100, sortable: true, editor: new fm.NumberField({
                              allowBlank: false
                          })},
                                                  { header: "VL Grace Period", dataIndex: "vacation_leave_grace_period", width: 100, sortable: true, editor: new fm.NumberField({
                              allowBlank: false
                          })},
                          { header: "VL Limit", dataIndex: "vl_limit", width: 100, sortable: true, editor: new fm.NumberField({
                              allowBlank: false
                          })},
                          { header: "SL Limit", dataIndex: "sl_limit", width: 100, sortable: true, editor: new fm.NumberField({
                              allowBlank: false
                          })},
                          { header: "EL Limit", dataIndex: "el_limit", width: 100, sortable: true, editor: new fm.NumberField({
                              allowBlank: false
                          })},
                          { header: "ML Limit", dataIndex: "ml_limit", width: 100, sortable: true, editor: new fm.NumberField({
                              allowBlank: false
                          })},
                          { header: "PL Limit", dataIndex: "pl_limit", width: 100, sortable: true, editor: new fm.NumberField({
                              allowBlank: false
                          })}]
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
 					     	text: 'SAVE',
							icon: '/images/icons/disk.png',
 							cls:'x-btn-text-icon',

 					     	handler: function(){
					 		var data = [];
					 		Ext.each(hrisv2_company_setup.app.Grid.getStore().getModifiedRecords(), function(record){
					 		    data.push(record.data);
					 		});
					 		hrisv2_company_setup.app.Grid.getStore().commitChanges();
					 		if(data.length >0){
					 		Ext.Ajax.request({
					 		    url: '<?php echo site_url('admin/updateCompanySetup')?>',
					 		    params: {data: Ext.encode(data)},
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
                                                                                                hrisv2_company_setup.app.Grid.getStore().load();

                                                                                                return;

                                                                                        }
					 		    },
					 		    failure: function(){

					 		    }
					 		});

					 		}else{}
						 	}

 					 	}
 	    			 ]
 	    	});

 			hrisv2_company_setup.app.Grid = grid;
 			hrisv2_company_setup.app.Grid.getStore().load({params:{start: 0, limit: 25}});


 			
var _window = new Ext.Panel({
 		        title: 'Company Setup',
 		        width: '100%',
 		        height:450,
 		        renderTo: 'mainBody',
 		        draggable: false,
 		        layout: 'fit',
 		        items: [hrisv2_company_setup.app.Grid],
 		        resizable: false

 			    /*listeners : {
 				    	  close: function(p){
 					    	  window.location="../"
 					      }
 			       	} */
 	        }).render();

/*
var tabs = new Ext.TabPanel({
		        renderTo: 'mainBody',
		        width:'100%',
		        activeTab: 0,
		        frame:true,
		        height: 450,
                       // layout: 'fit',
		        //defaults:{autoHeight: true},
		        items:[
		            {title: 'Leaves Setup', items: hrisv2_company_setup.app.Grid},
		            {title: 'Holidays', items: hrisv2_company_setup.app.holidayGrid}
                            //{title: 'Client Schedule', items: requests.app.clientScheduleGrid}
		        ]
		    }).render();*/


 		},
 			setForm: function(){
 				
 				var dateMenu = {
        text    : 'Choose Date',

        menu    : {
              xtype : 'datemenu'
       }
    };
 		    var form = new Ext.form.FormPanel({
 		        labelWidth: 100,
 		        url:"<?=site_url("admin/addEmployeeLeave")?>",
 		        method: 'POST',
 		        defaultType: 'textfield',
 		        frame: true,

 		        items: [ {
 					xtype:'fieldset',
 					title:'Employee Leave Setup',
 					width:'100%',
 					height:'auto',
 					items:[hrisv2_company_setup.app.employeeAllCombo(), 
 					new Ext.form.ComboBox({
                                                            fieldLabel: 'Year',
                                                            
                                                            id: 'year',
                                                            name: 'year',
                                                            allowBlank: false,
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


                                                                         , data: [<?php echo $input_string;?>]

                                                                }),
                                                                    valueField:'myId',
                                                                    displayField:'myText',
                                                                    mode:'local',
                                                                    anchor:'93%'

                                                        })

 		        ]
 					}
 		        ]
 		    });

 		    hrisv2_company_setup.app.Form = form;
 		},
 		Add: function(){

 			hrisv2_company_setup.app.setForm();

 		  	var _window;

 		    _window = new Ext.Window({
 		        title: 'New Employee Leave',
 		        width: 450,
 		        height:190,
 		        layout: 'fit',
 		        plain:true,
 		        modal: true,
 		        bodyStyle:'padding:5px;',
 		        buttonAlign:'center',
 		        items: hrisv2_company_setup.app.Form,
 		        buttons: [{
 		         	text: 'Save',
                                icon: '/images/icons/disk.png',  cls:'x-btn-text-icon',

 	                handler: function () {
 			            if(ExtCommon.util.validateFormFields(hrisv2_company_setup.app.Form)){//check if all forms are filled up

 		                hrisv2_company_setup.app.Form.getForm().submit({
 			                success: function(f,action){
                 		    	Ext.MessageBox.alert('Status', action.result.data);
                  		    	 Ext.Msg.show({
  								     title: 'Status',
 								     msg: action.result.data,
  								     buttons: Ext.Msg.OK,
  								     icon: 'icon'
  								 });
 				                ExtCommon.util.refreshGrid(hrisv2_company_setup.app.Grid.getId());
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
 		Edit: function(){


 			if(ExtCommon.util.validateSelectionGrid(hrisv2_company_setup.app.Grid.getId())){//check if user has selected an item in the grid
 			var sm = hrisv2_company_setup.app.Grid.getSelectionModel();
 			var id = sm.getSelected().data.id;

 			hrisv2_company_setup.app.setForm();
 		    _window = new Ext.Window({
 		        title: 'Update Call Log',
 		        width: 450,
 		        height:360,
 		        layout: 'fit',
 		        plain:true,
 		        modal: true,
 		        bodyStyle:'padding:5px;',
 		        buttonAlign:'center',
 		        items: hrisv2_company_setup.app.Form,
 		        buttons: [{
 		         	text: 'Save',
                                icon: '/images/icons/disk.png',  cls:'x-btn-text-icon',

 		            handler: function () {
 			            if(ExtCommon.util.validateFormFields(hrisv2_company_setup.app.Form)){//check if all forms are filled up
 		                hrisv2_company_setup.app.Form.getForm().submit({
 			                url: "<?=site_url("admin/updateCallLog")?>",
 			                params: {id: id},
 			                method: 'POST',
 			                success: function(f,action){
                 		    	Ext.MessageBox.alert('Status', action.result.data);
 				                ExtCommon.util.refreshGrid(hrisv2_company_setup.app.Grid.getId());
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




 		  	hrisv2_company_setup.app.Form.getForm().load({
 				url: "<?=site_url("admin/loadCL")?>",
 				method: 'POST',
 				params: {id: id},
 				timeout: 300000,
 				waitMsg:'Loading...',
 				success: function(form, action){
                                    Ext.getCmp('date_to').setValue("");

                                    _window.show();
                                    Ext.getCmp('employee_combo').setRawValue(action.result.data.employee_name);
                                    Ext.getCmp('hrisv2_company_setup_type').setRawValue(action.result.data.calllog);
                                    Ext.getCmp('date_to').setValue(action.result.data.date_to);

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
		Delete: function(){


			if(ExtCommon.util.validateSelectionGrid(hrisv2_company_setup.app.Grid.getId())){//check if user has selected an item in the grid
			var sm = hrisv2_company_setup.app.Grid.getSelectionModel();
			var id = sm.getSelected().data.id;
			Ext.Msg.show({
   			title:'Delete',
  			msg: 'Are you sure you want to delete this record?',
   			buttons: Ext.Msg.OKCANCEL,
   			fn: function(btn, text){
   			if (btn == 'ok'){

   			Ext.Ajax.request({
							url: "<?=site_url("admin/deleteCallLog")?>",
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
							hrisv2_company_setup.app.Grid.getStore().load({params:{start:0, limit: 25}});

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
			id:'hrisv2_company_setup_type',
			hiddenName: 'hrisv2_company_setup_type_id',
                        hiddenId: 'hrisv2_company_setup_type_id',
			name: 'hrisv2_company_setup_type',
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
                            hrisv2_company_setup.app.setNoOfDaysByPortion();
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
					dispvalue = (ddate1.getTime() > ddate2.getTime() ? "-"+(hrisv2_company_setup.app.round(totaldays)+1) : (hrisv2_company_setup.app.round(totaldays)+1));
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
				hrisv2_company_setup.app.setNoOfDays();
		},
                leaveFormat: function(val){

			var fmtVal;

			switch(val){
				case "1"	: 	fmtVal = '<span style="color: blue; font-weight: bold;">Yes</span>'; break;
			 	case "0"	:  	fmtVal = '<span style="color: red; font-weight: bold;">No</span>'; break;

			}

			return fmtVal;
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
			fieldLabel: 'Employee*'

			}
	}//end of functions
 	}

 }();

 Ext.onReady(hrisv2_company_setup.app.init, hrisv2_company_setup.app);

</script>

<div id="mainBody">
</div>
