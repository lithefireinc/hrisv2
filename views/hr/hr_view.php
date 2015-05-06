<script type="text/javascript">
 Ext.namespace("employee");
 employee.app = function()
 {
 	return{
 		init: function()
 		{
 			ExtCommon.util.init();
                        ExtCommon.util.validations();
 			ExtCommon.util.quickTips();
 			this.getGrid();
 		},
 		getGrid: function()
 		{
 			ExtCommon.util.renderSearchField('searchby');

 			var Objstore = new Ext.data.Store({
 						proxy: new Ext.data.HttpProxy({
 							url: "<?php echo site_url("hr/getEmployees"); ?>",
 							method: "POST"
 							}),
 						reader: new Ext.data.JsonReader({
 								root: "data",
 								id: "id",
 								totalProperty: "totalCount",
 								fields: [
 											{ name: "id"},
 											{ name: "firstname"},
                                                                                        { name: "middlename"},
                                                                                        { name: "lastname"},
                                                                                        { name: "gender"},
                                                                                        { name: "civil_status"},
                                                                                        { name: "email"},
                                                                                        { name: "birthdate"},
                                                                                        { name: "birth_place"},
                                                                                        { name: "address"},
                                                                                        { name: "provincial_address"}
 										]
 						}),
 						remoteSort: true,
 						baseParams: {start: 0, limit: 25}
 					});


 			var grid = new Ext.grid.GridPanel({
 				id: 'employeegrid',
 				height: 'auto',
 				width: 'auto',
 				border: true,
 				ds: Objstore,
 				cm:  new Ext.grid.ColumnModel(
 						[
 						  { header: "Id", dataIndex: "id", width: 100, sortable: true},
                                                  { header: "First Name", width: 200, sortable: true, dataIndex: "firstname" },
                                                  { header: "Middle Name", width: 200, sortable: true, dataIndex: "middlename" },
 						  { header: "Last Name", width: 200, sortable: true, dataIndex: "lastname" },
                                                  
                                                  { header: "Gender", width: 75, sortable: true, dataIndex: "gender" },
                                                  { header: "Civil Status", width: 100, sortable: true, dataIndex: "civil_status" },
                                                  { header: "Email", width: 100, sortable: true, dataIndex: "email" },
                                                  { header: "Date of Birth", width: 200, sortable: true, dataIndex: "birthdate" },
                                                  { header: "Place of Birth", width: 200, sortable: true, dataIndex: "birth_place" },
                                                  { header: "Address", width: 200, sortable: true, dataIndex: "address" },
                                                  { header: "Provincial Address", width: 200, sortable: true, dataIndex: "provincial_address" }
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
 					     	text: 'ADD',
							icon: '/images/icons/user_add.png',
 							cls:'x-btn-text-icon',

 					     	handler: employee.app.Add

 					 	},'-',{
 					     	xtype: 'tbbutton',
 					     	text: 'EDIT',
							icon: '/images/icons/user_edit.png',
 							cls:'x-btn-text-icon',

 					     	handler: employee.app.Edit

 					 	}, '-',{
 					     	xtype: 'tbbutton',
 					     	text: 'DELETE',
							icon: '/images/icons/user_delete.png',
 							cls:'x-btn-text-icon',

 					     	handler: employee.app.Delete

 					 	}
 	    			 ],
                                 listeners: {
			rowdblclick: function(grid, row, e){
                            //employee.app.Edit();
                        }
                        }
 	    	});

 			employee.app.Grid = grid;
 			employee.app.Grid.getStore().load({params:{start: 0, limit: 25}});

 			//var msgbx = Ext.MessageBox.wait("Redirecting to main page. . .","Status");


 			var _window = new Ext.Panel({
 		        title: 'Employee Information',
 		        width: '100%',
 		        height:450,
 		        renderTo: 'mainBody',
 		        draggable: false,
 		        layout: 'fit',
 		        items: [employee.app.Grid],
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

                       ExtCommon.util.renderSearchField('searchbyeducation');


                        var educationStore = new Ext.data.Store({
 						proxy: new Ext.data.HttpProxy({
 							url: "<?php echo site_url("hr/getEducation"); ?>",
 							method: "POST"
 							}),
 						reader: new Ext.data.JsonReader({
 								root: "data",
 								//id: "id",
 								totalProperty: "totalCount",
 								fields: [
 											{ name: "id"},
 											{ name: "school"},
                                                                                        { name: "course"}
 										]
 						}),
 						remoteSort: true,
 						baseParams: {start: 0, limit: 25}
 					});


                        var educationGrid = new Ext.grid.GridPanel({
 				id: 'educationGrid',
 				height: '258',
 				width: 'auto',
 				border: true,
 				ds: educationStore,
 				cm:  new Ext.grid.ColumnModel(
 						[
 						  
                                                  { header: "School", width: 300, sortable: true, dataIndex: "school" },
                                                  { header: "Course", width: 300, sortable: true, dataIndex: "course" }
 						]
 				),
 				sm: new Ext.grid.RowSelectionModel({singleSelect:true}),
 	        	loadMask: true,
 	        	bbar:
 	        		new Ext.PagingToolbar({
 		        		autoShow: true,
 				        pageSize: 25,
 				        store: educationStore,
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

 					     	handler: employee.app.educationAdd

 					 	},'-',{
 					     	xtype: 'tbbutton',
 					     	text: 'DELETE',
							icon: '/images/icons/application_delete.png',
 							cls:'x-btn-text-icon',

 					     	handler: employee.app.educationDelete

 					 	}
 	    			 ],
                                 listeners: {
			rowdblclick: function(grid, row, e){
                            employee.app.educationEdit();
                        }
                        }
 	    	});

                employee.app.educationGrid = educationGrid;

                var employmentStore = new Ext.data.Store({
 						proxy: new Ext.data.HttpProxy({
 							url: "<?php echo site_url("hr/getEmployment"); ?>",
 							method: "POST"
 							}),
 						reader: new Ext.data.JsonReader({
 								root: "data",
 								//id: "id",
 								totalProperty: "totalCount",
 								fields: [
 											{ name: "id"},
 											{ name: "company"},
                                                                                        { name: "position"},
                                                                                        { name: "date_start"},
                                                                                        { name: "date_end"}
 										]
 						}),
 						remoteSort: true,
 						baseParams: {start: 0, limit: 25}
 					});


                        var employmentGrid = new Ext.grid.GridPanel({
 				id: 'employmentGrid',
 				height: '258',
 				width: 'auto',
 				border: true,
 				ds: employmentStore,
 				cm:  new Ext.grid.ColumnModel(
 						[
 						  
                                                  { header: "Company", width: 200, sortable: true, dataIndex: "company" },
                                                  { header: "Position", width: 200, sortable: true, dataIndex: "position" },
                                                  { header: "Date Start", width: 150, sortable: true, dataIndex: "date_start" },
                                                  { header: "Date End", width: 150, sortable: true, dataIndex: "date_end" }
 						]
 				),
 				sm: new Ext.grid.RowSelectionModel({singleSelect:true}),
 	        	loadMask: true,
 	        	bbar:
 	        		new Ext.PagingToolbar({
 		        		autoShow: true,
 				        pageSize: 25,
 				        store: employmentStore,
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

 					     	handler: employee.app.employmentAdd

 					 	},'-',{
 					     	xtype: 'tbbutton',
 					     	text: 'DELETE',
							icon: '/images/icons/application_delete.png',
 							cls:'x-btn-text-icon',

 					     	handler: employee.app.employmentDelete

 					 	}
 	    			 ],
                                 listeners: {
			rowdblclick: function(grid, row, e){
                            employee.app.employmentEdit();
                        }
                        }
 	    	});

                employee.app.employmentGrid = employmentGrid;

                var trainingStore = new Ext.data.Store({
 						proxy: new Ext.data.HttpProxy({
 							url: "<?php echo site_url("hr/getTraining"); ?>",
 							method: "POST"
 							}),
 						reader: new Ext.data.JsonReader({
 								root: "data",
 								//id: "id",
 								totalProperty: "totalCount",
 								fields: [
 											{ name: "id"},
 											{ name: "title"},
                                                                                        { name: "location"},
                                                                                        { name: "details"},
                                                                                        { name: "date_start"},
                                                                                        { name: "date_end"}
 										]
 						}),
 						remoteSort: true,
 						baseParams: {start: 0, limit: 25}
 					});


                        var trainingGrid = new Ext.grid.GridPanel({
 				id: 'trainingGrid',
 				height: '258',
 				width: 'auto',
 				border: true,
 				ds: trainingStore,
 				cm:  new Ext.grid.ColumnModel(
 						[

                                                  { header: "Title", width: 200, sortable: true, dataIndex: "title" },
                                                  { header: "Location", width: 200, sortable: true, dataIndex: "location" },
                                                  { header: "Date Start", width: 150, sortable: true, dataIndex: "date_start" },
                                                  { header: "Date End", width: 150, sortable: true, dataIndex: "date_end" }
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
 				tbar: [
 					    {
 					     	xtype: 'tbfill'
 					 	},{
 					     	xtype: 'tbbutton',
 					     	text: 'ADD',
							icon: '/images/icons/application_add.png',
 							cls:'x-btn-text-icon',

 					     	handler: employee.app.trainingAdd

 					 	},'-',{
 					     	xtype: 'tbbutton',
 					     	text: 'DELETE',
							icon: '/images/icons/application_delete.png',
 							cls:'x-btn-text-icon',

 					     	handler: employee.app.trainingDelete

 					 	}
 	    			 ],
                                 listeners: {
			rowdblclick: function(grid, row, e){
                            employee.app.trainingEdit();
                        }
                        }
 	    	});

                employee.app.trainingGrid = trainingGrid;



 		    var form = new Ext.form.FormPanel({
 		        labelWidth: 90,
 		        url:"<?php echo site_url("hr/insertEmployee"); ?>",
 		        method: 'POST',
 		        defaultType: 'textfield',
 		        frame: true,
                       // autoScroll: true,
                       // width: 900,
 		        items: [ {
 					xtype:'fieldset',
 					title:'Employee Information',
 					width:'auto',
 					height:'auto',
                                        labelWidth: 100,
 					items:[
                                            {

                                                  xtype:'textfield',
                                                  fieldLabel: 'Employee ID',
                                                  labelWidth: 100,
                                                  name: 'employee_idno',

                                                  anchor:'20%',  // anchor width by percentage
	 	  	 	 		  id: 'employee_idno',
                                                  allowBlank: false

                                                  },
                                            {
			            layout:'column',
			            width: 'auto',
			            items: [
                                        {
	 	 			          columnWidth:.5,
	 	 			          layout: 'form',
                                                  
	 	 			          items: [{

                                                  xtype:'textfield',
                                                  fieldLabel: 'First Name*',
                                                  name: 'firstname',
	 	  	 	 		  allowBlank:false,
                                                  anchor:'90%',  // anchor width by percentage
	 	  	 	 		  id: 'firstname'

                                                  },
                                                  {
	 	  	 	 			 					xtype:'textfield',
	 	  	 	 			 		            fieldLabel: 'Last Name*',
	 	  	 	 			 		            name: 'lastname',
	 	  	 	 			 		            allowBlank:false,
	 	  	 	 			 		            anchor:'90%',  // anchor width by percentage
	 	  	 	 			 		            id: 'lastname'
	 	  	 	 		}
                                              ]
                                              },
                                              {
	 	 			          columnWidth:.5,
	 	 			          layout: 'form',
                                                  labelWidth: 90,
	 	 			          items: [{
	 	  	 	 			 					xtype:'textfield',
	 	  	 	 			 		            fieldLabel: 'Middle Name*',
	 	  	 	 			 		            name: 'middlename',
	 	  	 	 			 		            allowBlank:false,
	 	  	 	 			 		            anchor:'85%',  // anchor width by percentage
	 	  	 	 			 		            id: 'middlename'
	 	  	 	 			 		        },
                                                                                {
	 	  	 	 			 					xtype:'textfield',
	 	  	 	 			 		            fieldLabel: 'Email',
	 	  	 	 			 		            name: 'email',
	 	  	 	 			 		           // allowBlank:false,
	 	  	 	 			 		            anchor:'85%',  // anchor width by percentage
	 	  	 	 			 		            id: 'email',
                                                                                    vtype: 'email'
	 	  	 	 			 		        }
                                              ]
                                              }
                                    ]
                                }
                           

 		        ]
 					},
                                        new Ext.TabPanel({

		        width:'auto',
		        activeTab: 0,
		        frame:true,
		        height: 285,
                        autoScroll: true,
		        //defaults:{autoHeight: true},
		        items:[
		            {title: 'Employee Profile', height: 'auto',frame: true, layout: 'form', autoScroll: true, items:[
                                    {xtype: 'fieldset',
                                     title: 'Personal Information',
                                     width:920,
 					height:'auto',
 					items:[


                                    {
			            layout:'column',
			            width: 'auto',
			            items: [
	 	 			         {
	 	 			          columnWidth:.515,
	 	 			          layout: 'form',
	 	 			          items: [
                                                      
                                                  new Ext.form.ComboBox(
	 		 			      				   {

	 		 	 			       	   	         store: new Ext.data.SimpleStore(
	 		 	 			       		            {
	 		 	 			       		               fields: ['field', 'value'],
	 		 	 			       		               data : [['M', 'Male'],['F', 'Female']]
	 		 	 			          		         }),
	 		 	 			       	   	         	valueField:'field',
	 		 	 			       		            displayField:'value',
                                                                                    fieldLabel: 'Gender*',
	 		 	 			          		    name: 'gender',
	 		 	 			       		            id: 'gender',
	 		 	 			       		            editable: false,
	 		 	 			       		            mode: 'local',
	 		 	 			       		            anchor: '91%',
	 		 	 			       		            triggerAction: 'all',
	 		 	 			          		    selectOnFocus: true,
                                                                                    allowBlank: false,
	 		 	 			       		            forceSelection:true,
	 		 	 			       		            tabIndex: 0,
	 		 	 			       		            listeners: {
                                                                                    select: function(combo, record, index){

                                                                                    }
	 		 	 			       		            }
	 		 	 			          		    }),
                                                                                    {xtype: 'datefield',
		 	 			        fieldLabel: 'Date of Birth*',
		 	 			        name: 'birthdate',
		 	 			        id: 'birthdate',
		 	 			        allowBlank: false,
		 	 			        format: 'Y-m-d',
		 	 			        value: new Date(),
		 	 			        anchor: '91%'

		 	 			      },
                                                      employee.app.citiCombo()




	 	 			   ]
	 	 	 			            },{
	 	 	 	 			          columnWidth:.485,
	 	 	 	 			          layout: 'form',
	 	 	 	 			          items: [
	 	 	 	 			        	  
                                                               new Ext.form.ComboBox(
	 		 			      				   {

	 		 	 			       	   	         store: new Ext.data.SimpleStore(
	 		 	 			       		            {
	 		 	 			       		               fields: ['field', 'value'],
	 		 	 			       		               data : [['Single', 'Single'],['Married', 'Married'], ['Separated', 'Separated'], ['Widowed', 'Widowed']]
	 		 	 			          		         }),
	 		 	 			       	   	         	valueField:'field',
	 		 	 			       		            displayField:'value',
                                                                                    fieldLabel: 'Civil Status*',
	 		 	 			          		    name: 'civil_status',
	 		 	 			       		            id: 'civil_status',
	 		 	 			       		            editable: false,
	 		 	 			       		            mode: 'local',
	 		 	 			       		            anchor: '92%',
                                                                                  
	 		 	 			       		            triggerAction: 'all',
	 		 	 			          		    selectOnFocus: true,
                                                                                    allowBlank: false,
	 		 	 			       		            forceSelection:true,
	 		 	 			       		            tabIndex: 0,
	 		 	 			       		            listeners: {
                                                                                    select: function(combo, record, index){

                                                                                    }
	 		 	 			       		            }
	 		 	 			          		    }),
                                                                                    {
	 	  	 	 			 					xtype:'textfield',
	 	  	 	 			 		            fieldLabel: 'Place of Birth*',
	 	  	 	 			 		            name: 'birth_place',
	 	  	 	 			 		            allowBlank:false,
	 	  	 	 			 		            anchor:'92%',  // anchor width by percentage
	 	  	 	 			 		            id: 'birth_place'
	 	  	 	 			 		        }



	 	 	 	 			             	]
	 	 	 	 	 			            }

					    ]
			   },
                           {
	 	  	 	 			 					xtype:'textarea',
	 	  	 	 			 		            fieldLabel: 'Address*',
	 	  	 	 			 		            name: 'address',
	 	  	 	 			 		            allowBlank:false,
	 	  	 	 			 		            anchor:'96%',  // anchor width by percentage
	 	  	 	 			 		            id: 'address'
	 	  	 	 			 		        },
                                                                                {
	 	  	 	 			 					xtype:'textarea',
	 	  	 	 			 		            fieldLabel: 'Provincial Address',
	 	  	 	 			 		            name: 'provincial_address',
	 	  	 	 			 		           // allowBlank:false,
	 	  	 	 			 		            anchor:'96%',  // anchor width by percentage
	 	  	 	 			 		            id: 'provincial_address'
	 	  	 	 			 		        }
                                                                                //put brace here
                                        ]
                            },{xtype: 'fieldset',
                            title: 'Contact Information',
                            width: 920,
                            height:'auto',
                            items:[{
			            layout:'column',
			            width: 'auto',
			            items: [
	 	 			         {
	 	 			          columnWidth:.515,
	 	 			          layout: 'form',
                                                  
	 	 			          items: [{
                                                                                    xtype:'textfield',
	 	  	 	 			 		            fieldLabel: 'Telephone Number',
	 	  	 	 			 		            name: 'telephone',
	 	  	 	 			 		            //allowBlank:false,
	 	  	 	 			 		            anchor:'91%',  // anchor width by percentage
	 	  	 	 			 		            id: 'telephone'
	 	  	 	 			 		        },{
                                                                                    xtype:'textfield',
	 	  	 	 			 		            fieldLabel: 'Father\'s Name*',
	 	  	 	 			 		            name: 'fathers_name',
	 	  	 	 			 		            allowBlank:false,
	 	  	 	 			 		            anchor:'91%',  // anchor width by percentage
	 	  	 	 			 		            id: 'fathers_name'
	 	  	 	 			 		        },
                                                          {
                                                                                    xtype:'textfield',
	 	  	 	 			 		            fieldLabel: 'Mother\'s Name*',
	 	  	 	 			 		            name: 'mothers_name',
	 	  	 	 			 		            allowBlank:false,
	 	  	 	 			 		            anchor:'91%',  // anchor width by percentage
	 	  	 	 			 		            id: 'mothers_name'
	 	  	 	 			 		        },
                                                          {
                                                                                    xtype:'textfield',
	 	  	 	 			 		            fieldLabel: 'Spouse\'s Name',
	 	  	 	 			 		            name: 'spouse_name',
	 	  	 	 			 		           // allowBlank:false,
	 	  	 	 			 		            anchor:'91%',  // anchor width by percentage
	 	  	 	 			 		            id: 'spouse_name'
	 	  	 	 			 		        },
                                                                                {
                                                                                    xtype:'textfield',
	 	  	 	 			 		            fieldLabel: 'Children\'s Name',
	 	  	 	 			 		            name: 'childrens_name',
	 	  	 	 			 		           // allowBlank:false,
	 	  	 	 			 		            anchor:'91%',  // anchor width by percentage
	 	  	 	 			 		            id: 'childrens_name'
	 	  	 	 			 		        }


                                                          ]
	 	 	 			            },{
	 	 	 	 			          columnWidth:.485,
	 	 	 	 			          layout: 'form',
                                                                  
	 	 	 	 			          items: [{
                                                                                    xtype:'textfield',
	 	  	 	 			 		            fieldLabel: 'Mobile Number',
	 	  	 	 			 		            name: 'mobile',
	 	  	 	 			 		           // allowBlank:false,
	 	  	 	 			 		            anchor:'92%',  // anchor width by percentage
	 	  	 	 			 		            id: 'mobile'
	 	  	 	 			 		        },


                                                                                {
	 	  	 	 			 			xtype:'textfield',
	 	  	 	 			 		        fieldLabel: 'Father\'s Occupation*',
	 	  	 	 			 		            name: 'fathers_occupation',
	 	  	 	 			 		            allowBlank:false,
	 	  	 	 			 		            anchor:'92%',  // anchor width by percentage
	 	  	 	 			 		            id: 'fathers_occupation'
	 	  	 	 			 		        },
                                                                               {
                                                                                    xtype:'textfield',
	 	  	 	 			 		            fieldLabel: 'Mother\'s Occupation*',
	 	  	 	 			 		            name: 'mothers_occupation',
	 	  	 	 			 		            allowBlank:false,
	 	  	 	 			 		            anchor:'92%',  // anchor width by percentage
	 	  	 	 			 		            id: 'mothers_occupation'
	 	  	 	 			 		        },
                                                                                {
                                                                                    xtype:'textfield',
	 	  	 	 			 		            fieldLabel: 'Spouse\'s Occupation',
	 	  	 	 			 		            name: 'spouse_occupation',
	 	  	 	 			 		           // allowBlank:false,
	 	  	 	 			 		            anchor:'92%',  // anchor width by percentage
	 	  	 	 			 		            id: 'spouse_occupation'
	 	  	 	 			 		        }



	 	 	 	 			             	]
	 	 	 	 	 			            }

					    ]
			   }]

                            },
                            {xtype: 'fieldset',
                            title: 'Employment Information',
                            width: 920,
                            height:'auto',
                            items:[{
			            layout:'column',
			            width: 'auto',
			            items: [
	 	 			         {
	 	 			          columnWidth:.515,
	 	 			          layout: 'form',
	 	 			          items: [employee.app.departmentCombo(), employee.app.employeeCategoryCombo(), {
                                                                                    xtype:'textfield',
	 	  	 	 			 		            fieldLabel: 'Salary',
	 	  	 	 			 		            name: 'salary',
	 	  	 	 			 		           // allowBlank:false,
	 	  	 	 			 		            anchor:'91%',  // anchor width by percentage
	 	  	 	 			 		            id: 'salary'
	 	  	 	 			 		        },
                                                     {
                                                     xtype:'textfield',
	 	  	 	 			 fieldLabel: 'TIN Number',
	 	  	 	 			name: 'tin',
	 	  	 	 			//allowBlank:false,
	 	  	 	 			 anchor:'91%',  // anchor width by percentage
	 	  	 	 			 id: 'tin'
	 	  	 	 			 		        },
                                                  {xtype: 'datefield',
		 	 			        fieldLabel: 'Date Resigned*',
		 	 			        name: 'date_resigned',
		 	 			        id: 'date_resigned',
		 	 			       // allowBlank: false,
		 	 			        format: 'Y-m-d',
		 	 			        //value: new Date(),
		 	 			        anchor: '91%'

	 	 			      },
	 	 			      {
                                                     xtype:'textfield',
	 	  	 	 			 fieldLabel: 'Biometrics Id',
	 	  	 	 			name: 'biometrics_id',
	 	  	 	 			//allowBlank:false,
	 	  	 	 			 anchor:'91%',  // anchor width by percentage
	 	  	 	 			 id: 'biometrics_id'
	 	  	 	 			 		        }

                                                  ]

                                                  },
                                                  {
	 	 			          columnWidth:.485,
	 	 			          layout: 'form',
	 	 			          items: [
                                                  employee.app.positionCombo(),
                                                  employee.app.employeeStatusCombo(),
                                                  {xtype: 'datefield',
		 	 			        fieldLabel: 'Date Hired*',
		 	 			        name: 'date_hired',
		 	 			        id: 'date_hired',
		 	 			        allowBlank: false,
		 	 			        format: 'Y-m-d',
		 	 			        //value: new Date(),
		 	 			        anchor: '92%'

		 	 			      },
                                                  {
                                                     xtype:'textfield',
	 	  	 	 			 fieldLabel: 'SSS Number',
	 	  	 	 			name: 'sss',
	 	  	 	 			//allowBlank:false,
	 	  	 	 			 anchor:'92%',  // anchor width by percentage
	 	  	 	 			 id: 'sss'
	 	  	 	 			 		        },
                                                  {
                                                     xtype:'textfield',
	 	  	 	 			 fieldLabel: 'Reason',
	 	  	 	 			name: 'reason',
	 	  	 	 			//allowBlank:false,
	 	  	 	 			 anchor:'92%',  // anchor width by percentage
	 	  	 	 			 id: 'reason'
	 	  	 	 			 		        }
                                                  ]

                                                  }


                                                  ]}


                                                  ]},
                                                  {xtype: 'fieldset',
                            title: 'Emergency Contact Information',
                            width: 920,
                            height:'auto',
                            layout: 'form',
                            labelWidth: 100,
                            items:[
                            {
                           xtype:'textfield',
	 	  	   fieldLabel: 'Name',
	 	  	   name: 'emergency_contact',
	 	  	   //allowBlank:false,
	 	  	   anchor:'96%',  // anchor width by percentage
	 	  	   id: 'emergency_contact'
	 	  	   },
                           {
                           xtype:'textfield',
	 	  	   fieldLabel: 'Telephone/Mobile Number',
	 	  	   name: 'emergency_phone',
	 	  	   //allowBlank:false,
	 	  	   anchor:'96%',  // anchor width by percentage
	 	  	   id: 'emergency_phone'
	 	  	   },
                           {
                           xtype:'textarea',
	 	  	   fieldLabel: 'Address',
	 	  	   name: 'emergency_address',
	 	  	   //allowBlank:false,
	 	  	   anchor:'96%',  // anchor width by percentage
	 	  	   id: 'emergency_address'
	 	  	   }
                            ]},
                            {xtype: 'fieldset',
                            title: 'User Access Information',
                            width: 920,
                            height:'auto',
                            layout: 'form',
                            labelWidth: 100,
                            items:[
                            

                           {
			            layout:'column',
			            width: 'auto',
			            items: [
	 	 			         {
	 	 			          columnWidth:.515,
	 	 			          layout: 'form',
	 	 			          items: [{
                           xtype:'textfield',
	 	  	   fieldLabel: 'User Name',
	 	  	   name: 'username',
                           //disabled: true,
	 	  	   allowBlank:false,
	 	  	   anchor:'91%',  // anchor width by percentage
	 	  	   id: 'username'
	 	  	   }

	 	 			   ]
	 	 	 			            },{
	 	 	 	 			          columnWidth:.485,
	 	 	 	 			          layout: 'form',
	 	 	 	 			          items: [/*{
                           xtype:'textfield',
	 	  	   fieldLabel: 'Password',
	 	  	   name: 'password',
                           disabled: true,
                           inputType: 'password',
	 	  	   //allowBlank:false,
	 	  	   anchor:'92%',  // anchor width by percentage
	 	  	   id: 'password'
	 	  	   }*/

	 	 	 	 			             	]
	 	 	 	 	 			            }

					    ]
			   }
                            ]
                            }
                                                                                ]},
		            {title: 'Educational Background', disabled: true, items: [employee.app.educationGrid], id: "educationTab"},
		           {title: 'Employment History', disabled: true, items: [employee.app.employmentGrid], id: "employmentTab"},
		          	{title: 'Trainings and Seminars', disabled: true, items: [employee.app.trainingGrid], id: "trainingTab"}
		        ]
		    })
 		        ]
 		    });

 		    employee.app.Form = form;
 		},
 		Add: function(){

 			employee.app.setForm();

 		  	var _window;

 		    _window = new Ext.Window({
 		        title: 'New Employee',
 		        width: 1000,
 		        height:500,
 		        layout: 'fit',
 		        plain:true,
 		        modal: true,
 		        bodyStyle:'padding:5px;',
 		        buttonAlign:'center',
 		        items: employee.app.Form,
 		        buttons: [{
 		         	text: 'Save',
                                icon: '/images/icons/disk.png',
 	                handler: function () {
 			            if(ExtCommon.util.validateFormFields(employee.app.Form)){//check if all forms are filled up

 		                employee.app.Form.getForm().submit({
 			                success: function(f,action){
                 		    	Ext.MessageBox.alert('Status', action.result.data);
                  		    	 Ext.Msg.show({
  								     title: 'Status',
 								     msg: action.result.data,
  								     buttons: Ext.Msg.OK,
  								     icon: Ext.Msg.INFO
  								 });
 				                ExtCommon.util.refreshGrid(employee.app.Grid.getId());
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
                            icon: '/images/icons/cancel.png',
 		            handler: function(){
 			            _window.destroy();
 		            }
 		        }]
 		    });
 		  	_window.show();
 		},
 		Edit: function(){


 			if(ExtCommon.util.validateSelectionGrid(employee.app.Grid.getId())){//check if user has selected an item in the grid
 			var sm = employee.app.Grid.getSelectionModel();
 			var id = sm.getSelected().data.id;

 			employee.app.setForm();
 		    _window = new Ext.Window({
 		        title: 'Update Employee',
 		        width: 1000,
 		        height:500,
 		        layout: 'fit',
 		        plain:true,
 		        modal: true,
 		        bodyStyle:'padding:5px;',
 		        buttonAlign:'center',
 		        items: employee.app.Form,
 		        buttons: [{
 		         	text: 'Save',
                                icon: '/images/icons/disk.png',
 		            handler: function () {
 			            if(ExtCommon.util.validateFormFields(employee.app.Form)){//check if all forms are filled up
 		                employee.app.Form.getForm().submit({
 			                url: "<?php echo site_url("hr/updateEmployee"); ?>",
 			                params: {id: id},
 			                method: 'POST',
 			                success: function(f,action){
                 		    	Ext.MessageBox.alert('Status', action.result.data);
 				                ExtCommon.util.refreshGrid(employee.app.Grid.getId());
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
                            icon: '/images/icons/cancel.png',
 		            handler: function(){
 			            _window.destroy();
 		            }
 		        }]
 		    });

                    employee.app.Form.form.load({
							url:"<?php echo site_url("hr/loadEmployee"); ?>",
							waitMsg:'Loading...',
                                                        params:{id: id},
							success: function(f, a){
                                                            Ext.getCmp('username').setReadOnly(true);
                                                           // Ext.getCmp('password').setReadOnly(true);
                                                            //Ext.getCmp('password').setValue("");
                                                            Ext.getCmp('educationTab').enable();
                                                            Ext.getCmp('employmentTab').enable();
                                                            Ext.getCmp('trainingTab').enable();
                                                            employee.app.educationGrid.getStore().setBaseParam('employee_id', id);
                                                            employee.app.educationGrid.getStore().load();
                                                            employee.app.employmentGrid.getStore().setBaseParam('employee_id', id);
                                                            employee.app.employmentGrid.getStore().load();
                                                            employee.app.trainingGrid.getStore().setBaseParam('employee_id', id);
                                                            employee.app.trainingGrid.getStore().load();
                                                            //alert(a.result.data.employee_status_description);
                                                            
                                                            _window.show();
                                                            Ext.get("CITIIDNO").dom.value = a.result.data.CITIIDNO;
                                                            Ext.getCmp("employee_status_id").setRawValue(a.result.data.employee_status_description);
                                                            Ext.getCmp("employee_category_id").setRawValue(a.result.data.employee_category_description);
                                                            Ext.getCmp("position_id").setRawValue(a.result.data.position_description);
                                                            Ext.getCmp("department_id").setRawValue(a.result.data.department_description);
							}

						});


 		  	/*Ext.Ajax.request({
				url: "<?php //echo site_url("main/loademployee"); ?>",
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
			});*/


 		  /*	employee.app.Form.getForm().load({
 				url: "functions/loademployee.php",
 				method: 'POST',
 				params: {id: id},
 				timeout: 300000,
 				waitMsg:'Loading...',
 				success: function(form, action){
 			 		//Ext.get('employee').dom.value  = action.result.data.employee;
 			 		//Ext.get('toId').dom.value  = action.result.data.ToId;
 			 		Ext.getCmp('employee').setValue(action.result.data.employee);
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


			if(ExtCommon.util.validateSelectionGrid(employee.app.Grid.getId())){//check if user has selected an item in the grid
			var sm = employee.app.Grid.getSelectionModel();
			var id = sm.getSelected().data.id;
			Ext.Msg.show({
   			title:'Delete',
  			msg: 'Are you sure you want to delete this record?',
   			buttons: Ext.Msg.OKCANCEL,
   			fn: function(btn, text){
   			if (btn == 'ok'){

   			Ext.Ajax.request({
							url: "<?php echo site_url("hr/deleteEmployee"); ?>",
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
							employee.app.Grid.getStore().load({params:{start:0, limit: 25}});

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
                employmentAdd: function(){

 			//employee.app.setForm();
                        var sm = employee.app.Grid.getSelectionModel();
			var id = sm.getSelected().data.id;

                        var form = new Ext.form.FormPanel({
		        labelWidth: 150,
		        url:"<?php echo site_url("hr/insertEmployment"); ?>",
		        method: 'POST',
		        width: 500,
		        height:300,
		        defaultType: 'textfield',
		        frame: true,
		        height: 'auto',

		        items: [ {
		        			fieldLabel: 'Company*',
		        			id: 'company',
		        			name: 'company',
		        			allowBlank: false,
		        			anchor: '95%'
		        },
                        {                       xtype: 'textarea',
		        			fieldLabel: 'Address*',
		        			id: 'company_address',
		        			name: 'company_address',
		        			allowBlank: false,
		        			anchor: '95%'
		        },
                        {
		        			fieldLabel: 'Position*',
		        			id: 'position',
		        			name: 'position',
		        			allowBlank: false,
		        			anchor: '95%'
		        },
                        {                       xtype: 'textarea',
		        			fieldLabel: 'Reason for Leaving',
		        			id: 'reason_for_leaving',
		        			name: 'reason_for_leaving',
		        			//allowBlank: false,
		        			anchor: '95%'
		        },
                         {                       xtype: 'datefield',
		 	 			fieldLabel: 'Date Start*',
		 	 			name: 'date_start',
		 	 			id: 'date_start',
		 	 			allowBlank: false,
		 	 			format: 'Y-m-d',
		 	 			//value: new Date(),
		 	 			anchor: '95%',
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
		 	 			anchor: '95%',
                                                vtype: 'daterange',
                                                startDateField: 'date_start'

		 	 			      }
		        ]
		    });

 		  	var _employmentWindow;

 		    _employmentWindow = new Ext.Window({
 		        title: 'New Employment History',
 		        width: 510,
 		        height:320,
 		        layout: 'fit',
 		        plain:true,
 		        modal: true,
 		        bodyStyle:'padding:5px;',
 		        buttonAlign:'center',
 		        items: form,
 		        buttons: [{
 		         	text: 'Save',
                                icon: '/images/icons/disk.png',
 	                handler: function () {
 			            if(ExtCommon.util.validateFormFields(form)){//check if all forms are filled up

 		                form.getForm().submit({
                                        params: {employee_id: id},
 			                success: function(f,action){
                 		    	Ext.MessageBox.alert('Status', action.result.data);
                  		    	 Ext.Msg.show({
  								     title: 'Status',
 								     msg: action.result.data,
  								     buttons: Ext.Msg.OK,
  								     icon: Ext.Msg.INFO
  								 });
 				                ExtCommon.util.refreshGrid(employee.app.employmentGrid.getId());
 				                _employmentWindow.destroy();
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
                            icon: '/images/icons/cancel.png',
 		            handler: function(){
 			            _employmentWindow.destroy();
 		            }
 		        }]
 		    });
 		  	_employmentWindow.show();
 		},
                employmentEdit: function(){


 			if(ExtCommon.util.validateSelectionGrid(employee.app.employmentGrid.getId())){//check if user has selected an item in the grid
 			var sm = employee.app.employmentGrid.getSelectionModel();
 			var id = sm.getSelected().data.id;


                        var form = new Ext.form.FormPanel({
		        labelWidth: 150,
		        url:"<?php echo site_url("hr/insertEmployment"); ?>",
		        method: 'POST',
		        width: 500,
		        height:300,
		        defaultType: 'textfield',
		        frame: true,
		        height: 'auto',

		        items: [ {
		        			fieldLabel: 'Company*',
		        			id: 'company',
		        			name: 'company',
		        			allowBlank: false,
		        			anchor: '95%'
		        },
                        {                       xtype: 'textarea',
		        			fieldLabel: 'Address*',
		        			id: 'company_address',
		        			name: 'company_address',
		        			allowBlank: false,
		        			anchor: '95%'
		        },
                        {
		        			fieldLabel: 'Position*',
		        			id: 'position',
		        			name: 'position',
		        			allowBlank: false,
		        			anchor: '95%'
		        },
                        {                       xtype: 'textarea',
		        			fieldLabel: 'Reason for Leaving',
		        			id: 'reason_for_leaving',
		        			name: 'reason_for_leaving',
		        			//allowBlank: false,
		        			anchor: '95%'
		        },
                        {                       xtype: 'datefield',
		 	 			fieldLabel: 'Date Start*',
		 	 			name: 'date_start',
		 	 			id: 'date_start',
		 	 			allowBlank: false,
		 	 			format: 'Y-m-d',
		 	 			//value: new Date(),
		 	 			anchor: '95%',
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
		 	 			anchor: '95%'

		 	 			      }
		        ]
		    });



                        var _employmentWindow = new Ext.Window({
 		        title: 'Update Employment History',
 		        width: 520,
 		        height:320,
 		        layout: 'fit',
 		        plain:true,
 		        modal: true,
 		        bodyStyle:'padding:5px;',
 		        buttonAlign:'center',
 		        items: form,
 		        buttons: [{
 		         	text: 'Save',
                                icon: '/images/icons/disk.png',
 		            handler: function () {
 			            if(ExtCommon.util.validateFormFields(form)){//check if all forms are filled up
 		               form.getForm().submit({
 			                url: "<?php echo site_url("hr/updateEmployment"); ?>",
 			                params: {id: id},
 			                method: 'POST',
 			                success: function(f,action){
                 		    	Ext.MessageBox.alert('Status', action.result.data);
 				                ExtCommon.util.refreshGrid(employee.app.employmentGrid.getId());
 				                _employmentWindow.destroy();
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
                            icon: '/images/icons/cancel.png',
 		            handler: function(){
 			            _employmentWindow.destroy();
 		            }
 		        }]
 		    });

                    form.form.load({
							url:"<?php echo site_url("hr/loadEmployment"); ?>",
							waitMsg:'Loading...',
                                                        params:{id: id},
							success: function(){
                                                           
                                                            _employmentWindow.show();
							}

						});



 			}else return;
 		},
                employmentDelete: function(){


			if(ExtCommon.util.validateSelectionGrid(employee.app.employmentGrid.getId())){//check if user has selected an item in the grid
			var sm = employee.app.employmentGrid.getSelectionModel();
			var id = sm.getSelected().data.id;
			Ext.Msg.show({
   			title:'Delete',
  			msg: 'Are you sure you want to delete this record?',
   			buttons: Ext.Msg.OKCANCEL,
   			fn: function(btn, text){
   			if (btn == 'ok'){

   			Ext.Ajax.request({
							url: "<?php echo site_url("hr/deleteEmployment"); ?>",
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
							employee.app.employmentGrid.getStore().load({params:{start:0, limit: 25}});

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

                educationAdd: function(){

 			//employee.app.setForm();
                        var sm = employee.app.Grid.getSelectionModel();
			var id = sm.getSelected().data.id;

                        var form = new Ext.form.FormPanel({
		        labelWidth: 150,
		        url:"<?php echo site_url("hr/insertEducation"); ?>",
		        method: 'POST',
		        width: 900,
		        height:'auto',
		        defaultType: 'textfield',
		        frame: true,
		        height: 'auto',

		        items: [ {fieldLabel: 'Course*', xtype: 'textfield', readOnly: true, id:'course_id', name: 'course_id', allowBlank: false, anchor: '95%'}, {xtype: 'button', style: { marginLeft: '155px', paddingBottom: '4px'}, text: 'Browse', handler: employee.app.selectCourse},
		       
                        {fieldLabel: 'School*', xtype: 'textfield', readOnly: true, id:'school_id', name: 'school_id', allowBlank: false, anchor: '95%'}, {xtype: 'button', style: { marginLeft: '155px', paddingBottom: '4px'}, text: 'Browse', handler: employee.app.selectSchool},
		        
                        {                       xtype: 'textarea',
		        			fieldLabel: 'Address*',
		        			id: 'school_address2',
		        			name: 'school_address2',
		        			allowBlank: false,
		        			anchor: '95%',
                                                readOnly: true
		        },
                        {                       xtype: 'datefield',
		 	 			fieldLabel: 'Date Start*',
		 	 			name: 'date_start',
		 	 			id: 'date_start',
		 	 			allowBlank: false,
		 	 			format: 'Y-m-d',
		 	 			//value: new Date(),
		 	 			anchor: '95%',
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
		 	 			anchor: '95%',
                                                vtype: 'daterange',
                                                startDateField: 'date_start'

		 	 			      }
		        ]
		    });

 		  	var _employmentWindow;

 		    _employmentWindow = new Ext.Window({
 		        title: 'New Educational Background',
 		        width:510,
 		        height:330,
 		        layout: 'fit',
 		        plain:true,
 		        modal: true,
 		        bodyStyle:'padding:5px;',
 		        buttonAlign:'center',
 		        items: form,
 		        buttons: [{
 		         	text: 'Save',
                                icon: '/images/icons/disk.png',
 	                handler: function () {
 			            if(ExtCommon.util.validateFormFields(form)){//check if all forms are filled up

 		                form.getForm().submit({
                                        params: {employee_id: id},
 			                success: function(f,action){
                 		    	Ext.MessageBox.alert('Status', action.result.data);
                  		    	 Ext.Msg.show({
  								     title: 'Status',
 								     msg: action.result.data,
  								     buttons: Ext.Msg.OK,
  								     icon: Ext.Msg.INFO
  								 });
 				                ExtCommon.util.refreshGrid(employee.app.educationGrid.getId());
 				                _employmentWindow.destroy();
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
                            icon: '/images/icons/cancel.png',
 		            handler: function(){
 			            _employmentWindow.destroy();
 		            }
 		        }]
 		    });
 		  	_employmentWindow.show();
 		},
               educationEdit: function(){


 			if(ExtCommon.util.validateSelectionGrid(employee.app.educationGrid.getId())){//check if user has selected an item in the grid
 			var sm = employee.app.educationGrid.getSelectionModel();
 			var id = sm.getSelected().data.id;


                        var form = new Ext.form.FormPanel({
		        labelWidth: 150,
		        url:"<?php echo site_url("hr/insertEducation"); ?>",
		        method: 'POST',
		        width: 900,
		        height:'auto',
		        defaultType: 'textfield',
		        frame: true,
		        height: 'auto',

		        items: [ {fieldLabel: 'Course*', xtype: 'textfield', anchor: '95%', readOnly: true, id:'course_id', name: 'course_id', allowBlank: false, width: 638}, {xtype: 'button', style: { marginLeft: '155px', paddingBottom: '4px'}, text: 'Browse', handler: employee.app.selectCourse},
		        
                        {fieldLabel: 'School*', xtype: 'textfield', anchor: '95%', readOnly: true, id:'school_id', name: 'school_id', allowBlank: false, width: 638}, {xtype: 'button', style: { marginLeft: '155px', paddingBottom: '4px'}, text: 'Browse', handler: employee.app.selectSchool},
		        
                        {                       xtype: 'textarea',
		        			fieldLabel: 'Address*',
		        			id: 'school_address2',
		        			name: 'school_address2',
		        			allowBlank: false,
		        			anchor: '95%',
                                                readOnly: true
		        },
                        {                       xtype: 'datefield',
		 	 			fieldLabel: 'Date Start*',
		 	 			name: 'date_start',
		 	 			id: 'date_start',
		 	 			allowBlank: false,
		 	 			format: 'Y-m-d',
		 	 			//value: new Date(),
		 	 			anchor: '95%',
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
		 	 			anchor: '95%'

		 	 			      }
		        ]
		    });



                        var _employmentWindow = new Ext.Window({
 		        title: 'Update Educational Background',
 		        width: 920,
 		        height:330,
 		        layout: 'fit',
 		        plain:true,
 		        modal: true,
 		        bodyStyle:'padding:5px;',
 		        buttonAlign:'center',
 		        items: form,
 		        buttons: [{
 		         	text: 'Save',
                                icon: '/images/icons/disk.png',
 		            handler: function () {
 			            if(ExtCommon.util.validateFormFields(form)){//check if all forms are filled up
 		               form.getForm().submit({
 			                url: "<?php echo site_url("hr/updateEducation"); ?>",
 			                params: {id: id},
 			                method: 'POST',
 			                success: function(f,action){
                 		    	Ext.MessageBox.alert('Status', action.result.data);
 				                ExtCommon.util.refreshGrid(employee.app.educationGrid.getId());
 				                _employmentWindow.destroy();
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
                            icon: '/images/icons/cancel.png',
 		            handler: function(){
 			            _employmentWindow.destroy();
 		            }
 		        }]
 		    });

                    form.form.load({
							url:"<?php echo site_url("hr/loadEducation"); ?>",
							waitMsg:'Loading...',
                                                        params:{id: id},
							success: function(form, action){
                                                           // alert(action.result.data.school_id);
                                                           Ext.getCmp('school_id').setValue(action.result.data.school_id);
                                                           Ext.getCmp('course_id').setValue(action.result.data.course_id);
                                                            _employmentWindow.show();
							}

						});



 			}else return;
 		},
                educationDelete: function(){


			if(ExtCommon.util.validateSelectionGrid(employee.app.educationGrid.getId())){//check if user has selected an item in the grid
			var sm = employee.app.educationGrid.getSelectionModel();
			var id = sm.getSelected().data.id;
			Ext.Msg.show({
   			title:'Delete',
  			msg: 'Are you sure you want to delete this record?',
   			buttons: Ext.Msg.OKCANCEL,
   			fn: function(btn, text){
   			if (btn == 'ok'){

   			Ext.Ajax.request({
							url: "<?php echo site_url("hr/deleteEducation"); ?>",
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
							employee.app.educationGrid.getStore().load({params:{start:0, limit: 25}});

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
                trainingAdd: function(){

 			//employee.app.setForm();
                        var sm = employee.app.Grid.getSelectionModel();
			var id = sm.getSelected().data.id;

                        var form = new Ext.form.FormPanel({
		        labelWidth: 150,
		        url:"<?php echo site_url("hr/insertTraining"); ?>",
		        method: 'POST',
		        width: 500,
		        height:300,
		        defaultType: 'textfield',
		        frame: true,
		        height: 'auto',

		        items: [ employee.app.trainingTypeCombo(),
                            employee.app.supplierCombo(),
                            {                       xtype: 'textarea',
    		        			fieldLabel: 'Location*',
    		        			id: 'location',
    		        			name: 'location',
    		        			allowBlank: false,
    		        			anchor: '95%'
    		        },
                            {
		        			fieldLabel: 'Title*',
		        			id: 'title',
		        			name: 'title',
		        			allowBlank: false,
		        			anchor: '95%'
		        },
                        
                        {                       xtype: 'textarea',
		        			fieldLabel: 'Details*',
		        			id: 'details',
		        			name: 'details',
		        			allowBlank: false,
		        			anchor: '95%'
		        },
                       
                        {                       xtype: 'datefield',
		 	 			fieldLabel: 'Date Start*',
		 	 			name: 'date_start',
		 	 			id: 'date_start',
		 	 			allowBlank: false,
		 	 			format: 'Y-m-d',
		 	 			//value: new Date(),
		 	 			anchor: '95%',
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
		 	 			anchor: '95%',
                                                vtype: 'daterange',
                                                startDateField: 'date_start'

		 	 			      },
                        {                       xtype: 'timefield',
		        			fieldLabel: 'Start Time*',
		        			id: 'start_time',
		        			name: 'start_time',
                                                minValue: '00:00',
					    	maxValue: '23:00',
					    	//value: '08:00:00',
					    	increment: 15,
						format: 'H:i',
		        			allowBlank: false,
		        			anchor: '95%',
		        			vtype: 'timerange',
		        			endTimeField: 'end_time'
		        },
                        {                       xtype: 'timefield',
		        			fieldLabel: 'End Time*',
		        			id: 'end_time',
		        			name: 'end_time',
                                                minValue: '00:00',
					    	maxValue: '23:00',
					    	//value: '08:00:00',
					    	increment: 15,
						format: 'H:i',
		        			allowBlank: false,
		        			anchor: '95%',
		        			vtype: 'timerange',
		        			startTimeField: 'start_time'
		        }

		        ]
		    });

 		  	var _employmentWindow;

 		    _employmentWindow = new Ext.Window({
 		        title: 'New Training',
 		        width: 510,
 		        height:400,
 		        layout: 'fit',
 		        plain:true,
 		        modal: true,
 		        bodyStyle:'padding:5px;',
 		        buttonAlign:'center',
 		        items: form,
 		        buttons: [{
 		         	text: 'Save',
                                icon: '/images/icons/disk.png',
 	                handler: function () {
 			            if(ExtCommon.util.validateFormFields(form)){//check if all forms are filled up

 		                form.getForm().submit({
                                        params: {employee_id: id},
 			                success: function(f,action){
                 		    	Ext.MessageBox.alert('Status', action.result.data);
                  		    	 Ext.Msg.show({
  								     title: 'Status',
 								     msg: action.result.data,
  								     buttons: Ext.Msg.OK,
  								     icon: Ext.Msg.INFO
  								 });
 				                ExtCommon.util.refreshGrid(employee.app.trainingGrid.getId());
 				                _employmentWindow.destroy();
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
                            icon: '/images/icons/cancel.png',
 		            handler: function(){
 			            _employmentWindow.destroy();
 		            }
 		        }]
 		    });
 		  	_employmentWindow.show();
 		},
                trainingEdit: function(){


 			if(ExtCommon.util.validateSelectionGrid(employee.app.trainingGrid.getId())){//check if user has selected an item in the grid
 			var sm = employee.app.trainingGrid.getSelectionModel();
 			var id = sm.getSelected().data.id;


                        var form = new Ext.form.FormPanel({
		        labelWidth: 150,
		        url:"<?php echo site_url("hr/insertTraining"); ?>",
		        method: 'POST',
		        width: 500,
		        height:300,
		        defaultType: 'textfield',
		        frame: true,
		        height: 'auto',

		        items: [ employee.app.trainingTypeCombo(),
                            employee.app.supplierCombo(),
                            {                       xtype: 'textarea',
		        			fieldLabel: 'Location*',
		        			id: 'location',
		        			name: 'location',
		        			allowBlank: false,
		        			anchor: '95%'
		        },
                            {
		        			fieldLabel: 'Title*',
		        			id: 'title',
		        			name: 'title',
		        			allowBlank: false,
		        			anchor: '95%'
		        },
                        
                        {                       xtype: 'textarea',
		        			fieldLabel: 'Details*',
		        			id: 'details',
		        			name: 'details',
		        			allowBlank: false,
		        			anchor: '95%'
		        },

                        {                       xtype: 'datefield',
		 	 			fieldLabel: 'Date Start*',
		 	 			name: 'date_start',
		 	 			id: 'date_start',
		 	 			allowBlank: false,
		 	 			format: 'Y-m-d',
		 	 			//value: new Date(),
		 	 			anchor: '95%',
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
		 	 			anchor: '95%'

		 	 			      },
                        {                       xtype: 'timefield',
		        			fieldLabel: 'Start Time*',
		        			id: 'start_time',
		        			name: 'start_time',
                                                minValue: '00:00:00',
					    	maxValue: '23:00:00',
					    	//value: '08:00:00',
					    	increment: 15,
						format: 'H:i:s',
		        			allowBlank: false,
		        			anchor: '95%'
		        },
                        {                       xtype: 'timefield',
		        			fieldLabel: 'End Time*',
		        			id: 'end_time',
		        			name: 'end_time',
                                                minValue: '00:00:00',
					    	maxValue: '23:00:00',
					    	//value: '08:00:00',
					    	increment: 15,
						format: 'H:i:s',
		        			allowBlank: false,
		        			anchor: '95%'
		        }

		        ]
		    });



                        var _employmentWindow = new Ext.Window({
 		        title: 'Update Training',
 		        width: 520,
 		        height:400,
 		        layout: 'fit',
 		        plain:true,
 		        modal: true,
 		        bodyStyle:'padding:5px;',
 		        buttonAlign:'center',
 		        items: form,
 		        buttons: [{
 		         	text: 'Save',
                                icon: '/images/icons/disk.png',
 		            handler: function () {
 			            if(ExtCommon.util.validateFormFields(form)){//check if all forms are filled up
 		               form.getForm().submit({
 			                url: "<?php echo site_url("hr/updateTraining"); ?>",
 			                params: {id: id},
 			                method: 'POST',
 			                success: function(f,action){
                 		    	Ext.MessageBox.alert('Status', action.result.data);
 				                ExtCommon.util.refreshGrid(employee.app.trainingGrid.getId());
 				                _employmentWindow.destroy();
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
                            icon: '/images/icons/cancel.png',
 		            handler: function(){
 			            _employmentWindow.destroy();
 		            }
 		        }]
 		    });

                    form.form.load({
							url:"<?php echo site_url("hr/loadTraining"); ?>",
							waitMsg:'Loading...',
                                                        params:{id: id},
							success: function(){

                                                            _employmentWindow.show();
							}

						});



 			}else return;
 		},
                trainingDelete: function(){


			if(ExtCommon.util.validateSelectionGrid(employee.app.trainingGrid.getId())){//check if user has selected an item in the grid
			var sm = employee.app.trainingGrid.getSelectionModel();
			var id = sm.getSelected().data.id;
			Ext.Msg.show({
   			title:'Delete',
  			msg: 'Are you sure you want to delete this record?',
   			buttons: Ext.Msg.OKCANCEL,
   			fn: function(btn, text){
   			if (btn == 'ok'){

   			Ext.Ajax.request({
							url: "<?php echo site_url("hr/deleteTraining"); ?>",
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
							employee.app.trainingGrid.getStore().load({params:{start:0, limit: 25}});

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
                selectCourse: function(){
                ExtCommon.util.renderSearchField('searchbyCourse');

 			var courseStore = new Ext.data.Store({
 						proxy: new Ext.data.HttpProxy({
 							url: "<?php echo site_url("hr/getCourses"); ?>",
 							method: "POST"
 							}),
 						reader: new Ext.data.JsonReader({
 								root: "data",
 								id: "id",
 								totalProperty: "totalCount",
 								fields: [
 											{ name: "id"},
 											{ name: "description"}
 										]
 						}),
 						remoteSort: true,
 						baseParams: {start: 0, limit: 25}
 					});


 			var grid = new Ext.grid.GridPanel({
 				id: 'coursegrid',
 				height: 250,
 				width: 500,
 				border: true,
 				ds:courseStore,
 				cm:  new Ext.grid.ColumnModel(
 						[
 						  { header: "Description", dataIndex: "description", width: 200, sortable: true}
 						]
 				),
 				sm: new Ext.grid.RowSelectionModel({singleSelect:true}),
 	        	loadMask: true,
 	        	bbar:
 	        		new Ext.PagingToolbar({
 		        		autoShow: true,
 				        pageSize: 25,
 				        store: courseStore,
 				        displayInfo: true,
 				        displayMsg: 'Displaying Results {0} - {1} of {2}',
 				        emptyMsg: "No Data Found."
 				    }),
 				tbar: [new Ext.form.ComboBox({
                    fieldLabel: 'Search',
                    hiddenName:'searchby-form',
                    id: 'searchbyCourse',
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
				},'   ', new Ext.app.SearchField({ store: courseStore, width:250}),
                                '->',
                                {
                                    xtype: 'tbbutton', text: 'SELECT',
                                    icon: '/images/icons/accept.png',
                                    cls:'x-btn-text-icon',
                                    handler: function(){
                                        if(ExtCommon.util.validateSelectionGrid(grid.getId())){//check if user has selected an item in the grid
			
                                        var courseSm = grid.getSelectionModel();
                                        var courseDescription = courseSm.getSelected().data.description;
                                        _courseWindow.destroy();
                                        Ext.getCmp('course_id').setValue(courseDescription);
                                        
                                        }else return;
                                    }

                                }
 	    			 ],
                                 listeners: {
			rowdblclick: function(grid, row, e){
                            
                        }
                        }
 	    	});

 		employee.app.courseGrid = grid;
                employee.app.courseGrid.getStore().load();

                var courseForm = new Ext.form.FormPanel({
 		        labelWidth: 90,
                        width: 500,
 		        url:"<?php echo site_url("hr/insertCourse"); ?>",
 		        method: 'POST',
 		        defaultType: 'textfield',
 		        frame: true,

 		        items: [ {
 					xtype:'fieldset',
 					title:'Add a New Course',
 					width:485,
 					height:60,
 					items:[{
 					xtype:'textfield',
 		            fieldLabel: 'Description*',
                     maxLength:50,
                     autoCreate : {tag: "input", type: "text", size: "20", autocomplete: "off", maxlength: "50"},
 		            name: 'description',
 		            allowBlank:false,
 		            maxLength:50,
 		            anchor:'95%',  // anchor width by percentage
 		            id: 'description'
 		        }

 		        ]
 					}
 		        ],
                        buttonAlign: 'right',
                        buttons: [
                            {
                                text: 'ADD',
                                 icon: '/images/icons/add.png',
                                 cls:'x-btn-text-icon',
                                handler: function () {
 			            if(ExtCommon.util.validateFormFields(courseForm)){//check if all forms are filled up

                                    courseForm.getForm().submit({
                                        
 			                success: function(f,action){
                                                courseForm.getForm().reset();
 				                ExtCommon.util.refreshGrid(employee.app.courseGrid.getId());
 				                
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

                            }]
 		    });

                var _courseWindow;

 		    _courseWindow = new Ext.Window({
 		        title: 'Courses',
 		        width:520,
 		        height:410,
 		       // layout: 'fit',
 		        plain:true,
 		        modal: true,
 		        bodyStyle:'padding:5px;',
 		        buttonAlign:'center',
 		        items: [employee.app.courseGrid, courseForm]
 		    });
 		  	_courseWindow.show();


                },
                selectSchool: function(){
                ExtCommon.util.renderSearchField('searchbySchool');

 			var schoolStore = new Ext.data.Store({
 						proxy: new Ext.data.HttpProxy({
 							url: "<?php echo site_url("hr/getSchools"); ?>",
 							method: "POST"
 							}),
 						reader: new Ext.data.JsonReader({
 								root: "data",
 								id: "id",
 								totalProperty: "totalCount",
 								fields: [
 											{ name: "id"},
 											{ name: "description"},
                                                                                        { name: "abbreviation"},
                                                                                        { name: "school_address"}
 										]
 						}),
 						remoteSort: true,
 						baseParams: {start: 0, limit: 25}
 					});


 			var grid = new Ext.grid.GridPanel({
 				id: 'schoolgrid',
 				height: 250,
 				width: 500,
 				border: true,
 				ds:schoolStore,
 				cm:  new Ext.grid.ColumnModel(
 						[
 						  { header: "Description", dataIndex: "description", width: 200, sortable: true}
 						]
 				),
 				sm: new Ext.grid.RowSelectionModel({singleSelect:true}),
 	        	loadMask: true,
 	        	bbar:
 	        		new Ext.PagingToolbar({
 		        		autoShow: true,
 				        pageSize: 25,
 				        store: schoolStore,
 				        displayInfo: true,
 				        displayMsg: 'Displaying Results {0} - {1} of {2}',
 				        emptyMsg: "No Data Found."
 				    }),
 				tbar: [new Ext.form.ComboBox({
                    fieldLabel: 'Search',
                    hiddenName:'searchby-form',
                    id: 'searchbySchool',
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
				},'   ', new Ext.app.SearchField({ store: schoolStore, width:250}),
                                '->',
                                {
                                    xtype: 'tbbutton', text: 'SELECT',
                                    icon: '/images/icons/accept.png',
                                    cls:'x-btn-text-icon',
                                    handler: function(){
                                        if(ExtCommon.util.validateSelectionGrid(grid.getId())){//check if user has selected an item in the grid

                                        var schoolSm = grid.getSelectionModel();
                                        var schoolDescription = schoolSm.getSelected().data.description;
                                        var schoolAddress = schoolSm.getSelected().data.school_address;
                                        _courseWindow.destroy();
                                        Ext.getCmp('school_id').setValue(schoolDescription);
                                        Ext.getCmp('school_address2').setValue(schoolAddress);

                                        }else return;
                                    }

                                }
 	    			 ],
                                 listeners: {
			rowdblclick: function(grid, row, e){

                        }
                        }
 	    	});

 		employee.app.schoolGrid = grid;
                employee.app.schoolGrid.getStore().load();

                var schoolForm = new Ext.form.FormPanel({
 		        labelWidth: 90,
                        width: 500,
 		        url:"<?php echo site_url("hr/insertSchool"); ?>",
 		        method: 'POST',
 		        defaultType: 'textfield',
 		        frame: true,

 		        items: [ {
 					xtype:'fieldset',
 					title:'Add a New School',
 					width:485,
 					height:150,
 					items:[{
 					xtype:'textfield',
 		            fieldLabel: 'Description*',
                     maxLength:50,
                     autoCreate : {tag: "input", type: "text", size: "20", autocomplete: "off", maxlength: "128"},
 		            name: 'description',
 		            allowBlank:false,
 		            maxLength:50,
 		            anchor:'95%',  // anchor width by percentage
 		            id: 'description'
 		        },
                        {
 					xtype:'textfield',
 		            fieldLabel: 'Abbreviation*',
                     
                            autoCreate : {tag: "input", type: "text", size: "20", autocomplete: "off", maxlength: "20"},
 		            name: 'abbreviation',
 		            allowBlank:false,
 		            
 		            anchor:'95%',  // anchor width by percentage
 		            id: 'abbreviation'
 		        },
                        {
 					xtype:'textarea',
 		            fieldLabel: 'Address*',

                            //autoCreate : {tag: "input", type: "text", autocomplete: "off", maxlength: "250"},
 		            name: 'school_address',
 		            allowBlank:false,

 		            anchor:'95%',  // anchor width by percentage
 		            id: 'school_address'
 		        }

 		        ]
 					}
 		        ],
                        buttonAlign: 'right',
                        buttons: [
                            {
                                text: 'ADD',
                                 icon: '/images/icons/add.png',
                                 cls:'x-btn-text-icon',
                                handler: function () {
 			            if(ExtCommon.util.validateFormFields(schoolForm)){//check if all forms are filled up

                                   schoolForm.getForm().submit({

 			                success: function(f,action){
                                                schoolForm.getForm().reset();
 				                ExtCommon.util.refreshGrid(employee.app.schoolGrid.getId());

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

                            }]
 		    });

                var _courseWindow;

 		    _courseWindow = new Ext.Window({
 		        title: 'Schools',
 		        width:520,
 		        height:490,
 		       // layout: 'fit',
 		        plain:true,
 		        modal: true,
 		        bodyStyle:'padding:5px;',
 		        buttonAlign:'center',
 		        items: [employee.app.schoolGrid, schoolForm]
 		    });
 		  	_courseWindow.show();


                },
                //comboboxes start

                departmentCombo: function(){
											
		return {
			xtype:'combo',
			id:'department_id',
			hiddenName: 'department',
                        hiddenId: 'department',
			name: 'department_name',
			valueField: 'id',
			displayField: 'name',
			//width: 100,
			anchor: '91%',
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
			fields:[{name: 'id', mapping: 'dept_idno'}, {name: 'name', type:'string', mapping: 'dept_type'}],
			url: "<?php echo site_url("hr/getDepartment"); ?>",
			baseParams: {start: 0, limit: 10}
													
			}),
			listeners: {
			select: function (combo, record, index){
			this.setRawValue(record.get('name'));
                        //this.setValue(record.get('name'));
			Ext.getCmp(this.id).hiddenValue  = record.get('id');
			
			},
			blur: function(){
			var val = this.getRawValue();
			this.setRawValue.defer(1, this, [val]);
			this.validate();
			},
			render: function() {
			this.el.set({qtip: 'Type at least ' + this.minChars + ' characters to search for a department'});
														
			},
			keypress: {buffer: 100, fn: function() {
			Ext.get(this.hiddenName).dom.value  = '';
			if(!this.getRawValue()){
			this.doQuery('', true);
			}
			}}
			},
			fieldLabel: 'Department*'
											
			}
	},
  subDepartmentCombo: function(){
                      
    return {
      xtype:'combo',
      id:'subdepartment_id',
      hiddenName: 'subdepartment',
      hiddenId: 'subdepartment',
      name: 'subdepartment_name',
      valueField: 'id',
      displayField: 'name',
      //width: 100,
      anchor: '91%',
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
      fields:[{name: 'id', mapping: 'dept_idno'}, {name: 'name', type:'string', mapping: 'dept_type'}],
      url: "<?php echo site_url("hr/getSubDepartment"); ?>",
      baseParams: {start: 0, limit: 10}
                          
      }),
      listeners: {
      select: function (combo, record, index){
      this.setRawValue(record.get('name'));
      Ext.getCmp(this.id).hiddenValue  = record.get('id');
      
      },
      blur: function(){
      var val = this.getRawValue();
      this.setRawValue.defer(1, this, [val]);
      this.validate();
      },
      render: function() {
      this.el.set({qtip: 'Type at least ' + this.minChars + ' characters to search for a sub-department'});
                            
      },
      keypress: {buffer: 100, fn: function() {
      if(!this.getRawValue()){
      this.doQuery('', true);
      }
      }}
      },
      fieldLabel: 'Sub-department*'
                      
      }
  },
        positionCombo: function(){

		return {
			xtype:'combo',
			id:'position_id',
			hiddenName: 'position',
                        hiddenId: 'position',
			name: 'position_name',
			valueField: 'id',
			displayField: 'name',
			//width: 100,
			anchor: '92%',
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
			url: "<?php echo site_url("hr/getPosition"); ?>",
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
			this.el.set({qtip: 'Type at least ' + this.minChars + ' characters to search for a position'});

			},
			keypress: {buffer: 100, fn: function() {
			Ext.get(this.hiddenName).dom.value  = '';
			if(!this.getRawValue()){
			this.doQuery('', true);
			}
			}}
			},
			fieldLabel: 'Position*'

			}
	},
        employeeCategoryCombo: function(){

		return {
			xtype:'combo',
			id:'employee_category_id',
			hiddenName: 'employee_category',
                        hiddenId: 'employee_category',
			name: 'employee_category_name',
			valueField: 'id',
			displayField: 'name',
			//width: 100,
			anchor: '91%',
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
			url: "<?php echo site_url("hr/getEmployeeCategory"); ?>",
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
			this.el.set({qtip: 'Type at least ' + this.minChars + ' characters to search for an employee category'});

			},
			keypress: {buffer: 100, fn: function() {
			Ext.get(this.hiddenName).dom.value  = '';
			if(!this.getRawValue()){
			this.doQuery('', true);
			}
			}}
			},
			fieldLabel: 'Employee Category*'

			}
	},
        employeeStatusCombo: function(){

		return {
			xtype:'combo',
			id:'employee_status_id',
			hiddenName: 'employee_status',
                        hiddenId: 'employee_status',
			name: 'employee_status_id',
			valueField: 'id',
			displayField: 'name',
			//width: 100,
			anchor: '92%',
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
			url: "<?php echo site_url("hr/getEmployeestatus"); ?>",
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
			//Ext.get(this.hiddenName).dom.value  = '';
			if(!this.getRawValue()){
			this.doQuery('', true);
			}
			}}
			},
			fieldLabel: 'Employee Status*'

			}
	},
        supplierCombo: function(){

		return {
			xtype:'combo',
			id:'supplier',
			hiddenName: 'supplier_id',
			name: 'supplier_name',
			valueField: 'id',
			displayField: 'name',
			//width: 100,
			anchor: '95%',
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
			fields:[{name: 'id', type:'int', mapping:'id'}, {name: 'name', type:'string', mapping: 'name'}, {name: 'address'}],
			url: "<?php echo site_url("hr/getSupplier"); ?>",
			baseParams: {start: 0, limit: 10}

			}),
			listeners: {
			select: function (combo, record, index){
			this.setRawValue(record.get('name'));
			this.setValue(record.get('name'));
                        Ext.getCmp('location').setValue(record.get('address'));

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
			//Ext.get(this.hiddenName).dom.value  = '';
			if(!this.getRawValue()){
			this.doQuery('', true);
			}
			}}
			},
			fieldLabel: 'Supplier*'

			}
	},
        trainingTypeCombo: function(){

		return {
			xtype:'combo',
			id:'training_type',
			hiddenName: 'training_type_id',
			name: 'training_type_name',
			valueField: 'id',
			displayField: 'name',
			//width: 100,
			anchor: '95%',
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
			this.setValue(record.get('name'));

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
			//Ext.get(this.hiddenName).dom.value  = '';
			if(!this.getRawValue()){
			this.doQuery('', true);
			}
			}}
			},
			fieldLabel: 'Training Type*'

			}
	},
	citiCombo: function(){

 			return {
 				xtype:'combo',
 				id:'CITIZENSHIP',
 				hiddenName: 'CITIIDNO',
                                hiddenId: 'CITIIDNO',
 				name: 'CITIZENSHIP',
 				valueField: 'id',
 				displayField: 'name',
 				//width: 100,
 				anchor: '91%',
 				triggerAction: 'all',
 				minChars: 2,
 				forceSelection: true,
 				enableKeyEvents: true,
 				pageSize: 10,
 				resizable: true,
 				//readOnly: true,
 				minListWidth: 300,
 				allowBlank: false,
 				store: new Ext.data.JsonStore({
 				id: 'idsocombo',
 				root: 'data',
 				totalProperty: 'totalCount',
 				fields:[{name: 'id'}, {name: 'name', type:'string', mapping: 'name'}, {name: 'firstname'}, {name: 'middlename'}, {name: 'lastname'}, {name: 'cellphone'}, {name: 'homephone'}, {name: 'memberId'}],
 				url: "<?=site_url("hr/getCitizenshipCombo")?>",
 				baseParams: {start: 0, limit: 10}

 				}),
 				listeners: {
 				beforequery: function()
				{
					/*if (Ext.getCmp('SERIALNUM').getValue() == "")
						return false;

					this.store.baseParams = {id: Ext.getCmp('SERIALNUM').getValue()};

		            var o = {start: 0, limit:10};
		            this.store.baseParams = this.store.baseParams || {};
		            this.store.baseParams[this.paramName] = '';
		            this.store.load({params:o, timeout: 300000});*/
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
 				this.el.set({qtip: 'Type at least ' + this.minChars + ' characters to search for a citizenship'});

 				},
 				keypress: {buffer: 100, fn: function() {
 				Ext.get(this.hiddenName).dom.value  = '';
 				if(!this.getRawValue()){
 				this.doQuery('', true);
 				}
 				}}
 				},
 				fieldLabel: 'Citizenship*'

 				}
 	}//end of functions
 	}

 }();

 Ext.onReady(employee.app.init, employee.app);

</script>

<div class="mainBody" id="mainBody" >
</div>

