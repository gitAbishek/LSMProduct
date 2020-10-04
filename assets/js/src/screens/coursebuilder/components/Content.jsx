import { React } from '@wordpress/element';
import styled from 'styled-components';
import colors from '../../../config/colors';
import PropTypes from 'prop-types';
import FlexRow from '../../../components/common/FlexRow';
import DragHandle from './DragHandle';
import defaultStyle, { BaseLine } from '../../../config/defaultStyle';
import fontSize from '../../../config/fontSize';
import { Draggable } from 'react-beautiful-dnd';

const Content = (props) => {
	const { content, index } = props;
	return (
		<Draggable draggableId={content.id} index={index}>
			{(provided, snapshot) => (
				<Container
					ref={provided.innerRef}
					{...provided.draggableProps}
					isDragging={snapshot.isDragging}>
					<FlexRow>
						<DragHandle {...provided.dragHandleProps} />
						<ContentTitle>{content.title}</ContentTitle>
					</FlexRow>
				</Container>
			)}
		</Draggable>
	);
};

Content.propTypes = {
	content: PropTypes.object,
	index: PropTypes.any,
};

const Container = styled.div`
	background-color: ${colors.WHITE};
	box-shadow: ${(props) =>
		props.isDragging ? '0 0 15px rgba(0, 0, 0, 0.1)' : 'none'};
	border: 1px solid ${colors.BORDER};
	padding: ${BaseLine * 2}px;
	border-radius: ${defaultStyle.borderRadius};
	margin-bottom: ${BaseLine * 2}px;
`;

const ContentTitle = styled.h5`
	margin: 0;
	font-weight: 400;
	font-size: ${fontSize.LARGE};
`;
export default Content;
