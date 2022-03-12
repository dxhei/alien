<?php
trait WPJAM_Register_Trait{
	protected $name;
	protected $args;
	protected $filtered	= false;

	public function __construct($name, $args=[]){
		$this->name	= $name;
		$this->args	= $args;
	}

	public function parse_args(){
		return $this->args;
	}

	protected function get_args(){
		if(!$this->filtered){
			$filter	= strtolower(get_called_class()).'_args';
			$args	= $this->parse_args();

			$this->args		= apply_filters($filter, $args, $this->name);
			$this->filtered	= true;
		}

		return $this->args;
	}

	public function __get($key){
		if($key == 'name'){
			return $this->name;
		}else{
			$args	= $this->get_args();
			return $args[$key] ?? null;
		}
	}

	public function __set($key, $value){
		if($key != 'name'){
			$this->args	= $this->get_args();
			$this->args[$key]	= $value;
		}
	}

	public function __isset($key){
		$args	= $this->get_args();
		return isset($args[$key]);
	}

	public function __unset($key){
		$this->args	= $this->get_args();
		unset($this->args[$key]);
	}

	public function to_array(){
		return $this->get_args();
	}

	protected static $_registereds	= [];

	public static function parse_name($name){
		if(empty($name)){
			trigger_error(self::class.'的注册 name 为空');
			return null;
		}elseif(is_numeric($name)){
			trigger_error(self::class.'的注册 name「'.$name.'」'.'为纯数字');
			return null;
		}elseif(!is_string($name)){
			trigger_error(self::class.'的注册 name「'.var_export($name, true).'」不为字符串');
			return null;
		}

		return $name;
	}

	public static function register($name, $args){
		if($name = self::parse_name($name)){
			$instance	= new static($name, $args);

			return self::register_instance($name, $instance);
		}

		return null;
	}

	protected static function register_instance($name, $instance){
		if($name = self::parse_name($name)){
			self::$_registereds[$name]	= $instance;

			return $instance;
		}

		return null;
	}

	public static function unregister($name){
		unset(self::$_registereds[$name]);
	}

	public static function get_by($args=[], $output='objects', $operator='and'){
		return self::get_registereds($args, $output, $operator);
	}

	public static function get_registereds($args=[], $output='objects', $operator='and'){
		$registereds	= $args ? wp_filter_object_list(self::$_registereds, $args, $operator, false) : self::$_registereds;

		if($output == 'names'){
			return array_keys($registereds);
		}elseif(in_array($output, ['args', 'settings'])){
			return array_map(function($registered){ return $registered->to_array(); }, $registereds);
		}else{
			return $registereds;
		}
	}

	public static function get($name){
		return self::$_registereds[$name] ?? null;
	}

	public static function exists($name){
		return isset(self::$_registereds[$name]);
	}
}

trait WPJAM_Type_Trait{
	use WPJAM_Register_Trait;
}

class WPJAM_Meta_Type{
	use WPJAM_Register_Trait;

	private $lazyloader	= null;

	public function __call($method, $args){
		if(in_array($method, ['get_meta', 'add_meta', 'update_meta', 'delete_meta', 'lazyload_meta'])){
			$method	= str_replace('_meta', '_data', $method);
		}elseif(in_array($method, ['delete_meta_by_key', 'update_meta_cache', 'create_meta_table', 'get_meta_table'])){
			$method	= str_replace('_meta', '', $method);
		}

		return call_user_func([$this, $method], ...$args);
	}

	public function lazyload_data($ids){
		if(is_null($this->lazyloader)){
			$this->lazyloader	= wpjam_register_lazyloader($this->name.'_meta', [
				'filter'	=> 'get_'.$this->name.'_metadata', 
				'callback'	=> [$this, 'update_cache']
			]);
		}

		$this->lazyloader->queue_objects($ids);
	}

	public function get_data($id, $meta_key='', $single=false){
		return get_metadata($this->name, $id, $meta_key, $single);
	}

	public function add_data($id, $meta_key, $meta_value, $unique=false){
		return add_metadata($this->name, $id, $meta_key, wp_slash($meta_value), $unique);
	}

