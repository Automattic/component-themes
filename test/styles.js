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
} );
