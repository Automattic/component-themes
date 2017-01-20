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
import defaultTheme from '~/src/themes/default.json';
import '~/src/lib';

const ComponentThemes = {
	renderPage( theme, info, page, target ) {
		const App = <ComponentThemePage theme={ theme } defaultTheme={ defaultTheme } page={ page } info={ info } />;
		ReactDOM.render( App, target );
	},
};

export default ComponentThemes;