	public function update_data($id, $meta_key, $meta_value, $prev_value=''){
		if($meta_value){
			return update_metadata($this->name, $id, $meta_key, wp_slash($meta_value), $prev_value);
		}else{
			return delete_metadata($this->name, $id, $meta_key, $prev_value);
		}
	}

	public function delete_data($id, $meta_key, $meta_value=''){
		return delete_metadata($this->name, $id, $meta_key, $meta_value);
	}

	public function delete_by_key($meta_key){
		return delete_metadata($this->name, null, $meta_key, '', true);
	}

	public function update_cache($object_ids){
		if($object_ids){
			update_meta_cache($this->name, $object_ids);
		}
	}

	public function get_table(){
		return $this->table ?: $GLOBALS['wpdb']->prefix.sanitize_key($this->name).'meta';
	}

	public function create_table(){
		$table	= $this->get_table();

		if($GLOBALS['wpdb']->get_var("show tables like '{$table}'") != $table){
			$column	= sanitize_key($this->name).'_id';

			$GLOBALS['wpdb']->query("CREATE TABLE {$table} (
				meta_id bigint(20) unsigned NOT NULL auto_increment,
				{$column} bigint(20) unsigned NOT NULL default '0',
				meta_key varchar(255) default NULL,
				meta_value longtext,
				PRIMARY KEY  (meta_id),
				KEY {$column} ({$column}),
				KEY meta_key (meta_key(191))
			)");
		}
	}
}

class WPJAM_Lazyloader{
	use WPJAM_Register_Trait;

	private $pending_objects	= [];

	public function callback($check){
		if($this->pending_objects){
			if($this->accepted_args && $this->accepted_args > 1){
				foreach($this->pending_objects as $object){
					call_user_func($this->callback, $object['ids'], ...$object['args']);
				}
			}else{
				call_user_func($this->callback, $this->pending_objects);
			}

			$this->pending_objects	= [];
		}
	
		remove_filter($this->filter, [$this, 'callback']);

		return $check;
	}

	public function queue_objects($object_ids, ...$args){
		if($this->accepted_args && $this->accepted_args > 1){
			if((count($args)+1) >= $this->accepted_args){
				$key	= wpjam_json_encode($args);

				if(isset($this->pending_objects[$key])){
					$this->pending_objects[$key]['ids']	= array_merge($this->pending_objects[$key]['ids'], $object_ids);
					$this->pending_objects[$key]['ids']	= array_unique($this->pending_objects[$key]['ids']);
				}else{
					$this->pending_objects[$key]	= ['ids'=>$object_ids, 'args'=>$args];
				}
			}
		}else{
			$this->pending_objects	= array_merge($this->pending_objects, $object_ids);
			$this->pending_objects	= array_unique($this->pending_objects);
		}

		add_filter($this->filter, [$this, 'callback']);
	}
}

class WPJAM_Show_IF{
	private $show_if;

	public function __construct($show_if){
		$this->show_if	= wp_parse_args($show_if);

		$this->init();
	}

	public function __get($key){
		if($key == 'show_if'){
			return $this->show_if;
		}else{
			return $this->show_if[$key] ?? null;
		}
	}

	public function __set($key, $value){
		$this->show_if[$key]	= $value;
	}

	public function __isset($key){
		return isset($this->show_if);
	}

	public function init(){
		if($this->key){
			$this->compare	= $this->compare ? strtoupper($this->compare) : '=';

			if($this->compare == 'ITEM'){
				$this->show_if	= [];
			}elseif(in_array($this->compare, ['IN', 'NOT IN', 'BETWEEN', 'NOT BETWEEN'])){
				if(!is_array($this->value)){
					$this->value	= preg_split('/[,\s]+/', $this->value);
				}

				if(count($this->value) == 1){
					$this->value	= current($this->value);
					$this->compare	= in_array($this->compare, ['IN', 'BETWEEN']) ? '=' : '!=';
				}
			}else{
				$this->value	= trim($this->value);
			}
		}else{
			$this->show_if	= [];
		}
	}

