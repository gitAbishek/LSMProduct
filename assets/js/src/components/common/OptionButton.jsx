import { React } from '@wordpress/element';
import styled from 'styled-components';
import colors from '../../config/colors';
import defaultStyle from '../../config/defaultStyle';
import fontSize from '../../config/fontSize';
import Icon from './Icon';
import { BiDotsVerticalRounded } from 'react-icons/bi';

const OptionButton = (props) => {
	return (
		<StyledButton {...props}>
			<Icon icon={<BiDotsVerticalRounded />} />
		</StyledButton>
	);
};

const StyledButton = styled.button`
	cursor: pointer;
	transition: all 0.35s ease-in-out;
	border: 1px solid ${colors.BORDER};
	padding: 10px 12px;
	font-weight: 500;
	font-size: ${fontSize.EXTRA_LARGE};
	border-radius: ${defaultStyle.borderRadius};
	background-color: ${colors.WHITE};
	color: ${colors.TEXT};
	line-height: 1;
	display: flex;
	align-items: center;
	outline: none;

	&:hover {
		color: ${colors.PRIMARY};
		border-color: ${colors.PRIMARY};
	}
`;

export default OptionButton;
