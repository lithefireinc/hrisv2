holidaySetForm: function(){
 		    var form = new Ext.form.FormPanel({
 		        labelWidth: 100,
 		        url:"<?=site_url("admin/addHoliday")?>",
 		        method: 'POST',
 		        defaultType: 'textfield',
 		        frame: true,

 		        items: [ {
 					xtype:'fieldset',
 					title:'Holiday details',
 					width:'100%',
 					height:'auto',
 					items:[
                                            {

                            xtype:'textfield',
 		            fieldLabel: 'Holiday Name*',
                            autoCreate : {tag: "input", type: "text", size: "20", autocomplete: "off", maxlength: "47"},
 		            name: 'holiday_name',
 		            allowBlank:false,
 		            anchor:'93%',  // anchor width by percentage
 		            id: 'holiday_name'
 		        },
                                            {
                                                            xtype: 'datefield',
                                                            name: 'holiday_date',
                                                            id: 'holiday_date',
                                                            format: 'Y-m-d',
                                                            fieldLabel: 'Date',
                                                            allowBlank: false,
                                                            anchor: '93%'
                                                       },
                                                       new Ext.form.ComboBox({
                                                            fieldLabel: 'Type',
                                                            hiddenName:'type',
                                                            id: 'holiday_type',
                                                            name: 'holiday_type',
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


                                                                         , data: [['REGULAR', 'REGULAR'], ['SPECIAL', 'SPECIAL']]

                                                                }),
                                                                    valueField:'myId',
                                                                    displayField:'myText',
                                                                    mode:'local',
                                                                    anchor:'93%'

                                                        }),
					{ xtype: 'textarea',
                                          id: 'description',
                                          name: 'description',
                                          anchor:'93%',
                                          fieldLabel: 'Description',
                                          allowBlank: false,
                                          maxLength: '128'
                                        }

 		        ]
 					}
 		        ]
 		    });

 		    admin_setup.app.Form = form;
 		},
 		holidayAdd: function(){

 			admin_setup.app.holidaySetForm();

 		  	var _window;

 		    _window = new Ext.Window({
 		        title: 'New Holiday',
 		        width: 450,
 		        height:280,
 		        layout: 'fit',
 		        plain:true,
 		        modal: true,
 		        bodyStyle:'padding:5px;',
 		        buttonAlign:'center',
 		        items: admin_setup.app.Form,
 		        buttons: [{
 		         	text: 'Save',
                                icon: '/images/icons/disk.png',  cls:'x-btn-text-icon',

 	                handler: function () {
 			            if(ExtCommon.util.validateFormFields(admin_setup.app.Form)){//check if all forms are filled up

 		                admin_setup.app.Form.getForm().submit({
 			                success: function(f,action){
                 		    	Ext.MessageBox.alert('Status', action.result.data);
                  		    	 Ext.Msg.show({
  								     title: 'Status',
 								     msg: action.result.data,
  								     buttons: Ext.Msg.OK,
  								     icon: 'icon'
  								 });
 				                ExtCommon.util.refreshGrid(admin_setup.app.holidayGrid.getId());
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
 		holidayEdit: function(){


 			if(ExtCommon.util.validateSelectionGrid(admin_setup.app.holidayGrid.getId())){//check if user has selected an item in the grid
 			var sm = admin_setup.app.holidayGrid.getSelectionModel();
 			var id = sm.getSelected().data.id;

 			admin_setup.app.holidaySetForm();
 		    _window = new Ext.Window({
 		        title: 'Update Holiday',
 		        width: 450,
 		        height:280,
 		        layout: 'fit',
 		        plain:true,
 		        modal: true,
 		        bodyStyle:'padding:5px;',
 		        buttonAlign:'center',
 		        items: admin_setup.app.Form,
 		        buttons: [{
 		         	text: 'Save',
                                icon: '/images/icons/disk.png',  cls:'x-btn-text-icon',

 		            handler: function () {
 			            if(ExtCommon.util.validateFormFields(admin_setup.app.Form)){//check if all forms are filled up
 		                admin_setup.app.Form.getForm().submit({
 			                url: "<?=site_url("admin/updateHoliday")?>",
 			                params: {id: id},
 			                method: 'POST',
 			                success: function(f,action){
                 		    	Ext.MessageBox.alert('Status', action.result.data);
 				                ExtCommon.util.refreshGrid(admin_setup.app.holidayGrid.getId());
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




 		  	admin_setup.app.Form.getForm().load({
 				url: "<?=site_url("admin/loadHoliday")?>",
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
		holidayDelete: function(){


			if(ExtCommon.util.validateSelectionGrid(admin_setup.app.holidayGrid.getId())){//check if user has selected an item in the grid
			var sm = admin_setup.app.holidayGrid.getSelectionModel();
			var id = sm.getSelected().data.id;
			Ext.Msg.show({
   			title:'Delete',
  			msg: 'Are you sure you want to delete this record?',
   			buttons: Ext.Msg.OKCANCEL,
   			fn: function(btn, text){
   			if (btn == 'ok'){

   			Ext.Ajax.request({
							url: "<?=site_url("admin/deleteHoliday")?>",
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
							admin_setup.app.holidayGrid.getStore().load({params:{start:0, limit: 25}});

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