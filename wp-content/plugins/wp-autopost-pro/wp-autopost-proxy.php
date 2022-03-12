<?php
global $t_ap_proxy;
 
$id = null;


 if(isset($_POST['saction'])){
  
   if($_POST['saction']=='updateOption' && $_POST['proxy_id']>0){  // update
     
	 if(trim($_POST['proxy_name'])=='' || trim($_POST['proxy_ip'])=='' || trim($_POST['proxy_port'])==''){     
	   $errMsg = 'Please at least enter the <b>[Proxy Name]</b> and <b>[Hostname / IP]</b> and <b>[Port]</b>';
       $id = $_POST['proxy_id'];
	 }elseif( $_POST['proxy_type']!=0 && (!function_exists('shell_exec')) ){
	   $errMsg = 'Please enable  <b>[shell_exec]</b> function to use Socks5 Proxy';
       $id = $_POST['proxy_id'];	 
	 }else{
       $wpdb->query($wpdb->prepare("update $t_ap_proxy set 
	   proxy_name = %s,
	   proxy_type = %s,
	   proxy_ip = %s,
	   proxy_port = %s,
	   proxy_user = %s,
	   proxy_pass = %s where id = %d",
	   $_POST['proxy_name'],$_POST['proxy_type'],$_POST['proxy_ip'],$_POST['proxy_port'],$_POST['proxy_user'],$_POST['proxy_pass'],$_POST['proxy_id']));
	 }

   }elseif($_POST['saction']=='updateOption'){ // new
     if(trim($_POST['proxy_name'])=='' || trim($_POST['proxy_ip'])=='' || trim($_POST['proxy_port'])==''){     
	   $errMsg = 'Please at least enter the <b>[Proxy Name]</b> and <b>[Hostname / IP]</b> and <b>[Port]</b>';
       $id = 'new';
	 }elseif( $_POST['proxy_type']!=0 && (!function_exists('shell_exec')) ){
	    $errMsg = 'Please enable  <b>[shell_exec]</b> function to use Socks5 Proxy';
       $id = 'new';	 
	 }else{
       $wpdb->query($wpdb->prepare("insert into $t_ap_proxy (proxy_name,proxy_type,proxy_ip,proxy_port,proxy_user,proxy_pass) values (%s,%s,%s,%s,%s,%s)",$_POST['proxy_name'],$_POST['proxy_type'],$_POST['proxy_ip'],$_POST['proxy_port'],$_POST['proxy_user'],$_POST['proxy_pass']));
	 }
   }


   
   if($_POST['saction']=='test_proxy' && $_POST['proxy_id']>0){ // update and test
     
	if(trim($_POST['proxy_name'])=='' || trim($_POST['proxy_ip'])=='' || trim($_POST['proxy_port'])==''){     
	   $errMsg = 'Please at least enter the <b>[Proxy Name]</b> and <b>[Hostname / IP]</b> and <b>[Port]</b>';
       $id = $_POST['proxy_id'];
	 }elseif( $_POST['proxy_type']!=0 && (!function_exists('shell_exec')) ){
	   $errMsg = 'Please enable  <b>[shell_exec]</b> function to use Socks5 Proxy';
       $id = $_POST['proxy_id'];	 
	 }else{
       $wpdb->query($wpdb->prepare("update $t_ap_proxy set 
	   proxy_name = %s,
	   proxy_type = %s,
	   proxy_ip = %s,
	   proxy_port = %s,
	   proxy_user = %s,
	   proxy_pass = %s where id = %d",
	   $_POST['proxy_name'],$_POST['proxy_type'],$_POST['proxy_ip'],$_POST['proxy_port'],$_POST['proxy_user'],$_POST['proxy_pass'],$_POST['proxy_id']));
	   
	   $id = $_POST['proxy_id'];
	   $canTest = true;
	    
	 }

   }elseif($_POST['saction']=='test_proxy'){ // new and test
     
	 if(trim($_POST['proxy_name'])=='' || trim($_POST['proxy_ip'])=='' || trim($_POST['proxy_port'])==''){     
	   $errMsg = 'Please at least enter the <b>[Proxy Name]</b> and <b>[Hostname / IP]</b> and <b>[Port]</b>';
       $id = 'new';
	 }elseif( $_POST['proxy_type']!=0 && (!function_exists('shell_exec')) ){
	   $errMsg = 'Please enable  <b>[shell_exec]</b> function to use Socks5 Proxy';
       $id = 'new';	 
	 }else{
       $wpdb->query($wpdb->prepare("insert into $t_ap_proxy (proxy_name,proxy_type,proxy_ip,proxy_port,proxy_user,proxy_pass) values (%s,%s,%s,%s,%s,%s)",$_POST['proxy_name'],$_POST['proxy_type'],$_POST['proxy_ip'],$_POST['proxy_port'],$_POST['proxy_user'],$_POST['proxy_pass']));
	 
	   $id = $wpdb->get_var("SELECT LAST_INSERT_ID()");
	   $canTest = true;
	 }

   }


 }

