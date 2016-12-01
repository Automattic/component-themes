/* globals window, fetch */

/**
 * External dependencies
 */
import Promise from 'promise-polyfill';
if ( ! window.Promise ) {
	window.Promise = Promise;
}
import 'whatwg-fetch';

function checkStatus( response ) {
	if ( response.status >= 200 && response.status < 300 ) {
		return response;
	}
	const err = new Error( response.statusText );
	err.response = response;
	throw err;
}

function parseJson( response ) {
	return response.json();
}

function fetchRequiredApiEndpoint( key, endpoint ) {
	return new Promise( resolve => {
		fetch( '/wp-json' + endpoint )
		.then( checkStatus )
		.then( parseJson )
		.then( json => resolve( { [ key ]: json } ) );
	} );
}

export function fetchRequiredApiData( componentType, requirements ) {
	return new Promise( resolve => {
		const requests = Object.keys( requirements ).map( key => fetchRequiredApiEndpoint( key, requirements[ key ] ) );
		Promise.all( requests )
			.then( values => {
				const apiData = values.reduce( ( fetched, response ) => {
					return Object.assign( {}, fetched, response );
				}, {} );
				resolve( apiData );
			} );
	} );
}

export function getBootstrappedRequiredApiData( componentType ) {
	if ( window.ComponentThemesApiData && window.ComponentThemesApiData[ componentType ] ) {
		return window.ComponentThemesApiData[ componentType ];
	}
}

