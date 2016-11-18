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
	return `.StrangerThemes ${ key }{${ getStyleStringFromStyleData( style ) }}`;
}

function prependNamespaceToStyleString( namespace, styles ) {
	const styleObj = css.parse( styles, { silent: true } );
	const updatedObj = traverse( styleObj ).map( function( node ) {
		return this.key === 'selectors' ? this.update( node.map( selector => `${ namespace } ${ selector }` ) ) : node;
	} );
	return css.stringify( updatedObj );
}

function expandStyleVariants( styles, themeConfig ) {
	if ( ! themeConfig[ 'variant-styles' ] || ! themeConfig[ 'active-variant-styles' ] ) {
		return styles;
	}
	const variants = themeConfig[ 'variant-styles' ];
	const activeVariants = themeConfig[ 'active-variant-styles' ] || [];
	const defaults = variants.defaults || {};
	const finalVariants = activeVariants.reduce( ( prev, variantKey ) => {
		return Object.assign( {}, prev, variants[ variantKey ] || {} );
	}, defaults );
	return Object.keys( finalVariants ).reduce( ( prev, varName ) => prev.replace( `$${ varName }`, finalVariants[ varName ] ), styles );
}

export function buildStylesFromTheme( themeConfig ) {
	const stylesByComponent = themeConfig.styles || {};
	if ( typeof stylesByComponent === 'string' ) {
		return prependNamespaceToStyleString( '.StrangerThemes', expandStyleVariants( stylesByComponent, themeConfig ) );
	}
	return expandStyleVariants( Object.keys( stylesByComponent )
		.map( key => buildStyleBlock( key, stylesByComponent[ key ] ) )
		.join( '' ), themeConfig );
}
