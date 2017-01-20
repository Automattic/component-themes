/* globals window */
const ComponentThemes = window.ComponentThemes;
const { React, registerComponent, apiDataWrapper } = ComponentThemes;

const PostList = ( { posts, children, className } ) => {
	const newChildren = ( posts || [] ).map( ( postInfo ) => {
		return React.Children.map(
			children,
			child => React.cloneElement( child, postInfo )
		);
	} );
	return (
		<div className={ className }>
			{ newChildren }
			{ ! posts || posts.length < 1 ? <p>No posts</p> : null }
		</div>
	);
};

const mapApiToProps = ( getApiEndpoint ) => {
	const postsData = getApiEndpoint( '/wp/v2/posts' ) || [];
	const posts = postsData.map( post => {
		const author = getApiEndpoint( '/wp/v2/users/' + post.author ) || {};
		return {
			postId: post.id,
			postType: post.type,
			slug: post.slug,
			title: post.title.rendered,
			content: post.content.rendered,
			author: author.name,
			date: post.date,
			link: post.link,
		};
	} );
	return { posts };
};

registerComponent( 'PostList', apiDataWrapper( mapApiToProps )( PostList ), {
	title: 'Post List',
	description: 'A list of posts.',
	editableProps: {
		posts: {
			type: 'array',
			label: 'The post data objects. Usually provided by content rather than props.'
		},
		post: {
			type: 'object',
			label: 'The component to use for rendering each post. Use PostBody and PostTitle, PostContent, etc. Defaults to a standard blog post format.'
		}
	},
} );
