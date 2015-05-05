<script type="text/javascript">

Ext.onReady(function(){
	
	Ext.QuickTips.init();

	SearchLeaveType = new Ext.form.ComboBox({
		fieldLabel: 'Leave Type',
		//store:subject,
		mode: 'remote',
		displayField: 'leave',
		valueField: 'leaveid',
		anchor:'90%',
		triggerAction: 'all',
		hn_subject: 'leaveid'
	});
	
	SearchDateFrom = new Ext.form.DateField({
		fieldLabel: 'Date From',
		anchor : '90%'
	});
	
	SearchDateTo = new Ext.form.DateField({
		fieldLabel: 'Date To',
		anchor : '90%'
	});
													
			
	var LeavesSearchForm = new Ext.FormPanel({
       labelAlign: 'top'
       ,bodyStyle: 'padding: 5px'
       ,width: 250
	   ,border:false
       ,items: [{
         layout: 'form',
         border: false,
         items: [ SearchLeaveType,SearchDateFrom,SearchDateTo ],
         buttons: [{
               text: 'Search'
               //,handler: listSearch
             },{
               text: 'Reset'//,
               //,handler: function(){
				//   LeavesSearchForm.getForm().reset();
			   //}
             }]
         }]
     });


	var  LeavesGrid =  new Ext.grid.GridPanel({
		id: 'LeavesGrid-id'
		//,store: bookStore
		,loadMask: true
		,layout: 'fit'
		,columns: [
			{header: "Date Filed", dataIndex: 'datefiled', width: 100, align: 'left'}
			,{header: "Date From", dataIndex: 'datefrom', width: 400, align: 'left'}
			,{header: "Date To", dataIndex: 'dateto', width: 150, align: 'left'}
			,{header: "No. of Days", dataIndex: 'noofdays', width: 150, align: 'left'}
			,{header: "Leave Type", dataIndex: 'leave', width: 180, align: 'left'}
			,{header: "Status", dataIndex: 'status', width: 180, align: 'left'}
		]
		,selModel: new Ext.grid.RowSelectionModel({singleSelect:true})
		,stripeRows: true
		,tbar: [{
			text: 'Apply Leave',
			tooltip: 'Apply Leave Form',
			iconCls:'add'
		},{
			text: 'Cancel Leave',
			tooltip: 'Cancel selected Leave',
			iconCls:'cancel'
		}]
		,bbar: new Ext.PagingToolbar({
			pageSize: 17,
			//store: bookStore,
			displayInfo: true,
			displayMsg: 'Displaying Records {0} - {1} of {2}',
			emptyMsg: "No records found"
		})
	});
	
	var leavespanel = {
		id:'leaves-panel',
		layout:'border',
		renderTo: 'content',
		bodyBorder: false,
		defaults: {
			//collapsible: true,
			split: true,
			animFloat: false,
			autoHide: false,
			useSplitTips: true
			//bodyStyle: 'padding:15px'
		},
		items: [{
			 title: 'Search Form'
			,region:'west'
			,floatable: false
			,collapsible: true
			//margins: '5 0 0 0',
			//cmargins: '5 5 0 0',
			,width: 300
			,minSize: 100
			,maxSize: 250
			,items: LeavesSearchForm
		},{
			title: 'Leaves Application',
			region:'center',
			layout: 'fit',
			items: LeavesGrid
		}]
		
	};	 
	 
});
</script>

<div id="content" style="width:900px;height:500px;">

</div>