/* globals window */

/**
 * External dependencies
 */
import React from 'react';
import ReactDOM from 'react-dom';
import styled from 'styled-components';
import omit from 'lodash/omit';

/**
 * Internal dependencies
 */
import { ComponentThemePage } from '~/src/';
import { apiDataWrapper } from '~/src/lib/api';
import { registerComponent, registerPartial } from '~/src/lib/components';
import defaultTheme from '~/src/themes/default.json';

const ComponentThemes = {
	renderPage: function( theme, info, page, target ) {
		const App = <ComponentThemePage theme={ theme } defaultTheme={ defaultTheme } page={ page } info={ info } />;
		ReactDOM.render( App, target );
	},

	React,
	styled,
	registerComponent,
	registerPartial,
	apiDataWrapper,
	omit,
};

if ( typeof window !== 'undefined' ) {
	window.ComponentThemes = ComponentThemes;
}

export default ComponentThemes;
