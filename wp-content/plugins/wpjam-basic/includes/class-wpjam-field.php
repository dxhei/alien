<?php
class WPJAM_Field{
	private $field = [];

	public function __construct($field, $key=''){
		foreach($field as $attr => $value){
			if(is_numeric($attr)){
				$attr	= $value = strtolower(trim($value));

				if(!self::is_bool_attr($attr)){
					continue;
				}
			}else{
				$attr	= strtolower(trim($attr));
			
				if(self::is_bool_attr($attr)){
					if(!$value){
						continue;
					}

					$value	= $attr;
				}
			}

			$this->field[$attr]	= $value;
		}

		$this->options	= wp_parse_args($this->options);

		$this->type		= $this->type 	?: ($this->options ? 'select' : 'text');
		$this->key		= $this->key	?: $key;
		$this->name		= $this->name	?: $this->key;
		$this->id		= $this->id		?: $this->key;
	}

	public function __get($key){
		if($key == 'option_values'){
			if($this->type == 'select'){
				$allows	= [];

				foreach($this->options as $opt_value => $opt_title){
					if(!empty($opt_title['options'])){
						foreach($opt_title['options'] as $sub_opt_value => $sub_opt_title){
							$allows[]	= (string)$sub_opt_value;
						}
					}else{
						$allows[]	= (string)$opt_value;
					}
				}

				return $allows;
			}else{
				return array_map('strval', array_keys($this->options));
			}
		}elseif($key == 'mu_type'){
			return in_array($this->type, ['mu-image', 'mu-file', 'mu-text', 'mu-img', 'mu-fields'], true);
		}else{
			$value	= $this->field[$key] ?? null;

			if($key == 'fields'){
				$value	= $value ?: [];

				if($this->type == 'fieldset'){
					foreach($value as $key => &$field){
						$field['key']	= $key;
						$field['name']	= $field['name'] ?? $key;

						if($this->fieldset_type == 'array'){
							$field['name']	= $this->name.$this->generate_sub_name($field['name']);
							$field['key']	= $this->key.'_'.$key;
						}
					}
				}
			}elseif($key == 'max_items'){
				$value	= is_null($value) ? $this->total : $value;
				
				return (int)$value;
			}elseif(in_array($key, ['min', 'max', 'minlength', 'maxlength'])){
				return is_numeric($value) ? $value : null;
			}

			return $value;
		}
	}

	public function __set($key, $value){
		$this->field[$key]	= $value;
	}

	public function __isset($key){
		return isset($this->field[$key]);
	}

	public function __unset($key){
		unset($this->field[$key]);
	}

	public function validate($value, $validate=true){
		$title		= $this->title ?: $this->key;
		$title		= '???'.$title.'???';
		$required	= $validate ? $this->required : false;

		if(is_null($value) && $required){
			return new WP_Error('value_required', $title.'??????????????????');
		}

		if($this->validate_callback && is_callable($this->validate_callback)){
			$result	= call_user_func($this->validate_callback, $value);

			if($result === false){
				return $validate ? new WP_Error('invalid_value', $title.'????????????') : null;
			}elseif(is_wp_error($result)){
				return $validate ? $result : null;
			}
		}

		if($this->type == 'checkbox'){
			if($this->options){
				$value	= is_array($value) ? $value : [];
				$value	= array_values(array_intersect($this->option_values, $value));

				if($value){
					if($this->max_items && count($value) > $this->max_items){
						$value	= array_slice($value, 0, $this->max_items);
					}
				}else{
					if($required){
						$value	= null;
					}
				}
			}else{
				if($validate){
					$value	= (int)$value;
				}
			}
		}elseif($this->mu_type){
			if($value){
				if(!is_array($value)){
					$value	= wpjam_json_decode($value);
				}else{
					$value	= wpjam_array_filter($value, function($item){ return !empty($item) || is_numeric($item); });
				}
			}

			if(empty($value) || is_wp_error($value)){
				$value	= null;
			}else{
				$value	= array_values($value);

				if($this->max_items && count($value) > $this->max_items){
					$value	= array_slice($value, 0, $this->max_items);
				}
			}
		}else{
			if(empty($value) && !is_numeric($value) && $required){
				$value	= null;
			}else{
				if(in_array($this->type, ['radio', 'select'])){
					if(!in_array($value, $this->option_values)){
						$value	= null;
					}
				}elseif(in_array($this->type, ['number', 'range'])){
					if(!is_null($value)){
						if($this->step && ($this->step == 'any' || strpos($this->step, '.'))){
							$value	= (float)$value;
						}else{
							$value	= (int)$value;
						}

						if(isset($this->min) && $value < $this->min){
							$value	= $this->min;
						}

						if(isset($this->max) && $value > $this->max){
							$value	= $this->max;
						}
					}
				}else{
					if(!is_null($value)){
						if($validate){
							if(isset($this->minlength) && mb_strlen($value) < $this->minlength){
								return new WP_Error('invalid_value', $title.'???????????????????????????'.$this->minlength);
							}

							if(isset($this->maxlength) && mb_strlen($value) > $this->maxlength){
								return new WP_Error('invalid_value', $title.'???????????????????????????'.$this->maxlength);
							}
						}

						if($this->type == 'textarea'){
							$value	= str_replace("\r\n", "\n", $value);
						}
					}
				}
			}
		}

		if($this->data_type && $value){
			$value	= apply_filters('wpjam_data_type_field_value', $value, $this->field);
		}

		if(is_null($value) && $required){
			return new WP_Error('value_required', $title.'?????????????????????');
		}

		if($this->sanitize_callback && is_callable($this->sanitize_callback)){
			$value	= call_user_func($this->sanitize_callback, $value);
		}

		return $value;
	}

