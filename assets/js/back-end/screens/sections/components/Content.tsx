import {
	AlertDialog,
	AlertDialogBody,
	AlertDialogContent,
	AlertDialogFooter,
	AlertDialogHeader,
	AlertDialogOverlay,
	Button,
	ButtonGroup,
	Flex,
	Icon,
	IconButton,
	Stack,
	Text,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useRef, useState } from 'react';
import { Draggable } from 'react-beautiful-dnd';
import { BiAlignLeft, BiCopy, BiEdit, BiTimer, BiTrash } from 'react-icons/bi';
import { useMutation, useQueryClient } from 'react-query';
import { useHistory } from 'react-router';
import { Sortable } from '../../../assets/icons';
import routes from '../../../constants/routes';
import urls from '../../../constants/urls';
import API from '../../../utils/api';

interface Props {
	id: number;
	name: string;
	type: 'lesson' | 'quiz' | string;
	index: any;
	courseId: number;
}

const Content: React.FC<Props> = (props) => {
	const { id, name, type, index, courseId } = props;
	const [isDeleteModalOpen, setDeleteModalOpen] = useState(false);
	const queryClient = useQueryClient();
	const history = useHistory();
	const cancelRef = useRef<any>();
	const lessonAPI = new API(urls.lessons);
	const quizAPI = new API(urls.quizes);

	const deleteLesson = useMutation((id: number) => lessonAPI.delete(id), {
		onSuccess: () => {
			queryClient.invalidateQueries(`builder${courseId}`);
			setDeleteModalOpen(false);
		},
	});

	const deleteQuiz = useMutation((id: number) => quizAPI.delete(id), {
		onSuccess: () => {
			queryClient.invalidateQueries(`builder${courseId}`);
			setDeleteModalOpen(false);
		},
	});

	const onDeleteModalClose = () => {
		setDeleteModalOpen(false);
	};

	const onDeletePress = () => {
		setDeleteModalOpen(true);
	};

	const onDeleteConfirm = () => {
		if (type === 'lesson') {
			deleteLesson.mutate(id);
		} else if (type === 'quiz') {
			deleteQuiz.mutate(id);
		}
	};

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
					p="2"
					mb="3"
					_last={{ mb: 0 }}
					ref={draggableProvided.innerRef}
					{...draggableProvided.draggableProps}>
					<Stack direction="row" spacing="3" align="center">
						<span {...draggableProvided.dragHandleProps}>
							<Icon as={Sortable} fontSize="lg" color="gray.500" />
						</span>
						<Icon
							color="blue.400"
							as={type === 'lesson' ? BiAlignLeft : BiTimer}
							fontSize="xl"
						/>
						<Text fontSize="sm" onClick={onEditPress}>
							{name}
						</Text>
					</Stack>
					<ButtonGroup color="gray.300" size="xs">
						<IconButton
							onClick={onEditPress}
							variant="unstyled"
							icon={<Icon fontSize="xl" as={BiEdit} />}
							aria-label={__('Edit')}
						/>
						<IconButton
							variant="unstyled"
							icon={<Icon fontSize="xl" as={BiCopy} />}
							aria-label={__('Edit')}
						/>
						<IconButton
							onClick={onDeletePress}
							variant="unstyled"
							icon={<Icon fontSize="xl" as={BiTrash} />}
							aria-label={__('Edit')}
						/>
					</ButtonGroup>

					<AlertDialog
						isOpen={isDeleteModalOpen}
						onClose={onDeleteModalClose}
						isCentered
						leastDestructiveRef={cancelRef}>
						<AlertDialogOverlay>
							<AlertDialogContent>
								<AlertDialogHeader>
									{__('Delete Section')} {name}
								</AlertDialogHeader>
								<AlertDialogBody>
									{__(
										"Are you sure? You can't restore this section",
										'masteriyo'
									)}
								</AlertDialogBody>
								<AlertDialogFooter>
									<ButtonGroup>
										<Button
											ref={cancelRef}
											onClick={onDeleteModalClose}
											variant="outline">
											{__('Cancel', 'masteriyo')}
										</Button>
										<Button
											colorScheme="red"
											onClick={onDeleteConfirm}
											isLoading={
												deleteQuiz.isLoading || deleteLesson.isLoading
											}>
											{__('Delete', 'masteriyo')}
										</Button>
									</ButtonGroup>
								</AlertDialogFooter>
							</AlertDialogContent>
						</AlertDialogOverlay>
					</AlertDialog>
				</Flex>
			)}
		</Draggable>
	);
};

export default Content;
