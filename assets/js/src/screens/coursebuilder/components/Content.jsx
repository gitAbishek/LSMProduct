import { React } from '@wordpress/element';
import styled from 'styled-components';
import colors from '../../../config/colors';

const Content = (props) => {
	const { title } = props;
	return <Container>{title}</Container>;
};

const Container = styled.div`
	background-color: ${colors.LIGHT_BLUEISH_GRAY};
`;
export default Content;
