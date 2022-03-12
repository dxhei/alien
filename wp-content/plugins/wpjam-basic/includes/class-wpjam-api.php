<?php
class WPJAM_JSON{
	use WPJAM_Register_Trait;

	public function response(){
		do_action('wpjam_json_response', $this);

		$current_user	= wpjam_get_current_user($this->auth);

		if(is_wp_error($current_user)){
			wpjam_send_json($current_user);
		}

		$response	= [
			'errcode'		=> 0,
			'current_user'	=> $current_user
		];

		if($_SERVER['REQUEST_METHOD'] != 'POST'){
			$response['page_title']		= (string)$this->page_title;
			$response['share_title']	= (string)$this->share_title ;
			$response['share_image']	= (string)$this->share_image;
		}

		if($this->modules){
			if(!wp_is_numeric_array($this->modules)){
				$this->modules	= [$this->modules];
			}

			foreach($this->modules as $module){
				if(empty($module['args'])){
					continue;
				}

				$module_args	= is_array($module['args']) ? $module['args'] : wpjam_parse_shortcode_attr(stripslashes_deep($module['args']), 'module');
				$module_type	= $module['type'] ?? '';

				if($module_type == 'post_type'){
					$result	= $this->parse_post_type_module($module_args);
				}elseif($module_type == 'taxonomy'){
					$result	= $this->parse_taxonomy_module($module_args);
				}elseif($module_type == 'setting'){
					$result	= $this->parse_setting_module($module_args);
				}elseif($module_type == 'media'){
					$result	= $this->parse_media_module($module_args);
				}else{
					$result	= $module_args;
				}

				$response	= $this->merge_result($result, $response);
			}
		}elseif($this->callback || $this->template){
			if($this->callback && is_callable($this->callback)){
				$result	= call_user_func($this->callback, $this->args, $this->name);
			}elseif($this->template && is_file($this->template)){
				$result	= include $this->template;
			}else{
				$result	= null;
			}

			$response	= $this->merge_result($result, $response);
		}else{
			$response	= $this->merge_result($this->args, $response);
		}

		$response	= apply_filters('wpjam_json', $response, $this->args, $this->name);

		if($_SERVER['REQUEST_METHOD'] != 'POST'){
			if(empty($response['page_title'])){
				$response['page_title']		= html_entity_decode(wp_get_document_title());
			}

			if(empty($response['share_title'])){
				$response['share_title']	= $response['page_title'];
			}

			if(!empty($response['share_image'])){
				$response['share_image']	= wpjam_get_thumbnail($response['share_image'], '500x400');
			}
		}

		wpjam_send_json($response);
	}

	protected static function merge_result($result, $response){
		if(is_wp_error($result)){
			wpjam_send_json($result);
		}elseif(is_array($result)){
			$except	= [];

			foreach(['page_title', 'share_title', 'share_image'] as $key){
				if(!empty($response[$key]) && isset($result[$key])){
					$except[]	= $key;
				}
			}

			if($except){
				$result	= wpjam_array_except($result, $except);
			}

			$response	= array_merge($response, $result);
		}

		return $response;
	}

	protected static $current_json	= '';

	public static function is_request(){
		if(get_option('permalink_structure')){
			if(preg_match("/\/api\/(.*)\.json/", $_SERVER['REQUEST_URI'])){ 
				return true;
			}
		}else{
			if(isset($_GET['module']) && $_GET['module'] == 'json'){
				return true;
			}
		}

		return false;
	}

	public static function module($action){
		if(!wpjam_doing_debug()){ 
			self::send_origin_headers();

			if(wp_is_jsonp_request()){
				@header('Content-Type: application/javascript; charset='.get_option('blog_charset'));
			}else{
				@header('Content-Type: application/json; charset='.get_option('blog_charset'));
			}
		}

		if(strpos($action, 'mag.') !== 0){
			return;
		}

		self::$current_json	= $json	= str_replace(['mag.','/'], ['','.'], $action);

		do_action('wpjam_api', $json);

		if($json_obj = self::get($json)){
			$json_obj->response();
		}else{
			wpjam_send_json(['errcode'=>'api_not_defined',	'errmsg'=>'接口未定义！']);
		}
	}

