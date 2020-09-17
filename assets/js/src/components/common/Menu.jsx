import { React } from '@wordpress/element';
import Icon from './Icon';
import styled from 'styled-components';
import PropTypes from 'prop-types';
import propTypes from 'prop-types';

const Menu = styled.ul`
	list-style-type: none;
	padding: 0;
	margin: 0;
	display: flex;
`;

const MenuItem = (props) => {
	const { icon, children } = props;
	return (
		<li>
			{icon && <Icon icon={icon} />}
			<span>{children}</span>
		</li>
	);
};

MenuItem.propTypes = {
	icon: PropTypes.object,
	children: propTypes.any,
};

export default Menu;
