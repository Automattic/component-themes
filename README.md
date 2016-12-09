[![Build Status](https://travis-ci.org/Automattic/component-themes.svg?branch=master)](https://travis-ci.org/Automattic/component-themes)

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
const pageSlug = 'home';

const App = () => (
	<div>
		<ComponentThemePage theme={ themeConfig } page={ pageConfig } slug={ pageSlug } />
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
const pageSlug = 'home';
ComponentThemes.renderPage( themeConfig, pageSlug, pageConfig, document.getElementById( 'root' ) );
```

To render a page using PHP, use the `Component_Themes->render_page()` method:

```php
<?php
require( './node_modules/component-themes/server/class-component-themes.php' );
require( './node_modules/component-themes/server/core-components.php' );

$themeConfig = json_decode( '{
	"name": "MyTheme",
	"slug": "mytheme"
}', true);
$pageConfig = json_decode( '{ "id": "siteLayout", "componentType": "ColumnComponent", "children": [
	{ "id": "helloWorld", "componentType": "TextWidget", "props": { "text": "hello world" } }
] }', true );
$pageSlug = 'home';

$renderer = new Component_Themes();
$rendered_output = $renderer->render_page( $themeConfig, $pageSlug, $pageConfig );
echo $rendered_output;
```

## Content

**WARNING: Heavily subject to change!!**

To inject content (eg: site title, posts) into pages, we will actually provide the content as props to the components that need it. For this to work we need to know the ID of the component we want to pass data to. Here's a component with the ID `myPosts`:

```json
{ "id": "myPosts", "componentType": "PostList" }
```

This component accepts an array of posts as a prop and then it displays them. The content, therefore, needs to be an array of post data in a JSON object under the key `myPosts`:

```json
{"myPosts":{"posts":[{"postId":1,"title":"My First Post","date":"February 22, 2013","author":"The Human","link":"http://localhost:3000","content":"This is my very first blog post."}]}}
```

This data can then be passed to the rendering engine as an additional parameter.

Here's how to send it in Javascript:

```js
import { ComponentThemePage } from 'component-themes';

const themeConfig = {
	"name": "MyTheme",
	"slug": "mytheme"
};
const pageConfig = { "id": "siteLayout", "componentType": "ColumnComponent", "children": [
	{ "id": "myPosts", "componentType": "PostList" }
] };
const pageSlug = 'home';
const content = {"myPosts":{"posts":[{"postId":1,"title":"My First Post","date":"February 22, 2013","author":"The Human","link":"http://localhost:3000","content":"This is my very first blog post."}]}};

const App = () => (
	<div>
		<ComponentThemePage theme={ themeConfig } page={ pageConfig } slug={ pageSlug } content={ content } />
	</div>
);
```

And here's how to send the data in PHP:

```php
<?php
require( './node_modules/component-themes/server/class-component-themes.php' );

$themeConfig = json_decode( '{
	"name": "MyTheme",
	"slug": "mytheme"
}', true );
$pageConfig = json_decode( '{ "id": "siteLayout", "componentType": "ColumnComponent", "children": [
	{ "id": "myPosts", "componentType": "PostList" }
] }', true );
$pageSlug = 'home';
$content = json_decode( '{"myPosts":{"posts":[{"postId":1,"title":"My First Post","date":"February 22, 2013","author":"The Human","link":"http://localhost:3000","content":"This is my very first blog post."}]}}', true );

$renderer = new Component_Themes();
$rendered_output = $renderer->render_page( $themeConfig, $pageSlug, $pageConfig, $content );
echo $rendered_output;
```

## Usage as a WordPress Plugin

Download and move this repository into your `wp-content/plugins` directory.

For now, you'll need to build the plugin by entering into the repository directory and typing `npm install && npm run build`. In the future that step will not be necessary.

Then activate the plugin in your WordPress admin interface and visit any front-end page to see it operate.
