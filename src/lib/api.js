/* globals window, fetch */
import React from 'react';

/**
 * External dependencies
 */
import 'es6-promise/auto';
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
	let api = {};
	if ( window.ComponentThemesApiData ) {
		api = window.ComponentThemesApiData;
	}
	return { api };
}

export function apiDataWrapper( mapApiToProps ) {
	return ( Target ) => {
		const ApiDataWrapper = ( props, context ) => {
			if ( ! context.fetchApiData ) {
				throw new Error( `Cannot render '${ Target }'. Components wrapped with apiDataWrapper must have an ancestor wrapped in apiDataProvider` );
			}
			const newProps = Object.assign( {}, props, mapApiToProps( context.apiProps, { getApiEndpoint: context.getApiEndpoint }, props ) );
			return <Target { ...newProps } >{ newProps.children }</Target>;
		};

		ApiDataWrapper.contextTypes = {
			apiProps: React.PropTypes.object,
			fetchApiData: React.PropTypes.func,
			getApiEndpoint: React.PropTypes.func,
		};

		return Object.assign( ApiDataWrapper, Target );
	};
}

export function apiDataProvider() {
	return ( Target ) => {
		class ApiProvider extends React.Component {
			constructor( props ) {
				super( props );
				this.getApiEndpoint = this.getApiEndpoint.bind( this );
				this.fetchApiData = this.fetchApiData.bind( this );
				const apiProps = getBootstrappedRequiredApiData();
				this.state = { apiProps };
			}

			getChildContext() {
				return { apiProps: this.state.apiProps, fetchApiData: this.fetchApiData, getApiEndpoint: this.getApiEndpoint };
			}

			getApiEndpoint( endpoint ) {
				const endpointData = this.state.apiProps.api[ endpoint ];
				if ( ! endpointData ) {
					this.fetchApiData( endpoint );
				}
				return this.state.apiProps.api[ endpoint ];
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
			getApiEndpoint: React.PropTypes.func,
		};

		return Object.assign( ApiProvider, Target );
	};
}
