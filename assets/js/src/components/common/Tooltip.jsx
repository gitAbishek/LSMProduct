import { React, memo } from '@wordpress/element';
import styled from 'styled-components';
import RcTooltip from 'rc-tooltip';
import PropTypes from 'prop-types';
import './styles/Tooltip.scss';
const Tooltip = (props) => {
	return <StyledTooltip {...props}>{props.children}</StyledTooltip>;
};

Tooltip.propTypes = {
	children: PropTypes.object,
};

const StyledTooltip = styled(RcTooltip)`
	background-color: red;
`;
export default memo(Tooltip);
