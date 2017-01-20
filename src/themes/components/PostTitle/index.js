/* globals window */
const ComponentThemes = window.ComponentThemes;
const { React, registerComponent, getComponentByType } = ComponentThemes;

const PostTitle = ( { link, title, className, slug, postId, postType } ) => {
	const Link = getComponentByType('Link');
	return (
		<h1 className={ className }>
			<Link
				url={ link }
				slug={ slug }
				postId={ postId }
				postType={ postType }
				className="PostTitle_link">{ title || 'No title' }</Link>
		</h1>
	);
};

registerComponent( 'PostTitle', PostTitle, {
	title: 'Post Title',
	description: 'The title block for a post. Use inside a PostBody.',
	editableProps: {
		title: {
			type: 'string',
			label: 'The title string for the post'
		},
		link: {
			type: 'string',
			label: 'The url for the post title link'
		},
	},
} );
