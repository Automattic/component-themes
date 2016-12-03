/* globals window */

/**
 * External dependencies
 */
import React from 'react';
import ReactDOM from 'react-dom';

/**
 * Internal dependencies
 */
import { ComponentThemePage } from '../build';
import { apiDataWrapper } from '../build/lib/api';
import { registerComponent } from '../build/lib/components';
import { makeComponentWith } from '../build/lib/component-builder';

const ComponentThemes = {
	renderPage: function( theme, slug, page, target ) {
		const App = React.createElement( ComponentThemePage, { theme, page, slug } );
		ReactDOM.render( App, target );
	},

	React,
	registerComponent,
	apiDataWrapper,
	makeComponentWith,
};

window.ComponentThemes = ComponentThemes;
