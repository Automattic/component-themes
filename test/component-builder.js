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

const TextWidget = ( { text, color, className } ) => {
	return (
		<div className={ className }>
			<p>text is: { text || 'This is a text widget with no data!' }</p>
			<p>color is: { color || 'default' }</p>
		</div>
	);
};
registerComponent( 'TextWidget', TextWidget );

describe( 'buildComponentsFromTheme()', function() {
	describe( 'for an unregistered componentType', function() {
		beforeEach( function() {
			theme = { name: 'TestTheme', slug: 'testtheme' };
			page = { id: 'helloWorld', componentType: 'WeirdThing', props: { text: 'hello world' } };
		} );

		it( 'returns a React component', function() {
			const Result = buildComponentsFromTheme( theme, page );
			expect( Result.props ).to.include.keys( 'text' );
		} );

		it( 'mentions the undefined componentType', function() {
			const Result = buildComponentsFromTheme( theme, page );
			const wrapper = shallow( Result );
			expect( wrapper.text() ).to.contain( "'WeirdThing'" );
		} );
	} );

	describe( 'for a registered componentType', function() {
		beforeEach( function() {
			theme = { name: 'TestTheme', slug: 'testtheme' };
			page = { id: 'helloWorld', componentType: 'TextWidget', props: { text: 'hello world' } };
		} );

		it( 'returns a React component', function() {
			const Result = buildComponentsFromTheme( theme, page );
			expect( Result.props ).to.include.keys( 'text' );
		} );

		it( 'includes the id as a className', function() {
			const Result = buildComponentsFromTheme( theme, page );
			const wrapper = shallow( Result );
			expect( wrapper.find( '.helloWorld' ) ).to.have.length( 1 );
		} );

		it( 'includes the componentType as a className', function() {
			const Result = buildComponentsFromTheme( theme, page );
			const wrapper = shallow( Result );
			expect( wrapper.find( '.TextWidget' ) ).to.have.length( 1 );
		} );

		it( 'includes the props passed in the object description', function() {
			const Result = buildComponentsFromTheme( theme, page );
			const wrapper = shallow( Result );
			expect( wrapper.text() ).to.contain( 'text is: hello world' );
		} );

		it( 'includes props not passed in the object description as falsy values', function() {
			const Result = buildComponentsFromTheme( theme, page );
			const wrapper = shallow( Result );
			expect( wrapper.text() ).to.contain( 'color is: default' );
		} );
	} );
} );

