<?php

$search_post_types = array(
	array( 'wp-parser-function', __( 'Functions', 'wporg' ) ),
	array( 'wp-parser-hook', __( 'Hooks', 'wporg' ) ),
	array( 'wp-parser-class', __( 'Classes', 'wporg' ) ),
	array( 'wp-parser-method', __( 'Methods', 'wporg' ) ),
);

$selected_post_types = array_filter( (array) get_query_var( 'post_type' ) );
$no_filters   = true === $GLOBALS['wp_query']->is_empty_post_type_search;
if ( in_array( 'any', $selected_post_types ) || $no_filters ) {
	// No filters used.
	$selected_post_types = array();
}

wp_interactivity_state(
	'wporg/developer/search-filters',
	array(
		'selectedPostTypes' => $selected_post_types,
		'searchPostTypes' => $search_post_types,

		/*
		 * START: derived state
		 * This should be implemented in view.js as well.
		 */
		'isChecked' => function () use ( $selected_post_types ) {
			return in_array( wp_interactivity_get_context()['item'][0], $selected_post_types );
		},

		'ariaIsPressedAll' => function () use ( $selected_post_types ) {
			return array() === $selected_post_types ? 'true' : 'false';
		}
		/*
		 * END: derived state
		 */
	)
);

?>
<div <?php echo get_block_wrapper_attributes(); ?> data-wp-interactive="wporg/developer/search-filters">
	<div class="wp-block-button is-style-toggle is-small">
		<button id="wp-block-wporg-search-filters-all" class="wp-block-button__link wp-element-button" data-wp-bind--aria-pressed="state.ariaIsPressedAll" data-wp-on--click="handleAll"><?php esc_html_e( __( 'All', 'wporg' ) ); ?></button>
	</div>
	<template data-wp-each="state.searchPostTypes" data-wp-each-key="context.item.0">
		<div class="wp-block-button is-style-toggle is-small">
			<input data-wp-bind--id="context.item.0" type="checkbox" name="post_type[]" data-wp-bind--value="context.item.0" data-wp-bind--checked="state.isChecked" data-wp-on--change="handleChange" />
			<label data-wp-bind--for="context.item.0" class="wp-block-button__link wp-element-button" data-wp-text="context.item.1"></label>
		</div>
	</template>
	<div class="wp-block-button is-style-text is-small">
		<button class="wp-block-button__link wp-element-button" type="submit"><?php esc_html_e( __( 'Apply', 'wporg' ) ); ?></button>
	</div>
</div>
