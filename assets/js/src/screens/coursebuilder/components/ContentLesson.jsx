import { React } from '@wordpress/element';
import PropTypes from 'prop-types';
import FlexRow from '../../../components/common/FlexRow';
import DragHandle from './DragHandle';
import { Draggable } from 'react-beautiful-dnd';
import { ContentContainer, ContentTitle } from './styled';

const Content = (props) => {
	const { content, index } = props;
	return (
		<Draggable draggableId={content.id} index={index}>
			{(provided, snapshot) => (
				<ContentContainer
					ref={provided.innerRef}
					{...provided.draggableProps}
					isDragging={snapshot.isDragging}>
					<FlexRow>
						<DragHandle {...provided.dragHandleProps} />
						<ContentTitle>{content.title}</ContentTitle>
					</FlexRow>
				</ContentContainer>
			)}
		</Draggable>
	);
};

Content.propTypes = {
	content: PropTypes.object,
	index: PropTypes.any,
};

export default Content;
