import React from 'react';

const PostBody = ( { children, className } ) => {
	return (
		<div className={ className }>
			{ children }
		</div>
	);
};

PostBody.description = 'A wrapper for a post. Use PostContent, PostTitle, PostDate, and PostAuthor for the inside.';
PostBody.hasChildren = true;

export default PostBody;
