import {
	ActionContainer,
	ContentContainer,
	ContentHeader,
	ContentIcon,
	ContentTitle,
} from '../styled';
import { BiAlignLeft, BiTrash } from 'react-icons/bi';

import Button from 'Components/common/Button';
import DragHandle from '../components/DragHandle';
import { Draggable } from 'react-beautiful-dnd';
import Dropdown from 'rc-dropdown';
import DropdownOverlay from 'Components/common/DropdownOverlay';
import FlexRow from 'Components/common/FlexRow';
import Icon from 'Components/common/Icon';
import OptionButton from 'Components/common/OptionButton';
import PropTypes from 'prop-types';
import { React } from '@wordpress/element';

const Lesson = (props) => {
	const { id, title, index } = props;
	return (
		<Draggable draggableId={id} index={index}>
			{(provided, snapshot) => (
				<ContentContainer
					ref={provided.innerRef}
					{...provided.draggableProps}
					isDragging={snapshot.isDragging}>
					<ContentHeader>
						<FlexRow>
							<DragHandle {...provided.dragHandleProps} />
							<ContentIcon icon={<BiAlignLeft />} />
							<ContentTitle>{title}</ContentTitle>
						</FlexRow>
						<FlexRow>
							<ActionContainer>
								<Button>Edit</Button>
								<Dropdown
									trigger={'click'}
									placement={'bottomRight'}
									animation={'slide-up'}
									overlay={
										<DropdownOverlay>
											<ul>
												<li>
													<Icon icon={<BiTrash />} />
													Delete
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
			)}
		</Draggable>
	);
};

Lesson.propTypes = {
	id: PropTypes.string,
	title: PropTypes.string,
	index: PropTypes.number,
};

export default Lesson;
