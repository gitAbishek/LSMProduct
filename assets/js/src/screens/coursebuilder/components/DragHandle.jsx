import { React } from '@wordpress/element';
import styled from 'styled-components';
import Sortable from '../../../assets/icons/Sortable';
import Icon from '../../../components/common/Icon';
import colors from '../../../config/colors';
import { BaseLine } from '../../../config/defaultStyle';
import fontSize from '../../../config/fontSize';

const DragHandle = (props) => {
	return <SortableIcon icon={<Sortable />} {...props} />;
};

const SortableIcon = styled(Icon)`
	margin-right: ${BaseLine * 2}px;
	color: ${colors.DISABLED};
	font-size: ${fontSize.EXTRA_LARGE};

	&:hover {
		cursor: move;
	}

	&:active {
		cursor: move;
	}
`;

export default DragHandle;