/*
$proxy = get_option('wp-autopost-proxy');
if(isset($_POST['save_setting'])&&$_POST['save_setting']!=''){
  $proxy['ip'] =  $_POST['ip'];
  $proxy['port'] =  $_POST['port'];
  $proxy['user'] =  $_POST['user'];
  $proxy['password'] =  $_POST['password'];
  update_option( 'wp-autopost-proxy', $proxy);
  $proxy = get_option('wp-autopost-proxy');
}*/





?>


<script type="text/javascript">
function updateOption(){
  document.getElementById("saction").value='updateOption';
  document.getElementById("myform").action='admin.php?page=wp-autopost-pro/wp-autopost-proxy.php';
  document.getElementById("myform").submit();
}
function test_proxy(){
  document.getElementById("saction").value='test_proxy';
  document.getElementById("myform").action='admin.php?page=wp-autopost-pro/wp-autopost-proxy.php';
  document.getElementById("myform").submit();
}
</script>



<div class="wrap">

<?php
 if(isset($_GET['id']))$id = $_GET['id'];
 if($id==''||$id==null ):
?>
  
  <div class="icon32" id="icon-wp-autopost"><br/></div>
  <h2><?php echo __('Proxy Options','wp-autopost'); ?> <a href="admin.php?page=wp-autopost-pro/wp-autopost-proxy.php&id=new" class="add-new-h2"><?php echo __('Add New','wp-autopost'); ?></a> </h2>
  <p><?php _e( 'Tips: You can create different proxy applied to different tasks.', 'wp-autopost' );?></p>
  
  
  <?php
   if(isset($_GET['del'])){   
       $wpdb->query('DELETE FROM '.$t_ap_proxy.' WHERE id = '.$_GET['del']);  
   }
   $proxys = $wpdb->get_results('SELECT * FROM '.$t_ap_proxy.' order by id');
  ?>

  <table class="widefat tablehover plugins"  style="margin-top:4px"> 
     <thead>
      <tr>
        <th scope="col" width="250" style="text-align:center;"><?php _e( 'Proxy Name', 'wp-autopost' );?></th>
        <th scope="col" width="150" style="text-align:center;"><?php _e( 'Proxy Type', 'wp-autopost' );?></th>
        <th scope="col" width="250" style="text-align:center;"><?php _e( 'Hostname / IP', 'wp-autopost' );?></th>
        <th scope="col" width="150" style="text-align:center;"><?php _e( 'Port', 'wp-autopost' );?></th>
        <th scope="col" width="150" style="text-align:center;"><?php _e( 'User', 'wp-autopost' );?></th>
		<th scope="col" width="150" style="text-align:center;"><?php _e( 'Password', 'wp-autopost' );?></th>
      </tr>
     </thead>
	 <tbody id="the-list">
