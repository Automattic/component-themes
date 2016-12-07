/* globals window */
const ComponentThemes = window.ComponentThemes;
const { React, registerComponent, apiDataWrapper, makeComponentWith } = ComponentThemes;

const PostList = ( { posts, post, className } ) => {
	const defaultPostConfig = { componentType: 'PostBody', children: [
		{ componentType: 'PostTitle' },
		{ partial: 'PostDateAndAuthor' },
		{ componentType: 'PostContent' }
	] };
	return (
		<div className={ className }>
			{ ( posts || [] ).map( postData => makeComponentWith( post || defaultPostConfig, postData ) ) }
			{ ! posts || posts.length < 1 ? <p>No posts</p> : null }
		</div>
	);
};

PostList.description = 'A list of posts.';
PostList.editableProps = {
	posts: {
		type: 'array',
		label: 'The post data objects. Usually provided by content rather than props.'
	},
	post: {
		type: 'object',
		label: 'The component to use for rendering each post. Use PostBody and PostTitle, PostContent, etc. Defaults to a standard blog post format.'
	}
};

const mapApiToProps = ( api ) => {
	const postsData = api[ '/wp/v2/posts' ] || [];
	const posts = postsData.map( post => ( {
		title: post.title.rendered,
		content: post.content.rendered,
		date: post.date,
	} ) );
	return { posts };
};

registerComponent( 'PostList', apiDataWrapper( [ '/wp/v2/posts' ], mapApiToProps )( PostList ) );
