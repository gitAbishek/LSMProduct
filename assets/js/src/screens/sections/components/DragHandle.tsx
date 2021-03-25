import Icon from 'Components/common/Icon';
import React from 'react';
import { Sortable } from '../../../assets/icons';
import tw from 'tailwind-styled-components';

interface Props {}

const DragHandle: React.FC<Props> = (props) => {
	return (
		<DragHandleContainer {...props}>
			<Icon icon={<Sortable />} />
		</DragHandleContainer>
	);
};

const DragHandleContainer = tw.div`
	mto-mr-3
	mto-cursor-move
	mto-text-2xl
	mto-text-gray-700
`;
export default DragHandle;
