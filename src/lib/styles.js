/* globals window */
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
	return `.ComponentThemes ${ key }{${ getStyleStringFromStyleData( style ) }}`;
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

function addAdditionalStyles( styles, themeConfig ) {
	const additional = themeConfig[ 'additional-styles' ] || {};
	return styles + Object.keys( additional ).map( key => additional[ key ] ).join( '' );
}

export function buildStylesFromTheme( themeConfig ) {
	const stylesByComponent = themeConfig.styles || {};
	if ( typeof stylesByComponent === 'string' ) {
		return prependNamespaceToStyleString( '.ComponentThemes', expandStyleVariants( addAdditionalStyles( stylesByComponent, themeConfig ), themeConfig ) );
	}
	const basicStyles = Object.keys( stylesByComponent )
		.map( key => buildStyleBlock( key, stylesByComponent[ key ] ) )
		.join( '' );
	return expandStyleVariants( addAdditionalStyles( basicStyles, themeConfig ), themeConfig );
}

export function writeStylesToPage( key, styles ) {
	if ( typeof window === 'undefined' ) {
		return;
	}
	const className = `component-themes-${ key }`;
	let el = window.document.querySelector( `.${ className }` );
	if ( ! el ) {
		const head = window.document.querySelector( 'head' );
		if ( ! head ) {
			return;
		}
		el = window.document.createElement( 'style' );
		el.className = className;
		head.appendChild( el );
	}
	el.innerHTML = styles;
}
