import Icon from 'Components/common/Icon';
import React from 'react';
import { Sortable } from '../../../assets/icons';

interface Props {}

const DragHandle: React.FC<Props> = (props) => {
	return (
		<div
			className="mto-mr-3 mto-text-lg mto-text-gray-300 mto-cursor-move"
			{...props}>
			<Icon icon={<Sortable />} />
		</div>
	);
};

export default DragHandle;
