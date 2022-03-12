<?php
/**
 * @author Jegtheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'JNews_Migration_Soledad_Dashboard' ) ) {
	class JNews_Migration_Soledad_Dashboard {

		/**
		 * @var JNews_Migration_Soledad_Dashboard
		 */
		private static $instance;

		/**
		 * @return JNews_Migration_Soledad_Dashboard
		 */
		public static function getInstance() {
			if ( null === static::$instance ) {
				static::$instance = new static();
			}
			return static::$instance;
		}

		/**
		 * JNews_Migration_Soledad_Dashboard constructor
		 */
		private function __construct() {
			add_action( 'admin_enqueue_scripts', array( $this, 'load_assets' ) );
			add_filter( 'jnews_admin_menu', array( $this, 'admin_menu' ) );
			add_filter( 'jnews_admin_slug', array( $this, 'admin_slug' ) );
		}

		/**
		 * Load plugins css and js assest
		 */
		public function load_assets() {
			wp_register_style( 'jnews-migration-soledad-style', JNEWS_MIGRATION_SOLEDAD_URL . '/assets/css/plugin.css', null, JNEWS_MIGRATION_SOLEDAD_VERSION );
			wp_enqueue_style( 'jnews-migration-soledad-style' );

			wp_register_script( 'jnews-migration-pubsliher-script', JNEWS_MIGRATION_SOLEDAD_URL . '/assets/js/plugin.js', null, JNEWS_MIGRATION_SOLEDAD_VERSION );
			wp_enqueue_script( 'jnews-migration-pubsliher-script' );

			add_editor_style( JNEWS_MIGRATION_SOLEDAD_URL . '/assets/css/shortcode.css' );
		}

		/**
		 * Set migration dashboard menu
		 *
		 * @param  array $menu
		 *
		 * @return array
		 */
		public function admin_menu( $menu ) {
			$slug = apply_filters( 'jnews_get_admin_slug', '' );

			$migration_menu = array(
				array(
					'title'        => esc_html__( 'Migration - Soledad', 'jnews-migration-soledad' ),
					'menu'         => esc_html__( 'Migration - Soledad', 'jnews-migration-soledad' ),
					'slug'         => $slug['migration_soledad'],
					'action'       => array( &$this, 'migration_soledad' ),
					'priority'     => 59,
					'show_on_menu' => true,
				),
			);

			return array_merge( $menu, $migration_menu );
		}

		public function admin_slug( $slug ) {
			$translation_slug = array(
				'migration_soledad' => 'jnews_migration_soledad',
			);

			return array_merge( $translation_slug, $slug );
		}

		/**
		 * Render migration dashboard template
		 */
		public function migration_soledad() {
			require_once 'template/migration-dashboard.php';
		}

	}
}

