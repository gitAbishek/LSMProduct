import { React, memo } from '@wordpress/element';
import RcTooltip from 'rc-tooltip';
import PropTypes from 'prop-types';
import 'rc-tooltip/assets/bootstrap.css';

const Tooltip = (props) => {
	return <RcTooltip {...props}>{props.children}</RcTooltip>;
};

Tooltip.propTypes = {
	children: PropTypes.object,
};

export default memo(Tooltip);
