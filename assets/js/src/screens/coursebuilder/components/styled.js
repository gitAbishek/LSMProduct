import styled from 'styled-components';
import FlexRow from '../../../components/common/FlexRow';
import Icon from '../../../components/common/Icon';
import colors from '../../../config/colors';
import defaultStyle, { BaseLine } from '../../../config/defaultStyle';
import fontSize from '../../../config/fontSize';

export const ContentContainer = styled.div`
	background-color: ${colors.WHITE};
	box-shadow: ${(props) =>
		props.isDragging ? '0 0 15px rgba(0, 0, 0, 0.1)' : 'none'};
	border: 1px solid ${colors.BORDER};
	padding: ${BaseLine * 1.3}px ${BaseLine * 2}px;
	border-radius: ${defaultStyle.borderRadius};
	margin-bottom: ${BaseLine * 2}px;
`;

export const ContentHeader = styled.header`
	display: flex;
	justify-content: space-between;
`;

export const ContentTitle = styled.h5`
	margin: 0;
	font-weight: 400;
	font-size: ${fontSize.LARGE};
	color: ${colors.TEXT};
`;

export const ContentIcon = styled(Icon)`
	font-size: ${fontSize.HUGE};
	margin-right: ${BaseLine}px;
	color: ${colors.TEXT};
`;

export const ActionContainer = styled(FlexRow)`
	button {
		margin-left: ${BaseLine * 2}px;
	}
`;
