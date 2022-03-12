<?php
function wpjam_get_volc_imagex_thumbnail($img_url, $args=[]){
	if($img_url && (!wpjam_is_image($img_url) || !wpjam_is_cdn_url($img_url))){
		return $img_url;
	}

	if($volc_imagex_template = wpjam_cdn_get_setting('volc_imagex_template')){
		$width	= (int)($args['width'] ?? 0);
		$height	= (int)($args['height'] ?? 0);

		$thumb_arg	= str_replace(
			['resize_width', 'resize_height', 'crop_width', 'crop_height'],
			[$width, $height, $width, $height],
			$volc_imagex_template
		);
	
		if($query = parse_url($img_url, PHP_URL_QUERY)){
			$img_url	= str_replace('?'.$query, '', $img_url);
		}

		$img_url	= $img_url.$thumb_arg;
	}

	return $img_url;
}

return 'wpjam_get_volc_imagex_thumbnail';