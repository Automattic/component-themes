/* globals window */

function fetchRequiredApiEndpoint( endpoint ) {
	// TODO: fetch data from endpoint
}

export function fetchRequiredApiData( componentType, requirements ) {
	if ( window.ComponentThemesApiData && window.ComponentThemesApiData[ componentType ] ) {
		return window.ComponentThemesApiData[ componentType ];
	}
	return Object.keys( requirements ).reduce( ( fetched, key ) => {
		return Object.assign( {}, fetched, { [ key ]: fetchRequiredApiEndpoint( requirements[ key ] ) } );
	}, {} );
}