	public function parse_show_in_rest(&$type, &$default){
		if($show_in_rest = $this->show_in_rest){
			$type		= null;
			$default	= $this->value;

			if(is_array($show_in_rest)){
				if(isset($show_in_rest['type'])){
					$type		= $show_in_rest['type'];
				}

				if(isset($show_in_rest['default'])){
					$default	= $show_in_rest['default'];
				}
			}else{
				if($this->type == 'radio'){
					$show_in_rest	= [
						'schema' => [
							'enum'	=> $this->option_values,
						]
					];
				}elseif($this->type == 'checkbox'){
					if($this->options){
						$show_in_rest	= [
							'schema' => [
								'items'	=> [
									'type'	=> 'string',
									'enum'	=> $this->option_values,
								]
							]
						];
					}
				}elseif($this->type == 'mu-fields'){
				}elseif($this->type == 'mu-text'){
					$show_in_rest	= [
						'schema' => [
							'items'	=> [
								'type'		=> 'string'
							]
						]
					];
				}elseif($this->mu_type){
					$show_in_rest	= [
						'schema' => [
							'items'	=> [
								'type'		=> 'string',
								'format'	=> 'uri',
							]
						]
					];
				}elseif($this->type == 'select'){
					$show_in_rest	= [
						'schema' => [
							'enum'	=> $this->option_values,
						]
					];
				}elseif(in_array($this->type, ['url', 'img'])){
					$show_in_rest	= [
						'schema' => [
							'format'	=> 'uri',
						]
					];
				}elseif($this->type == 'email'){
					$show_in_rest	= [
						'schema' => [
							'format' => 'email',
						]
					];
				}
			}

			if(is_null($type)){
				$type	= 'string';

				if(in_array($this->type, ['number', 'range'])){
					if($this->step && ($this->step == 'any' || strpos($this->step, '.'))){
						$type	= 'number';
					}else{
						$type	= 'integer';
					}
				}elseif($this->type == 'checkbox'){
					if($this->options){
						$type	= 'array';
					}else{
						$type	= 'integer';
					}
				}elseif($this->mu_type){
					$type	= 'array';
				}
			}

			return $show_in_rest;
		}

		return false;
	}

	public function callback($args=[]){
		if($this->type == 'fieldset'){
			return $this->fieldset_callback($args);
		}

		if(empty($args['is_add'])){
			$this->value	= $this->parse_value($args);
		}

		if(!empty($args['show_if_keys']) && in_array($this->key, $args['show_if_keys'])){
			$this->show_if_key	= true;
		}

		if(!empty($args['name'])){
			$this->name	= $args['name'].$this->generate_sub_name($this->name);
		}

		return $this->render();
	}

	public function fieldset_callback($args=[]){
		$html	= $this->title ? '<legend class="screen-reader-text"><span>'.$this->title.'</span></legend>' : '';

		if($fields = $this->fields){
			$group_obj	= new WPJAM_Field_Group();

			foreach($fields as $sub_key => $sub_field){
				$object		= new self($sub_field);
				$sub_html	= $object->callback($args);
				$sub_type	= $object->type;

				if($sub_type == 'fieldset'){
					wp_die('fieldset ??????????????? fieldset');
				}elseif($sub_type == 'hidden'){
					$html	.= $sub_html;
				}else{
					$sub_id		= esc_attr($object->id);
					$sub_html	= '<div class="sub-field-detail">'.$sub_html.'</div>';

					if($sub_title = $object->title){
						$sub_title	= '<label class="sub-field-label" for="'.$sub_id.'">'.$sub_title.'</label>';
					}

					$html	.= $group_obj->render($object->group);
					$html	.= '<div '.$object->parse_wrap_attr('sub-field').' id="div_'.$sub_id.'">'.$sub_title.$sub_html.'</div>';
				}
			}
			
			$html	.= $group_obj->reset();

			unset($sub_field);
		}

		if($this->description){
			$html	.= '<p class="description">'.$this->description.'</p>';
		}

		if($this->group){
			$html	= '<div class="field-group">'.$html.'</div>';
		}

		return $html;
	}

	public function parse_value($args=[]){
		$default	= is_admin() ? $this->value : $this->defaule;
		$cb_args	= isset($args['id']) ? $args['id'] : $args;

		$name		= $this->name;
		$name_obj	= WPJAM_Field_Name::get_instance($name);
		$name		= $name_obj->top_name;

		if($value_callback = $this->value_callback){
			if(!is_callable($value_callback)){
				wp_die($this->key.'??? value_callback???'.$value_callback.'?????????');
			}

			$value	= call_user_func($value_callback, $name, $cb_args);
		}else{
			if(in_array($this->type, ['view', 'br','hr']) && !is_null($default)){
				return $default;
			}

			if(!empty($args['data']) && isset($args['data'][$name])){
				$value	= $args['data'][$name];
			}elseif(!empty($args['value_callback'])){
				$value	= call_user_func($args['value_callback'], $name, $cb_args);
			}else{
				$value	= null;
			}
		}

		$value	= $name_obj->parse_value($value);

		return is_null($value) ? $default : $value;
	}

