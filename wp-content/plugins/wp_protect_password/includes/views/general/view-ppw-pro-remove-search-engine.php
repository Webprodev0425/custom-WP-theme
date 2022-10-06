<?php
$search_engine_checker = ppw_core_get_setting_type_bool( PPW_Pro_Constants::WPP_REMOVE_SEARCH_ENGINE ) ? 'checked' : '';
?>
<tr>
	<td>
		<label class="pda_switch" for="ppwp_remove_search_engine">
			<input type="checkbox"
			       id="ppwp_remove_search_engine" <?php echo esc_attr( $search_engine_checker ); ?> />
			<span class="pda-slider round"></span>
		</label>
	</td>
	<td>
		<p>
			<label><?php echo esc_html__( 'Block Search Indexing', PPW_Constants::DOMAIN ); ?></label>
			<?php echo _e( '<a target="_blank" rel="noopener noreferrer" href="https://passwordprotectwp.com/docs/settings/#block-indexing">Prevent search engines from indexing</a> your password protected content', PPW_Constants::DOMAIN ); ?>
		</p>
	</td>
</tr>
