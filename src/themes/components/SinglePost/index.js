/* globals window */
const ComponentThemes = window.ComponentThemes;
const { React, registerComponent, apiDataWrapper, makeComponentWith } = ComponentThemes;

const SinglePost = ( { postData, post, className } ) => {
	const defaultPostConfig = { componentType: 'PostBody', children: [
		{ componentType: 'PostTitle' },
		{ partial: 'PostDateAndAuthor' },
		{ componentType: 'PostContent' }
	] };
	return (
		<div className={ className }>
			{ makeComponentWith( post || defaultPostConfig, postData ) }
		</div>
	);
};

SinglePost.description = 'A full single post. Not to be used in a list; for that, use PostList instead.';
SinglePost.editableProps = {
	postData: {
		type: 'array',
		label: 'The post data object.'
	},
	post: {
		type: 'object',
		label: 'The component to use for rendering each post. Use PostBody and PostTitle, PostContent, etc. Defaults to a standard blog post format.'
	}
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

registerComponent( 'SinglePost', apiDataWrapper( mapApiToProps )( SinglePost ) );

