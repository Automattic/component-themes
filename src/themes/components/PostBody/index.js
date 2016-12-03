/* globals window */
const ComponentThemes = window.ComponentThemes;
const { React, registerComponent } = ComponentThemes;

const PostBody = ( { children, className } ) => {
	return (
		<div className={ className }>
			{ children }
		</div>
	);
};

PostBody.description = 'A wrapper for a post. Use PostContent, PostTitle, PostDate, and PostAuthor for the inside.';
PostBody.hasChildren = true;

registerComponent( 'PostBody', PostBody );
