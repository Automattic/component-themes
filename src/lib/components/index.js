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

export function getComponentByType( componentType ) {
	return componentMap[ componentType ] || getNotFoundComponent( componentType );
}

export function getComponentTypes() {
	return Object.keys( componentMap ).sort();
}

export function getComponentDescription( componentType ) {
	return getComponentByType( componentType ).description || '';
}

export function getComponentProps( componentType ) {
	return getComponentByType( componentType ).editableProps || {};
}

export function canComponentHaveChildren( componentType ) {
	return getComponentByType( componentType ).hasChildren || false;
}

export function registerComponent( componentType, component ) {
	componentMap[ componentType ] = component;
}

export function getPartialByType( partialType ) {
	return partialMap[ partialType ] || getNotFoundPartial( partialType );
}

export function registerPartial( partialType, componentConfig ) {
	partialMap[ partialType ] = componentConfig;
}