	public function validate($item){
		if($this->key && isset($item[$this->key])){
			return self::compare($item[$this->key], $this->compare, $this->value);
		}

		return null;	// 没有比较
	}

	public static function compare($value, $operator, $compare_value){
		if(is_array($value)){
			if($operator == '='){
				return in_array($compare_value, $value);
			}else if($operator == '!='){
				return !in_array($compare_value, $value);
			}else if($operator == 'IN'){
				return array_intersect($value, $compare_value) == $compare_value;
			}else if($operator == 'NOT IN'){
				return array_intersect($value, $compare_value) == [];
			}
		}else{
			if($operator == '='){
				return $value == $compare_value;
			}else if($operator == '!='){
				return $value != $compare_value;
			}else if($operator == '>'){
				return $value > $compare_value;
			}else if($operator == '>='){
				return $value >= $compare_value;
			}else if($operator == '<'){
				return $value < $compare_value;
			}else if($operator == '<='){
				return $value <= $compare_value;
			}else if($operator == 'IN'){
				return in_array($value, $compare_value);
			}else if($operator == 'NOT IN'){
				return !in_array($value, $compare_value);
			}else if($operator == 'BETWEEN'){
				return $value > $compare_value[0] && $value < $compare_value[1];
			}else if($operator == 'NOT BETWEEN'){
				return $value < $compare_value[0] && $value > $compare_value[1];
			}
		}

		return false;
	}
}

class WPJAM_Var{
	public $data	= [];

	public static $instance	= null;

	private function __construct(){
		$this->data	= self::parse_user_agent();
	}

	public function __get($name){
		$value	= $this->data[$name] ?? null;
		
		return apply_filters('wpjam_determine_'.$name.'_var', $value);
	}

	public function __isset($key){
		return $this->$key !== null;
	}

	public static function get_instance(){
		if(is_null(self::$instance)){
			self::$instance	= new self();
		}

		return self::$instance;
	}

	public static function get_ip(){
		return $_SERVER['REMOTE_ADDR'] ??'';
	}

	public static function parse_ip($ip=''){
		$ip	= $ip ?: self::get_ip();

		if($ip == 'unknown'){
			return false;
		}

		$ipdata	= IP::find($ip);

		return [
			'ip'		=> $ip,
			'country'	=> $ipdata['0'] ?? '',
			'region'	=> $ipdata['1'] ?? '',
			'city'		=> $ipdata['2'] ?? '',
			'isp'		=> '',
		];
	}

