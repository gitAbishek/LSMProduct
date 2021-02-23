import {
	ActionContainer,
	ContentContainer,
	ContentHeader,
	ContentIcon,
	ContentTitle,
} from './styled';
import { AlignLeft, Trash } from '../../../assets/icons';

import Button from 'Components/common/Button';
import DragHandle from '../components/DragHandle';
import Dropdown from 'rc-dropdown';
import DropdownOverlay from 'Components/common/DropdownOverlay';
import FlexRow from 'Components/common/FlexRow';
import Icon from 'Components/common/Icon';
import OptionButton from 'Components/common/OptionButton';
import React from 'react';
import { __ } from '@wordpress/i18n';

interface Props {
	id: number;
	title: string;
	index: number;
}

const Lesson: React.FC<Props> = (props) => {
	const { id, title, index } = props;
	return (
		<ContentContainer>
			<ContentHeader>
				<FlexRow>
					<DragHandle />
					<ContentIcon icon={<AlignLeft />} />
					<ContentTitle>{title}</ContentTitle>
				</FlexRow>
				<FlexRow>
					<ActionContainer>
						<Button>{__( 'Edit', 'masteriyo' )}</Button>
						<Dropdown
							trigger={'click'}
							placement={'bottomRight'}
							animation={'slide-up'}
							overlay={
								<DropdownOverlay>
									<ul>
										<li>
											<Icon icon={<Trash />} />
											{__( 'Delete', 'masteriyo' )}
										</li>
									</ul>
								</DropdownOverlay>
							}>
							<OptionButton />
						</Dropdown>
					</ActionContainer>
				</FlexRow>
			</ContentHeader>
		</ContentContainer>
	);
};

export default Lesson;
