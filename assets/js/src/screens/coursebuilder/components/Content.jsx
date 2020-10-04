import { React } from '@wordpress/element';
import styled from 'styled-components';
import colors from '../../../config/colors';
import PropTypes from 'prop-types';

const Content = (props) => {
	const { title } = props;
	return <Container>{title}</Container>;
};

Content.propTypes = {
	title: PropTypes.string,
};

const Container = styled.div`
	background-color: ${colors.LIGHT_BLUEISH_GRAY};
`;
export default Content;
