/**
 * External dependencies
 */
import React from 'react';

/**
 * Internal dependencies
 */
import TextWidget from '~/src/themes/components/TextWidget';
import MenuWidget from '~/src/themes/components/MenuWidget';
import SearchWidget from '~/src/themes/components/SearchWidget';
import FooterText from '~/src/themes/components/FooterText';
import PostBody from '~/src/themes/components/PostBody';
import PostTitle from '~/src/themes/components/PostTitle';
import PostDate from '~/src/themes/components/PostDate';
import PostAuthor from '~/src/themes/components/PostAuthor';
import PostContent from '~/src/themes/components/PostContent';
import ColumnComponent from '~/src/themes/components/ColumnComponent';
import PageLayout from '~/src/themes/components/PageLayout';
import RowComponent from '~/src/themes/components/RowComponent';
const ErrorComponent = ( { message } ) => <p>{message}</p>;

// TODO: register these automatically
const componentMap = {
	TextWidget: TextWidget,
	MenuWidget: MenuWidget,
	SearchWidget: SearchWidget,
	FooterText: FooterText,
	PostBody: PostBody,
	PostTitle: PostTitle,
	PostDate: PostDate,
	PostAuthor: PostAuthor,
	PostContent: PostContent,
	ColumnComponent: ColumnComponent,
	PageLayout: PageLayout,
	RowComponent: RowComponent,
	ErrorComponent: ErrorComponent,
};

function getNotFoundComponent( componentType ) {
	return () => <p>I could not find the component '{componentType}'!</p>;
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
