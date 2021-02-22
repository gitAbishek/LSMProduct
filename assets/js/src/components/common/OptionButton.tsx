import React, { ReactPropTypes } from 'react';

import { DotsVertical } from '../../assets/icons';
import Icon from 'Components/common/Icon';
import colors from 'Config/colors';
import defaultStyle from 'Config/defaultStyle';
import fontSize from 'Config/fontSize';
import styled from 'styled-components';

interface Props extends React.ComponentProps<any> {}

const OptionButton: React.FC<Props> = (props) => {
	return (
		<StyledButton {...props}>
			<Icon icon={<DotsVertical />} />
		</StyledButton>
	);
};

const StyledButton = styled.button`
	cursor: pointer;
	transition: all 0.35s ease-in-out;
	border: 1px solid ${colors.BORDER};
	padding: 9px 12px;
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
