import * as IAPI from '@wordpress/interactivity';

const { state } = IAPI.store( 'wporg/developer/search-filters', {
	state: {
		isChecked: () => {
			return state.selectedPostTypes.includes( IAPI.getContext().item[ 0 ] );
		},

		ariaIsPressedAll: () => {
			return ! state.selectedPostTypes.length;
		},
	},

	/** @param {Event} evt */
	handleChange: ( evt ) => {
		const region = evt.target.closest( '[data-wp-interactive]' );
		state.selectedPostTypes = [ ...region.querySelectorAll( ':checked' ) ].map( ( { value } ) => value );
	},

	/** @param {Event} evt */
	handleAll: ( evt ) => {
		evt.preventDefault();
		state.selectedPostTypes = [];
	},
} );
