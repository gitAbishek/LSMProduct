import { memo, React } from '@wordpress/element';
import PropTypes from 'prop-types';
import RcTooltip from 'rc-tooltip';
import './styles/Tooltip.scss';

const Tooltip = (props) => {
	return <RcTooltip {...props}>{props.children}</RcTooltip>;
};

Tooltip.propTypes = {
	children: PropTypes.object,
};

export default memo(Tooltip);
