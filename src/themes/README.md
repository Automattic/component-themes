# Themes and Pages

Pages are built of components, which are self-contained content blocks that have their own settings. Some components can also have child components which will be displayed within that component. This allows creating page layout by composing components (a technique familiar to anyone who has used [React](https://facebook.github.io/react/) or similar libraries).

Each page of a site is a JSON object that refers to a single component (typically of type `PageLayout`) with child components that represent the layout of the page. The components used can be either *core components* (those included in Component Themes) or *theme components* (components built of other components and included in the theme). It's also possible for the site owner to create their own theme components by adding them to the theme with an editor.

A theme also includes **templates**, which are fully pre-constructed pages that can be copied to create new pages.

There are a few default page templates that are included with all sites unless overridden by the theme: `home`, `post`, and `post-list`. These templates will be used by default for the home page, the single post page, and all post list pages respectively.

Styles for the components in a site are also included in the theme.

Some page content and settings are included directly in the page JSON, but that data can also be overridden by content provided by the site itself (eg: a list of blog posts).

As with React, content and settings are provided to a component in the form of "props".

A site editor for will modify page JSON and possibly a copy of the site's theme JSON, essentially turning it into a custom theme for that site.

## Theme

Each theme is a JSON file that contains one object. The only requirement for the theme is that the object has the keys `name` and `slug` which identify the theme.

**That said, the JSON file is not intended to be edited directly!** It is intended to be read and modified by tools. The owner of a site or a theme developer might have an app to customize styles, layout, and settings. An advanced theme developer might use command-line tools to build the styles and components into the theme with their own editor.

Advanced theme developers might also build their own core components, which require their own React (and optionally PHP) files. Many themes should be able to be built without editing components, however, by just customizing the props for those components and composing shared sets of theme components ("partials") in the theme itself.

Here is a very basic example theme with a custom home page template that simply has a `TextWidget` displaying the default text of "hello world".

```json
{
	"name": "MyTheme",
	"slug": "mytheme",
	"templates": {
		"home": { "id": "siteLayout", "componentType": "ColumnComponent", "children": [
			{ "id": "helloWorld", "componentType": "TextWidget", "props": { "text": "hello world" } }
		] }
	}
}
```

Here is a slightly more realistic example of a theme for a blog (still without any styling).

```json
{
	"name": "MyTheme",
	"slug": "mytheme",
	"templates": {
		"home": { "id": "siteLayout", "componentType": "ColumnComponent", "children": [
			{ "id": "pageLayout", "componentType": "ColumnComponent", "children": [
				{ "id": "headerLayout", "componentType": "RowComponent", "children": [
					{ "id": "headerText", "componentType": "HeaderText" }
				] },
					{ "id": "contentLayout", "componentType": "RowComponent", "children": [
						{ "id": "myPosts", "componentType": "PostList" },
						{ "id": "sidebarLayout", "componentType": "ColumnComponent", "children": [
							{ "id": "sidebarSearch", "componentType": "SearchWidget" }
						] }
					] }
			] }
		] }
	}
}
```

## Components

You can read more about the format of Components in the [Components README](./components/README.md).

## Core Components

There are two main categories of components: **container** components and **content** components. Container components generally have few or no visual elements but serve to lay out other components. Content components display some content. Both are designed to be customized by providing props (content and settings).

Each component in a page is an object that has at least two properties: `id` and `componentType`.

`componentType` is a string that refers to an existing component.

`id` is a unique identifier string for that instance of the component.

When styles are applied, they are selected by either the `componentType` (to affect all components of that type) or by `id` (to affect just one instance of a component in a page).

Here is a simple TextWidget component:

```json
{ "id": "helloWorld", "componentType": "TextWidget" }
```

Here is a TextWidget that also sets some default text for the component by passing props (the props that a component accepts are defined in the component; `TextWidget` accepts just one: `text`).

```json
{ "id": "helloWorld", "componentType": "TextWidget", "props": { "text": "hello world" } }
```

Using container components requires putting components into other components as children. Here is a RowComponent with two TextWidgets:

```json
{ "id": "aRow", "componentType": "RowComponent", "children": [
	{ "id": "firstColumn", "componentType": "TextWidget", "props": { "text": "I am the first column" } },
	{ "id": "secondColumn", "componentType": "TextWidget", "props": { "text": "I am the second column" } }
] },
```

`RowComponent` should be the most common container component as it makes it easy to create responsive layouts that collapse to a single column at small widths.

## Theme Components ('Partials')

A theme can also contain components which are actually composed of other components (rather than writing the code for them directly).

These components can be created by a user, saved in the theme, and even shared between pages. Such components are called "partials" and are defined in a separate object within the theme called `partials`.

To include a partial within a page, there are two options:

1. Copy the partial directly into the page.
2. Refer to the partial within the page, which will share that partial between all pages.

To use a shared partial, create an object with the key `partial` and the value set to the key of the partial. That object will be replaced with the component from the partials object.

For example, here is a `header` partial used in a page:

```json
{
	"name": "MyTheme",
	"slug": "mytheme",
	"partials": {
		"header": { "id": "headerLayout", "componentType": "RowComponent", "children": [
			{ "id": "headerText", "componentType": "HeaderText" }
		] },
	},
	"templates": {
		"home": { "id": "siteLayout", "componentType": "ColumnComponent", "children": [
			{ "id": "pageLayout", "componentType": "ColumnComponent", "children": [
				{ "partial": "header" },
				{ "id": "contentLayout", "componentType": "RowComponent", "children": [
					{ "id": "myPosts", "componentType": "PostList" }
				] }
			] }
		] }
	}
}
```

## Templates

A whole page can be saved as a template within the theme. These are defined in an object within the theme called `templates` and are identical to the definition of pages (they may also contain partials).

Just as with theme components, there are two ways to use a template:

1. Copy the template into a new page.
2. Refer to the template in the page, which will use the theme's version of the page.

To use a page template in place of defining a page directly, just replace the page definition object with one that contains the key `template` where the value is set to the key of the template.

For example, here is a `blog` template used as a home page:

```json
{
	"name": "MyTheme",
	"slug": "mytheme",
	"partials": "...",
	"templates": {
		"blog": { "id": "siteLayout", "componentType": "ColumnComponent", "children": [
			{ "id": "pageLayout", "componentType": "ColumnComponent", "children": [
				{ "partial": "header" },
				{ "id": "contentLayout", "componentType": "RowComponent", "children": [
					{ "id": "myPosts", "componentType": "PostList" }
				] }
			] }
		] },
		"home": { "template": "blog" }
	}
}
```

## Components as props

When rendering a component like `PostList` we need to iterate over a data set and render a React component for each data element.

To define what React component is rendered, we need to pass that component as a prop to `PostList`. But we're not passing the created component, we're just passing the component *type*.

Then, when rendered, PostList must iterate over its data, *create an instance of the passed component type*, and render that component, passing it the data element as props.

This is normal React practice, but the difference here is that the component we're passing around is not a React component, it's part of a config. So in addition to the above, we need to transform the config object into a component before creating its instance.

Specifying these components in the theme is just like regular components except they lack an `id` property:

```json
{ "id": "myPosts", "componentType": "PostList", "props": {
	"post": { "componentType": "PostBody", "children": [
		{ "componentType": "PostTitle" },
		{ "componentType": "PostContent" },
		{ "componentType": "PostDate" },
		{ "componentType": "PostAuthor" },
	] }
} }
```

In order to build these component type objects into component instances, the `PostList` component must use `makeComponentWith()`. The function's first argument is a the component type object, and the second argument is the props that that component and all its children should have access to (see below):

```js
const postData = { "title": "My post" };
const postType = { "componentType": "PostTitle" };
const postList = posts.map( postData => makeComponentWith( postType, postData ) );
```

Components created in this way may have their own children (that are also JSON objects).

What we do in this case is that the components passed to `PostList` are aware that they will need certain parameters and they can request them at their leisure when instantiated. This is similar to how Redux silently passes its state tree to all components and they choose what data they want.

Any component created using `makeComponentWith( type, data )` or any descendant of that component will receive that `data` as a special prop which it can access using the Higher-Order-Component function (HOC) `getPropsFromParent()`:

```js
const PostTitle = ( { link, title } ) => {
	//...
};

function mapProps( props ) {
	return { link: props.link, title: props.title };
}
export default getPropsFromParent( mapProps )( PostTitle );
```

## Styles

Let's take that simple TextWidget component from earlier:

```json
{ "id": "helloWorld", "componentType": "TextWidget" }
```

That will generate markup something like this:

```html
<div class="TextWidget helloWorld"></div>
```

The theme can contain a key called `styles` which holds CSS rules for the theme as a single string.

For example, here is a theme config with styles.

```json
{
	"name": "MyTheme",
	"slug": "mytheme",
	"styles": ".contentLayout { font-family: 'Lucida Grande',Verdana,Arial,Sans-Serif; padding: 0 0 20px 45px; } .siteLayout { background-color: #e7e7e7;height: 100%; } .myPosts { width: 450px; }",
}
```

When making style selectors, it's important to use the CSS cascade as little as possible and try to follow a slightly modified version of [BEM](http://getbem.com/introduction/) syntax.

Here are all the types of styles in the order they are applied.

### Component Type styles

Some components have styles built-in. You cannot change these, but you can override them using the the theme.

**You cannot change these directly!**

### Theme Global styles

If you need to change something globally across a theme, use a global selector in the theme, like this:

```json
"a { text-decoration: none; }"
```

**Try to use global styles as little as possible!**

### Theme Component Type styles

Each component, as mentioned above, gets the component type as a className, so you can target that with a selector to affect all instances of that component.

```json
".PostBody { margin: 30px 0 0; }"
```

To target specific parts of a component, use the modified BEM syntax, where two underscores identifies a component element:

```json
".PostTitle_link { color: #333; font-family: 'Trebuchet MS','Lucida Grande',Verdana,Arial,Sans-Serif; font-size: 1.2em; font-weight: bold; text-decoration: none; }"
```

**Use this type of style the most!**

### Theme Component Instance styles

Each component instance within a theme layout also has a unique key which is the `id` specified in the component object. That key is added as a className also so you can target that specific instance of a component with a selector.

```json
".myWidget { background-color: #e7e7e7; }"
```

**Use this type of style to override the previous styles for a specific component!**

## Variant Styles

Each theme can have any number of style variations included. This is done by using variables in the style rules and then setting those variables in the theme key `variant-styles`. The value of `variant-styles` is an object that contains the keys and values for each variable. All variables must have a default value set in the `defaults` key:

```json
{
	"name": "MyTheme",
	"slug": "mytheme",
	"styles": "h1 { color: $header-color; }",
	"variant-styles": {
		"defaults": {
			"header-color": "black"
		},
		"Blue Headers": {
			"header-color": "skyblue"
		}
	}
}
```

Site editors should expose these variants to the user as theme options. Any number of variants can be active at once (although the last activated variable definition will override any values in previous variants). Active variant style keys are added to the `active-variant-styles` array:

```json
{
	"name": "MyTheme",
	"slug": "mytheme",
	"styles": "h1 { color: $header-color; }",
	"variant-styles": {
		"defaults": {
			"header-color": "black"
		},
		"Blue Headers": {
			"header-color": "skyblue"
		}
	},
	"active-variant-styles": [
		"Blue Headers"
	]
}
```

