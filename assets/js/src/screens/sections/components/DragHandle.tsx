import { BaseLine } from 'Config/defaultStyle';
import Icon from 'Components/common/Icon';
import { React } from '@wordpress/element';
import { Sortable } from 'Icons';
import colors from 'Config/colors';
import fontSize from 'Config/fontSize';
import styled from 'styled-components';

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
