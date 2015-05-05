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
 					items:[call_log.app.employeeCombo(),
                                            {
                                                            xtype: 'datefield',
                                                            name: 'date_from',
                                                            id: 'date_from',
                                                            format: 'Y-m-d',
                                                            fieldLabel: 'Date From',
                                                            allowBlank: false,
                                                            anchor: '93%',
                                                            vtype: 'daterange',
                                                            endDateField: 'date_to',
                                                            listeners:{
                                                                change: function(){
				                		call_log.app.setNoOfDays();
                                                            },
                                                                blur: function(){
					                  	call_log.app.setNoOfDays();
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
                                                            anchor: '93%',
                                                            vtype: 'daterange',
                                                            startDateField: 'date_from',
                                                            listeners:{
                                                                change: function(){
				                		call_log.app.setNoOfDays();
                                                            },
                                                                blur: function(){
					                  	call_log.app.setNoOfDays();
                                                            }
                                                            }
                                                       },
                                                       new Ext.form.ComboBox({
                                                            fieldLabel: 'Portion',
                                                            hiddenName:'portion',
                                                            id: 'leave_portion',
                                                            name: 'portion_name',
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


                                                                         , data: [['WHOLE DAY', 'WHOLE DAY'], ['FIRST HALF', 'FIRST HALF'], ['SECOND HALF', 'SECOND HALF']]

                                                                }),
                                                                    valueField:'myId',
                                                                    displayField:'myText',
                                                                    mode:'local',
                                                                    anchor:'93%',
                                                                    listeners:{
                                                                        change: function(){
                                                                        call_log.app.setNoOfDaysByPortion();
                                                                    },
                                                                        blur: function(){
                                                                        call_log.app.setNoOfDaysByPortion();
                                                                    }
                                                                    }

                                                        }),
		               		{ xtype: 'textfield',
                                          name: 'no_days',
                                          id: 'no_days',
                                          anchor:'93%',
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

 		    call_log.app.Form = form;
 		},
 		suspensionAdd: function(){

 			call_log.app.suspensionSetForm();

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
 		        items: call_log.app.Form,
 		        buttons: [{
 		         	text: 'Save',
                                icon: '/images/icons/disk.png',  cls:'x-btn-text-icon',

 	                handler: function () {
 			            if(ExtCommon.util.validateFormFields(call_log.app.Form)){//check if all forms are filled up

 		                call_log.app.Form.getForm().submit({
 			                success: function(f,action){
                 		    	Ext.MessageBox.alert('Status', action.result.data);
                  		    	 Ext.Msg.show({
  								     title: 'Status',
 								     msg: action.result.data,
  								     buttons: Ext.Msg.OK,
  								     icon: 'icon'
  								 });
 				                ExtCommon.util.refreshGrid(call_log.app.suspensionGrid.getId());
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


 			if(ExtCommon.util.validateSelectionGrid(call_log.app.suspensionGrid.getId())){//check if user has selected an item in the grid
 			var sm = call_log.app.suspensionGrid.getSelectionModel();
 			var id = sm.getSelected().data.id;

 			call_log.app.suspensionSetForm();
 		    _window = new Ext.Window({
 		        title: 'Update Suspension',
 		        width: 450,
 		        height:330,
 		        layout: 'fit',
 		        plain:true,
 		        modal: true,
 		        bodyStyle:'padding:5px;',
 		        buttonAlign:'center',
 		        items: call_log.app.Form,
 		        buttons: [{
 		         	text: 'Save',
                                icon: '/images/icons/disk.png',  cls:'x-btn-text-icon',

 		            handler: function () {
 			            if(ExtCommon.util.validateFormFields(call_log.app.Form)){//check if all forms are filled up
 		                call_log.app.Form.getForm().submit({
 			                url: "<?=site_url("admin/updateSuspension")?>",
 			                params: {id: id},
 			                method: 'POST',
 			                success: function(f,action){
                 		    	Ext.MessageBox.alert('Status', action.result.data);
 				                ExtCommon.util.refreshGrid(call_log.app.suspensionGrid.getId());
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




 		  	call_log.app.Form.getForm().load({
 				url: "<?=site_url("admin/loadSuspension")?>",
 				method: 'POST',
 				params: {id: id},
 				timeout: 300000,
 				waitMsg:'Loading...',
 				success: function(form, action){
                                    Ext.getCmp('date_to').setValue("");

                                    _window.show();
                                    Ext.getCmp('employee_combo').setRawValue(action.result.data.employee_name);
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


			if(ExtCommon.util.validateSelectionGrid(call_log.app.suspensionGrid.getId())){//check if user has selected an item in the grid
			var sm = call_log.app.suspensionGrid.getSelectionModel();
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
							call_log.app.suspensionGrid.getStore().load({params:{start:0, limit: 25}});

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


		}