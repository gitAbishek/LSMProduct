import { BaseLine } from 'Config/defaultStyle';
import Icon from 'Components/common/Icon';
import { Plus } from 'Icons/index';
import PropTypes from 'prop-types';
import { React } from '@wordpress/element';
import colors from 'Config/colors';
import fontSize from 'Config/fontSize';
import { lighten } from 'polished';
import styled from 'styled-components';

const AddNewButton = (props) => {
	return (
		<StyledButton {...props}>
			<Icon icon={<Plus />} />
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

	a {
		text-decoration: none;
		color: ${colors.HEADING};
	}

	&:hover {
		color: ${colors.PRIMARY};
		i {
			background-color: ${lighten(0.06, colors.PRIMARY)};
		}
	}
`;

export default AddNewButton;
