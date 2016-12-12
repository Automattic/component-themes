/* globals window */
const ComponentThemes = window.ComponentThemes;
const { React, registerComponent, apiDataWrapper } = ComponentThemes;

const SinglePost = ( { postData, children, className } ) => {
	const newChildren = React.Children.map( children, child => React.cloneElement( child, postData ) );
	return (
		<div className={ className }>
			{ newChildren }
		</div>
	);
};

const mapApiToProps = ( getApiEndpoint, state ) => {
	const { postId } = state.pageInfo || {};
	if ( ! postId ) {
		return {};
	}
	const post = getApiEndpoint( '/wp/v2/posts/' + postId );
	if ( ! post ) {
		return {};
	}
	const author = getApiEndpoint( '/wp/v2/users/' + post.author ) || {};
	return {
		postData: {
			title: post.title.rendered,
			content: post.content.rendered,
			date: post.date,
			link: post.link,
			author: author.name,
		}
	};
};

registerComponent( 'SinglePost', apiDataWrapper( mapApiToProps )( SinglePost ), {
	description: 'A full single post. Not to be used in a list; for that, use PostList instead.',
	editableProps: {
		postData: {
			type: 'array',
			label: 'The post data object.'
		},
		post: {
			type: 'object',
			label: 'The component to use for rendering each post. Use PostBody and PostTitle, PostContent, etc. Defaults to a standard blog post format.'
		}
	},
} );

