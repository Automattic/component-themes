/* global require, module */

// no import because this is running directly from node
const omit = require( 'lodash' ).omit;

// These style functions are similar to those in lib/styles but include spacing
function getStyleStringFromStyleData( style ) {
	return Array.isArray( style ) ? style.join( '\n\t' ) : style;
}

function buildStyleBlock( key, style ) {
	return `${ key } {\n\t${ getStyleStringFromStyleData( style ) }\n}`;
}

function getStylesFromTheme( themeConfig ) {
	const stylesByComponent = themeConfig.styles || {};
	if ( typeof stylesByComponent === 'string' ) {
		return stylesByComponent;
	}
	return Object.keys( stylesByComponent )
		.map( key => buildStyleBlock( key, stylesByComponent[ key ] ) )
		.join( '\n' );
}

function getNonStylesFromTheme( themeConfig ) {
	return JSON.stringify( omit( themeConfig, 'styles' ), null, 2 );
}

function buildTheme( styles, components ) {
	return Object.assign( {}, components, { styles } );
}

// no export because this is running directly from node
module.exports = {
	buildTheme,
	getNonStylesFromTheme,
	getStylesFromTheme,
};
