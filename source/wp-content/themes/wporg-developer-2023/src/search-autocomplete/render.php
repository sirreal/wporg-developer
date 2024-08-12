<?php

$id_prefix = wp_unique_id( 'wporg-search-ac' );

$context = array(
	'idListbox' => "{$id_prefix}-listbox",
	'props' => array(
		'minLength' => 3,
	),
	'query' => '',
	'options' => array(),
);

wp_interactivity_state(
	'wporg-developer/search-autocomplete',
	array(
	)
);

wp_interactivity_config(
	'wporg-developer/search-autocomplete',
	array(
		'endpointUrl' => rest_url('wporg-developer/v1/autocomplete'),
		'nonce'        => wp_create_nonce( 'wporg/autocomplete' ),
	)
);

?>
<div
	class="autocomplete__wrapper"
	data-wp-interactive="wporg-developer/search-autocomplete"
	data-wp-init="init"
	<?php echo wp_interactivity_data_wp_context( $context ); ?>
>
	<div
		class="autocomplete__status"
		style="
			border: 0px;
			clip: rect(0px, 0px, 0px, 0px);
			height: 1px;
			margin-bottom: -1px;
			margin-right: -1px;
			overflow: hidden;
			padding: 0px;
			position: absolute;
			white-space: nowrap;
			width: 1px;
		"
	>
		<div id="autocomplete-default__status--A" role="status" aria-atomic="true" aria-live="polite"></div>
		<div id="autocomplete-default__status--B" role="status" aria-atomic="true" aria-live="polite"></div>
	</div>
	<ul
		data-wp-bind--aria-labelledby="context.searchId"
		data-wp-bind--id="context.idListbox"
		role="listbox"
		class="autocomplete__menu autocomplete__menu--inline autocomplete__menu--hidden"
	>
		<template data-wp-each--option="context.options" data-wp-each-key="state.getOptionKey">
			<li
				data-wp-bind--aria-selected="state.getOptionIsFocused"
				data-wp-text="context.option.label"
				data-wp-bind--class="`${optionClassName}${optionModifierFocused}${optionModifierOdd}`"
				id="`${id}__option--${index}`"
				data-wp-on--blur="handleOptionBlur"
				data-wp-on--click="handleOptionClick"
				data-wp-on--mousedown="handleOptionMouseDown"
				data-wp-on--mouseenter="handleOptionMouseEnter"
				role='option'
				tabIndex='-1'
				aria-posinset="state.getOptionsPosinset"
				aria-setsize="state.getOptionsLength"
			></li>
		</template>
		<li
			aria-selected="false"
			class="autocomplete__option"
			id="autocomplete-default__option--0"
			role="option"
			tabindex="-1"
			aria-posinset="1"
			aria-setsize="220"
		>
			Afghanistan
		</li>
		<li
			aria-selected="false"
			class="autocomplete__option autocomplete__option--odd"
			id="autocomplete-default__option--1"
			role="option"
			tabindex="-1"
			aria-posinset="2"
			aria-setsize="220"
		>
			Akrotiri
		</li>
	</ul>
</div>
