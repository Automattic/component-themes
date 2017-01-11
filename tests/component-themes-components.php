<?php
use function Corretto\describe, Corretto\it, Corretto\expect, Corretto\beforeEach;

require_once( './server/class-component-themes-builder.php' );
require_once( './server/class-react.php' );
require_once( './server/class-component-themes.php' );
require_once( './server/core-components.php' );

class Mock_JSON_Component extends Component_Themes_Component {
	public function render() {
		$props = ct_omit( $this->props, [ 'context', 'children' ] );
		$json_text = json_encode( $props );
		return React::createElement( 'span', [], [ $json_text ] );
	}
}

describe( 'ColumnComponent', function() {
	beforeEach( function( $c ) {
		$c->props = [ 'className' => 'ColumnComponent' ];
		$c->children = [
			Component_Themes_TextWidget( [ 'text' => 'Hello world' ] ),
			Component_Themes_TextWidget( [ 'text' => 'Foo bar' ] ),
		];
	} );
	describe( '#render', function() {
		it( 'should render passed children', function( $c ) {
			$component = Component_Themes_Column_Component( [], $c->children );
			$output = React::render( $component );

			expect( $output )->toEqual( '<div><div>Hello world</div> <div>Foo bar</div></div>' );
		} );
		it( 'should contain passed className in class attribute', function( $c ) {
			$component = Component_Themes_Column_Component( [ 'className' => 'test-class' ], [] );
			$output = React::render( $component );

			expect( $output )->toContain( '<div class="test-class">' );
		} );
		it( 'should send passed props to the children', function( $c ) {
			$c->props[ 'childProp' ] = 'child-value';
			$c->props[ 'testProp' ] = 'test-value';
			$component = Component_Themes_Column_Component( $c->props, [ new Mock_JSON_Component() ] );
			$output = React::render( $component );

			expect( $output )->toEqual( '<div class="ColumnComponent"><span>{"childProp":"child-value","testProp":"test-value"}</span></div>' );
		} );
	} );
} );

describe( 'FooterText', function() {
	beforeEach( function ( $c ) {
		$c->props = [ 'className' => 'FooterText', 'text' => 'The footer text' ];
	} );
	describe( '#render', function() {
		it( 'should contain passed className and text', function( $c ) {
			$component = new Component_Themes_FooterText( $c->props );
			$output = React::render( $component );

			expect( $output )->toEqual( '<div class="FooterText">The footer text</div>' );
		} );
		it( 'should contain passed text prop as text node', function( $c ) {
			$c->props['text'] = 'Hello world';
			$component = new Component_Themes_FooterText( $c->props );
			$output = React::render( $component );

			expect( $output )->toEqual( '<div class="FooterText">Hello world</div>' );
		} );
		it( 'should contain passed className in class attribute', function( $c ) {
			$c->props['className'] = 'test-class';
			$component = new Component_Themes_FooterText( $c->props );
			$output = React::render( $component );

			expect( $output )->toEqual( '<div class="test-class">The footer text</div>' );
		} );
		it( 'should contain default text when passed text is empty', function( $c ) {
			unset( $c->props['text'] );
			$component = new Component_Themes_FooterText( $c->props );
			$output = React::render( $component );

			expect( $output )->toEqual( '<div class="FooterText"><a href="/">Create a free website or blog at WordPress.com.</a></div>' );
		} );
		it( 'should not contain class attribute when className prop is empty', function( $c ) {
			unset( $c->props['className'] );
			$component = new Component_Themes_FooterText( $c->props );
			$output = React::render( $component );

			expect( $output )->toEqual( '<div>The footer text</div>' );
		} );
	} );
} );

