/* eslint-disable object-shorthand, id-length */
import * as IAPI from '@wordpress/interactivity';

const NAMESPACE = 'wporg-developer/search-autocomplete';

const store = IAPI.store( NAMESPACE, {
	state: {
		get hasAutoselect() {
			return isIosDevice() ? false : IAPI.getContext().props.autoselect;
		},
	},

	init: () => {
		/** @type {HTMLInputElement|null} */
		const input = document.querySelector( '#wporg-search input[type="search"]' );

		if ( input ) {
			const ctx = IAPI.getContext();
			if ( ! input.id ) {
				input.id = window.crypto.randomUUID();
			}
			ctx.searchId = input.id;
			input.setAttribute( 'aria-autocomplete', 'list' );
			input.setAttribute( 'aria-expanded', 'false' );
			input.setAttribute( 'aria-owns', ctx.idListbox );
			input.setAttribute( 'autocomplete', 'off' );
			input.setAttribute( 'role', 'combobox' );

			input.addEventListener( 'input', store.handleInput, { passive: true } );
		}
	},

	setState: ( obj ) => {
		const ctx = IAPI.getContext();
		for ( const [ key, value ] of Object.entries( obj ) ) {
			ctx[ key ] = value;
		}
	},

	/** @param {InputEvent} event */
	handleInput: ( event ) => {
		const ctx = IAPI.getContext();

		const { minLength, showAllValues } = ctx.props;

		const autoselect = store.state.hasAutoselect;
		const query = event.target.value;
		const queryEmpty = query.length === 0;
		const queryChanged = ctx.query !== query;
		const queryLongEnough = query.length >= minLength;

		store.setState( {
			query,
			ariaHint: queryEmpty,
		} );

		const searchForOptions = showAllValues || ( ! queryEmpty && queryChanged && queryLongEnough );
		if ( searchForOptions ) {
			source( query, ( options ) => {
				const optionsAvailable = options.length > 0;
				store.setState( {
					menuOpen: optionsAvailable,
					options,
					selected: autoselect && optionsAvailable ? 0 : -1,
					validChoiceMade: false,
				} );
			} );
		} else if ( queryEmpty || ! queryLongEnough ) {
			store.setState( {
				menuOpen: false,
				options: [],
			} );
		}
	},
} );

async function source_p( query ) {
	const cfg = IAPI.getConfig( NAMESPACE );
	const url = new URL( cfg.endpointUrl );
	const requestData = {
		search: query,
		post_types: [],
		nonce: cfg.nonce,
	};
	const response = await fetch( url, {
		method: 'POST',
		body: JSON.stringify( requestData ),
		headers: { 'Content-Type': 'application/json' },
	} );
	if ( ! response.ok ) {
		throw new Error( 'Bad response.' );
	}
	return await response.json();
}

function source( query, cb ) {
	return source_p( query ).then(
		( { posts } ) => cb( posts ),
		( err ) => {
			console.error( err );
			return [];
		}
	);
}

function isIosDevice() {
	return (
		typeof navigator !== 'undefined' &&
		/(iPod|iPhone|iPad)/g.test( window.navigator.userAgent ) &&
		/AppleWebKit/g.test( window.navigator.userAgent )
	);
}