	public function render(){
		if(is_numeric($this->key)){
			trigger_error('Field ??? key???'.$this->key.'???'.'????????????');
			return;
		}

		if(is_null($this->value)){
			if($this->type == 'radio' && $this->options){
				$this->value	= current(array_keys($this->options));
			}else{
				$this->value	= '';
			}
		}

		if(is_null($this->class)){
			if($this->type == 'mu-text'){
				$field_type	= $this->item_type ?: 'text';
			}else{
				$field_type	= $this->type;
			}

			if(in_array($field_type, ['textarea', 'editor'])){
				$this->class	= ['large-text'];
			}elseif(in_array($field_type, ['text', 'password', 'url', 'image', 'file', 'mu-file', 'mu-image'], true)){
				$this->class	= ['regular-text'];
			}else{
				$this->class	= [];
			}
		}elseif($this->class){
			if(!is_array($this->class)){
				$this->class	= explode(' ', $this->class);
			}
		}else{
			$this->class	= [];
		}

		if($this->description){
			if($this->type == 'checkbox' && !$this->options){
				$this->description	= '&thinsp;'.$this->description;
			}elseif($this->mu_type
				|| in_array($this->type, ['img', 'color', 'checkbox', 'radio', 'textarea']) 
				|| in_array('large-text', $this->class)
				|| in_array('regular-text', $this->class)
			){
				$this->description	= '<p class="description">'.$this->description.'</p>';
			}else{
				$this->description	= '&ensp;<span class="description">'.$this->description.'</span>';
			}
		}else{
			$this->description	= '';
		}

		if($this->mu_type){
			$html	= $this->render_mu_type();
		}elseif(in_array($this->type, ['view','br'], true)){
			if($options = $this->options){
				$values	= $this->value ? [$this->value] : ['', 0];

				foreach($values as $v){
					if(isset($options[$v])){
						return $options[$v];
					}
				}
			}

			return $this->value;
		}elseif($this->type == 'hr'){
			return '<hr />';
		}elseif($this->type == 'img'){
			if(current_user_can('upload_files')){
				$attr	= [];
				
				$attr['item_type']	= $this->item_type ?: '';
				$attr['uploader_id']= 'wpjam_uploader_'.$this->id;
				$attr['img_style']	= '';

				if($size = wpjam_array_pull($this->field, 'size')){
					$size	= wpjam_parse_size($size);

					list($width, $height)	= wp_constrain_dimensions($size['width'], $size['height'], 600, 600);

					$attr['img_style']	.= $width > 2 ? 'width:'.($width/2).'px;' : '';
					$attr['img_style']	.= $height > 2 ? ' height:'.($height/2).'px;' : '';

					$attr['thumb_args']	= wpjam_get_thumbnail('',$size);
				}else{
					$attr['thumb_args']	= wpjam_get_thumbnail('', 400);
				}

				$class		= '';
				$img_tag	= '';

				if(!empty($this->value)){
					$img_url	= $attr['item_type'] == 'url' ? $this->value : wp_get_attachment_url($this->value);

					if($img_url){
						$class		.= ' has-img';
						$img_tag	= '<img style="'.$attr['img_style'].'" src="'.wpjam_get_thumbnail($img_url, $size).'" alt="" />';
					}
				}

				if(!$this->readonly && !$this->disabled){
					$img_tag	.= self::get_icon('del_img').'<div class="wp-media-buttons"><button type="button" class="button add_media"><span class="wp-media-buttons-icon"></span> ????????????</button></div>';
				}else{
					$class	.= ' readonly';
				}

				$html	= '<div class="wpjam-img'.$class.'" '.wpjam_data_attribute_string($attr).'>'.$img_tag.'</div>';
				$html	.= ((!$this->readonly && !$this->disabled) ? $this->render_input(['type'=>'hidden']) : '').$this->description;
			}
		}elseif(in_array($this->type, ['file', 'image'], true)){
			if(current_user_can('upload_files')){
				if($this->type == 'image'){
					$btn_name	= '??????';
					$item_type	= 'image';
				}else{
					$btn_name	= '??????';
					$item_type	= $this->item_type ?: '';
				}

				$button	= sprintf('<a class="button" data-uploader_id="%s" data-item_type="%s">??????%s</a>', 'wpjam_uploader_'.$this->id, $item_type, $btn_name);
				$html	= $this->render_input(['type'=>'url', 'description'=>'']).' '.$button;
				$html	= '<div class="wpjam-file">'.$html.'</div>'.$this->description;
			}
		}elseif($this->type == 'editor'){
			$settings	= wpjam_array_pull($this->field, 'settings') ?: [];
			$settings	= wp_parse_args($settings, [
				'tinymce'		=>[
					'wpautop'	=> true,
					'plugins'	=> 'charmap colorpicker compat3x directionality hr image lists media paste tabfocus textcolor wordpress wpautoresize wpdialogs wpeditimage wpemoji wpgallery wplink wptextpattern wpview',
					'toolbar1'	=> 'bold italic underline strikethrough | bullist numlist | blockquote hr | alignleft aligncenter alignright alignjustify | link unlink | wp_adv',
					'toolbar2'	=> 'formatselect forecolor backcolor | pastetext removeformat charmap | outdent indent | undo redo | wp_help'
				],
				'quicktags'		=> true,
				'mediaButtons'	=> true
			]);

			if(wp_doing_ajax()){
				$html	= $this->render_input(['id'=>'editor_'.$this->id, 'data-settings'=>wpjam_json_encode($settings)]);
			}else{
				ob_start();

				wp_editor($this->value, 'editor_'.$this->id, $settings);

				$editor	= ob_get_clean();

				$style	= $this->style ? ' style="'.$this->style.'"' : '';
				$html 	= '<div'.$style.'>'.$editor.'</div>'.$this->description;
			}	
		}else{
			$html	= $this->render_input();

			if($this->list && $this->options){
				$html	.= '<datalist id="'.$this->list.'">';

				foreach($this->options as $opt_value => $opt_title){
					$html	.= '<option label="'.esc_attr($opt_title).'" value="'.esc_attr($opt_value).'" />';
				}

				$html	.= '</datalist>';
			}
		}

		return apply_filters('wpjam_field_html', $html, $this->field);
	}

