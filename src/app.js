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
import { registerComponent, registerPartial, getComponentByType } from '~/src/lib/components';
import date from '~/src/lib/date';
import route from '~/src/lib/route';
import storage from '~/src/lib/storage';
import defaultTheme from '~/src/themes/default.json';

const ComponentThemes = {
	renderPage( target ) {
		var { themeConfig, pageInfo, pageConfig } = storage.get();
		const App = <ComponentThemePage theme={ themeConfig } defaultTheme={ defaultTheme } page={ pageConfig } info={ pageInfo } />;
		if ( target || this.rootElement ) {
			ReactDOM.render( App, target || this.rootElement );
		}
	},
	React,
	styled,
	registerComponent,
	registerPartial,
	getComponentByType,
	apiDataWrapper,
	omit,
	date,
	storage,
	route,
};

storage.on( 'update', () => ComponentThemes.renderPage() );

if ( typeof window !== 'undefined' ) {
	window.ComponentThemes = ComponentThemes;
}

export default ComponentThemes;
