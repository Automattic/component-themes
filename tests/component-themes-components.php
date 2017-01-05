<?php
use function Corretto\describe, Corretto\it, Corretto\expect, Corretto\beforeEach;

require_once( './server/class-react.php' );
require_once( './server/class-component-themes.php' );
require_once( './server/core-components.php' );

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
