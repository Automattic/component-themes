const ComponentThemes = window.ComponentThemes;
const { React, registerComponent, route } = ComponentThemes;

// Link React component
function Link( props ) {
	const postInfo = {
		id: props.postId,
		type: props.postType,
		slug: props.slug
	};

	const clickHandler = ( event ) => {
		route.moveTo( props.url, postInfo );
		event.preventDefault();
	};

	return (
		<a
			href={ props.url }
			className={ props.className }
			onClick={ clickHandler }
		>{props.children}</a>
	);
}

registerComponent( 'Link', Link, {
	title: 'Link',
	description: 'An anchor element that supports client-side routing.',
	hasChildren: true,
	editableProps: {
		url: {
			type: 'string',
			label: 'URL to move to'
		},
		postId: {
			type: 'number',
			label: 'Post ID'
		},
		postType: {
			type: 'string',
			label: 'Post type'
		},
		slug: {
			type: 'string',
			label: 'Post slug'
		}
	}
} );
