 <script type="text/javascript">
 Ext.namespace("suspension");
 suspension.app = function()
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
 							url: "<?php echo site_url("user/getSuspension")?>",
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
                                                                                        { name: "date_from"},
                                                                                        { name: "date_to"},
                                                                                        { name: "no_days"},
                                                                                        { name: "date_requested"},
                                                                                        { name: "reason"},
                                                                                        { name: "requested_by"}
 										]
 						}),
 						remoteSort: true,
 						baseParams: {start: 0, limit: 25}
 					});


 			var Grid = new Ext.grid.GridPanel({
 				id: 'suspensiongrid',
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
                                                  { header: "No. of days", dataIndex: "no_days", width: 100, sortable: true},
                                                  { header: "Reason", dataIndex: "reason", width: 150, sortable: true},
                                                  { header: "Filed by", dataIndex: "requested_by", width: 150, sortable: true}
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
 					 	},{
 					     	xtype: 'tbbutton',
 					     	text: 'VIEW',
							icon: '/images/icons/application_form_magnify.png',
 							cls:'x-btn-text-icon',

 					     	handler: suspension.app.suspensionEdit

 					 	}
 	    			 ]
 	    	});

 			suspension.app.Grid = Grid;
 			suspension.app.Grid.getStore().load({params:{start: 0, limit: 25}});

