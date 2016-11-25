/**
 * External dependencies
 */
import React from 'react';
import classNames from 'classnames';
import shortid from 'shortid';

/**
 * Internal dependencies
 */
import { getComponentByType } from '~/src/lib/components';

function buildComponent( Component, props = {}, children = [] ) {
	return <Component key={ props.key } { ...props }>{ children }</Component>;
}

function buildComponentTreeFromConfig( componentConfig, childProps = {} ) {
	const { id, componentType, children, props } = componentConfig;
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

function fetchRequiredApiEndpoint( endpoint ) {}

//TODO: move this to its own module
function fetchRequiredApiData( componentType, requirements ) {
	if ( window.ComponentThemesApiData && window.ComponentThemesApiData[ componentType ] ) {
		return window.ComponentThemesApiData[ componentType ];
	}
	return Object.keys( requirements ).reduce( ( fetched, key ) => {
		return Object.assign( {}, fetched, { [ key ]: fetchRequiredApiEndpoint( requirements[ key ] ) } );
	}, {} );
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
	const apiProps = Component.requiredApiData ? fetchRequiredApiData( componentType, Component.requiredApiData ) : {};
	const props = Object.assign( {}, componentProps, getContentById( content, componentId, componentType ), apiProps );
	return buildComponent( Component, props, children );
}

export function makeComponentWith( componentConfig, childProps = {} ) {
	return buildComponentFromTree( buildComponentTreeFromConfig( componentConfig, childProps ) );
}

function buildComponentFromConfig( componentConfig, content = {} ) {
	return buildComponentFromTree( buildComponentTreeFromConfig( componentConfig ), content );
}

function expandConfigPartials( componentConfig, partials ) {
	if ( componentConfig.partial ) {
		if ( partials[ componentConfig.partial ] ) {
			return partials[ componentConfig.partial ];
		}
		return { componentType: 'ErrorComponent', props: { message: `No partial found matching '${ componentConfig.partial }'` } };
	}
	if ( componentConfig.children ) {
		const children = componentConfig.children.map( child => expandConfigPartials( child, partials ) );
		return Object.assign( {}, componentConfig, { children } );
	}
	return componentConfig;
}

function expandConfigTemplates( componentConfig, themeConfig ) {
	if ( componentConfig.template ) {
		return getTemplateForSlug( themeConfig, componentConfig.template );
	}
	return componentConfig;
}

export function buildComponentsFromTheme( themeConfig, pageConfig, content = {} ) {
	return buildComponentFromConfig( expandConfigPartials( expandConfigTemplates( pageConfig, themeConfig ), themeConfig.partials || {} ), content );
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
