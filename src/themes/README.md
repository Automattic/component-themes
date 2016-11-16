# Themes

A theme file is a JSON file that contains everything that makes up that site. It includes placeholder content which can be overridden by the user in the form of content and settings.

When a user customizes the layout of their site, they will actually be editing a copy of their theme file; in a very real sense, they will be making a new theme.

**That said, the JSON file is not intended to be edited directly!** It is intended to be read and modified by tools. The owner of a site or a entry-level theme developer might have a drag-and-drop interface for layout and settings. An advanced theme developer might use command-line tools to build the styles and components into the theme.

Advanced theme developers might also build their own components, which require their own React (and optionally PHP) files. Many themes should be able to be built without editing components, however, by just customizing the props for those components and composing shared sets of components ("partials") in the theme itself.

## Overview

Themes describe layouts of components for the pages of a site along with a set of styles for those components.

Typically those components are aligned in rows (via the `RowComponent` container) but at this point that is purely a convention to make customization easier.

While the theme is separate from its content and settings (which are created and stored separately), it can include default content and settings for its components. As with React, content and settings are provided to a component in the form of "props".

A page editor for a site will modify a copy of the site's theme, essentially turning it into a custom theme for that site.

Each theme is a JSON file that contains one object. The only requirement for the theme is that the object has the key `pages.home`, with the value of a component config. That is the component that will be rendered as the home page for the site. It must also have the keys `name` and `slug` which identify the theme.

Here is a very basic example theme that simply has a `TextWidget` displaying the default text of "hello world".

```json
{
	"name": "MyTheme",
	"slug": "mytheme",
	"pages": {
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
	"pages": {
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

There are two main categories of components: **container** components and **content** components. Container components generally have few or no visual elements but serve to lay out other components. Content components display some content. Both are designed to be customized by providing props (content and settings).

Each component in a theme is an object that has at least two properties: `id` and `componentType`.

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

## Partials (Shared Composed Components)

A theme can also contain components which are actually composed of other components (rather than writing the code for them directly).

These components can be created by a user, saved in the theme, and even shared between pages. Such components are called "partials" and are defined in a separate object within the theme called `partials`.

To include a partial within a page, create an object with the key `partial` and the value set to the key of the partial. That object will be replaced with the component from the partials object.

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
	"pages": {
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

Sometimes it will be desirable to customize a partial to be used in a particular location. In this case the partial object should be copied into the location previously occupied by the partial reference.

## Page Templates

A whole page can be saved as a shared component called a "layout". These are defined in an object within the theme called `templates` and are identical to the definition of pages (they may also contain partials), but they must be referenced in a page to see them.

To use a page layout in place of defining a page directly, just replace the page definition object with one that contains the key `layout` where the value is set to the key of the layout.

For example, here is a `blog` layout used as a home page:

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
		] }
	},
	"pages": {
		"home": { "template": "blog" }
	}
}
```

Just as with partials, if one wants to customize a layout for a particular page, the layout object should be copied into the place of the page and customized directly.

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
		{ "componentType": "PostDateAndAuthor" }
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

The theme can contain a key called "styles" which holds CSS rules for the theme.

The rules can be in three formats currently, although this may change in the
future.

1. A single string.
2. An object of style rule strings, keyed by the selector.
3. An object of style rules arrays, keyed by the selector. Each member of the array is a style string.

For example, here is a theme config with styles. All of these styles are valid:

```json
{
	"styles": {
		".contentLayout": [
			"font-family: 'Lucida Grande',Verdana,Arial,Sans-Serif;",
			"padding: 0 0 20px 45px;"
		],
		".siteLayout": [
			"background-color: #e7e7e7;height: 100%;"
		],
		".myPosts": "width: 450px;"
	},
	"pages": {}
}
```

That could also be:

```json
{
	"styles": ".contentLayout { font-family: 'Lucida Grande',Verdana,Arial,Sans-Serif; padding: 0 0 20px 45px; } .siteLayout { background-color: #e7e7e7;height: 100%; } .myPosts { width: 450px; }",
	"pages": {}
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
"a": "text-decoration: none;"
```

**Try to use global styles as little as possible!**

### Theme Component Type styles

Each component, as mentioned above, gets the component type as a className, so you can target that with a selector to affect all instances of that component.

```json
".BlogPost": "margin: 30px 0 0;"
```

To target specific parts of a component, use the modified BEM syntax, where two underscores identifies a component element:

```json
".BlogPost__title": [
	"color: #333;",
	"font-family: 'Trebuchet MS','Lucida Grande',Verdana,Arial,Sans-Serif;",
	"font-size: 1.2em;",
	"font-weight: bold;",
	"text-decoration: none;"
],
```

**Use this type of style the most!**

### Theme Component Instance styles

Each component instance within a theme layout also has a unique key which is the `id` specified in the component object. That key is added as a className also so you can target that specific instance of a component with a selector.

```json
".myWidget": "background-color: #e7e7e7;"
```

**Use this type of style to override the previous styles for a specific component!**