<?php if($proxys!=null)foreach($proxys as $proxy){ ?>
      <tr style="text-align:center" >
		<td style="vertical-align: middle; text-align:center;">
		   <strong><?php echo $proxy->proxy_name; ?></strong>
		   <div class="row-actions-visible">
              <a href="admin.php?page=wp-autopost-pro/wp-autopost-proxy.php&id=<?php echo $proxy->id; ?>"><?php echo __('Setting','wp-autopost'); ?></a> | <span class="trash"><a class="submitdelete delete" title="delete" href="admin.php?page=wp-autopost-pro/wp-autopost-proxy.php&del=<?php echo $proxy->id; ?>" ><?php echo __('Delete'); ?></a></span> 
		   </div>
		</td>
        <td style="vertical-align: middle; text-align:center;">
		  <?php if($proxy->proxy_type==0) _e( 'HTTP', 'wp-autopost' ); else _e( 'Socks5', 'wp-autopost' ); ?>	
		</td>
        <td style="vertical-align: middle; text-align:center;">
		  <?php echo $proxy->proxy_ip; ?>	
		</td>
		<td style="vertical-align: middle; text-align:center;">
		  <?php echo $proxy->proxy_port; ?>	
		</td>
		<td style="vertical-align: middle; text-align:center;">
		  <?php echo $proxy->proxy_user; ?>	
		</td>
		<td style="vertical-align: middle; text-align:center;">
		  <?php echo $proxy->proxy_pass; ?>	
		</td>
	  </tr>
 <?php } ?>
	 </tbody>
   </table>

<?php
 else:
?>
  <?php
   if($id >0 ){
     $proxy = $wpdb->get_row('SELECT * FROM '.$t_ap_proxy.' where id = '.$id);
	 $proxy_name = $proxy->proxy_name;
	 $proxy_type = $proxy->proxy_type;
	 $proxy_ip = $proxy->proxy_ip;
	 $proxy_port = $proxy->proxy_port;
	 $proxy_user = $proxy->proxy_user;
	 $proxy_pass = $proxy->proxy_pass;
   }else{
     $proxy_name = '';
	 $proxy_type = 0;
	 $proxy_ip = '';
	 $proxy_port = '';
	 $proxy_user = '';
	 $proxy_pass = '';
   }
 ?>




<?php
if(isset($canTest)&&$canTest&&trim($_POST['url'])!=''){

  if($_POST['proxy_type']==0){
    
	$curlHandle = curl_init();
    $agent='Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.19 (KHTML, like Gecko) Chrome/25.0.1323.1 Safari/537.19';
    curl_setopt( $curlHandle , CURLOPT_URL, $_POST['url'] );
    curl_setopt( $curlHandle , CURLOPT_TIMEOUT, 30 );
    curl_setopt( $curlHandle , CURLOPT_USERAGENT, $agent );  
    @curl_setopt( $curlHandle , CURLOPT_REFERER, _REFERER_ );     
    curl_setopt( $curlHandle , CURLOPT_HEADER, false);
    curl_setopt( $curlHandle , CURLOPT_RETURNTRANSFER, 1 );
    
	
	curl_setopt($curlHandle,CURLOPT_PROXY,$_POST['proxy_ip']);
    curl_setopt($curlHandle,CURLOPT_PROXYPORT,$_POST['proxy_port']);
	if($_POST['proxy_user']!=''&& $_POST['proxy_user']!=NULL && $_POST['proxy_pass']!='' && $_POST['proxy_pass']!=NULL){
       $userAndPass = $_POST['proxy_user'].':'.$_POST['proxy_pass'];
	   curl_setopt($curlHandle,CURLOPT_PROXYUSERPWD,$userAndPass);    // curl_setopt($ch,CURLOPT_PROXYUSERPWD,'user:password');
	}
	   
    $result = curl_exec( $curlHandle );
    curl_close( $curlHandle );
	
	$file = dirname(__FILE__).'/proxy_temp.html';
    $fileUrl=plugins_url('/proxy_temp.html', __FILE__ );
    
	file_put_contents ( $file, $result );

	$show=true;  
	    
  }else{
    $proxy = array();
    $proxy['ip'] = $_POST['proxy_ip'];
    $proxy['port'] = $_POST['proxy_port'];
	$result = ap_socks5_proxy($_POST['url'],$proxy);
    $file = dirname(__FILE__).'/proxy_temp.html';
    $fileUrl=plugins_url('/proxy_temp.html', __FILE__ );   
	file_put_contents ( $file, $result );
	$show=true;  
  }

}