	private function render_input($args=[], $lable_attr=''){
		$field	= array_merge($this->field, $args);

		$type	= wpjam_array_pull($field, 'type');
		$value	= wpjam_array_pull($field, 'value');
		$sep	= wpjam_array_pull($field, 'sep', '&emsp;');

		$options		= wpjam_array_pull($field, 'options');
		$description	= wpjam_array_pull($field, 'description');

		if($options && in_array($type, ['radio', 'checkbox'])){
			$args['required']	= false;
			$args['options']	= [];

			$wrap_id	= $field['id'].'_options';
			$attr		= 'id="'.esc_attr($wrap_id).'"';

			if($type == 'checkbox'){
				$args['name']	= $field['name'].'[]';

				if($this->max_items){
					$attr	.= 'data-max_items="'.$this->max_items.'"';
				}
			}

			$items	= [];

			if($type == 'checkbox' && !is_array($value) && ($value || is_numeric($value))){
				$value	= [$value];
			}

			foreach($options as $opt_value => $opt_title){
				if($type == 'checkbox'){
					$checked	= is_array($value) && in_array($opt_value, $value);
				}else{
					$checked	= $opt_value == $value;
				}

				$class		= $checked ? ['checked'] : [];
				$opt_title	= $this->parse_option_title($opt_title, $class, $lable_attr);

				$args['id']				= $field['id'].'_'.$opt_value;
				$args['data-wrap_id']	= $wrap_id;
				$args['value']			= $opt_value;
				$args['checked']		= $checked ? 'checked' : false;
				$args['description']	= '&thinsp;'.$opt_title;
				$args['options']		= [];

				$items[]	= $this->render_input($args, $lable_attr);
			}

			return '<div '.$attr.'>'.implode($sep, $items).'</div>'.$description;
		}else{
			if($type == 'checkbox'){
				if(!isset($args['checked'])){
					$field['checked']	= $value == 1 ? 'checked' : false;

					$value	= 1;
				}
			}elseif($type == 'textarea'){
				$field	= wp_parse_args($field, ['rows'=>6, 'cols'=>50]);
			}elseif($type == 'color'){
				$field['class'][]	= 'color';
			}elseif($type == 'editor'){
				$type	= 'textarea';
				$field	= wp_parse_args($field, ['type'=>'textarea', 'rows'=>12, 'cols'=>50]);

				$field['class'][]	= 'wpjam-editor';
			}

			foreach(['readonly', 'disabled'] as $attr_key){
				if(isset($field[$attr_key])){
					$field['class'][]	= $attr_key;
				}
			}

			if(wpjam_array_pull($field, 'show_if_key') 
				|| in_array($type, ['checkbox', 'radio', 'select'], true)){
				$field['class'][]	= 'show-if-key';
			}

			$query_title		= WPJAM_Field_Data_Type::parse_query_title($field, $value);

			$field['data-key']	= wpjam_array_pull($field, 'key');
			$field['class']		= $field['class'] ? implode(' ', array_unique($field['class'])) : '';

			$keys	= ['title','default','description','fields','sortable_column','parse_required','item_type','group','show_if','show_in_rest','creatable','post_type','taxonomy','value_callback','sanitize_callback','validate_callback','column_callback','show_admin_column','max_items','total'];

			$attr	= [];

			foreach($field as $attr_key => $attr_value){
				if(!in_array($attr_key, $keys)){
					if(is_object($attr_value) || is_array($attr_value)){
						trigger_error($attr_key.' '.var_export($attr_value, true).var_export($field, true));
					}elseif(is_int($attr_value) || $attr_value){
						$attr[]	= $attr_key.'="'.esc_attr($attr_value).'"';
					}
				}
			}

			$attr	= implode(' ', $attr);

			if($type == 'select'){
				$html	= '<select '.$attr.'>'.$this->render_select_options($options, $value).'</select>' .$description;
			}elseif($type == 'textarea'){
				$html	= '<textarea '.$attr.'>'.esc_textarea($value).'</textarea>'.$description;
			}else{
				$attr	.= $type == 'color' ? 'type="text"' : 'type="'.esc_attr($type).'"';
				$html	= '<input value="'.esc_attr($value).'" '.$attr.' />'.$query_title;

				if(($lable_attr || $description) && $type != 'hidden'){
					$lable_attr	.= ' id="label_'.esc_attr($field['id']).'" for="'.esc_attr($field['id']).'"';

					if(in_array($type, ['color'])){
						$html	= '<label '.$lable_attr.'>'.$html.'</label>'.$description;
					}else{
						$html	= '<label '.$lable_attr.'>'.$html.$description.'</label>';
					}
				}
			}

			return $html;
		}
	}

