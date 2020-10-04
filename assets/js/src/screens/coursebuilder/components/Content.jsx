import { React } from '@wordpress/element';
import styled from 'styled-components';
import colors from '../../../config/colors';
import PropTypes from 'prop-types';
import FlexRow from '../../../components/common/FlexRow';
import DragHandle from './DragHandle';
import defaultStyle, { BaseLine } from '../../../config/defaultStyle';
import fontSize from '../../../config/fontSize';

const Content = (props) => {
	const { title } = props;
	return (
		<Container>
			<FlexRow>
				<DragHandle />
				<ContentTitle> {title}</ContentTitle>
			</FlexRow>
		</Container>
	);
};

Content.propTypes = {
	title: PropTypes.string,
};

const Container = styled.div`
	background-color: ${colors.WHITE};
	border: 1px solid ${colors.BORDER};
	padding: ${BaseLine * 2}px;
	border-radius: ${defaultStyle.borderRadius};
	margin-bottom: ${BaseLine * 2}px;
`;

const ContentTitle = styled.h5`
	margin: 0;
	font-weight: 400;
	font-size: ${fontSize.LARGE};
`;
export default Content;
