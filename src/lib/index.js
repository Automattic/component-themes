import { getComponentByType, registerComponent, registerPartial } from './components';
import { apiDataWrapper, apiDataProvider } from './api';
import date from './date';
import styled from 'styled-components';

export default {
	/* component/index.js */
	getComponentByType,
	registerComponent,
	registerPartial,

	/* api.js */
	apiDataWrapper,
	apiDataProvider,

	/* date.js */
	date,

	/* external libraries */
	styled,
};
