/* globals window */

/**
 * External dependencies
 */
import React from 'react';
import ReactDOM from 'react-dom';

/**
 * Internal dependencies
 */
import { ComponentThemePage } from '~/src/';
import { apiDataWrapper } from '~/src/lib/api';
import { registerComponent } from '~/src/lib/components';
import { makeComponentWith, getPropsFromParent } from '~/src/lib/component-builder';
import Styles from '~/src/components/Styles';

const ComponentThemes = {
	renderPage: function( theme, slug, page, target ) {
		const App = <ComponentThemePage theme={ theme } page={ page } slug={ slug } />;
		ReactDOM.render( App, target );
	},

	React,
	registerComponent,
	apiDataWrapper,
	makeComponentWith,
	getPropsFromParent,
	Styles,
};

window.ComponentThemes = ComponentThemes;
