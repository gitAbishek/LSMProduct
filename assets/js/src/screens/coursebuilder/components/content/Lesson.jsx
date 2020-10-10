import { React } from '@wordpress/element';
import PropTypes from 'prop-types';
import Dropdown from 'rc-dropdown';
import { Draggable } from 'react-beautiful-dnd';
import { BiAlignLeft, BiTrash } from 'react-icons/bi';
import Button from '../../../../components/common/Button';
import DropdownOverlay from '../../../../components/common/DropdownOverlay';
import FlexRow from '../../../../components/common/FlexRow';
import Icon from '../../../../components/common/Icon';
import OptionButton from '../../../../components/common/OptionButton';
import DragHandle from '../DragHandle';
import {
	ActionContainer,
	ContentContainer,
	ContentHeader,
	ContentIcon,
	ContentTitle,
} from '../styled';

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
