/* globals window, fetch */
import React from 'react';

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

function fetchRequiredApiEndpoint( endpoint ) {
	return new Promise( resolve => {
		fetch( '/wp-json' + endpoint )
		.then( checkStatus )
		.then( parseJson )
		.then( json => resolve( { [ endpoint ]: json } ) );
	} );
}

export function getBootstrappedRequiredApiData() {
	if ( window.ComponentThemesApiData ) {
		return window.ComponentThemesApiData;
	}
}

export function apiDataWrapper( endpoints, mapApiToProps ) {
	return ( Target ) => {
		const ApiProps = ( props, context ) => {
			if ( ! context.fetchApiData ) {
				throw new Error( `Cannot render '${ Target }'. Components wrapped with apiDataWrapper must have an ancestor wrapped in apiDataProvider` );
			}
			endpoints.map( endpoint => {
				if ( ! getApiEndpoint( context.apiProps, endpoint ) ) {
					context.fetchApiData( endpoint );
				}
			} );
			const newProps = Object.assign( {}, props, mapApiToProps( context.apiProps ) );
			return <Target { ...newProps } >{ newProps.children }</Target>;
		};

		ApiProps.contextTypes = {
			apiProps: React.PropTypes.object,
			fetchApiData: React.PropTypes.func,
		};

		return Object.assign( ApiProps, Target );
	};
}

export function getApiEndpoint( api = {}, endpoint ) {
	return api[ endpoint ];
}

export function apiDataProvider() {
	return ( Target ) => {
		class ApiProvider extends React.Component {
			constructor( props ) {
				super( props );
				this.fetchApiData = this.fetchApiData.bind( this );
				const apiProps = getBootstrappedRequiredApiData();
				this.state = { apiProps };
			}

			getChildContext() {
				return { apiProps: this.state.apiProps, fetchApiData: this.fetchApiData };
			}

			fetchApiData( endpoint ) {
				if ( ! endpoint ) {
					throw new Error( `Cannot call fetchApiData without an endpoint. See ${ Target }` );
				}
				fetchRequiredApiEndpoint( endpoint )
					.then( result => {
						const apiProps = Object.assign( {}, this.state.apiProps, result );
						this.setState( { apiProps } );
					} );
			}

			render() {
				return <Target { ...this.props }>{ this.props.children }</Target>;
			}
		}

		ApiProvider.childContextTypes = {
			apiProps: React.PropTypes.object,
			fetchApiData: React.PropTypes.func,
		};

		return Object.assign( ApiProvider, Target );
	};
}