	protected static function send_origin_headers(){
		header('X-Content-Type-Options: nosniff');

		if($origin	= get_http_origin()){
			// Requests from file:// and data: URLs send "Origin: null"
			if('null' !== $origin){
				$origin	= esc_url_raw($origin);
			}

			@header('Access-Control-Allow-Origin: ' . $origin);
			@header('Access-Control-Allow-Methods: GET, POST');
			@header('Access-Control-Allow-Credentials: true');
			@header('Access-Control-Allow-Headers: Authorization, Content-Type');
			@header('Vary: Origin');

			if('OPTIONS' === $_SERVER['REQUEST_METHOD']){
				exit;
			}
		}

		if('OPTIONS' === $_SERVER['REQUEST_METHOD']){
			status_header(403);
			exit;
		}
	}

	public static function get_current(){
		return self::$current_json;
	}

	public static function parse_post_type_module($module_args){
		$module_action	= $module_args['action'] ?? '';

		if(empty($module_action)){
			wpjam_send_json(['errcode'=>'empty_action',	'errmsg'=>'没有设置 action']);
		}

		global $wp, $wpjam_query_vars;	// 两个 post 模块的时候干扰。。。。

		if(empty($wpjam_query_vars)){
			$wpjam_query_vars	= $wp->query_vars; 
		}else{
			$wp->query_vars		= $wpjam_query_vars;
		}

		if($module_action == 'list'){
			return self::parse_post_list_module($module_args);
		}elseif($module_action == 'get'){
			return self::parse_post_get_module($module_args);
		}elseif($module_action == 'upload'){
			return self::parse_media_upload_module($module_args);
		}
	}

	/* 规则：
	** 1. 分成主的查询和子查询（$query_args['sub']=1）
	** 2. 主查询支持 $_GET 参数 和 $_GET 参数 mapping
	** 3. 子查询（sub）只支持 $query_args 参数
	** 4. 主查询返回 next_cursor 和 total_pages，current_page，子查询（sub）没有
	** 5. $_GET 参数只适用于 post.list 
	** 6. term.list 只能用 $_GET 参数 mapping 来传递参数
	*/
	public static function parse_post_list_module($query_args){
		global $wp, $wp_query;

		$is_main_query	= empty($query_args['sub']);

		if(!$is_main_query){	// 子查询不支持 $_GET 参数
			$wp->query_vars	= [];
		}

		// 缓存处理
		$wp->set_query_var('cache_results', true);

		$output	= wpjam_array_pull($query_args, 'output');

		foreach($query_args as $query_key => $query_var){
			$wp->set_query_var($query_key, $query_var);
		}

		$post_type	= $wp->query_vars['post_type'] ?? '';

		if(empty($output)){
			$output	= ($post_type && !is_array($post_type)) ? $post_type.'s' : 'posts';
		}

		if($is_main_query){
			if($posts_per_page = (int)wpjam_get_parameter('posts_per_page')){
				$wp->set_query_var('posts_per_page', ($posts_per_page > 20 ? 20 : $posts_per_page));
			}

			if($offset = (int)wpjam_get_parameter('offset')){
				$wp->set_query_var('offset', $offset);
			}

			$orderby	= $wp->query_vars['orderby'] ?? 'date';
			$paged		= $wp->query_vars['paged'] ?? null;
			$use_cursor	= (empty($paged) && is_null(wpjam_get_parameter('s')) && !is_array($orderby) && in_array($orderby, ['date', 'post_date']));

			if($use_cursor){
				if($cursor = (int)wpjam_get_parameter('cursor')){
					$wp->set_query_var('cursor', $cursor);
					$wp->set_query_var('ignore_sticky_posts', true);
				}

				if($since = (int)wpjam_get_parameter('since')){
					$wp->set_query_var('since', $since);
					$wp->set_query_var('ignore_sticky_posts', true);
				}
			}

			// taxonomy 参数处理，同时支持 $_GET 和 $query_args 参数
			$taxonomies	= $post_type ? get_object_taxonomies($post_type) : get_taxonomies(['public'=>true]);
			$taxonomies	= array_diff($taxonomies, ['post_format']);

			if(wpjam_array_pull($taxonomies, 'category') && empty($wp->query_vars['cat'])){
				foreach(['category_id', 'cat_id'] as $cat_key){
					if($term_id	= (int)wpjam_get_parameter($cat_key)){
						$wp->set_query_var('cat', $term_id);
						break;
					}
				}
			}

			foreach($taxonomies as $taxonomy){
				$query_key	= wpjam_get_taxonomy_query_key($taxonomy);

				if($term_id	= (int)wpjam_get_parameter($query_key)){
					$wp->set_query_var($query_key, $term_id);
				}
			}

			if($term_id	= (int)wpjam_get_parameter('term_id')){
				if($taxonomy = wpjam_get_parameter('taxonomy')){
					$wp->set_query_var('term_id', $term_id);
					$wp->set_query_var('taxonomy', $taxonomy);
				}
			}
		}

		wpjam_parse_query_vars($wp);

		$wp->query_posts();

		$_posts = [];

		while($wp_query->have_posts()){
			$wp_query->the_post();

			$_posts[]	= wpjam_get_post(get_the_ID(), $query_args);
		}

		$posts_json = [];

		if($is_main_query){
			if(is_category() || is_tag() || is_tax()){
				if($current_term = get_queried_object()){
					$taxonomy		= $current_term->taxonomy;
					$current_term	= wpjam_get_term($current_term, $taxonomy);

					$posts_json['current_taxonomy']		= $taxonomy;
					$posts_json['current_'.$taxonomy]	= $current_term;
				}else{
					$posts_json['current_taxonomy']		= null;
				}
			}elseif(is_author()){
				if($author = $wp_query->get('author')){
					$posts_json['current_author']	= WPJAM_User::get_instance($author)->parse_for_json();
				}else{
					$posts_json['current_author']	= null;
				}
			}

			$posts_json['total']		= (int)$wp_query->found_posts;
			$posts_json['total_pages']	= (int)$wp_query->max_num_pages;
			$posts_json['current_page']	= (int)($wp_query->get('paged') ?: 1);

			if($use_cursor){
				$posts_json['next_cursor']	= ($_posts && $wp_query->max_num_pages>1) ? end($_posts)['timestamp'] : 0;
			}

			$posts_json['page_title']	= $posts_json['share_title'] = html_entity_decode(wp_get_document_title());
		}

		$posts_json[$output]	= $_posts;

		return apply_filters('wpjam_posts_json', $posts_json, $wp_query, $output);
	}

