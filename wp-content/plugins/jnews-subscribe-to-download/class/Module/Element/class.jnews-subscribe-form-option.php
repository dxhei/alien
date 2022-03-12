<?php
/**
 * @author : Jegtheme
 */

/**
 * Class JNews_Element_Subscribe_Option
 */
class JNews_Element_Subscribe_Option extends \JNews\Module\ModuleOptionAbstract {
	public static function get_site_domain() {
		return str_ireplace( 'www.', '', parse_url( home_url(), PHP_URL_HOST ) );
	}

	public function get_category() {
		return esc_html__( 'JNews - Element', 'jnews-subscribe' );
	}

	public function compatible_column() {
		return array( 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12 );
	}

	public function get_module_name() {
		return esc_html__( 'JNews - Subscribe to Download', 'jnews-subscribe' );
	}

	public function set_options() {
		$this->get_std_option();
		$this->set_style_option();
	}

	public function get_std_option() {
		// DOWNLOAD TYPE
		$this->options[] = array(
			'type'        => 'dropdown',
			'param_name'  => 'download_type',
			'heading'     => esc_html__( 'Download Type', 'jnews-subscribe' ),
			'description' => esc_html__( 'Choose which download type you want to use.', 'jnews-subscribe' ),
			'std'         => 'form',
			'value'       => array(
				esc_html__( 'Button Download', 'jnews-subscribe' ) => 'button',
				esc_html__( 'Form Subscribe', 'jnews-subscribe' )  => 'form',
			),
		);

		// BUTTON DOWNLOAD ICON.
		$this->options[] = array(
			'type'        => 'iconpicker',
			'param_name'  => 'button_download_icon',
			'heading'     => esc_html__( 'Button Download Icon', 'jnews-subscribe' ),
			'description' => esc_html__( 'Choose which icon you want to use on the button.', 'jnews-subscribe' ),
			'std'         => '',
			'settings'    => array(
				'emptyIcon'    => true,
				'iconsPerPage' => 100,
			),
			'dependency'  => array(
				'element' => 'download_type',
				'value'   => array( 'button' ),
			),
		);

		// BUTTON DOWNLOAD TEXT.
		$this->options[] = array(
			'type'        => 'textfield',
			'param_name'  => 'button_download_text',
			'heading'     => esc_html__( 'Button Download Text', 'jnews-subscribe' ),
			'description' => esc_html__( 'Input text you want to use as download button text.', 'jnews-subscribe' ),
			'std'         => 'download',
			'dependency'  => array(
				'element' => 'download_type',
				'value'   => array( 'button' ),
			),
		);

		// FILE URL.
		$this->options[] = array(
			'type'        => 'select',
			'param_name'  => 'file_id',
			'heading'     => esc_html__( 'Select File', 'jnews-subscribe' ),
			'description' => esc_html__( 'Select your download file', 'jnews-subscribe' ),
			'value'       => array_flip(
				call_user_func(
					function () {
						$post = get_posts(
							array(
								'posts_per_page' => - 1,
								'post_type'      => 'jnews-download',
							)
						);

						$file = array();
						$file[] = esc_html__( 'Choose File', 'jnews-subscribe' );

						if ( $post ) {
							foreach ( $post as $value ) {
								$file[ $value->ID ] = $value->post_title;
							}
						}

						return $file;
					}
				)
			),
		);
		// ACTIONS AFTER SUBMIT.
		$this->options[] = array(
			'type'        => 'select',
			'multiple'    => PHP_INT_MAX,
			'param_name'  => 'add_action',
			'heading'     => esc_html__( 'Actions After Submit', 'jnews-subscribe' ),
			'description' => esc_html__( 'Add actions that will be performed after a visitor submits the form (e.g. send an email notification). Choosing an action will add the chosen file setting.', 'jnews-subscribe' ),
			'std'         => '',
			'value'       => array(
				esc_html__( 'Email', 'jnews-subscribe' ) => 'email',
				esc_html__( 'MailChimp', 'jnews-subscribe' ) => 'mailchimp',
			),
		);

		// HEADING TYPE.
		$this->options[] = array(
			'type'        => 'dropdown',
			'param_name'  => 'heading_type',
			'group'       => esc_html__( 'Form Setting', 'jnews-subscribe' ),
			'heading'     => esc_html__( 'Heading Type', 'jnews-subscribe' ),
			'description' => esc_html__( 'Choose which heading type you want to use.', 'jnews-subscribe' ),
			'std'         => 'h2',
			'value'       => array(
				esc_html__( 'H1', 'jnews-subscribe' ) => 'h1',
				esc_html__( 'H2', 'jnews-subscribe' ) => 'h2',
				esc_html__( 'H3', 'jnews-subscribe' ) => 'h3',
				esc_html__( 'H4', 'jnews-subscribe' ) => 'h4',
				esc_html__( 'H5', 'jnews-subscribe' ) => 'h5',
				esc_html__( 'H6', 'jnews-subscribe' ) => 'h6',
			),
		);

		// HEADING TEXT.
		$this->options[] = array(
			'type'        => 'textfield',
			'param_name'  => 'heading_text',
			'group'       => esc_html__( 'Form Setting', 'jnews-subscribe' ),
			'heading'     => esc_html__( 'Heading Text', 'jnews-subscribe' ),
			'description' => esc_html__( 'Input text you want to use as heading.', 'jnews-subscribe' ),
		);

		// TEXT.
		$this->options[] = array(
			'type'        => 'textfield',
			'param_name'  => 'text',
			'group'       => esc_html__( 'Form Setting', 'jnews-subscribe' ),
			'heading'     => esc_html__( 'Text', 'jnews-subscribe' ),
			'description' => esc_html__( 'Input text you want to use as a description.', 'jnews-subscribe' ),
		);

		// BUTTON ICON.
		$this->options[] = array(
			'type'        => 'iconpicker',
			'param_name'  => 'button_icon',
			'group'       => esc_html__( 'Form Setting', 'jnews-subscribe' ),
			'heading'     => esc_html__( 'Button Icon', 'jnews-subscribe' ),
			'description' => esc_html__( 'Choose which icon you want to use on the button.', 'jnews-subscribe' ),
			'std'         => '',
			'settings'    => array(
				'emptyIcon'    => true,
				'iconsPerPage' => 100,
			),
		);

		// BUTTON TEXT.
		$this->options[] = array(
			'type'        => 'textfield',
			'param_name'  => 'button_text',
			'group'       => esc_html__( 'Form Setting', 'jnews-subscribe' ),
			'heading'     => esc_html__( 'Button Text', 'jnews-subscribe' ),
			'std'         => 'subscribe',
			'description' => esc_html__( 'Input text you want to use as button text.', 'jnews-subscribe' ),
		);

		$this->options[] = array(
			'type'        => 'textfield',
			'param_name'  => 'button_placeholder',
			'group'       => esc_html__( 'Form Setting', 'jnews-subscribe' ),
			'heading'     => esc_html__( 'Placeholder Text', 'jnews-subscribe' ),
			'std'         => 'Email',
			'description' => esc_html__( 'Input placeholder text for input box.', 'jnews-subscribe' ),
		);

		// AGREEMENT CHECK.
		$this->options[] = array(
			'type'        => 'checkbox',
			'param_name'  => 'agreement',
			'group'       => esc_html__( 'Form Setting', 'jnews-subscribe' ),
			'heading'     => esc_html__( 'Enable Agreement', 'jnews-subscribe' ),
			'description' => esc_html__( 'Enable this option to showing up the Agreement checkbox.', 'jnews-subscribe' ),
		);

		// AGREEMENT TEXT 1.
		$this->options[] = array(
			'type'        => 'textfield',
			'param_name'  => 'agreement_1',
			'group'       => esc_html__( 'Form Setting', 'jnews-subscribe' ),
			'heading'     => esc_html__( 'Agree on Terms', 'jnews-subscribe' ),
			'description' => esc_html__( 'Set the Agree Terms text.', 'jnews-subscribe' ),
			'dependency'  => array(
				'element' => 'agreement',
				'value'   => 'true',
			),
		);

		// AGREEMENT TEXT 2.
		$this->options[] = array(
			'type'        => 'textfield',
			'param_name'  => 'agreement_2',
			'group'       => esc_html__( 'Form Setting', 'jnews-subscribe' ),
			'heading'     => esc_html__( 'Agree on Subscription', 'jnews-subscribe' ),
			'description' => esc_html__( 'Set the Agree Subscription Text.', 'jnews-subscribe' ),
			'dependency'  => array(
				'element' => 'agreement',
				'value'   => 'true',
			),
		);

	}

}
