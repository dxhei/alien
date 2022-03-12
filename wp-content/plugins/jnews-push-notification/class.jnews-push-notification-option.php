<?php
/**
 * @author : Jegtheme
 */

/**
 * Class Theme JNews Option
 */
class JNews_Push_Notification_Option {

	/**
	 * @var JNews_Push_Notification_Option
	 */
	private static $instance;

	/**
	 * @var Jeg\Customizer\Customizer
	 */
	private $customizer;

	/**
	 * @return JNews_Push_Notification_Option
	 */
	public static function getInstance() {
		if ( null === static::$instance ) {
			static::$instance = new static();
		}
		return static::$instance;
	}

	/**
	 * JNews_Push_Notification_Option constructor
	 */
	private function __construct() {
		if ( class_exists( 'Jeg\Customizer\Customizer' ) ) {
			$this->customizer = Jeg\Customizer\Customizer::get_instance();

			$this->set_section();
		}
	}

	public function set_section() {
		$push_notification_section = array(
			'id'       => 'jnews_push_notification_section',
			'title'    => esc_html__( 'Push Notification', 'jnews-push-notification' ),
			'panel'    => 'jnews_global_panel',
			'priority' => 190,
			'type'     => 'jnews-lazy-section',
		);

		$this->customizer->add_section( $push_notification_section );
	}
}