	public static function parse_post_get_module($query_args){
		global $wp, $wp_query;

		$post_id	= $query_args['id'] ?? (int)wpjam_get_parameter('id');
		$post_type	= $query_args['post_type'] ?? wpjam_get_parameter('post_type',	['default'=>'any']);

		if($post_type != 'any'){
			$pt_obj	= get_post_type_object($post_type);

			if(!$pt_obj){
				wpjam_send_json(['errcode'=>'post_type_not_exists',	'errmsg'=>'post_type 未定义']);
			}
		}

		if(empty($post_id)){
			if($post_type == 'any'){
				wpjam_send_json(['errcode'=>'empty_post_id',	'errmsg'=>'文章ID不能为空']);
			}

			$orderby	= wpjam_get_parameter('orderby');

			if($orderby == 'rand'){
				$wp->set_query_var('orderby', 'rand');
			}else{
				$name_key	= $pt_obj->hierarchical ? 'pagename' : 'name';

				$wp->set_query_var($name_key,	wpjam_get_parameter($name_key,	['required'=>true]));
			}
		}else{
			$wp->set_query_var('p', $post_id);
		}

		$wp->set_query_var('post_type', $post_type);
		$wp->set_query_var('posts_per_page', 1);
		$wp->set_query_var('cache_results', true);

		$wp->query_posts();

		if($wp_query->have_posts()){
			$post_id	= $wp_query->post->ID;
		}else{
			if($post_name = get_query_var('name')){
				if($post_id = apply_filters('old_slug_redirect_post_id', null)){
					$post_type	= 'any';

					$wp->set_query_var('post_type', $post_type);
					$wp->set_query_var('posts_per_page', 1);
					$wp->set_query_var('p', $post_id);
					$wp->set_query_var('name', '');
					$wp->set_query_var('pagename', '');

					$wp->query_posts();
				}else{
					wpjam_send_json(['errcode'=>'empty_query',	'errmsg'=>'查询结果为空']);
				}
			}else{
				wpjam_send_json(['errcode'=>'empty_query',	'errmsg'=>'查询结果为空']);
			}
		}

		$_post	= wpjam_get_post($post_id, $query_args);

		$post_json	= [];

		$post_json['page_title']	= html_entity_decode(wp_get_document_title());

		if($share_title = wpjam_array_pull($_post, 'share_title')){
			$post_json['share_title']	= $share_title;
		}else{
			$post_json['share_title']	= $post_json['page_title'];
		}

		if($share_image = wpjam_array_pull($_post, 'share_image')){
			$post_json['share_image']	= $share_image;
		}

		$output	= $query_args['output'] ?? '';
		$output	= $output ?: $_post['post_type'];

		$post_json[$output]	= $_post;

		return $post_json;
	}

