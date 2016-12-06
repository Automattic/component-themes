<?php
use function Corretto\describe, Corretto\it, Corretto\expect, Corretto\beforeEach;

require( './server/class-component-themes.php' );

// @codingStandardsIgnoreStart
function Component_Themes_TextWidget( $props ) {
	// @codingStandardsIgnoreEnd
	$class = ct_get_value( $props, 'className', '' );
	$text = ct_get_value( $props, 'text', 'This is a text widget with no data!' );
	$color = ct_get_value( $props, 'color', 'default' );
	return React::createElement( 'div', [ 'className' => $class ], [ React::createElement( 'p', [], 'text is: ' . $text ), React::createElement( 'p', [], 'color is: ' . $color ) ] );
};
// @codingStandardsIgnoreStart
class Component_Themes_ColumnComponent extends Component_Themes_Component {
	// @codingStandardsIgnoreEnd
	public function render() {
		return "<div class='" . $this->get_prop( 'className' ) . "'>" . $this->render_children() . '</div>';
	}
}

$test_partial = [ 'id' => 'helloWorld', 'componentType' => 'TextWidget', 'props' => [ 'text' => 'test partial' ] ];
Component_Themes_Builder::register_partial( 'TestPartial', $test_partial );

describe( 'Component_Themes_Builder', function() {
	beforeEach( function( $c ) {
		$c->builder = Component_Themes_Builder::get_builder();
	} );

	describe( '#render()', function() {
		describe( 'for an unregistered componentType', function() {
			beforeEach( function( $c ) {
				$c->theme = [ 'name' => 'TestTheme', 'slug' => 'testtheme' ];
				$c->page = [ 'id' => 'helloWorld', 'componentType' => 'WeirdThing', 'props' => [ 'text' => 'hello world' ] ];
			} );

			it( 'mentions the undefined componentType', function( $c ) {
				$result = $c->builder->render( $c->theme, $c->page );
				expect( $result )->toContain( "'WeirdThing'" );
			} );
		} );

		describe( 'for a registered componentType', function() {
			beforeEach( function( $c ) {
				$c->theme = [ 'name' => 'TestTheme', 'slug' => 'testtheme' ];
				$c->page = [ 'id' => 'helloWorld', 'componentType' => 'TextWidget', 'props' => [ 'text' => 'hello world' ] ];
			} );

			it( 'includes the id as a className', function( $c ) {
				$result = $c->builder->render( $c->theme, $c->page );
				expect( $result )->toContain( 'helloWorld' );
			} );

			it( 'includes the componentType as a className', function( $c ) {
				$result = $c->builder->render( $c->theme, $c->page );
				expect( $result )->toContain( 'TextWidget' );
			} );

			it( 'includes the props passed in the object description', function( $c ) {
				$result = $c->builder->render( $c->theme, $c->page );
				expect( $result )->toContain( 'text is: hello world' );
			} );

			it( 'includes props not passed in the object description as falsy values', function( $c ) {
				$result = $c->builder->render( $c->theme, $c->page );
				expect( $result )->toContain( 'color is: default' );
			} );
		} );

		describe( 'with a template that is part of the theme', function() {
			beforeEach( function( $c ) {
				$c->theme = [ 'name' => 'TestTheme', 'slug' => 'testtheme', 'templates' => [ 'hello' => [ 'id' => 'helloWorld', 'componentType' => 'TextWidget' ] ] ];
				$c->page = [ 'template' => 'hello' ];
			} );

			it( 'includes the template id as a className', function( $c ) {
				$result = $c->builder->render( $c->theme, $c->page );
				expect( $result )->toContain( 'helloWorld' );
			} );

			it( 'includes the template componentType as a className', function( $c ) {
				$result = $c->builder->render( $c->theme, $c->page );
				expect( $result )->toContain( 'TextWidget' );
			} );
		} );

		describe( 'with a partial that is part of the theme', function() {
			beforeEach( function( $c ) {
				$c->theme = [ 'name' => 'TestTheme', 'slug' => 'testtheme', 'partials' => [ 'hello' => [ 'id' => 'helloWorld', 'componentType' => 'TextWidget' ] ] ];
				$c->page = [ 'id' => 'layout', 'componentType' => 'ColumnComponent', 'children' => [ [ 'id' => 'existing', 'componentType' => 'TextWidget' ], [ 'partial' => 'hello' ] ] ];
			} );

			it( 'does not affect sibling components', function( $c ) {
				$result = $c->builder->render( $c->theme, $c->page );
				expect( $result )->toContain( 'existing' );
			} );

			it( 'includes the partial id as a className', function( $c ) {
				$result = $c->builder->render( $c->theme, $c->page );
				expect( $result )->toContain( 'helloWorld' );
			} );
		} );

		describe( 'with a registered partial that is not part of the theme', function() {
			beforeEach( function( $c ) {
				$c->theme = [ 'name' => 'TestTheme', 'slug' => 'testtheme' ];
				$c->page = [ 'id' => 'layout', 'componentType' => 'ColumnComponent', 'children' => [ [ 'id' => 'existing', 'componentType' => 'TextWidget' ], [ 'partial' => 'TestPartial' ] ] ];
			} );

			it( 'does not affect sibling components', function( $c ) {
				$result = $c->builder->render( $c->theme, $c->page );
				expect( $result )->toContain( 'existing' );
			} );

			it( 'includes the partial id as a className', function( $c ) {
				$result = $c->builder->render( $c->theme, $c->page );
				expect( $result )->toContain( 'helloWorld' );
			} );
		} );
	} );

	describe( '#merge_themes()', function() {
		beforeEach( function( $c ) {
			$c->theme1 = [ 'name' => 'First Theme', 'slug' => 'first', 'templates' => [ 'firstTemplate' => [ 'id' => 'helloWorld', 'componentType' => 'TextWidget', 'props' => [ 'text' => 'first text' ] ], 'mergingTemplate' => [ 'id' => 'toBeOverwritten', 'componentType' => 'TextWidget' ] ] ];
			$c->theme2 = [ 'name' => 'Second Theme', 'partials' => [], 'templates' => [ 'secondTemplate' => [ 'id' => 'helloWorld', 'componentType' => 'TextWidget', 'props' => [ 'text' => 'second text' ] ], 'mergingTemplate' => [ 'id' => 'overwriter', 'componentType' => 'TextWidget' ] ] ];
		} );

		it( 'includes the keys of both themes', function( $c ) {
			$theme = $c->builder->merge_themes( $c->theme1, $c->theme2 );
			expect( array_keys( $theme ) )->toContain( 'name' );
			expect( array_keys( $theme ) )->toContain( 'slug' );
			expect( array_keys( $theme ) )->toContain( 'partials' );
			expect( array_keys( $theme ) )->toContain( 'templates' );
		} );

		it( 'includes the templates in the first theme', function( $c ) {
			$theme = $c->builder->merge_themes( $c->theme1, $c->theme2 );
			expect( array_keys( $theme['templates'] ) )->toContain( 'firstTemplate' );
		} );

		it( 'includes the templates in the second theme', function( $c ) {
			$theme = $c->builder->merge_themes( $c->theme1, $c->theme2 );
			expect( array_keys( $theme['templates'] ) )->toContain( 'secondTemplate' );
		} );

		it( 'overwrites string properties of the first theme with properties of the second', function( $c ) {
			$theme = $c->builder->merge_themes( $c->theme1, $c->theme2 );
			expect( $theme['name'] )->toEqual( 'Second Theme' );
		} );

		it( 'overwrites number properties of the first theme with properties of the second', function( $c ) {
			$c->theme1['name'] = 4;
			$c->theme2['name'] = 8;
			$theme = $c->builder->merge_themes( $c->theme1, $c->theme2 );
			expect( $theme['name'] )->toEqual( 8 );
		} );

		it( 'overwrites array properties of the first theme with properties of the second', function( $c ) {
			$c->theme1['name'] = [ 'a' ];
			$c->theme2['name'] = [ 'b' ];
			$theme = $c->builder->merge_themes( $c->theme1, $c->theme2 );
			expect( $theme['name'] )->toEqual( [ 'b' ] );
		} );

		it( 'overwrites object property properties of the first theme with those of the second', function( $c ) {
			$theme = $c->builder->merge_themes( $c->theme1, $c->theme2 );
			expect( $theme['templates']['mergingTemplate']['id'] )->toEqual( 'overwriter' );
		} );
	} );
} );
