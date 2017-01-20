/* globals window */
const ComponentThemes = window.ComponentThemes;
const { React, registerComponent, omit } = ComponentThemes;

const PostBody = ( props ) => {
	const childProps = omit( props, [ 'className', 'children' ] );
	const newChildren = React.Children.map( props.children, child => React.cloneElement( child, childProps ) );
	return (
		<div className={ props.className }>
			{ newChildren }
		</div>
	);
};

registerComponent( 'PostBody', PostBody, {
	title: 'Post',
	description: 'A wrapper for a post. Use PostContent, PostTitle, PostDate, and PostAuthor for the inside.',
	hasChildren: true,
} );