	private function render_mu_type(){
		$max_items		= $this->max_items;
		$max_reached	= false;

		$value			= $this->value;

		if($value || is_numeric($value)){
			if(is_array($value)){
				$value	= wpjam_array_filter($value, function($item){ 
					return !empty($item) || is_numeric($item); 
				});

				if($max_items && count($value) >= $max_items){
					$max_reached	= true;

					$value	= array_slice($value, 0, $max_items);
				}

				$value	= array_values($value);
			}else{
				$value	= (array)$value;
			}
		}else{
			$value	= [];
		}

		if(!$max_reached){
			if($this->type == 'mu-fields'){
				$value[]	= [];
			}elseif($this->type != 'mu-img'){
				$value[]	= '';
			}
		}

		$item_class	= 'mu-item';
		$mu_items	= [];
		$last_item	= array_key_last($value);

		if($this->type == 'mu-img'){
			if(!current_user_can('upload_files')){
				return '';
			}

			$mu_class	= 'mu-imgs';
			$item_class	.= ' mu-img';
			$item_type	= $this->item_type ?: '';
			$item_args	= ['id'=>'', 'type'=>'hidden', 'name'=>$this->name.'[]'];

			foreach($value as $img){
				$img_url	= $item_type == 'url' ? $img : wp_get_attachment_url($img);
				$img_tag	= '<img src="'.wpjam_get_thumbnail($img_url, 200, 200).'" alt="">';
				$img_tag	= '<a href="'.$img_url.'" class="wpjam-modal">'.$img_tag.'</a>';

				if(!$this->readonly && !$this->disabled){
					$img_tag	.= $this->render_input(array_merge($item_args, ['value'=>$img])).self::get_icon('del_icon');
				}

				$mu_items[]	= $img_tag;
			}

			if(!$this->readonly && !$this->disabled){
				$attr		= ['name'=>$this->name.'[]', 'item_class'=>$item_class, 'item_type'=>$item_type, 'uploader_id'=>'wpjam_uploader_'.$this->id, 'thumb_args'=>wpjam_get_thumbnail('', [200,200])];
				$button		= '<div class="wpjam-mu-img dashicons dashicons-plus-alt2" '.wpjam_data_attribute_string($attr).'>'.self::get_icon('del_icon').'</div>';
			}else{
				$mu_class	.= ' readonly';
				$button		=  '';
			}
		}elseif($this->type == 'mu-fields'){
			if(!$this->fields){
				return '';
			}

			if(wpjam_array_pull($this->field, 'group')){
				$item_class	.= ' field-group';
			}

			$mu_class	= 'mu-fields';
			$tmpl_id	= md5($this->name);
			$button		= ' <a class="wpjam-mu-fields button" data-i="%s" data-item_class="'.$item_class.'" data-tmpl_id="'.$tmpl_id.'">????????????</a>';

			foreach($value as $i => $item){
				$item_html	= $this->render_mu_fields($i, $item);
				$item_html	.= ($last_item === $i) ? sprintf($button, $i) : self::get_icon('del_btn,move_btn');

				$mu_items[]	= $item_html;
			}

			$this->description	.= self::generate_tmpl($tmpl_id, $this->render_mu_fields('{{ data.i }}').sprintf($button, '{{ data.i }}'));
		}elseif($this->type == 'mu-text'){
			$this->field	= wpjam_array_except($this->field, 'required');	// ??????????????????

			$mu_class	= 'mu-texts';
			$button		= ' <a class="wpjam-mu-text button">????????????</a>';
			$item_type	= $this->item_type ?: 'text';
			$item_args	= ['type'=>$item_type, 'id'=>'', 'name'=>$this->name.'[]', 'description'=>''];

			foreach($value as $i => $item){
				$item_html	= $this->render_input(array_merge($item_args, ['value'=>$item]));
				$item_html	.= ($last_item === $i) ? $button : self::get_icon('del_btn,move_btn');

				$mu_items[]	= $item_html;
			}
		}elseif(in_array($this->type, ['mu-file', 'mu-image'], true)){
			if(!current_user_can('upload_files')){
				return '';
			}

			$mu_class	= 'mu-files';
			$item_type	= $this->type == 'mu-image' ? 'image' : '';
			$item_args	= ['type'=>'url', 'id'=>'', 'name'=>$this->name.'[]', 'description'=>''];

			$title		= $item_type == 'image' ? '??????' : '??????';
			$attr		= ['name'=>$this->name.'[]', 'item_class'=>'mu-item', 'item_type'=>$item_type, 'uploader_id'=>'wpjam_uploader_'.$this->id,	'title'=>'??????'.$title];
			$button		= '<a class="wpjam-mu-file button" '.wpjam_data_attribute_string($attr).'>??????'.$title.'[??????]</a>';

			foreach($value as $i => $item){
				$item_html	= $this->render_input(array_merge($item_args, ['value'=>$item]));
				$item_html	.= ($last_item === $i) ? $button : self::get_icon('del_btn,move_btn');
				
				$mu_items[]	= $item_html;
			}
		}

		$html	= $mu_items ? '<div class="'.$item_class.'">'.implode('</div> <div class="'.$item_class.'">', $mu_items).'</div>' : '';

		if($this->type == 'mu-img'){
			$html	.= $button;
		}

		return '<div class="'.$mu_class.'" id="'.$this->id.'" data-max_items="'.$max_items.'">'.$html.'</div>'.$this->description;
	}

