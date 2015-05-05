<div id="userControls">

</div>
<script type="text/javascript">
   	var addreslinkdev = "/dcarchive";
   	var addreslinklive = "/dcarchive";
ExtCommon.util.init();
ExtCommon.util.quickTips();
ExtCommon.util.validations();



		new Ext.Toolbar({

			renderTo: 'userControls',

			items:

			[
		{
    xtype: 'tbbutton',
    text: 'HOME',
    icon: '/images/icons/house.png',
    cls: 'x-btn-text-icon',
    handler: function(){
        window.location="<?php echo site_url("dashboard") ?>";
    }
}, '-',
		<?php
		//include("/home/lithefzj/public_html/sms/functions/connect.php");
		//@session_start();
		$dbLink = new mysqli("localhost", "infobahn_dca", "TdF5yCJRLxnKsH8b", "infobahn_hrisv2");
		$id = $userId;
		$username = $userName;
		#echo $id;

			$sql = "SELECT DISTINCT a.description AS btn, a.icon, b.description AS mnu, b.link, b.group
FROM module_category a LEFT JOIN module b ON a.id = b.category_id
LEFT JOIN module_group_access c ON c.module_id = b.id
LEFT JOIN module_group d ON d.id = c.group_id
LEFT JOIN module_group_users e ON d.id = e.group_id
WHERE b.is_public = 1 OR e.username = '$username' ORDER BY a.order, b.group, b.order, b.description";
			/*$sql = mysql_query("SELECT a.description AS btn, a.icon, b.description AS mnu, b.link, b.group FROM tbl_button a JOIN tbl_menu b ON a.button_id = b.button_id
WHERE a.access_level IN (0, $id) ORDER BY b.order");*/

			$result = $dbLink->query($sql);

			while($rows=$result->fetch_assoc()){

			$btnarray[] = $rows;

			}
		//print_r($btnarray);

  foreach($btnarray as $key => $value){

  $buttons[$value['btn']][] = array('menu'=>$value['mnu'], 'link'=>$value['link'], 'group'=>$value['group']);
  $icon[$value['btn']] = $value['icon'];
  $lastmenu[$value['btn']] = $value['mnu'];
  $lastbutton = $value['btn'];
  }
  #print_r($lastmenu);

  $count = 0;

  foreach($buttons as $key => $value){
  //print_r($val);
  echo "{
					xtype: 'tbbutton',
					icon: '".base_url().$icon[$key]."',
					cls: 'x-btn-text-icon',
					text: '$key',
						menu: [";
  $group = NULL;
  foreach($value as $k=> $val){
  if($count == 0 && $key == 'FILE REFERENCE'){
  $group = $val['group'];
  $count++;
  }
  if($group != $val['group'] && $key == 'FILE REFERENCE'){
  	echo "'-',";
  	$group = $val['group'];
  	}
        if($val['menu'] == 'Change Password'){
            echo "{

							text: '".$val['menu']."',
							handler: function(){


							var form = new Ext.form.FormPanel({
				 		        labelWidth: 150,
				 		        url:'".site_url("userMatrix/updatePassword")."',
				 		        method: 'POST',
				 		        defaultType: 'textfield',
				 		        frame: true,
				 		        height: 100,

				 		        items: [ {
				 					xtype:'fieldset',
				 					title:'Please Confirm',
				 					width:370,
				 					height:'auto',
				 					defaults: {

				 				        anchor: '95%',
				 				        allowBlank: false
				 				      },
				 				      defaultType: 'textfield',

				 					items:[
						 			  {
				 				        fieldLabel: 'Old Password*',
				 				        inputType: 'password',
				 				        name: 'oldpass',
				 				        allowBlank: false,
				 				        id: 'oldpass'
				 				      },

						 			  {
				 				        fieldLabel: 'New Password*',
				 				        inputType: 'password',
				 				        name: 'pass',
				 				        allowBlank: false,
				 				        id: 'pass'
				 				      },{
				 				        fieldLabel: 'Confirm Password*',
				 				        inputType: 'password',
				 				        name: 'pass-cfrm',
				 				        vtype: 'password',
                                                                        enableKeyEvents: true,
				 				        allowBlank: false,
				 				        initialPassField: 'pass', // id of the initial password field
                                                                        listeners: {
                                                                            specialkey: function(f, e){
                                                                            if(e.getKey() == e.ENTER){
                                                                                if(ExtCommon.util.validateFormFields(form)){//check if all forms are filled up

				 		                form.getForm().submit({
					 		                params: {id: '$id'},
				 			                success: function(f,action){
				                 		    	Ext.MessageBox.alert('Status', action.result.data);
				                  		    	 Ext.Msg.show({
				  								     title: 'Status',
				 								     msg: action.result.data,
				  								     buttons: Ext.Msg.OK,
				  								     icon: Ext.Msg.INFO
				  								 });

				 				                _pwwindow.destroy();
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
                                                                            }
                                                                        }
				 				      }

				 		        ]
				 					}
				 		        ]
				 		    });

				 		    var _pwwindow = new Ext.Window({
				 		        title: 'Change Password',
				 		        width: 410,
				 		        height:225,
				 		        layout: 'fit',
				 		        plain:true,
				 		        modal: true,
				 		        bodyStyle:'padding:5px;',
				 		        buttonAlign:'center',
				 		        items: form,
				 		        buttons: [{
				 		         	text: 'Save',
				 		         	 icon: '/images/icons/disk.png',  cls:'x-btn-text-icon',
				 	                handler: function () {
				 			            if(ExtCommon.util.validateFormFields(form)){//check if all forms are filled up

				 		                form.getForm().submit({
					 		                params: {id: '$id'},
				 			                success: function(f,action){
				                 		    	Ext.MessageBox.alert('Status', action.result.data);
				                  		    	 Ext.Msg.show({
				  								     title: 'Status',
				 								     msg: action.result.data,
				  								     buttons: Ext.Msg.OK,
				  								     icon: Ext.Msg.INFO
				  								 });

				 				                _pwwindow.destroy();
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
				 		             icon: '/images/icons/cancel.png',  cls:'x-btn-text-icon',
				 		            handler: function(){
				 			            _pwwindow.destroy();
				 		            }
				 		        }]
				 		    });
				 		  	_pwwindow.show();

  						}";

						echo "}";
        }else{
  	echo "{

							text: '".$val['menu']."',
							handler: function(){
  						window.location='".site_url($val['link'])."';
  						}";

						echo "}";
        }
  	if($val['menu'] != $lastmenu[$key])
  	echo ",";

  }

  echo "
						]


				}";

  if($key != $lastbutton)
  echo ", '-',";
  //print_r($buttons);
  //echo $lastbutton;
  }
		?>
, '-', {
    xtype: 'tbbutton',
    text: 'LOGOUT',
    icon: '<?php echo base_url()?>/images/icons/door_out.png',
    cls: 'x-btn-text-icon',
    handler: function(){
        window.location="<?php echo site_url("main/logout") ?>";
    }
}

			]

		}).render();

    </script>