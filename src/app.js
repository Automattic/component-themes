/* globals window, require */
/* eslint-disable no-var */

/**
 * External dependencies
 */
var React = require( 'react' );
var ReactDOM = require( 'react-dom' );

/**
 * Internal dependencies
 */
var ComponentThemePage = require( '../build' ).ComponentThemePage;
var apiDataWrapper = require( '../build/lib/api' ).apiDataWrapper;
var registerComponent = require( '../build/lib/components' ).registerComponent;
var makeComponentWith = require( '../build/lib/component-builder' ).makeComponentWith;

var ComponentThemes = {
	renderPage: function( theme, slug, page, target ) {
		var App = React.createElement( ComponentThemePage, { theme, page, slug } );
		ReactDOM.render( App, target );
	},

	React,
	registerComponent,
	apiDataWrapper,
	makeComponentWith,
};

window.ComponentThemes = ComponentThemes;
