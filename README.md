# Component Themes

The theming system in WordPress dates [back to 2005](https://wordpress.org/news/2005/02/strayhorn/). A lot has changed on the web, and in WordPress, since then.

What would we build for WordPress theming and customizing today? Component Themes is our answer.

## Why?

WordPress is frustrating for many users who just want to make their website look the way they want.

The proliferation of drag-and-drop/wysiwyg/page-builder products for WP and its competition speaks to a basic desire: to have direct control of the website layout and appearance.

The WP Theme Customizer can't do this, because WP themes weren't built to be customized. Actually, they *were* built to be customized: by editing your PHP template files. Even though WP is open source software made by people who love technology, most of our users would love to never see anything that looks like code, ever again. They want control. They want to edit what they see, where they see it. And maybe that's also a democratization of publishing.

## What's in a name?

That which we call a theme by any other name would look as sweet.

Let's redefine what makes up a theme by allowing each page to have its own layout and content, building it up from a library of components and templates. These pages would still benefit from the WordPress template hierarchy, but would also allow creating a website in a more natural and customizable manner, right down to the layout of a blog post.

## Parts

There are three pieces to this project.

1. Library: this project provides a pure Javascript, a React Component, and a PHP means of rendering a page using a Component Theme.
2. WordPress Plugin: this project is also a WordPress plugin that will add the Component Theme system to a site.
3. Site Builder: a separate project is a site builder app which will allow editing the theme and pages.

In the future, the Library and Plugin may become separate repositories.

You can read all about the pieces of a theme and a page in the [theme directory](./src/themes/README.md).

## Usage as a Library

To render a theme config as a React component, use the `ComponentThemePage` component:

```js
import { ComponentThemePage } from 'component-themes';

const themeConfig = {
	"name": "MyTheme",
	"slug": "mytheme"
};
const pageConfig = { "id": "siteLayout", "componentType": "ColumnComponent", "children": [
	{ "id": "helloWorld", "componentType": "TextWidget", "props": { "text": "hello world" } }
] };
const info = { slug: 'home' };

const App = () => (
	<div>
		<ComponentThemePage theme={ themeConfig } page={ pageConfig } info={ info } />
	</div>
);
```

But you don't need React just to render the page. Just load the Javascript file `component-themes/build/app.js` and use the globally exported `ComponentThemes.renderPage()` function.

```js
const themeConfig = {
	"name": "MyTheme",
	"slug": "mytheme"
};
const pageConfig = { "id": "siteLayout", "componentType": "ColumnComponent", "children": [
	{ "id": "helloWorld", "componentType": "TextWidget", "props": { "text": "hello world" } }
] };
const info = { slug: 'home' };
ComponentThemes.renderPage( themeConfig, info, pageConfig, document.getElementById( 'root' ) );
```

To render a page using PHP, use the `Component_Themes->render_page()` method:

```php
<?php
require( './node_modules/component-themes/server/class-component-themes.php' );
require( './node_modules/component-themes/server/core-components.php' );

$theme_config = json_decode( '{
	"name": "MyTheme",
	"slug": "mytheme"
}', true);
$page_config = json_decode( '{ "id": "siteLayout", "componentType": "ColumnComponent", "children": [
	{ "id": "helloWorld", "componentType": "TextWidget", "props": { "text": "hello world" } }
] }', true );
$page_slug = ( is_home() || is_front_page() ) ? 'home' : get_post_field( 'post_name', get_post() );
$page_type = ( is_home() || is_front_page() ) ? 'home' : ( is_single() ? 'post' : ( is_archive() ? 'archive' : 'page' ) );
$page_id = ( 'post' === $page_type || 'page' === $page_type ) ? $wp_query->post->ID : '';
$page_info = [
	'slug' => $page_slug,
	'type' => $page_type,
	'postId' => $page_id,
];

$renderer = new Component_Themes();
$rendered_output = $renderer->render_page( $theme_config, $page_info, $page_config );
echo $rendered_output;
```

## Usage as a WordPress Plugin

Download and move this repository into your `wp-content/plugins` directory.

For now, you'll need to build the plugin by entering into the repository directory and typing `npm install && npm run build`. In the future that step will not be necessary.

Then activate the plugin in your WordPress admin interface and visit any front-end page to see it operate.
