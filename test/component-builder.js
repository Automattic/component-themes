/* eslint-disable wpcalypso/import-docblock */
/* globals describe, it, beforeEach */
import React from 'react';
import { shallow } from 'enzyme';
import { expect } from 'chai';

import { buildComponentsFromTheme } from '~/src/lib/component-builder';

let theme;
let page;

import ComponentThemes from '~/src/app';
const { registerComponent } = ComponentThemes;

const TextWidget = ( { text, className } ) => {
	return (
		<div className={ className }>
			{ text || 'This is a text widget with no data!' }
		</div>
	);
};
registerComponent( 'TextWidget', TextWidget );

describe( 'buildComponentsFromTheme()', function() {
	describe( 'for a TextWidget', function() {
		beforeEach( function() {
			theme = {
				name: 'TestTheme',
				slug: 'testtheme',
			};
			page = { id: 'helloWorld', componentType: 'TextWidget', props: { text: 'hello world' } };
		} );

		it( 'returns a React component for a component in a page', function() {
			const Result = buildComponentsFromTheme( theme, page );
			expect( Result.props ).to.include.keys( 'text' );
		} );

		it( 'includes the id as a className', function() {
			const Result = buildComponentsFromTheme( theme, page );
			const wrapper = shallow( Result );
			expect( wrapper.find( '.helloWorld' ) ).to.have.length( 1 );
		} );
	} );
} );