	public static function parse_user_agent($user_agent='', $referer=''){
		$user_agent	= $user_agent ?: ($_SERVER['HTTP_USER_AGENT'] ?? '');
		$user_agent	= $user_agent.' ';	// 为了特殊情况好匹配
		$referer	= $referer ?: $_SERVER['HTTP_REFERER'] ?? '';

		$os = $device =  $app = $browser = '';
		$os_version = $browser_version = $app_version = 0;

		if(strpos($user_agent, 'iPhone') !== false){
			$device	= 'iPhone';
			$os 	= 'iOS';
		}elseif(strpos($user_agent, 'iPad') !== false){
			$device	= 'iPad';
			$os 	= 'iOS';
		}elseif(strpos($user_agent, 'iPod') !== false){
			$device	= 'iPod';
			$os 	= 'iOS';
		}elseif(strpos($user_agent, 'Android') !== false){
			$os		= 'Android';

			if(preg_match('/Android ([0-9\.]{1,}?); (.*?) Build\/(.*?)[\)\s;]{1}/i', $user_agent, $matches)){
				if(!empty($matches[1]) && !empty($matches[2])){
					$os_version	= trim($matches[1]);

					$device		= $matches[2];

					if(strpos($device,';')!==false){
						$device	= substr($device, strpos($device,';')+1, strlen($device)-strpos($device,';'));
					}

					$device		= trim($device);
					// $build	= trim($matches[3]);
				}
			}
		}elseif(stripos($user_agent, 'Windows NT')){
			$os		= 'Windows';
		}elseif(stripos($user_agent, 'Macintosh')){
			$os		= 'Macintosh';
		}elseif(stripos($user_agent, 'Windows Phone')){
			$os		= 'Windows Phone';
		}elseif(stripos($user_agent, 'BlackBerry') || stripos($user_agent, 'BB10')){
			$os		= 'BlackBerry';
		}elseif(stripos($user_agent, 'Symbian')){
			$os		= 'Symbian';
		}else{
			$os		= 'unknown';
		}

		if($os == 'iOS'){
			if(preg_match('/OS (.*?) like Mac OS X[\)]{1}/i', $user_agent, $matches)){
				$os_version	= (float)(trim(str_replace('_', '.', $matches[1])));
			}
		}

		if(strpos($user_agent, 'MicroMessenger') !== false){
			if(strpos($referer, 'https://servicewechat.com') !== false){
				$app	= 'weapp';
			}else{
				$app	= 'weixin';
			}

			if(preg_match('/MicroMessenger\/(.*?)\s/', $user_agent, $matches)){
				$app_version = $matches[1];
			}

			if(preg_match('/NetType\/(.*?)\s/', $user_agent, $matches)){
				$net_type = $matches[1];
			}
		}elseif(strpos($user_agent, 'ToutiaoMicroApp') !== false || strpos($referer, 'https://tmaservice.developer.toutiao.com') !== false){
			$app	= 'bytedance';
		}

		if(strpos($user_agent, 'Lynx') !== false){
			$browser	= 'lynx';
		}elseif(stripos($user_agent, 'safari') !== false){
			$browser	= 'safrai';

			if(preg_match('/Version\/(.*?)\s/i', $user_agent, $matches)){
				$browser_version	= (float)(trim($matches[1]));
			}
		}elseif(strpos($user_agent, 'Edge') !== false){
			$browser	= 'edge';

			if(preg_match('/Edge\/(.*?)\s/i', $user_agent, $matches)){
				$browser_version	= (float)(trim($matches[1]));
			}
		}elseif(stripos($user_agent, 'chrome')){
			$browser	= 'chrome';

			if(preg_match('/Chrome\/(.*?)\s/i', $user_agent, $matches)){
				$browser_version	= (float)(trim($matches[1]));
			}
		}elseif(stripos($user_agent, 'Firefox') !== false){
			$browser	= 'firefox';

			if(preg_match('/Firefox\/(.*?)\s/i', $user_agent, $matches)){
				$browser_version	= (float)(trim($matches[1]));
			}
		}elseif(strpos($user_agent, 'MSIE') !== false || strpos($user_agent, 'Trident') !== false){
			$browser	= 'ie';
		}elseif(strpos($user_agent, 'Gecko') !== false){
			$browser	= 'gecko';
		}elseif(strpos($user_agent, 'Opera') !== false){
			$browser	= 'opera';
		}

		return compact('os', 'device', 'app', 'browser', 'os_version', 'browser_version', 'app_version');
	}
}

class WPJAM_Bit{
	protected $bit;

	public function __construct($bit){
		$this->bit	= $bit;
	}

	public function __get($name){
		return $name == 'bit' ? $this->bit : null;
	}

	public function __isset($name){
		return $name == 'bit';
	}

	public function has($bit){
		return ($this->bit & $bit) == $bit;
	}

	public function add($bit){
		$this->bit = $this->bit | (int)$bit;

		return $this->bit;
	}

	public function remove($bit){
		$this->bit = $this->bit & (~(int)$bit);

		return $this->bit;
	}

	protected function set_bit($bit){
		$this->bit	= $bit;
	}

	protected function get_bit(){
		return $this->bit;
	}

}

class WPJAM_Crypt{
	private $method		= 'aes-256-cbc';
	private $key 		= '';
	private $iv			= '';
	private $options	= OPENSSL_ZERO_PADDING;
	private $block_size	= 32;	// 注意 PHP 默认 aes cbc 算法的 block size 都是 16 位

	public function __construct($args=[]){
		foreach ($args as $key => $value) {
			if(in_array($key, ['key', 'method', 'options', 'iv', 'block_size'])){
				$this->$key	= $value;
			}
		}
	}