	public static function parse_taxonomy_module($module_args){
		$taxonomy	= $module_args['taxonomy'] ?? '';
		$tax_obj	= $taxonomy ? get_taxonomy($taxonomy) : null;

		if(empty($tax_obj)){
			wpjam_send_json(['errcode'=>'invalid_taxonomy',	'errmsg'=>'无效的自定义分类']);
		}

		$args	= $module_args;

		if($mapping = wpjam_array_pull($args, 'mapping')){
			$mapping	= wp_parse_args($mapping);

			if($mapping && is_array($mapping)){
				foreach($mapping as $key => $get){
					if($value = wpjam_get_parameter($get)){
						$args[$key]	= $value;
					}
				}
			}
		}

		$number		= (int)wpjam_array_pull($args, 'number');
		$output		= wpjam_array_pull($args, 'output') ?: $taxonomy.'s';
		$max_depth	= wpjam_array_pull($args, 'max_depth') ?: ($tax_obj->levels ?? -1);

		$terms_json	= [];

		if($terms = wpjam_get_terms($args, $max_depth)){
			if($number){
				$paged	= $args['paged'] ?? 1;
				$offset	= $number * ($paged-1);

				$terms_json['current_page']	= (int)$paged;
				$terms_json['total_pages']	= ceil(count($terms)/$number);
				$terms = array_slice($terms, $offset, $number);
			}

			$terms_json[$output]	= array_values($terms);
		}else{
			$terms_json[$output]	= [];
		}

		$terms_json['page_title']	= $tax_obj->label;

		return $terms_json;
	}

	public static function parse_media_upload_module($module_args){
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/media.php';
		require_once ABSPATH . 'wp-admin/includes/image.php';

		$media_id	= $module_args['media'] ?? 'media';
		$output		= $module_args['output'] ?? 'url';

		if (!isset($_FILES[$media_id])) {
			wpjam_send_json(['errcode'=>'empty_media',	'errmsg'=>'媒体流不能为空！']);
		}

		$post_id		= (int)wpjam_get_parameter('post_id',	['method'=>'POST', 'default'=>0]);
		$attachment_id	= media_handle_upload($media_id, $post_id);

		if(is_wp_error($attachment_id)){
			wpjam_send_json($attachment_id);
		}

		return [$output=>wp_get_attachment_url($attachment_id)];
	}

	public static function parse_media_module($module_args){
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/media.php';
		require_once ABSPATH . 'wp-admin/includes/image.php';

		$media_id	= $module_args['media'] ?? 'media';
		$output		= $module_args['output'] ?? 'url';

		if(!isset($_FILES[$media_id])){
			wpjam_send_json(['errcode'=>'empty_media',	'errmsg'=>'媒体流不能为空！']);
		}

		$upload_file	= wp_handle_upload($_FILES[$media_id], ['test_form'=>false]);

		if(isset($upload_file['error'])){
			wpjam_send_json(['errcode'=>'upload_error',	'errmsg'=>$upload_file['error']]);
		}

		return [$output=>$upload_file['url']];
	}

	public static function parse_setting_module($module_args){
		if(empty($module_args['option_name'])){
			return new WP_Error('empty_option_name', 'option_name 不能为空');
		}

		$option_name	= $module_args['option_name'] ?? '';
		$setting_name	= $module_args['setting_name'] ?? ($module_args['setting'] ?? '');
		$output			= $module_args['output'] ?? '';

		if($setting_name){
			$output	= $output ?: $setting_name; 
			$value	= wpjam_get_setting($option_name, $setting_name);
		}else{
			$output	= $output ?: $option_name;
			$value	= wpjam_get_option($option_name);
		}

		$value	= apply_filters('wpjam_setting_json', $value, $option_name, $setting_name);

		if(is_wp_error($value)){
			return $value;
		}

		return [$output=>$value];
	}
}

class WPJAM_API{
	public static function __callStatic($method, $args){
		$function	= 'wpjam_'.$method;

		if(function_exists($function)){
			return call_user_func($function, ...$args);
		}
	}

	public static function get_apis(){
		return WPJAM_JSON::get_by();
	}
}