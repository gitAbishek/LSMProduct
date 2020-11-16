import { React } from '@wordpress/element';
import styled from 'styled-components';
import Sortable from 'Icons/Sortable';
import Icon from 'Components/common/Icon';
import colors from 'Config/colors';
import { BaseLine } from 'Config/defaultStyle';
import fontSize from 'Config/fontSize';

const DragHandle = (props) => {
	return <SortableIcon icon={<Sortable />} {...props} />;
};

const SortableIcon = styled(Icon)`
	margin-right: ${BaseLine}px;
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
