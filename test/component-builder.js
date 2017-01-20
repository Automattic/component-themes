/* eslint-disable wpcalypso/import-docblock */
/* globals describe, it, beforeEach */
import React from 'react';
import { shallow } from 'enzyme';
import { expect } from 'chai';

import { registerComponent, registerPartial } from '~/src/lib/components';
import { buildComponentsFromTheme, mergeThemes } from '~/src/lib/component-builder';

const TextWidget = ( { text, color, className } ) => {
	return (
		<div className={ className }>
			<p>text is: { text || 'This is a text widget with no data!' }</p>
			<p>color is: { color || 'default' }</p>
		</div>
	);
};
registerComponent( 'TextWidget', TextWidget );

const ColumnComponent = ( { children, className } ) => {
	return (
		<div className={ className }>
			{ children }
		</div>
	);
};
registerComponent( 'ColumnComponent', ColumnComponent );

const TestPartial = { id: 'helloWorld', componentType: 'TextWidget', props: { text: 'test partial' } };
registerPartial( 'TestPartial', TestPartial );

let theme;
let page;

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

	describe( 'with a template that is part of the theme', function() {
		beforeEach( function() {
			theme = { name: 'TestTheme', slug: 'testtheme', templates: { hello: { id: 'helloWorld', componentType: 'TextWidget', props: { text: 'something' } } } };
			page = { template: 'hello' };
		} );

		it( 'returns a React component', function() {
			const Result = buildComponentsFromTheme( theme, page );
			expect( Result.props ).to.include.keys( 'text' );
		} );

		it( 'includes the template id as a className', function() {
			const Result = buildComponentsFromTheme( theme, page );
			const wrapper = shallow( Result );
			expect( wrapper.find( '.helloWorld' ) ).to.have.length( 1 );
		} );

		it( 'includes the template componentType as a className', function() {
			const Result = buildComponentsFromTheme( theme, page );
			const wrapper = shallow( Result );
			expect( wrapper.find( '.TextWidget' ) ).to.have.length( 1 );
		} );
	} );

	describe( 'with a partial that is part of the theme', function() {
		beforeEach( function() {
			theme = { name: 'TestTheme', slug: 'testtheme', partials: { hello: { id: 'helloWorld', componentType: 'TextWidget', props: { text: 'something' } } } };
			page = { id: 'layout', componentType: 'ColumnComponent', children: [ { id: 'existing', componentType: 'TextWidget' }, { partial: 'hello' } ] };
		} );

		it( 'does not affect sibling components', function() {
			const Result = buildComponentsFromTheme( theme, page );
			const wrapper = shallow( Result );
			expect( wrapper.find( '.existing' ) ).to.have.length( 1 );
		} );

		it( 'includes the partial id as a className', function() {
			const Result = buildComponentsFromTheme( theme, page );
			const wrapper = shallow( Result );
			expect( wrapper.find( '.helloWorld' ) ).to.have.length( 1 );
		} );

		it( 'includes the partial componentType as a className', function() {
			const Result = buildComponentsFromTheme( theme, page );
			const wrapper = shallow( Result );
			expect( wrapper.find( '.TextWidget' ) ).to.have.length( 2 );
		} );
	} );

	describe( 'with a registered partial that is not part of the theme', function() {
		beforeEach( function() {
			theme = { name: 'TestTheme', slug: 'testtheme' };
			page = { id: 'layout', componentType: 'ColumnComponent', children: [ { id: 'existing', componentType: 'TextWidget' }, { partial: 'TestPartial' } ] };
		} );

		it( 'does not affect sibling components', function() {
			const Result = buildComponentsFromTheme( theme, page );
			const wrapper = shallow( Result );
			expect( wrapper.find( '.existing' ) ).to.have.length( 1 );
		} );

		it( 'includes the partial id as a className', function() {
			const Result = buildComponentsFromTheme( theme, page );
			const wrapper = shallow( Result );
			expect( wrapper.find( '.helloWorld' ) ).to.have.length( 1 );
		} );

		it( 'includes the partial componentType as a className', function() {
			const Result = buildComponentsFromTheme( theme, page );
			const wrapper = shallow( Result );
			expect( wrapper.find( '.TextWidget' ) ).to.have.length( 2 );
		} );
	} );
} );

describe( 'mergeThemes()', function() {
	let theme1, theme2;

	beforeEach( function() {
		theme1 = { name: 'First Theme', slug: 'first', 'additional-styles': { basic: '.foo { color: green; }' }, templates: { firstTemplate: { id: 'helloWorld', componentType: 'TextWidget', props: { text: 'first text' } }, mergingTemplate: { id: 'toBeOverwritten', componentType: 'TextWidget' } } };
		theme2 = { name: 'Second Theme', partials: { hello: { id: 'helloWorld', componentType: 'TextWidget', props: { text: 'something' } } }, templates: { secondTemplate: { id: 'helloWorld', componentType: 'TextWidget', props: { text: 'second text' } }, mergingTemplate: { id: 'overwriter', componentType: 'TextWidget' } } };
	} );

	it( 'includes the keys of both themes', function() {
		const newTheme = mergeThemes( theme1, theme2 );
		expect( newTheme ).to.include.keys( 'name', 'slug', 'partials', 'templates', 'additional-styles' );
	} );

	it( 'includes the templates in the first theme', function() {
		const newTheme = mergeThemes( theme1, theme2 );
		expect( newTheme.templates ).to.include.keys( 'firstTemplate' );
	} );

	it( 'includes the templates in the second theme', function() {
		const newTheme = mergeThemes( theme1, theme2 );
		expect( newTheme.templates ).to.include.keys( 'secondTemplate' );
	} );

	it( 'overwrites string properties of the first theme with properties of the second', function() {
		const newTheme = mergeThemes( theme1, theme2 );
		expect( newTheme.name ).to.equal( 'Second Theme' );
	} );

	it( 'overwrites number properties of the first theme with properties of the second', function() {
		theme1.name = 4;
		theme2.name = 8;
		const newTheme = mergeThemes( theme1, theme2 );
		expect( newTheme.name ).to.equal( 8 );
	} );

	it( 'overwrites array properties of the first theme with properties of the second', function() {
		theme1.name = [ 'a' ];
		theme2.name = [ 'b' ];
		const newTheme = mergeThemes( theme1, theme2 );
		expect( newTheme.name ).to.eql( [ 'b' ] );
	} );

	it( 'overwrites object property properties of the first theme with those of the second', function() {
		const newTheme = mergeThemes( theme1, theme2 );
		expect( newTheme.templates.mergingTemplate.id ).to.equal( 'overwriter' );
	} );

	it( 'contains the object properties of the second theme that are not present in the first', function() {
		const newTheme = mergeThemes( theme1, theme2 );
		expect( newTheme.partials ).to.include.keys( 'hello' );
	} );

	it( 'contains the object properties of the first theme that are not present in the second', function() {
		const newTheme = mergeThemes( theme1, theme2 );
		expect( newTheme[ 'additional-styles' ] ).to.include.keys( 'basic' );
	} );
} );
