import { React } from '@wordpress/element';
import styled from 'styled-components';
import RcTooltip from 'rc-tooltip';
import PropTypes from 'prop-types';
import 'rc-tooltip/assets/bootstrap_white.css';
const Tooltip = (props) => {
	return <StyledTooltip {...props}>{props.children}</StyledTooltip>;
};

Tooltip.propTypes = {
	children: PropTypes.object,
};

const StyledTooltip = styled(RcTooltip)`
	/* style */
`;
export default Tooltip;
