/**
 * External dependencies
 */
import React from 'react';
import classNames from 'classnames';
import shortid from 'shortid';

/**
 * Internal dependencies
 */
import { getComponentByType, getPartialByType, registerPartial } from '~/src/lib/components';

function buildComponent( Component, props = {}, children = [] ) {
	return <Component key={ props.key } { ...props }>{ children }</Component>;
}

function buildComponentTreeFromConfig( componentConfig, childProps = {} ) {
	const { id, componentType, children, props, partial } = componentConfig;
	if ( partial ) {
		return buildComponentTreeFromConfig( getPartialByType( partial ), childProps );
	}
	const componentId = id || shortid.generate();
	const Component = getComponentByType( componentType );
	const childComponents = children ? children.map( child => buildComponentTreeFromConfig( child, childProps ) ) : null;
	const componentProps = Object.assign(
		{},
		props || {},
		{ childProps },
		{ className: classNames( componentType, componentId ), key: componentId }
	);
	return { Component, componentId, componentProps, childComponents, componentType };
}

export function getPropsFromParent( mapPropsToProps ) {
	return function( Child ) {
		const ParentProps = ( props ) => {
			const newProps = mapPropsToProps( props.childProps );
			return <Child { ...newProps } { ...props } />;
		};
		return Object.assign( ParentProps, Child );
	};
}

function getContentById( content, componentId, componentType ) {
	return Object.assign( {}, content[ componentId ] || {}, content[ componentType ] || {} );
}

function buildComponentFromTree( tree, content = {} ) {
	const { Component, componentProps, childComponents, componentId, componentType } = tree;
	const children = childComponents ? childComponents.map( child => buildComponentFromTree( child, content ) ) : null;
	const props = Object.assign( {}, componentProps, getContentById( content, componentId, componentType ) );
	return buildComponent( Component, props, children );
}

export function makeComponentWith( componentConfig, childProps = {} ) {
	return buildComponentFromTree( buildComponentTreeFromConfig( componentConfig, childProps ) );
}

function buildComponentFromConfig( componentConfig, content = {} ) {
	return buildComponentFromTree( buildComponentTreeFromConfig( componentConfig ), content );
}

function mergeThemeProperty( property, theme1, theme2 ) {
	const isObject = obj => ( ! Array.isArray( obj ) && obj === Object( obj ) );
	const prop1 = theme1[ property ] || {};
	const prop2 = theme2[ property ] || {};
	if ( ! isObject( prop1 ) || ! isObject( prop2 ) ) {
		return prop2;
	}
	return Object.assign( {}, prop1, prop2 );
}

export function mergeThemes( theme1, theme2 ) {
	const properties = Object.keys( theme1 ).concat( Object.keys( theme2 ) ).filter( ( val, index, keys ) => keys.indexOf( val ) === index );
	return properties.reduce( ( theme, prop ) => {
		theme[ prop ] = mergeThemeProperty( prop, theme1, theme2 );
		return theme;
	}, {} );
}

function expandConfigTemplates( componentConfig, themeConfig ) {
	if ( componentConfig.template ) {
		return getTemplateForSlug( themeConfig, componentConfig.template );
	}
	return componentConfig;
}

function registerPartials( partials ) {
	Object.keys( partials ).map( key => registerPartial( key, partials[ key ] ) );
}

export function buildComponentsFromTheme( themeConfig, pageConfig, content = {} ) {
	registerPartials( themeConfig.partials || {} );
	return buildComponentFromConfig( expandConfigTemplates( pageConfig, themeConfig ), content );
}

export function getTemplateForSlug( themeConfig, slug ) {
	const originalSlug = slug;
	if ( ! themeConfig.templates ) {
		return { componentType: 'ErrorComponent', props: { message: `No template found matching '${ slug }' and no templates were defined in the theme` } };
	}
	// Try a '404' template, then 'home'
	if ( ! themeConfig.templates[ slug ] ) {
		slug = '404';
	}
	if ( ! themeConfig.templates[ slug ] ) {
		slug = 'home';
	}
	if ( ! themeConfig.templates[ slug ] ) {
		return { componentType: 'ErrorComponent', props: { message: `No template found matching '${ originalSlug }' and no 404 or home templates were defined in the theme` } };
	}
	const template = themeConfig.templates[ slug ];
	if ( template.template ) {
		return getTemplateForSlug( themeConfig, template.template );
	}
	return template;
}
