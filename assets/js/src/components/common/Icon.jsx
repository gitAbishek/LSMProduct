import { React, memo } from '@wordpress/element';
import PropTypes from 'prop-types';
import styled from 'styled-components';

const Icon = (props) => {
	const { icon, size, color } = props;
	return (
		<StyledIcon size={size} color={color} {...props}>
			{icon}
		</StyledIcon>
	);
};

Icon.propTypes = {
	icon: PropTypes.object.isRequired,
	size: PropTypes.string,
	color: PropTypes.string,
};

const StyledIcon = styled.i`
	font-size: ${(props) => props.size || 'inherit'};
	color: ${(props) => props.color || 'inherit'};
`;

export default memo(Icon);