var _window = new Ext.Panel({
 		        title: 'Suspension',
 		        width: '100%',
 		        height:450,
 		        renderTo: 'mainBody',
 		        draggable: false,
 		        layout: 'fit',
 		        items: [suspension.app.Grid],
 		        resizable: false

 			    /*listeners : {
 				    	  close: function(p){
 					    	  window.location="../"
 					      }
 			       	} */
 	        }).render();


 		},
 			suspensionSetForm: function(){
 		    var form = new Ext.form.FormPanel({
 		        labelWidth: 100,
 		        url:"<?=site_url("admin/addSuspension")?>",
 		        method: 'POST',
 		        defaultType: 'textfield',
 		        frame: true,

 		        items: [ {
 					xtype:'fieldset',
 					title:'Suspension details',
 					width:'100%',
 					height:'auto',
 					items:[
                                            {
                                                            xtype: 'datefield',
                                                            name: 'date_from',
                                                            id: 'date_from',
                                                            format: 'Y-m-d',
                                                            fieldLabel: 'Date From',
                                                            allowBlank: false,
                                                            readOnly: true,
                                                            anchor: '93%',
                                                            vtype: 'daterange',
                                                            endDateField: 'date_to',
                                                            listeners:{
                                                                change: function(){
				                		suspension.app.setNoOfDays();
                                                            },
                                                                blur: function(){
					                  	suspension.app.setNoOfDays();
                                                            }
                                                            }
                                                       },

                                                      {
                                                            xtype: 'datefield',
                                                            name: 'date_to',
                                                            id: 'date_to',
                                                            format: 'Y-m-d',
                                                            readOnly: true,
                                                            fieldLabel: 'Date To',
                                                            allowBlank: false,
                                                            anchor: '93%',
                                                            vtype: 'daterange',
                                                            startDateField: 'date_from',
                                                            listeners:{
                                                                change: function(){
				                		suspension.app.setNoOfDays();
                                                            },
                                                                blur: function(){
					                  	suspension.app.setNoOfDays();
                                                            }
                                                            }
                                                       },
                                                       new Ext.form.ComboBox({
                                                            fieldLabel: 'Portion',
                                                            hiddenName:'portion',
                                                            id: 'leave_portion',
                                                            name: 'portion_name',
                                                            allowBlank: false,
                                                            readOnly: true,
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
                                                                    anchor:'93%',
                                                                    listeners:{
                                                                        change: function(){
                                                                        suspension.app.setNoOfDaysByPortion();
                                                                    },
                                                                        blur: function(){
                                                                        suspension.app.setNoOfDaysByPortion();
                                                                    }
                                                                    }

                                                        }),
		               		{ xtype: 'textfield',
                                          name: 'no_days',
                                          id: 'no_days',
                                          anchor:'93%',
                                          readOnly: true,
                                          fieldLabel: 'No. of Days',
                                          readOnly: true,
                                          allowBlank: true
                                        },
					{ xtype: 'textarea',
                                          id: 'txtreason',
                                          name: 'reason',
                                          anchor:'93%',
                                          readOnly: true,
                                          fieldLabel: 'Reason',
                                          allowBlank: false,
                                          maxLength: '128'
                                        }

 		        ]
 					}
 		        ]
 		    });

 		    suspension.app.Form = form;
 		},
 		suspensionAdd: function(){

 			suspension.app.suspensionSetForm();

 		  	var _window;

 		    _window = new Ext.Window({
 		        title: 'New Suspension',
 		        width: 450,
 		        height:330,
 		        layout: 'fit',
 		        plain:true,
 		        modal: true,
 		        bodyStyle:'padding:5px;',
 		        buttonAlign:'center',
 		        items: suspension.app.Form,
 		        buttons: [{
 		         	text: 'Save',
                                icon: '/images/icons/disk.png',  cls:'x-btn-text-icon',

 	                handler: function () {
 			            if(ExtCommon.util.validateFormFields(suspension.app.Form)){//check if all forms are filled up

 		                suspension.app.Form.getForm().submit({
 			                success: function(f,action){
                 		    	Ext.MessageBox.alert('Status', action.result.data);
                  		    	 Ext.Msg.show({
  								     title: 'Status',
 								     msg: action.result.data,
  								     buttons: Ext.Msg.OK,
  								     icon: 'icon'
  								 });
 				                ExtCommon.util.refreshGrid(suspension.app.Grid.getId());
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
 		suspensionEdit: function(){


 			if(ExtCommon.util.validateSelectionGrid(suspension.app.Grid.getId())){//check if user has selected an item in the grid
 			var sm = suspension.app.Grid.getSelectionModel();
 			var id = sm.getSelected().data.id;

 			suspension.app.suspensionSetForm();
 		    _window = new Ext.Window({
 		        title: 'View Suspension',
 		        width: 450,
 		        height:300,
 		        layout: 'fit',
 		        plain:true,
 		        modal: true,
 		        bodyStyle:'padding:5px;',
 		        buttonAlign:'center',
 		        items: suspension.app.Form,
 		        buttons: [{
 		            text: 'Close',
                            icon: '/images/icons/cancel.png', cls:'x-btn-text-icon',

 		            handler: function(){
 			            _window.destroy();
 		            }
 		        }]
 		    });




 		  	suspension.app.Form.getForm().load({
 				url: "<?=site_url("admin/loadSuspension")?>",
 				method: 'POST',
 				params: {id: id},
 				timeout: 300000,
 				waitMsg:'Loading...',
 				success: function(form, action){
                                    Ext.getCmp('date_to').setValue("");

                                    _window.show();
                                
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
		suspensionDelete: function(){


			if(ExtCommon.util.validateSelectionGrid(suspension.app.Grid.getId())){//check if user has selected an item in the grid
			var sm = suspension.app.Grid.getSelectionModel();
			var id = sm.getSelected().data.id;
			Ext.Msg.show({
   			title:'Delete',
  			msg: 'Are you sure you want to delete this record?',
   			buttons: Ext.Msg.OKCANCEL,
   			fn: function(btn, text){
   			if (btn == 'ok'){

   			Ext.Ajax.request({
							url: "<?=site_url("admin/deleteSuspension")?>",
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
							suspension.app.Grid.getStore().load({params:{start:0, limit: 25}});

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
			id:'suspension_type',
			hiddenName: 'suspension_type_id',
                        hiddenId: 'suspension_type_id',
			name: 'suspension_type',
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
                            suspension.app.setNoOfDaysByPortion();
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
					dispvalue = (ddate1.getTime() > ddate2.getTime() ? "-"+(suspension.app.round(totaldays)+1) : (suspension.app.round(totaldays)+1));
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
				suspension.app.setNoOfDays();
		},
                leaveFormat: function(val){

			var fmtVal;

			switch(val){
				case "1"	: 	fmtVal = '<span style="color: blue; font-weight: bold;">Yes</span>'; break;
			 	case "0"	:  	fmtVal = '<span style="color: red; font-weight: bold;">No</span>'; break;

			}

			return fmtVal;
		}//end of functions
 	}

 }();

 Ext.onReady(suspension.app.init, suspension.app);

</script>

<div id="mainBody">
</div>
