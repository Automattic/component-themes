const ComponentThemes = window.ComponentThemes;
const { React, registerComponent, route } = ComponentThemes;

// Link React component
function Link( props ) {
	const postInfo = {
		id: props.postId,
		type: props.postType,
		slug: props.slug
	};

	return (
		<a
			href={ props.url }
			className={ props.className }
			onClick={ (e) => { route.moveTo( props.url, postInfo ); e.preventDefault(); } }
		>{props.children}</a>
	);
}

registerComponent( 'Link', Link, {
	title: 'Link',
	description: 'An anchor element that routes an application on client-side.',
} );
