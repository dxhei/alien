<?php
	$migration = JNews_Migration_Soledad::getInstance();
	$count     = $migration->content_migration( true );
	$review    = $migration->review_plugin_check();
	$recipe    = $migration->recipe_plugin_check();
?>

<div class="jnews-container">
	<div class="jnews-install-plugin-wrap">
		<div class="jnews-install-plugin-header">
			<h3><?php esc_html_e( 'Content Migration', 'jnews-migration-soledad' ); ?></h3>
		</div>

		<div class="jnews-section-description">
			<!-- Notice JNews Review Plugin -->
			<?php if ( $review ) : ?>
				<p class="migration-notice warning">
					<?php echo wp_kses( sprintf( __( 'We notice you\'re not %1$s <strong>JNews Review Plugin</strong> yet. Please %2$s <strong>JNews Review Plugin</strong> on this page <a href="%3$s">here</a>.', 'jnews-migration-soledad' ), $review, $review, esc_url( menu_page_url( 'jnews_plugin', false ) ) . '#' . 'jnews-review' ), wp_kses_allowed_html() ); ?>
				</p>
			<?php endif; ?>

			<!-- Notice JNews Food Recipe Plugin -->
			<?php if ( $recipe ) : ?>
				<p class="migration-notice warning">
					<?php echo wp_kses( sprintf( __( 'We notice you\'re not %1$s <strong>JNews Food Recipe Plugin</strong> yet. Please %2$s <strong>JNews Food Recipe Plugin</strong> on this page <a href="%3$s">here</a>.', 'jnews-migration-soledad' ), $recipe, $recipe, esc_url( menu_page_url( 'jnews_plugin', false ) ) . '#' . 'jnews-food-recipe' ), wp_kses_allowed_html() ); ?>
				</p>
			<?php endif; ?>
		</div>

		<div class="jnews-dashboard-notice jeg-migration-wrapper" data-success="<?php echo wp_kses( __( 'Migration Done. You can check migration log on each post below.', 'jnews-migration-soledad' ), wp_kses_allowed_html() ); ?>">
			<div class="jnews-row">
				<div class="jnews-item">
					<div class="jnews-item-description">
						<h3 class="jnews-item-title"><?php esc_html_e( 'Migration Status', 'jnews-migration-soledad' ); ?></h3>

						<?php if ( ! empty( $count ) ) : ?>
							<p class="migration-status"><?php echo wp_kses( sprintf( __( '%1$d post%2$s found and ready to migrate into <strong>JNews</strong>.', 'jnews-migration-soledad' ), $count, ( $count > 1 ) ? 's' : '' ), wp_kses_allowed_html() ); ?></p>
						<?php else : ?>
							<p class="migration-status"><?php echo wp_kses( __( 'No post found.', 'jnews-migration-soledad' ), wp_kses_allowed_html() ); ?></p>
						<?php endif; ?>

					</div>

					<div class="jeg-progress-bar">
						<div class="progress-line"><span class="progress"></span></div>
					</div>

					<div class="jnews-item-button jnews-migration-btn" data-post-count="<?php esc_attr_e( $count ); ?>">
						<input type="hidden" value="<?php echo wp_create_nonce( 'jnews_migration_soledad' ); ?>" class="nonce"/>
						<a class="button button-primary nodirect" data-progress="<?php esc_html_e( 'Processing...', 'jnews-migration-soledad' ); ?>" data-success="<?php esc_html_e( 'Go To Post', 'jnews-migration-soledad' ); ?>" href="<?php echo admin_url( 'edit.php' ); ?>">
							<?php ! empty( $count ) ? esc_html_e( 'Begin Migration', 'jnews-migration-soledad' ) : esc_html_e( 'No Post Found', 'jnews-migration-soledad' ); ?>
						</a>
					</div>
				</div>
			</div>
		</div>

		<div class="jnews-migration-log">
			<ul class="migration-log-list"></ul>
		</div>
	</div>
</div>
