import {
	Box,
	ButtonGroup,
	Flex,
	Icon,
	IconButton,
	Stack,
	Text,
	Tooltip,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { Draggable } from 'react-beautiful-dnd';
import { BiAlignLeft, BiEdit, BiPlay, BiTimer, BiTrash } from 'react-icons/bi';
import { useHistory } from 'react-router';
import { Sortable } from '../../../assets/icons';
import routes from '../../../constants/routes';

interface Props {
	id: number;
	name: string;
	type: 'lesson' | 'quiz';
	index: any;
	courseId: number;
	onContentDeletePress: any;
	hasVideo: boolean;
}

const Content: React.FC<Props> = (props) => {
	const { id, name, type, index, courseId, onContentDeletePress, hasVideo } =
		props;

	const getContentIcon = (itemType: 'quiz' | 'lesson', video: boolean) => {
		if (itemType === 'quiz') {
			return BiTimer;
		}

		if (itemType === 'lesson') {
			if (video) {
				return BiPlay;
			} else {
				return BiAlignLeft;
			}
		}
	};

	const history = useHistory();

	const onEditPress = () => {
		if (type === 'lesson') {
			history.push(
				routes.lesson.edit
					.replace(':lessonId', id.toString())
					.replace(':courseId', courseId.toString())
			);
		}
		if (type === 'quiz') {
			history.push(
				routes.quiz.edit
					.replace(':quizId', id.toString())
					.replace(':courseId', courseId.toString())
			);
		}
	};

	return (
		<Draggable draggableId={id.toString()} index={index}>
			{(draggableProvided) => (
				<Flex
					justify="space-between"
					rounded="sm"
					bg="white"
					border="1px"
					borderColor="gray.100"
					mb="3"
					_last={{ mb: 0 }}
					ref={draggableProvided.innerRef}
					{...draggableProvided.draggableProps}>
					<Stack direction="row" spacing="3" align="center">
						<Box
							as="span"
							p="2"
							{...draggableProvided.dragHandleProps}
							borderRight="1px"
							borderColor="gray.200">
							<Icon as={Sortable} fontSize="lg" color="gray.500" />
						</Box>
						<Icon
							color="blue.400"
							as={getContentIcon(type, hasVideo)}
							fontSize="xl"
						/>
						<Text fontSize="sm" onClick={onEditPress}>
							{name}
						</Text>
					</Stack>
					<ButtonGroup color="gray.400" size="xs" p="2">
						<Tooltip label={__('Edit', 'masteriyo')}>
							<IconButton
								_hover={{ color: 'gray.700' }}
								onClick={onEditPress}
								variant="unstyled"
								icon={<Icon fontSize="xl" as={BiEdit} />}
								aria-label={__('Edit', 'masteriyo')}
							/>
						</Tooltip>

						<Tooltip label={__('Delete', 'masteriyo')}>
							<IconButton
								_hover={{ color: 'red.500' }}
								onClick={() => onContentDeletePress(id, type)}
								variant="unstyled"
								icon={<Icon fontSize="xl" as={BiTrash} />}
								aria-label={__('Delete', 'masteriyo')}
							/>
						</Tooltip>
					</ButtonGroup>
				</Flex>
			)}
		</Draggable>
	);
};

export default Content;