describe( 'HeaderText', function() {
	beforeEach( function ( $c ) {
		$c->props = [
			'siteTitle' => 'The blog',
			'siteTagline' => 'This is a tagline',
			'className' => 'HeaderText',
			'link' => 'https://te.st',
		];
	} );
	describe( '#render', function() {
		it( 'should contain passed siteTitle, siteTagline, className and link', function( $c ) {
			$component = new Component_Themes_HeaderText( $c->props );
			$output = React::render( $component );

			expect( $output )->toEqual( '<div class="HeaderText"><a href="https://te.st"><h1 class="HeaderText__title">The blog</h1> <div class="HeaderText__tagline">This is a tagline</div></a></div>' );
		} );
		it( 'should contain passed siteTitle prop as text node', function( $c ) {
			$c->props['siteTitle'] = 'Hello world';
			$component = new Component_Themes_HeaderText( $c->props );
			$output = React::render( $component );

			expect( $output )->toEqual( '<div class="HeaderText"><a href="https://te.st"><h1 class="HeaderText__title">Hello world</h1> <div class="HeaderText__tagline">This is a tagline</div></a></div>' );
		} );
		it( 'should contain passed siteTagline prop as text node', function( $c ) {
			$c->props['siteTagline'] = 'A tagline for my blog';
			$component = new Component_Themes_HeaderText( $c->props );
			$output = React::render( $component );

			expect( $output )->toEqual( '<div class="HeaderText"><a href="https://te.st"><h1 class="HeaderText__title">The blog</h1> <div class="HeaderText__tagline">A tagline for my blog</div></a></div>' );
		} );
		it( 'should contain passed link prop in the anchor element', function( $c ) {
			$c->props['link'] = 'http://wordpress.com';
			$component = new Component_Themes_HeaderText( $c->props );
			$output = React::render( $component );

			expect( $output )->toEqual( '<div class="HeaderText"><a href="http://wordpress.com"><h1 class="HeaderText__title">The blog</h1> <div class="HeaderText__tagline">This is a tagline</div></a></div>' );
		} );
		it( 'should contain passed className in class attribute', function( $c ) {
			$c->props['className'] = 'test-class';
			$component = new Component_Themes_HeaderText( $c->props );
			$output = React::render( $component );

			expect( $output )->toEqual( '<div class="test-class"><a href="https://te.st"><h1 class="HeaderText__title">The blog</h1> <div class="HeaderText__tagline">This is a tagline</div></a></div>' );
		} );
		it( 'should contain default site title when siteTitle prop is empty', function( $c ) {
			unset( $c->props['siteTitle'] );
			$component = new Component_Themes_HeaderText( $c->props );
			$output = React::render( $component );

			expect( $output )->toEqual( '<div class="HeaderText"><a href="https://te.st"><h1 class="HeaderText__title">My Website</h1> <div class="HeaderText__tagline">This is a tagline</div></a></div>' );
		} );
		it( 'should contain default tagline when siteTagline prop is empty', function( $c ) {
			unset( $c->props['siteTagline'] );
			$component = new Component_Themes_HeaderText( $c->props );
			$output = React::render( $component );

			expect( $output )->toEqual( '<div class="HeaderText"><a href="https://te.st"><h1 class="HeaderText__title">The blog</h1> <div class="HeaderText__tagline">My home on the web</div></a></div>' );
		} );
		it( 'should not contain class attribute when className prop is empty', function( $c ) {
			unset( $c->props['className'] );
			$component = new Component_Themes_HeaderText( $c->props );
			$output = React::render( $component );

			expect( $output )->toEqual( '<div><a href="https://te.st"><h1 class="HeaderText__title">The blog</h1> <div class="HeaderText__tagline">This is a tagline</div></a></div>' );
		} );
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
		it( 'should contain <h2> and <li> in the output', function( $c ) {
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

describe( 'PageLayout', function() {
	beforeEach( function( $c ) {
		$c->props = [ 'className' => 'PageLayout' ];
		$c->children = [
			Component_Themes_TextWidget( [ 'text' => 'Hello world' ] ),
			Component_Themes_TextWidget( [ 'text' => 'Foo bar' ] ),
		];
	} );
	describe( '#render', function() {
		it( 'should render passed children', function( $c ) {
			$component = new Component_Themes_PageLayout( $c->props, $c->children );
			$output = React::render( $component );

			expect( $output )->toEqual( '<div class="PageLayout"><div class="PageLayout__content"><div>Hello world</div> <div>Foo bar</div></div></div>' );
		} );
		it( 'should contain passed className in class attribute', function( $c ) {
			$component = new Component_Themes_PageLayout( [ 'className' => 'test-class' ], [] );
			$output = React::render( $component );

			expect( $output )->toEqual( '<div class="test-class"><div class="PageLayout__content"></div></div>' );
		} );
		it( 'should not contain class attribute when className prop is empty', function( $c ) {
			unset( $c->props['className'] );
			$component = new Component_Themes_PageLayout( $c->props, [] );
			$output = React::render( $component );

			expect( $output )->toEqual( '<div><div class="PageLayout__content"></div></div>' );
		} );
		it( 'should send passed props to the children', function( $c ) {
			$c->props[ 'childProp' ] = 'child-value';
			$c->props[ 'testProp' ] = 'test-value';
			$component = new Component_Themes_PageLayout( $c->props, [ new Mock_JSON_Component() ] );
			$output = React::render( $component );

			expect( $output )->toEqual( '<div class="PageLayout"><div class="PageLayout__content"><span>{"childProp":"child-value","testProp":"test-value"}</span></div></div>' );
		} );
	} );
} );

describe( 'TextWidget', function() {
	describe( '#render', function() {
		it( 'should contain passed text prop as text node', function( $c ) {
			$component1 = Component_Themes_TextWidget( [ 'text' => 'Hello world' ] );
			$output1 = React::render( $component1 );

			expect( $output1 )->toEqual( "<div>Hello world</div>" );

			$component2 = Component_Themes_TextWidget( [ 'text' => 'Great to see you!' ] );
			$output2 = React::render( $component2 );

			expect( $output2 )->toEqual( "<div>Great to see you!</div>" );
		} );

		it( 'should contain default text when text prop is empty', function( $c ) {
			$component1 = Component_Themes_TextWidget( [] );
			$output1 = React::render( $component1 );

			expect( $output1 )->toEqual( "<div>This is a text widget with no data!</div>" );

			$component2 = Component_Themes_TextWidget( [ 'text' => '' ] );
			$output2 = React::render( $component2 , [ 'text' => 'The footer text' ] );;

			expect( $output2 )->toEqual( "<div>This is a text widget with no data!</div>" );
		} );

		it( 'should contain passed className in class attribute', function( $c ) {
			$component = Component_Themes_TextWidget( [ 'text' => 'Lorem ipsum', 'className' => 'test-class' ], [] );
			$output = React::render( $component );

			expect( $output )->toEqual( '<div class="test-class">Lorem ipsum</div>' );
		} );
	} );
} );

describe( 'PostTitle', function() {
	describe( '#render', function() {
		beforeEach( function( $c ) {
			$c->props = [ 'title' => 'Post Title', 'link' => 'http://te.st/1234', 'className' => 'PostTitle' ];
		} );

		it( 'should render properly', function( $c ) {
			$component = Component_Themes_Post_Title( $c->props );
			$output = React::render( $component );
			expect( $output )->toEqual( '<h1 class="PostTitle"><a class="PostTitle_link" href="http://te.st/1234">Post Title</a></h1>' );
		} );

		it( 'should contain detault title when title prop is empty', function( $c ) {
			unset( $c->props['title'] );
			$component = Component_Themes_Post_Title( $c->props );
			$output = React::render( $component );
			expect( $output )->toEqual( '<h1 class="PostTitle"><a class="PostTitle_link" href="http://te.st/1234">No title</a></h1>' );
		} );

		it( 'should contain passed title prop as text node', function( $c ) {
			$c->props['title'] = 'Post Title #2';
			$component = Component_Themes_Post_Title( $c->props );
			$output = React::render( $component );
			expect( $output )->toEqual( '<h1 class="PostTitle"><a class="PostTitle_link" href="http://te.st/1234">Post Title #2</a></h1>' );
		} );

		it( 'should contain passed link prop in href attribute of an anchor element', function( $c ) {
			$c->props['link'] = 'http://te.st/567';
			$component = Component_Themes_Post_Title( $c->props );
			$output = React::render( $component );
			expect( $output )->toEqual( '<h1 class="PostTitle"><a class="PostTitle_link" href="http://te.st/567">Post Title</a></h1>' );
		} );

		it( 'should not contain class attribute when className prop is empty', function( $c ) {
			unset( $c->props['className'] );
			$component = Component_Themes_Post_Title( $c->props );
			$output = React::render( $component );
			expect( $output )->toEqual( '<h1><a class="PostTitle_link" href="http://te.st/1234">Post Title</a></h1>' );
		} );

		it( 'should contain passed className in class attribute', function( $c ) {
			$c->props['className'] = 'post-title';
			$component = Component_Themes_Post_Title( $c->props );
			$output = React::render( $component );
			expect( $output )->toEqual( '<h1 class="post-title"><a class="PostTitle_link" href="http://te.st/1234">Post Title</a></h1>' );
		} );
	} );
} );
