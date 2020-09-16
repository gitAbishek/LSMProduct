import { React, memo } from '@wordpress/element';
import PropTypes from 'prop-types';
import styled from 'styled-components';
// import './style.css';

const Icon = (props) => {
	const { icon, size, color } = props;
	return (
		<StyledIcon size={size} color={color} {...props}>
			{icon}
		</StyledIcon>
	);
};

Icon.propTypes = {
	icon: PropTypes.object,
	size: PropTypes.string,
	color: PropTypes.string,
};

const StyledIcon = styled.i`
	display: flex;
	font-size: ${(props) => props.size || 'inherit'};
	color: ${(props) => props.color || 'inherit'};
`;

export default memo(Icon);