	private function render_mu_fields($i, $value=[]){
		$show_if_keys	= (new WPJAM_Fields($this->fields))->show_if_keys;;
		$group_obj		= new WPJAM_Field_Group();

		$html	= '';

		foreach($this->fields as $key => $field){
			$id		= $field['id'] ?? $key;
			$name	= $field['name'] ?? $key;

			if(preg_match('/\[([^\]]*)\]/', $name)){
				wp_die('mu-fields ??????????????????????????????[]??????');
			}

			$field['name']	= $this->name.'['.$i.']'.'['.$name.']';

			if($value && isset($value[$name])){
				$field['value']	= $value[$name];
			}

			if($show_if_keys && in_array($key, $show_if_keys)){
				$field['show_if_key']	= true;
			}

			if(isset($field['show_if'])){
				$field['show_if']['key']	.= '__'.$i;
			}

			$field['key']	= $key.'__'.$i;
			$field['id']	= $id.'__'.$i;

			$object	= new WPJAM_Field($field);

			if(in_array($object->type, ['fieldset', 'mu-fields'])){
				wp_die('mu-fields ??????????????? '.$object->type);
			}elseif($object->type == 'hidden'){
				$html	.= $object->render();
			}else{
				$html	.= $group_obj->render($object->group);
				$title	= $object->title ? '<label class="sub-field-label" for="'.$object->id.'">'.$object->title.'</label>' : '';

				$html	.= '<div '.$object->parse_wrap_attr('sub-field').'>'.$title.'<div class="sub-field-detail">'.$object->render().'</div></div>';
			}
		}
		
		$html	.= $group_obj->reset();

		return $html;
	}

	private function render_select_options($options, $value){
		$items		= [];

		foreach($options as $opt_value => $opt_title){
			if(is_array($opt_title) && !empty($opt_title['options'])){
				$sub_opts	= wpjam_array_pull($opt_title, 'options');
			}else{
				$sub_opts	= [];
			}

			$opt_title	= $this->parse_option_title($opt_title, [], $attr);

			if($sub_opts){
				$items[]	= '<optgroup '.$attr.' label="'.esc_attr($opt_title).'" >'.$this->render_select_options($sub_opts, $value).'</optgroup>';
			}else{
				$items[]	= '<option '.$attr.' value="'.esc_attr($opt_value).'" '.selected($opt_value, $value, false).'>'.$opt_title.'</option>';;
			}
		}

		return implode('', $items);
	}

	public function parse_wrap_attr($class=[]){
		$class		= (array)$class;
		$class[]	= wpjam_array_pull($this->field, 'wrap_class');

		if($show_if	= wpjam_parse_show_if($this->show_if, $class)){
			$data	= ['show_if'=>$show_if]; 
		}else{
			$data	= [];
		}

		return wpjam_attribute_string(['class'=>$class, 'data'=>$data]);
	}

	private function parse_option_title($opt_title, $class=[], &$attr){
		$data	= [];

		if(is_array($opt_title)){
			$opt_arr	= $opt_title;
			$opt_title	= wpjam_array_pull($opt_arr, 'title');

			foreach($opt_arr as $k => $v){
				if($k == 'show_if'){
					if($show_if = wpjam_parse_show_if($v, $class)){
						$data['show_if']	= $show_if;
					}
				}elseif($k == 'class'){
					$class	= array_merge($class, explode(' ', $v));
				}elseif(!is_array($v)){
					$data[$k]	= $v;
				}
			}
		}

		$attr	= wpjam_attribute_string(['class'=>$class, 'data'=>$data]);

		return $opt_title;
	}

	private function generate_sub_name($name){
		return WPJAM_Field_Name::get_instance($name)->sub_name;
	}

	public static function is_bool_attr($attr){
		return in_array($attr, ['allowfullscreen', 'allowpaymentrequest', 'allowusermedia', 'async', 'autofocus', 'autoplay', 'checked', 'controls', 'default', 'defer', 'disabled', 'download', 'formnovalidate', 'hidden', 'ismap', 'itemscope', 'loop', 'multiple', 'muted', 'nomodule', 'novalidate', 'open', 'playsinline', 'readonly', 'required', 'reversed', 'selected', 'typemustmatch'], true);
	}

	public static function get_icon($name){
		$return	= '';
		
		foreach(wp_parse_list($name) as $name){
			if($name == 'move_btn'){
				$return	.= ' <span class="dashicons dashicons-menu"></span>';
			}elseif($name == 'del_btn'){
				$return	.= ' <a href="javascript:;" class="button wpjam-del-item">??????</a>';
			}elseif($name == 'del_icon' || $name == 'del_img'){
				$class	= $name == 'del_img' ? 'wpjam-del-img' : 'wpjam-del-item';
				$return	.= ' <a href="javascript:;" class="del-item-icon dashicons dashicons-no-alt '.$class.'"></a>';
			}
		}

		return $return;
	}

	public  static function print_media_templates(){
		$tmpls	= [
			'mu-action'	=> self::get_icon('del_btn,move_btn'),
			'img'		=> '<img style="{{ data.img_style }}" src="{{ data.img_url }}{{ data.thumb_args }}" alt="" />',
			'mu-img'	=> '<img src="{{ data.img_url }}{{ data.thumb_args }}" /><input type="hidden" name="{{ data.name }}" value="{{ data.img_value }}" />',
			'mu-file'	=> '<input type="url" name="{{ data.name }}" class="regular-text" value="{{ data.img_url }}" />'
		];

		foreach($tmpls as $tmpl_id => $tmpl){
			echo self::generate_tmpl($tmpl_id, $tmpl);
		}

		echo '<div id="tb_modal"></div>';
	}

	public  static function generate_tmpl($tmpl_id, $tmpl){
		return "\n".'<script type="text/html" id="tmpl-wpjam-'.$tmpl_id.'">'.$tmpl.'</script>'."\n";
	}

