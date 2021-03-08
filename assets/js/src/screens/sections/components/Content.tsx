import { AlignLeft, Timer, Trash } from '../../../assets/icons';

import Button from 'Components/common/Button';
import DragHandle from './DragHandle';
import Dropdown from 'Components/common/Dropdown';
import DropdownOverlay from 'Components/common/DropdownOverlay';
import Icon from 'Components/common/Icon';
import OptionButton from 'Components/common/OptionButton';
import React from 'react';
import { __ } from '@wordpress/i18n';

interface Props {
	id: number;
	name: string;
	type: string;
}

const Content: React.FC<Props> = (props) => {
	const { id, name, type } = props;
	return (
		<div className="mto-bg-white mto-border mto-border-solid mto-border-gray-200 mto-px-4 mto-py-3 mto-flex mto-justify-between mto-items-center mto-mb-2">
			<div className="mto-flex mto-items-center">
				<DragHandle />
				<Icon
					className="mto-text-lg mto-mr-4"
					icon={type === 'lesson' ? <AlignLeft /> : <Timer />}
				/>
				<h5>{name}</h5>
			</div>
			<div className="mto-flex">
				<div className="mto-flex">
					<Button className="mto-mr-2" size="small">
						{__('Edit', 'masteriyo')}
					</Button>
					<Dropdown
						align={'end'}
						content={
							<DropdownOverlay>
								<ul className="mto-w-36 mto-text-gray-700 mto-m-4">
									<li className="mto-flex mto-items-center mto-text-sm mto-mb-4 hover:mto-text-primary mto-cursor-pointer">
										<Icon className="mto-mr-1" icon={<Trash />} />
										{__('Delete', 'masteriyo')}
									</li>
								</ul>
							</DropdownOverlay>
						}>
						<OptionButton />
					</Dropdown>
				</div>
			</div>
		</div>
	);
};

export default Content;
