<div class="jnews_podcast jnews_dock_player">
	<div class="jnews_dock_player_inner clearfix">
		<?php echo do_shortcode( '[jeg_player]' ); ?>
	</div>
	<script>
	  var initial_player = localStorage.getItem('jnews_player'),
		initial_player = null !== initial_player && 0 < initial_player.length ? JSON.parse(localStorage.getItem('jnews_player')) : {},
		playlist = 'undefined' !== typeof initial_player.playlist ? initial_player.playlist.length != 0 : false

	  if (playlist) {
		document.getElementsByClassName('jnews_dock_player')[0].classList.add('show')
	  }
	</script>
</div>
