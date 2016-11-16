/**
 * External dependencies
 */
import React, { Component, PropTypes } from 'react';

/**
 * Internal dependencies
 */
import Styles from '~/src/components/Styles';
import { buildStylesFromTheme } from '~/src/lib/styles';
import { buildComponentsFromTheme } from '~/src/lib/component-builder';

class ThemePreview extends Component {
	render() {
		const children = buildComponentsFromTheme(
			this.props.themeConfig,
			this.props.componentData
		);
		const styles = buildStylesFromTheme( this.props.themeConfig, this.props.componentData );
		return (
			<div className="PrometheusBuilder">
				<Styles styles={ styles } />
				{ children }
			</div>
		);
	}
}

ThemePreview.propTypes = {
	themeConfig: PropTypes.object.isRequired,
	componentData: PropTypes.object.isRequired,
};

export default ThemePreview;
