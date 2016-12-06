/* eslint-disable wpcalypso/import-docblock */
/* globals describe, it */

import { expect } from 'chai';
import { buildStylesFromTheme } from '~/src/lib/styles';

describe( 'buildStylesFromTheme()', function() {
	it( 'returns css with ComponentThemes prepended to each rule', function() {
		const theme = { name: 'MyTheme', slug: 'mytheme', styles: '.foo{color:green;}.bar{padding:1em;}' };
		const result = buildStylesFromTheme( theme );
		expect( result ).to.equal( '.ComponentThemes .foo{color:green;}.ComponentThemes .bar{padding:1em;}' );
	} );

	it( 'returns css with all additional-styles rules appended', function() {
		const theme = { name: 'MyTheme', slug: 'mytheme', styles: '.foo{color:green;}.bar{padding:1em;}', 'additional-styles': { basic: '.basic{color:#000;}', complex: '.complex{color:red;}' } };
		const result = buildStylesFromTheme( theme );
		expect( result ).to.equal( '.ComponentThemes .foo{color:green;}.ComponentThemes .bar{padding:1em;}.ComponentThemes .basic{color:#000;}.ComponentThemes .complex{color:red;}' );
	} );

	it( 'returns css with default variant-styles applied if none are active', function() {
		const theme = { name: 'MyTheme', slug: 'mytheme', styles: '.foo{color:$foo-color;}.bar{padding:1em;}', 'variant-styles': { defaults: { 'foo-color': 'yellow' } } };
		const result = buildStylesFromTheme( theme );
		expect( result ).to.equal( '.ComponentThemes .foo{color:yellow;}.ComponentThemes .bar{padding:1em;}' );
	} );

	it( 'returns css with active-variant-styles applied', function() {
		const theme = { name: 'MyTheme', slug: 'mytheme', styles: '.foo{color:$foo-color;}.bar{padding:1em;}', 'variant-styles': { defaults: { 'foo-color': 'yellow' }, red: { 'foo-color': 'red' } }, 'active-variant-styles': [ 'red' ] };
		const result = buildStylesFromTheme( theme );
		expect( result ).to.equal( '.ComponentThemes .foo{color:red;}.ComponentThemes .bar{padding:1em;}' );
	} );
} );
