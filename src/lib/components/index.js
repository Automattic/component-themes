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
import HeaderText from '~/src/themes/components/HeaderText';
import FooterText from '~/src/themes/components/FooterText';
import PostBody from '~/src/themes/components/PostBody';
import PostTitle from '~/src/themes/components/PostTitle';
import PostDateAndAuthor from '~/src/themes/components/PostDateAndAuthor';
import PostContent from '~/src/themes/components/PostContent';
import PostList from '~/src/themes/components/PostList';
import ColumnComponent from '~/src/themes/components/ColumnComponent';
import PageLayout from '~/src/themes/components/PageLayout';
import RowComponent from '~/src/themes/components/RowComponent';
const ErrorComponent = ( { message } ) => <p>{message}</p>;

const componentMap = {
	TextWidget: TextWidget,
	MenuWidget: MenuWidget,
	SearchWidget: SearchWidget,
	HeaderText: HeaderText,
	FooterText: FooterText,
	PostBody: PostBody,
	PostTitle: PostTitle,
	PostDateAndAuthor: PostDateAndAuthor,
	PostContent: PostContent,
	PostList: PostList,
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
