import { React } from '@wordpress/element';
import PropTypes from 'prop-types';
import { Draggable } from 'react-beautiful-dnd';
import FlexRow from '../../../../components/common/FlexRow';
import DragHandle from '../DragHandle';
import {
	ActionContainer,
	ContentContainer,
	ContentHeader,
	ContentIcon,
	ContentTitle,
} from '../styled';
import { BiTimer, BiTrash } from 'react-icons/bi';
import Button from '../../../../components/common/Button';
import Dropdown from 'rc-dropdown';
import DropdownOverlay from '../../../../components/common/DropdownOverlay';
import Icon from '../../../../components/common/Icon';
import OptionButton from '../../../../components/common/OptionButton';

const Quiz = (props) => {
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
							<ContentIcon icon={<BiTimer />} />
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

Quiz.propTypes = {
	id: PropTypes.string,
	title: PropTypes.string,
	index: PropTypes.number,
};

export default Quiz;
