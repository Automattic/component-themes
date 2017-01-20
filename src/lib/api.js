/* globals window, fetch */
import React from 'react';

/**
 * External dependencies
 */
import 'es6-promise/auto';
import 'whatwg-fetch';

import storage from './storage';

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
		fetch( '/?rest_route=' + endpoint )
		.then( checkStatus )
		.then( parseJson )
		.then( json => resolve( { [ endpoint ]: json } ) );
	} );
}

export function apiDataWrapper( mapApiToProps ) {
	return ( Target ) => {
		const ApiDataWrapper = ( props, context ) => {
			if ( ! context.fetchApiData ) {
				throw new Error( `Cannot render '${ Target }'. Components wrapped with apiDataWrapper must have an ancestor wrapped in apiDataProvider` );
			}
			const newProps = Object.assign( {}, props, mapApiToProps( context.getApiEndpoint, context.apiProps, props ) );
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
				this.setPageInfo = this.setPageInfo.bind( this );
				this.getApiEndpoint = this.getApiEndpoint.bind( this );
				this.fetchApiData = this.fetchApiData.bind( this );
			}

			getApiProps() {
				const data = storage.get();
				return { api: data.apiData.api || {}, pageInfo: data.pageInfo || {} };
			}

			getChildContext() {
				return { apiProps: this.getApiProps(), fetchApiData: this.fetchApiData, getApiEndpoint: this.getApiEndpoint };
			}

			setPageInfo( info ) {
				const data = storage.get();
				data.pageInfo = Object.assign( {}, data.pageInfo, info );
				storage.update( data );
			}

			getApiEndpoint( endpoint ) {
				const endpointData = this.getApiProps().api[ endpoint ];
				if ( ! endpointData ) {
					this.fetchApiData( endpoint );
					return null;
				}
				return endpointData;
			}

			fetchApiData( endpoint ) {
				if ( ! endpoint ) {
					throw new Error( `Cannot call fetchApiData without an endpoint. See ${ Target }` );
				}
				fetchRequiredApiEndpoint( endpoint )
					.then( result => {
						const data = storage.get();
						data.apiData.api = Object.assign( {}, data.apiData.api, result );
						storage.update( data );
					} );
			}

			render() {
				const props = Object.assign( {}, this.props, { setPageInfo: this.setPageInfo } );
				return <Target { ...props }>{ this.props.children }</Target>;
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
