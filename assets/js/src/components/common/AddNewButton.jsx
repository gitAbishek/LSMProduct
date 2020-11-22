import { React } from '@wordpress/element';
import { BiPlus } from 'react-icons/bi';
import styled from 'styled-components';
import Icon from 'Components/common/Icon';
import { BaseLine } from 'Config/defaultStyle';
import fontSize from 'Config/fontSize';
import colors from 'Config/colors';
import { lighten } from 'polished';
import PropTypes from 'prop-types';

const AddNewButton = (props) => {
	return (
		<StyledButton {...props}>
			<Icon icon={<BiPlus />} />
			{props.children}
		</StyledButton>
	);
};

AddNewButton.propTypes = {
	children: PropTypes.any,
};

const StyledButton = styled.button`
	margin-top: ${BaseLine * 3}px;
	border: none;
	background: transparent;
	display: flex;
	align-items: center;
	cursor: pointer;
	transition: all 0.35s ease-in-out;

	i {
		width: ${BaseLine * 4}px;
		transition: all 0.35s ease-in-out;
		height: ${BaseLine * 4}px;
		justify-content: center;
		align-items: center;
		font-size: ${fontSize.HUGE};
		background-color: ${colors.PRIMARY};
		border-radius: 100%;
		color: ${colors.WHITE};
		margin-right: ${BaseLine}px;
	}

	&:hover {
		color: ${colors.PRIMARY};
		i {
			background-color: ${lighten(0.06, colors.PRIMARY)};
		}
	}
`;

export default AddNewButton;
