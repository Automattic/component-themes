/**
 * External dependencies
 */
import React from 'react';

const ErrorComponent = ( { message } ) => <p>{message}</p>;

const componentMap = {
	ErrorComponent: ErrorComponent,
};

const partialMap = {};

function getNotFoundComponent( componentType ) {
	return () => <p>I could not find the component '{componentType}'!</p>;
}

function getNotFoundPartial( partialType ) {
	return { componentType: 'ErrorComponent', props: { message: `I could not find the partial '${ partialType }'` } };
}

function getComponentEntryByType( componentType ) {
	return componentMap[ componentType ] || { component: getNotFoundComponent( componentType ), properties: {} };
}

function getComponentPropertiesByType( componentType ) {
	return getComponentEntryByType( componentType ).properties;
}

export function getComponentByType( componentType ) {
	return getComponentEntryByType( componentType ).component;
}

export function getComponentTypes() {
	return Object.keys( componentMap ).sort();
}

export function getComponentDescription( componentType ) {
	return getComponentPropertiesByType( componentType ).description || '';
}

export function getComponentProps( componentType ) {
	return getComponentPropertiesByType( componentType ).editableProps || {};
}

export function canComponentHaveChildren( componentType ) {
	return getComponentPropertiesByType( componentType ).hasChildren || false;
}

export function registerComponent( componentType, component, properties = {} ) {
	componentMap[ componentType ] = { component, properties };
}

export function getPartialByType( partialType ) {
	return partialMap[ partialType ] || getNotFoundPartial( partialType );
}

export function registerPartial( partialType, componentConfig ) {
	partialMap[ partialType ] = componentConfig;
}
