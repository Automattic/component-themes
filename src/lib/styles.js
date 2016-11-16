/**
 * External dependencies
 */
import css from 'css';
import traverse from 'traverse';

function minifyStyleString( styleString ) {
	return styleString.split( /\s*\n\s*/ ).join( '' );
}

function getStyleStringFromStyleData( style ) {
	return minifyStyleString( Array.isArray( style ) ? style.join( '' ) : style );
}

function buildStyleBlock( key, style ) {
	return `.PrometheusBuilder ${ key }{${ getStyleStringFromStyleData( style ) }}`;
}

function prependNamespaceToStyleString( namespace, styles ) {
	const styleObj = css.parse( styles, { silent: true } );
	const updatedObj = traverse( styleObj ).map( function( node ) {
		return this.key === 'selectors' ? this.update( node.map( selector => `${ namespace } ${ selector }` ) ) : node;
	} );
	return css.stringify( updatedObj );
}

export function buildStylesFromTheme( themeConfig ) {
	const stylesByComponent = themeConfig.styles || {};
	if ( typeof stylesByComponent === 'string' ) {
		return prependNamespaceToStyleString( '.PrometheusBuilder', stylesByComponent );
	}
	return Object.keys( stylesByComponent )
		.map( key => buildStyleBlock( key, stylesByComponent[ key ] ) )
		.join( '' );
}
