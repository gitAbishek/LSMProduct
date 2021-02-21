import { BaseLine } from 'Config/defaultStyle';
import Icon from 'Components/common/Icon';
import React from 'react';
import { Sortable } from '../../../assets/icons';
import colors from 'Config/colors';
import fontSize from 'Config/fontSize';
import styled from 'styled-components';

interface Props {}

const DragHandle: React.FC<Props> = (props) => {
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