?>  
  
  <div class="icon32" id="icon-wp-autopost"><br/></div>
  <h2><?php echo __('Proxy Options','wp-autopost'); ?> <a href="admin.php?page=wp-autopost-pro/wp-autopost-proxy.php" class="add-new-h2"><?php echo __('Return','wp-autopost'); ?></a> </h2>
  

  <?php
    if(isset($errMsg)){
      echo '<div class="error fade"><p>'.__($errMsg,'wp-autopost').'</p></div>';  
    }
  ?>
  
  
  <form action="admin.php?page=wp-autopost-pro/wp-autopost-proxy.php" method="post" id="myform">
  <input type="hidden" name="proxy_id" id="proxy_id" value="<?php echo $id; ?>" />
  <input type="hidden" name="saction" id="saction" value="" />

   <table class="form-table">
    <tr>
      <th scope="row"><label><?php _e( 'Proxy Name', 'wp-autopost' );?>:</label></th>
	  <td>
	    <input type="text" name="proxy_name" value="<?php echo $proxy_name; ?>" size="60"/>
	  </td>
    </tr>

	<tr>
      <th scope="row"><label><?php _e( 'Proxy Type', 'wp-autopost' );?>:</label></th>
	  <td>
	     <input type="radio" name="proxy_type" value="0" <?php checked( '0', $proxy_type ); if( empty( $proxy_type ) ) echo 'checked'; ?> /><?php _e( 'HTTP', 'wp-autopost' );?>
	    &nbsp;&nbsp;&nbsp;
	    <input type="radio" name="proxy_type" value="1" <?php checked( '1', $proxy_type ); ?> /><?php _e( 'Socks5', 'wp-autopost' );?>
	  </td>
    </tr>
	
	<tr>
      <th scope="row"><label><?php _e( 'Hostname / IP', 'wp-autopost' );?>:</label></th>
	  <td>
	    <input type="text" name="proxy_ip" value="<?php echo $proxy_ip; ?>" size="60"/>
	  </td>
    </tr>
	<tr>
      <th scope="row"><label><?php _e( 'Port', 'wp-autopost' );?>:</label></th>
	  <td>
	    <input type="text" name="proxy_port" value="<?php echo $proxy_port; ?>"  size="60"/>
	  </td>
    </tr>
	<tr>
      <th scope="row"><label><?php _e( 'User', 'wp-autopost' );?> (<i><?php _e( 'optional', 'wp-autopost' );?></i>) :</label></th>
	  <td>
	    <input type="text" name="proxy_user" value="<?php echo $proxy_user; ?>"  size="60"/>
	  </td>
    </tr>
	<tr>
      <th scope="row"><label><?php _e( 'Password', 'wp-autopost' );?> (<i><?php _e( 'optional', 'wp-autopost' );?></i>) :</label></th>
	  <td>
	    <input type="text" name="proxy_pass" value="<?php echo $proxy_pass; ?>"  size="60"/>
	  </td>
    </tr>
   </table>

   <p class="submit"><input type="button" name="save_setting" class="button-primary" value="<?php echo __('Save Changes'); ?>" onclick="updateOption()" ></p>
   

   
   <table class="form-table" width="100%">
	<tr>
      <td colspan="2"><?php _e( 'URL', 'wp-autopost' );?> : <input type="text" name="url" value=""  size="90"/> <input type="button"  class="button" value="<?php echo __('Test','wp-autopost'); ?>" onclick="test_proxy()"></td>
    </tr>
  <?php if(@$show){ ?>
    <tr>
      <td colspan="2" >
	     <textarea cols="180" rows="5"><?php echo htmlspecialchars($result); ?></textarea>
	  </td>
	</tr>
	
	<tr>
      <td colspan="2" style="border-width:2px;border-style:solid;border-color:#dfdfdf">
		<iframe src="<?php echo $fileUrl; ?>"  width="100%" height="600" frameborder="0"  ></iframe>
	  </td>
	</tr>
	
  <?php } ?>
   </table>

  </form>

<?php
  endif;
?>

  
</div>