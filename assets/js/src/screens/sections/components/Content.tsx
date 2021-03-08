import { AlignLeft, Timer, Trash } from '../../../assets/icons';

import Button from 'Components/common/Button';
import DragHandle from './DragHandle';
import Dropdown from 'rc-dropdown';
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
		<div className="mto-bg-white mto-shadow-md mto-border mto-border-solid mto-border-gray-300 mto-px-4 mto-py-3">
			<header className="mto-flex mto-justify-between">
				<div className="mto-flex">
					<DragHandle />
					<Icon
						className="mto-text-lg mto-mr-4"
						icon={type === 'lesson' ? <AlignLeft /> : <Timer />}
					/>
					<h5>{name}</h5>
				</div>
				<div className="mto-flex">
					<div className="mto-flex">
						<Button className="mto-ml-4">{__('Edit', 'masteriyo')}</Button>
						<Dropdown
							trigger={'click'}
							placement={'bottomRight'}
							animation={'slide-up'}
							overlay={
								<DropdownOverlay>
									<ul>
										<li>
											<Icon icon={<Trash />} />
											{__('Delete', 'masteriyo')}
										</li>
									</ul>
								</DropdownOverlay>
							}>
							<OptionButton />
						</Dropdown>
					</div>
				</div>
			</header>
		</div>
	);
};

export default Content;
