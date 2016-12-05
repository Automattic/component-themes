<?php
use function Corretto\describe, Corretto\it, Corretto\expect, Corretto\beforeEach;

require( './server/class-component-themes.php' );

describe( 'Component_Themes_Builder', function() {
	describe( '#render()', function() {
		describe( 'for an unregistered componentType', function() {
			beforeEach( function( $c ) {
				$c->theme = [ 'name' => 'TestTheme', 'slug' => 'testtheme' ];
				$c->page = [ 'id' => 'helloWorld', 'componentType' => 'WeirdThing', 'props' => [ 'text' => 'hello world' ] ];
				$c->builder = Component_Themes_Builder::get_builder();
			} );

			it( 'mentions the undefined componentType', function( $c ) {
				$result = $c->builder->render( $c->theme, $c->page );
				expect( $result )->toContain( "'WeirdThing'" );
			} );
		} );

	} );
} );