	public function encrypt($text){
		if($this->options == OPENSSL_ZERO_PADDING && $this->block_size){
			$text	= $this->pkcs7_pad($text, $this->block_size);	//使用自定义的填充方式对明文进行补位填充
		}

		return openssl_encrypt($text, $this->method, $this->key, $this->options, $this->iv);
	}

	public function decrypt($encrypted_text){
		try{
			$text	= openssl_decrypt($encrypted_text, $this->method, $this->key, $this->options, $this->iv);
		}catch(Exception $e){
			return new WP_Error('decrypt_aes_failed', 'aes 解密失败');
		}

		if($this->options == OPENSSL_ZERO_PADDING && $this->block_size){
			$text	= $this->pkcs7_unpad($text, $this->block_size);	//去除补位字符
		}

		return $text;
	}

	public static function pkcs7_pad($text, $block_size=32){	//对需要加密的明文进行填充 pkcs#7 补位
		//计算需要填充的位数
		$amount_to_pad	= $block_size - (strlen($text) % $block_size);
		$amount_to_pad	= $amount_to_pad ?: $block_size;

		//获得补位所用的字符
		return $text . str_repeat(chr($amount_to_pad), $amount_to_pad);
	}

	public static function pkcs7_unpad($text, $block_size){	//对解密后的明文进行补位删除
		$pad	= ord(substr($text, -1));

		if($pad < 1 || $pad > $block_size){
			$pad	= 0;
		}

		return substr($text, 0, (strlen($text) - $pad));
	}

	public static function weixin_pad($text, $appid){
		$random = self::generate_random_string(16);		//获得16位随机字符串，填充到明文之前
		return $random.pack("N", strlen($text)).$text.$appid;
	}

	public static function weixin_unpad($text, &$appid){	//去除16位随机字符串,网络字节序和AppId
		$text		= substr($text, 16, strlen($text));
		$len_list	= unpack("N", substr($text, 0, 4));
		$text_len	= $len_list[1];
		$appid		= substr($text, $text_len + 4);
		return substr($text, 4, $text_len);
	}

	public static function sha1(...$args){
		sort($args, SORT_STRING);

		return sha1(implode($args));
	}

	public static function generate_weixin_signature($token, &$timestamp='', &$nonce='', $encrypt_msg=''){
		$timestamp	= $timestamp ?: time();
		$nonce		= $nonce ?: self::generate_random_string(8);
		return self::sha1($encrypt_msg, $token, $timestamp, $nonce);
	}

	public static function generate_random_string($length){
		$alphabet	= "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
		$max		= strlen($alphabet);

		$token		= '';
		for ($i = 0; $i < $length; $i++) {
			$token .= $alphabet[self::crypto_rand_secure(0, $max - 1)];
		}

		return $token;
	}

	private static function crypto_rand_secure($min, $max){
		$range	= $max - $min;

		if($range < 1){
			return $min;
		}

		$log	= ceil(log($range, 2));
		$bytes	= (int)($log / 8) + 1;		// length in bytes
		$bits	= (int)$log + 1;			// length in bits
		$filter	= (int)(1 << $bits) - 1;	// set all lower bits to 1

		do {
			$rnd	= hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
			$rnd	= $rnd & $filter;	// discard irrelevant bits
		}while($rnd > $range);

		return $min + $rnd;
	}
}

class IP{
	private static $ip = null;
	private static $fp = null;
	private static $offset = null;
	private static $index = null;
	private static $cached = [];

