/* globals window */
const ComponentThemes = window.ComponentThemes;
const { React, registerComponent } = ComponentThemes;

const PageLayout = ( { children, className } ) => {
	return (
		<div className={ className }>
			<div className="PageLayout__content">
				{ children }
			</div>
		</div>
	);
};

PageLayout.description = 'A wrapper for a whole page. Always use this as the parent component for all other page elements.';
PageLayout.hasChildren = true;

registerComponent( 'PageLayout', PageLayout );
