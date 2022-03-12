<?php
/**
 * @author Jegtheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'JNews_Migration_Jannah_Dashboard' ) ) {
	class JNews_Migration_Jannah_Dashboard {

		/**
		 * @var JNews_Migration_Jannah_Dashboard
		 */
		private static $instance;

		/**
		 * @return JNews_Migration_Jannah_Dashboard
		 */
		public static function getInstance() {
			if ( null === static::$instance ) {
				static::$instance = new static();
			}
			return static::$instance;
		}

		/**
		 * JNews_Migration_Jannah_Dashboard constructor
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
			wp_register_style( 'jnews-migration-jannah-style', JNEWS_MIGRATION_JANNAH_URL . '/assets/css/plugin.css', null, JNEWS_MIGRATION_JANNAH_VERSION );
			wp_enqueue_style( 'jnews-migration-jannah-style' );

			wp_register_script( 'jnews-migration-jannah-script', JNEWS_MIGRATION_JANNAH_URL . '/assets/js/plugin.js', null, JNEWS_MIGRATION_JANNAH_VERSION );
			wp_enqueue_script( 'jnews-migration-jannah-script' );
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
					'title'        => esc_html__( 'Migration - Jannah', 'jnews-migration-jannah' ),
					'menu'         => esc_html__( 'Migration - Jannah', 'jnews-migration-jannah' ),
					'slug'         => $slug['migration_jannah'],
					'action'       => array( &$this, 'migration_jannah' ),
					'priority'     => 59,
					'show_on_menu' => true,
				),
			);

			return array_merge( $menu, $migration_menu );
		}

		public function admin_slug( $slug ) {
			$translation_slug = array(
				'migration_jannah' => 'jnews_migration_jannah',
			);

			return array_merge( $translation_slug, $slug );
		}

		/**
		 * Render migration dashboard template
		 */
		public function migration_jannah() {
			require_once 'template/migration-dashboard.php';
		}

	}
}