	public static function find($ip){
		if (empty( $ip ) === true) {
			return 'N/A';
		}

		$nip	= gethostbyname($ip);
		$ipdot	= explode('.', $nip);

		if ($ipdot[0] < 0 || $ipdot[0] > 255 || count($ipdot) !== 4) {
			return 'N/A';
		}

		if (isset( self::$cached[$nip] ) === true) {
			return self::$cached[$nip];
		}

		if (self::$fp === null) {
			self::init();
		}

		$nip2 = pack('N', ip2long($nip));

		$tmp_offset	= (int) $ipdot[0] * 4;
		$start		= unpack('Vlen',
			self::$index[$tmp_offset].self::$index[$tmp_offset + 1].self::$index[$tmp_offset + 2].self::$index[$tmp_offset + 3]);

		$index_offset = $index_length = null;
		$max_comp_len = self::$offset['len'] - 1024 - 4;
		for ($start = $start['len'] * 8 + 1024; $start < $max_comp_len; $start += 8) {
			if (self::$index[$start].self::$index[$start+1].self::$index[$start+2].self::$index[$start+3] >= $nip2) {
				$index_offset = unpack('Vlen',
					self::$index[$start+4].self::$index[$start+5].self::$index[$start+6]."\x0");
				$index_length = unpack('Clen', self::$index[$start+7]);

				break;
			}
		}

		if ($index_offset === null) {
			return 'N/A';
		}

		fseek(self::$fp, self::$offset['len'] + $index_offset['len'] - 1024);

		self::$cached[$nip] = explode("\t", fread(self::$fp, $index_length['len']));

		return self::$cached[$nip];
	}

	private static function init(){
		if(self::$fp === null){
			self::$ip = new self();

			self::$fp = fopen(WP_CONTENT_DIR.'/uploads/17monipdb.dat', 'rb');
			if (self::$fp === false) {
				throw new Exception('Invalid 17monipdb.dat file!');
			}

			self::$offset = unpack('Nlen', fread(self::$fp, 4));
			if (self::$offset['len'] < 4) {
				throw new Exception('Invalid 17monipdb.dat file!');
			}

			self::$index = fread(self::$fp, self::$offset['len'] - 4);
		}
	}

	public function __destruct(){
		if(self::$fp !== null){
			fclose(self::$fp);
		}
	}
}

class WPJAM_AJAX{
	use WPJAM_Register_Trait;

	public function create_nonce($args=[]){
		$nonce_action	= $this->name;

		if($this->nonce_keys){
			foreach($this->nonce_keys as $key){
				if(!empty($args[$key])){
					$nonce_action	.= ':'.$args[$key];
				}
			}
		}

		return wp_create_nonce($nonce_action);
	}

	public function verify_nonce($nonce){
		$nonce_action	= $this->name;

		if($this->nonce_keys){
			foreach($this->nonce_keys as $key){
				if($value = wpjam_get_data_parameter($key)){
					$nonce_action	.= ':'.$value;
				}
			}
		}

		return wp_verify_nonce($nonce, $nonce_action);
	}

	public function callback(){
		if(!$this->callback || !is_callable($this->callback)){
			wp_die('0', 400);
		}
		
		$nonce	= wpjam_get_parameter('_ajax_nonce', ['method'=>'POST']);

		if(!$this->verify_nonce($nonce)){
			wpjam_send_json(['errcode'=>'invalid_nonce', 'errmsg'=>'非法操作']);
		}

		$result	= call_user_func($this->callback);

		wpjam_send_json($result);
	}

	public function get_data_attr($data=[], $return=''){
		$attr	= [
			'action'	=> $this->name,
			'nonce'		=> $this->create_nonce($data),
		];

		if($data){
			$attr['data']	= http_build_query($data);
		}

		return $return ? $attr : wpjam_data_attribute_string($attr);
	}

	
	public static $enqueued	= null;