	// ??????
	public  static function get_value($field, $args=[]){
		return wpjam_parse_field_value($field, $args);
	}
}

class WPJAM_Fields{
	private $fields;
	private $fieldset_object	= [];
	private $show_if_keys;

	public function __construct($fields){
		$show_if_keys	= [];

		foreach($fields as $key => $field){
			if(isset($field['show_if']) && !empty($field['show_if']['key'])){
				$show_if_keys[]	= $field['show_if']['key'];
			}

			if(isset($field['type']) && $field['type'] == 'fieldset' && !empty($field['fields'])){
				$this->fieldset_object[$key]	= $object = new self($field['fields']);

				$show_if_keys	= array_merge($show_if_keys, $object->show_if_keys);
			}
		}

		$this->fields		= $fields;
		$this->show_if_keys	= array_unique($show_if_keys);
	}

	public function __get($key){
		if($key == 'fields'){
			return $this->fields;
		}elseif($key == 'show_if_keys'){
			return $this->show_if_keys;
		}
	}

	public function get_data($values=null, $args=[]){
		$get_show_if	= $args['get_show_if'] ?? false;
		$show_if_values	= $args['show_if_values'] ?? [];
		$field_validate	= $get_show_if ? false : ($args['validate'] ?? true);

		$data	= [];

		foreach($this->fields as $key => $field){
			$object	= new WPJAM_Field($field, $key);

			if(in_array($object->type, ['view', 'br','hr']) 
				|| $object->disabled
				|| $object->readonly
				|| $object->show_admin_column === 'only'
			){
				continue;
			}

			$validate	= $field_validate;

			if($validate 
				&& $object->show_if
				&& wpjam_show_if($show_if_values, $object->show_if) === false
			){
				$validate	= false;
			}

			if($object->type == 'fieldset'){
				if($object->fields){
					$value	= $this->fieldset_object[$key]->get_data($values, array_merge($args, ['validate'=>$validate]));

					if(is_wp_error($value)){
						return $value;
					}

					if($object->fieldset_type == 'array'){
						$value	= array_filter($value, function($item){ return !is_null($item); });
					}

					$data	= wpjam_array_merge($data, $value);
				}
			}else{
				$name_obj	= WPJAM_Field_Name::get_instance($object->name);
				$name		= $name_obj->top_name;
				
				if(isset($values)){
					$value	= $values[$name] ?? null;
				}else{
					$value	= wpjam_get_parameter($name, ['method'=>'POST']);
				}

				$value	= $name_obj->parse_value($value);

				if($get_show_if){
					$key		= $object->key;	// show_if ??????????????? key //?????? fieldset array ??????????????? key ??? ${key}_{$sub_key}
					$data[$key]	= $object->validate($value, false);
				}else{
					$value = $object->validate($value, $validate);

					if(is_wp_error($value)){
						return $value;
					}

					$value	= $name_obj->wrap_value($value);
					$data	= wpjam_array_merge($data, [$name=>$value]);
				}
			}
		}

		return $data;
	}

	public function validate($values=null){
		$show_if_values	= $this->show_if_keys ? $this->get_data($values, ['get_show_if'=>true]) : [];

		return $this->get_data($values, ['show_if_values'=>$show_if_values]);
	}

	public function callback($args=[]){
		$output			= '';
		$fields_type	= $args['fields_type'] ?? 'table';

		$args['show_if_keys']	= $this->show_if_keys;

		foreach($this->fields as $key => $field){
			$object	= new WPJAM_Field($field, $key);

			if($object->show_admin_column === 'only'){
				continue;
			}

			$html	= $object->callback($args);

			if($object->type == 'hidden'){
				$output	.= $html;
				continue;
			}

			$wrap_class	= $args['wrap_class'] ?? [];
			$wrap_attr	= $object->parse_wrap_attr($wrap_class);
			$id			= esc_attr($object->id);
			$title		= $object->title;

			if($title && $object->type != 'fieldset'){
				$title	= '<label for="'.$key.'">'.$title.'</label>';
			}

			if($fields_type == 'div'){
				$output	.= '<div '.$wrap_attr.' id="div_'.$id.'">'.$title.$html.'</div>';
			}elseif($fields_type == 'list' || $fields_type == 'li'){
				$output	.= '<li '.$wrap_attr.' id="li_'.$id.'">'.$title.$html.'</li>';
			}elseif($fields_type == 'tr' || $fields_type == 'table'){
				$html	= $title ? '<th scope="row">'.$title.'</th><td>'.$html.'</td>' : '<td colspan="2">'.$html.'</td>';
				$output	.= '<tr '.$wrap_attr.' valign="top" '.'id="tr_'.$id.'">'.$html.'</tr>';
			}else{
				$output	.= $title.$html;
			}
		}

		if($fields_type == 'list'){
			$output	= '<ul>'.$output.'</ul>';
		}elseif($fields_type == 'table'){
			$output	= '<table class="form-table" cellspacing="0"><tbody>'.$output.'</tbody></table>';
		}

		if(!isset($args['echo']) || $args['echo']){
			echo $output;
		}else{
			return $output;
		}
	}
}

class WPJAM_Field_Name{
	private $top_name	= '';
	private $name_arr	= [];
	private $sub_arr	= [];

	public function __construct($name){
		if(preg_match('/\[([^\]]*)\]/', $name)){
			$name_arr	= wp_parse_args($name);

			$this->top_name	= current(array_keys($name_arr));
			$this->name_arr	= current(array_values($name_arr));
		}else{
			$this->top_name	= $name;
		}
	}

