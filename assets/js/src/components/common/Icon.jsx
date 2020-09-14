import { React } from '@wordpress/element';
import PropTypes from 'prop-types';
import styled from 'styled-components';

const Icon = (props) => {
	const { icon, size, color } = props;
	return (
		<StyledIcon size={size} color={color}>
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
	font-size: 14px;
	color: red;
`;

export default Icon;
