import { React } from '@wordpress/element';
import PropTypes from 'prop-types';
import { Draggable } from 'react-beautiful-dnd';
import FlexRow from '../../../../components/common/FlexRow';
import DragHandle from '../DragHandle';
import { ContentContainer, ContentTitle } from '../styled';
const Quiz = (props) => {
	const { id, title, index } = props;
	return (
		<Draggable draggableId={id} index={index}>
			{(provided, snapshot) => (
				<ContentContainer
					ref={provided.innerRef}
					{...provided.draggableProps}
					isDragging={snapshot.isDragging}>
					<FlexRow>
						<DragHandle {...provided.dragHandleProps} />
						<ContentTitle>{title}</ContentTitle>
					</FlexRow>
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
