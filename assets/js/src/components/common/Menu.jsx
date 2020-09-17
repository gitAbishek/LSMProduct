import { React } from '@wordpress/element';
import Icon from './Icon';
import styled from 'styled-components';
import PropTypes from 'prop-types';
import propTypes from 'prop-types';
import fontSize from '../../config/fontSize';
import colors from '../../config/colors';
import { BaseLine } from '../../config/defaultStyle';

const Menu = styled.ul`
	list-style-type: none;
	padding: 0;
	margin: 0;
	display: flex;
	margin-left: ${BaseLine * 8}px;
`;

const MenuItem = (props) => {
	const { icon, children } = props;
	return (
		<StyledLi {...props}>
			{icon && <Icon icon={icon} />}
			<span>{children}</span>
		</StyledLi>
	);
};

const StyledLi = styled.li`
	position: relative;
	font-size: ${fontSize.MEDIUM};
	display: flex;
	font-weight: 500;
	margin-right: ${BaseLine * 5}px;
	transition: all 0.35s ease-in-out;
	color: ${(props) => (props.active ? colors.PRIMARY : colors.TEXT)};
	padding: ${BaseLine * 3}px 0;
	cursor: pointer;

	&:before {
		content: '';
		position: absolute;
		height: 2px;
		left: 0;
		right: 0;
		bottom: 0;
		background-color: ${colors.PRIMARY};
		visibility: ${(props) => (props.active ? 'visible' : 'hidden')};
		opacity: ${(props) => (props.active ? '1' : '0')};
		transition: all 0.35s ease-in-out;
	}

	&:last-child {
		margin-right: 0;
	}

	&:hover {
		color: ${colors.PRIMARY};

		&::before {
			visibility: visible;
			opacity: 1;
		}
	}

	i {
		margin-right: ${BaseLine * 0.7}px;
		font-size: ${fontSize.LARGE};
	}
`;

MenuItem.propTypes = {
	icon: PropTypes.object,
	children: propTypes.any,
};

export { MenuItem };
export default Menu;
