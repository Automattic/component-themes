/**
 * Internal dependencies
 */
import { writeStylesToPage } from '~/src/lib/styles';

const Styles = ( { name, styles } ) => {
	writeStylesToPage( name, styles );
	return null;
};

export default Styles;