	public function __get($key){
		if($key == 'sub_name'){
			$name	= '['.$this->top_name.']';

			if($name_arr = $this->name_arr){
				do{
					$name		.='['.current(array_keys($name_arr)).']';
					$name_arr	= current(array_values($name_arr));
				}while($name_arr);
			}

			return $name;
		}elseif(in_array($key, ['top_name', 'name_arr'])){
			return $this->$key;
		}

		return null;
	}

	public function __isset($key){
		return $this->$key !== null;
	}

	public function parse_value($value){
		if($name_arr = $this->name_arr){
			$this->sub_arr	= [];

			do{
				$sub_name	= current(array_keys($name_arr));
				$name_arr	= current(array_values($name_arr));

				if(isset($value) && isset($value[$sub_name])){
					$value	= $value[$sub_name];
				}else{
					$value	= null;
				}

				array_unshift($this->sub_arr, $sub_name);
			}while($name_arr && $value);
		}

		return $value;
	}

	public function wrap_value($value){
		if($sub_arr = $this->sub_arr){
			foreach($sub_arr as $sub_name){
				$value	= [$sub_name => $value];
			}
		}

		return $value;
	}

	private static $instances	= [];

	public static function get_instance($name){
		if(!isset(self::$instances[$name])){
			self::$instances[$name]	= new self($name);
		}

		return self::$instances[$name];
	}
}

class WPJAM_Field_Group{
	private $group = '';

	public function render($group){
		$return	= '';

		if($group != $this->group){
			if($this->group){
				$return	.= '</div>';
			}

			if($group){
				$return	.= '<div class="field-group" id="field_group_'.esc_attr($group).'">';
			}
		
			$this->group	= $group;
		}

		return $return;
	}

	public function reset(){
		if($this->group){
			$this->group	= '';

			return '</div>';
		}

		return '';
	}
}

class WPJAM_Field_Data_Type{
	public static function parse_query_title(&$field, $value){
		$query_title	= '';
		$data_type		= wpjam_array_pull($field, 'data_type');
		$query_args		= wpjam_array_pull($field, 'query_args') ?: [];

		if($query_args && !is_array($query_args)){
			$query_args	= wp_parse_args($query_args);
		}

		if(!$data_type){
			return '';
		}elseif($data_type == 'post_type'){
			if($post_type = wpjam_array_pull($field, 'post_type')){
				$query_args['post_type']	= $post_type;
			}

			if($value && is_numeric($value)){
				if($data = get_post($value)){
					$query_title	= $data->post_title ?: $data->ID;
				}
			}
		}elseif($data_type == 'taxonomy'){
			if($taxonomy = wpjam_array_pull($field, 'taxonomy')){
				$query_args['taxonomy']	= $taxonomy;
			}

			if($value && is_numeric($value)){
				if($data = get_term($value)){
					$query_title	= $data->name ?: $data->term_id;
				}
			}
		}elseif($data_type == 'model'){
			if($model = wpjam_array_pull($field, 'model')){
				$query_args['model']	= $model;
			}

			$model	= $query_args['model'] ?? null;

			if(empty($model) || !class_exists($model)){
				wp_die($field['key'].' model ?????????');
			}

			$query_args	= wp_parse_args($query_args, ['label_key'=>'title', 'id_key'=>'id']);

			if($value){
				if($data = $model::get($value)){
					$label_key		= $query_args['label_key']; 
					$id_key			= $query_args['id_key'];
					$query_title	= $data[$label_key] ?: $data[$id_key];
				}
			}
		}

		$query_class	= $field['class'] ? ' '.implode(' ', array_unique($field['class'])) : '';

		if($query_title){
			$field['class'][]	= 'hidden';
		}else{
			$query_class	.= ' hidden';
		}

		$field['class'][]	= 'wpjam-autocomplete';

		$field['data-data_type']	= $data_type;
		$field['data-query_args']	= wpjam_json_encode($query_args);

		return '<span class="wpjam-query-title'.$query_class.'">
		<span class="dashicons dashicons-dismiss"></span>
		<span class="wpjam-query-text">'.$query_title.'</span>
		</span>';
	}

	public static function ajax_query(){
		$data_type	= wpjam_get_parameter('data_type',	['method'=>'POST']);
		$query_args	= wpjam_get_parameter('query_args',	['method'=>'POST']);

		if($data_type == 'post_type'){
			$query_args['posts_per_page']	= $query_args['posts_per_page'] ?? 10;
			$query_args['post_status']		= $query_args['post_status'] ?? 'publish';

			$query	= wpjam_query($query_args);
			$posts	= array_map(function($post){ return wpjam_get_post($post->ID); }, $query->posts);

			wpjam_send_json(['datas'=>$posts]);
		}elseif($data_type == 'taxonomy'){
			$query_args['number']		= $query_args['number'] ?? 10;
			$query_args['hide_empty']	= $query_args['hide_empty'] ?? 0;
			
			$terms	= wpjam_get_terms($query_args, -1);

			wpjam_send_json(['datas'=>$terms]);
		}elseif($data_type == 'model'){
			$model	= $query_args['model'];

			$query_args	= wpjam_array_except($query_args, ['model', 'label_key', 'id_key']);
			$query_args	= $query_args + ['number'=>10];
			
			$query	= $model::Query($query_args);

			wpjam_send_json(['datas'=>$query->datas]);
		}
	}
}