	public static function enqueue_scripts(){
		if(isset(self::$enqueued)){
			return;
		}

		self::$enqueued	= true;

		$scripts	= '
if(typeof ajaxurl == "undefined"){
	var ajaxurl	= "'.admin_url('admin-ajax.php').'";
}

jQuery(function($){
	if(window.location.protocol == "https:"){
		ajaxurl	= ajaxurl.replace("http://", "https://");
	}

	$.fn.extend({
		wpjam_submit: function(callback){
			let _this	= $(this);
			
			$.post(ajaxurl, {
				action:			$(this).data(\'action\'),
				_ajax_nonce:	$(this).data(\'nonce\'),
				data:			$(this).serialize()
			},function(data, status){
				callback.call(_this, data);
			});
		},
		wpjam_action: function(callback){
			let _this	= $(this);
			
			$.post(ajaxurl, {
				action:			$(this).data(\'action\'),
				_ajax_nonce:	$(this).data(\'nonce\'),
				data:			$(this).data(\'data\')
			},function(data, status){
				callback.call(_this, data);
			});
		}
	});
});
		';

		if(did_action('wpjam_static') && !wpjam_is_login()){
			wpjam_register_static('wpjam-script',	['title'=>'AJAX 基础脚本', 'type'=>'script',	'source'=>'value',	'value'=>$scripts]);
		}else{
			wp_enqueue_script('jquery');
			wp_add_inline_script('jquery', $scripts);
		}
	}
}

class WPJAM_Capability{
	use WPJAM_Register_Trait;

	public static function filter($caps, $cap, $user_id, $args){
		if(in_array('do_not_allow', $caps) || empty($user_id)){
			return $caps;
		}

		if($object = self::get($cap)){
			return call_user_func($object->map_meta_cap, $user_id, $args, $cap);
		}

		return $caps;
	}
}

class WPJAM_Verify_TXT{
	use WPJAM_Register_Trait;

	public function get_data($key=''){
		$data	= wpjam_get_setting('wpjam_verify_txts', $this->name) ?: [];

		return $key ? ($data[$key] ?? '') : $data;
	}

	public function set_data($data){
		return wpjam_update_setting('wpjam_verify_txts', $this->name, $data) || true;
	}

	public function get_fields(){
		$data	= $this->get_data();

		return [
			'name'	=>['title'=>'文件名称',	'type'=>'text',	'required', 'value'=>$data['name'] ?? '',	'class'=>'all-options'],
			'value'	=>['title'=>'文件内容',	'type'=>'text',	'required', 'value'=>$data['value'] ?? '']
		];
	}

	public static function __callStatic($method, $args){
		$name	= $args[0];

		if($object = self::get($name)){
			if(in_array($method, ['get_name', 'get_value'])){
				return $object->get_data(str_replace('get_', '', $method));
			}elseif($method == 'set' || $method == 'set_value'){
				return $object->set_data(['name'=>$args[1], 'value'=>$args[2]]);
			}
		}	
	}

	public static function filter_root_rewrite_rules($root_rewrite){
		if(empty($GLOBALS['wp_rewrite']->root)){
			$home_path	= parse_url(home_url());

			if(empty($home_path['path']) || '/' == $home_path['path']){
				$root_rewrite	= array_merge(['([^/]+)\.txt?$'=>'index.php?module=txt&action=$matches[1]'], $root_rewrite);
			}
		}
		
		return $root_rewrite;
	}

	public static function module($action){
		if($values = wpjam_get_option('wpjam_verify_txts')){
			$name	= str_replace('.txt', '', $action).'.txt';
			
			foreach($values as $key => $value) {
				if($value['name'] == $name){
					header('Content-Type: text/plain');
					echo $value['value'];

					exit;
				}
			}
		}

		wp_die('错误');
	}
}

class WPJAM_Cache_Group{
	private $group;

	public function __construct($group, $global=false){
		$this->group	= $group;

		if($global){
			wp_cache_add_global_groups($group);
		}
	}

	public function cache_get($key){
		return wp_cache_get($key, $this->group);
	}

	public function cache_add($key, $value, $time=DAY_IN_SECONDS){
		return wp_cache_add($key, $value, $this->group, $time);
	}

	public function cache_set($key, $value, $time=DAY_IN_SECONDS){
		return wp_cache_set($key, $value, $this->group, $time);
	}

	public function cache_delete($key){
		return wp_cache_delete($key, $this->group);
	}

	private static $instances	= [];

	public static function get_instance($group, $global=false){
		if(!isset(self::$instances[$group])){
			self::$instances[$group]	= new self($group, $global);
		}

		return self::$instances[$group];
	}
}

class WPJAM_ListCache{
	private $key;

	public function __construct($key){
		$this->key	= $key;
	}

	private function get_items(&$cas_token){
		$items	= wp_cache_get_with_cas($this->key, 'wpjam_list_cache', $cas_token);

		if($items === false){
			$items	= [];
			wp_cache_add($this->key, [], 'wpjam_list_cache', DAY_IN_SECONDS);
			$items	= wp_cache_get_with_cas($this->key, 'wpjam_list_cache', $cas_token);
		}

		return $items;
	}

	private function set_items($cas_token, $items){
		return wp_cache_cas($cas_token, $this->key, $items, 'wpjam_list_cache', DAY_IN_SECONDS);
	}

	public function get_all(){
		$items	= wp_cache_get($this->key, 'wpjam_list_cache');
		return $items ?: [];
	}

	public function get($k){
		$items = $this->get_all();
		return $items[$k]??false;  
	}

	public function add($item, $k=null){
		$cas_token	= '';
		$retry		= 10;

		do{
			$items	= $this->get_items($cas_token);

			if($k!==null){
				if(isset($items[$k])){
					return false;
				}

				$items[$k]	= $item;
			}else{
				$items[]	= $item;
			}

			$result	= $this->set_items($cas_token, $items);

			$retry	 -= 1;
		}while (!$result && $retry > 0);

		return $result;
	}

	public function increment($k, $offset=1){
		$cas_token	= '';
		$retry		= 10;

		do{
			$items		= $this->get_items($cas_token);
			$items[$k]	= $items[$k]??0; 
			$items[$k]	= $items[$k]+$offset;

			$result	= $this->set_items($cas_token, $items);

			$retry	 -= 1;
		}while (!$result && $retry > 0);

		return $result;
	}

	public function decrement($k, $offset=1){
		return $this->increment($k, 0-$offset);
	}

	public function set($item, $k){
		$cas_token	= '';
		$retry		= 10;

		do{
			$items		= $this->get_items($cas_token);
			$items[$k]	= $item;
			$result		= $this->set_items($cas_token, $items);
			$retry 		-= 1;
		}while(!$result && $retry > 0);

		return $result;
	}

	public function remove($k){
		$cas_token	= '';
		$retry		= 10;

		do{
			$items	= $this->get_items($cas_token);
			if(!isset($items[$k])){
				return false;
			}
			unset($items[$k]);
			$result	= $this->set_items($cas_token, $items);
			$retry 	-= 1;
		}while(!$result && $retry > 0);

		return $result;
	}

	public function empty(){
		$cas_token		= '';
		$retry	= 10;

		do{
			$items	= $this->get_items($cas_token);
			if($items == []){
				return [];
			}
			$result	= $this->set_items($cas_token, []);
			$retry 	-= 1;
		}while(!$result && $retry > 0);

		if($result){
			return $items;
		}

		return $result;
	}
}

class WPJAM_Cache{
	/* HTML 片段缓存
	Usage:

	if (!WPJAM_Cache::output('unique-key')) {
		functions_that_do_stuff_live();
		these_should_echo();
		WPJAM_Cache::store(3600);
	}
	*/
	public static function output($key) {
		$output	= get_transient($key);
		if(!empty($output)) {
			echo $output;
			return true;
		} else {
			ob_start();
			return false;
		}
	}

	public static function store($key, $cache_time='600') {
		$output = ob_get_flush();
		set_transient($key, $output, $cache_time);
		echo $output;
	}
}

class WPJAM_Theme_Upgrader{
	use WPJAM_Register_Trait;

	public function filter_site_transient($transient){
		if($this->upgrader_url){
			$theme	= $this->name;
	
			if(empty($transient->checked[$theme])){
				return $transient;
			}
			
			$remote	= get_transient('wpjam_theme_upgrade_'.$theme);

			if(false == $remote){
				$remote = wpjam_remote_request($this->upgrader_url);
		 
				if(!is_wp_error($remote)){
					set_transient('wpjam_theme_upgrade_'.$theme, $remote, HOUR_IN_SECONDS*12);
				}
			}

			if($remote && !is_wp_error($remote)){
				if(version_compare($transient->checked[$theme], $remote['new_version'], '<')){
					$transient->response[$theme]	= $remote;
				}
			}
		}
		
		return $transient;
	}
}

wp_cache_add_global_groups(['wpjam_list_cache']);
class_alias('WPJAM_Verify_TXT', 'WPJAM_VerifyTXT');