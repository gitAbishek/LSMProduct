import styled from 'styled-components';
import { BaseLine } from '../../config/defaultStyle';

const Container = styled.div`
	display: ${(props) => (props.flex ? 'flex' : 'block')};
	padding-right: ${BaseLine * 2}px;
	padding-left: ${BaseLine * 2}px;
	margin: 0 auto;

	@media (min-width: 576px) {
		max-width: 540px;
	}

	@media (min-width: 768px) {
		max-width: 720px;
	}

	@media (min-width: 992px) {
		max-width: 960px;
	}

	@media (min-width: 1200px) {
		max-width: 1140px;
	}

	@media (min-width: 1400px) {
		max-width: 1320px;
	}
`;

export const ContainerFluid = styled.div`
	display: ${(props) => (props.flex ? 'flex' : 'block')};
	padding-right: ${BaseLine * 2}px;
	padding-left: ${BaseLine * 2}px;
`;

export default Container;
