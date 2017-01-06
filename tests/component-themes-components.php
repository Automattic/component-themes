<?php
use function Corretto\describe, Corretto\it, Corretto\expect, Corretto\beforeEach;

require_once( './server/class-react.php' );
require_once( './server/class-component-themes.php' );
require_once( './server/core-components.php' );

describe( 'TextWidget', function() {
	it ( 'should contain passed text prop as text node', function( $c ) {
		$component1 = Component_Themes_TextWidget( [ 'text' => 'Hello world' ] );
		$output1 = React::render( $component1 );

		expect( $output1 )->toEqual( "<div>Hello world</div>" );

		$component2 = Component_Themes_TextWidget( [ 'text' => 'Great to see you!' ] );
		$output2 = React::render( $component2 );

		expect( $output2 )->toEqual( "<div>Great to see you!</div>" );
	} );

	it ( 'should contain default text when passed text is empty', function( $c ) {
		$component1 = Component_Themes_TextWidget( [] );
		$output1 = React::render( $component1 );

		expect( $output1 )->toEqual( "<div>This is a text widget with no data!</div>" );

		$component2 = Component_Themes_TextWidget( [ 'text' => '' ] );
		$output2 = React::render( $component2 );

		expect( $output2 )->toEqual( "<div>This is a text widget with no data!</div>" );
	} );

	it ( 'should contain passed className in class attribute', function( $c ) {
		$component = Component_Themes_TextWidget( [ 'text' => 'Lorem ipsum', 'className' => 'test-class' ], [] );
		$output = React::render( $component );

		expect( $output )->toEqual( '<div class="test-class">Lorem ipsum</div>' );
	} );
} );

describe( 'MenuWidget', function() {
	beforeEach( function( $c ) {
		$c->title = 'Title for test';
		$c->links = [
			[ 'text' => 'Link #1', 'url' => 'http://te.st/link1' ],
			[ 'text' => 'Link #2', 'url' => 'http://te.st/link2' ],
		];
	} );

	describe( '#render', function() {
		it ( 'should contain <h2> and <li> in the output', function( $c ) {
			$component = new Component_Themes_Menu_Widget( [ 'title' => $c->title, 'links' => $c->links ] );
			$output = React::render( $component );

			expect( $output )->toContain( "<h2 class='MenuWidget__title'>{$c->title}</h2>" );
			expect( $output )->toContain( "<li class='MenuWidget__link'><a href='{$c->links[0]["url"]}'>{$c->links[0]["text"]}</a></li>" );
			expect( $output )->toContain( "<li class='MenuWidget__link'><a href='{$c->links[1]["url"]}'>{$c->links[1]["text"]}</a></li>" );
		} );

		it( 'should contain passed className in class attribute', function( $c ) {
			$component = new Component_Themes_Menu_Widget( [ 'className' => 'test-class' ] );
			$output = React::render( $component );

			expect( $output )->toContain( "<div class='test-class'>" );
		} );

		it( 'should not contain a <h2> if no title prop is given', function( $c ) {
			$component = new Component_Themes_Menu_Widget( [ 'links' => $c->links ] );
			$output = React::render( $component );

			expect( $output )->toNotContain( "<h2 class='MenuWidget__title'>" );
		} );

		it( 'should not contain any <li>s if no link prop is given', function( $c ) {
			$component = new Component_Themes_Menu_Widget();
			$output = React::render( $component );

			expect( $output )->toNotContain( '<li' );
		} );
	} );
} );
