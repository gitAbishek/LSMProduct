import {
	AlertDialog,
	AlertDialogBody,
	AlertDialogContent,
	AlertDialogFooter,
	AlertDialogHeader,
	AlertDialogOverlay,
	Box,
	Button,
	ButtonGroup,
	Flex,
	Icon,
	IconButton,
	Link,
	Stack,
	Text,
	Tooltip,
	useDisclosure,
	useToast,
} from '@chakra-ui/react';
import { sprintf, __ } from '@wordpress/i18n';
import React, { useRef } from 'react';
import { Draggable } from 'react-beautiful-dnd';
import {
	BiAlignLeft,
	BiEdit,
	BiPlay,
	BiShow,
	BiTimer,
	BiTrash,
} from 'react-icons/bi';
import { useMutation, useQueryClient } from 'react-query';
import { useHistory } from 'react-router';
import { Sortable } from '../../../assets/icons';
import routes from '../../../constants/routes';
import urls from '../../../constants/urls';
import API from '../../../utils/api';

interface Props {
	id: number;
	name: string;
	type: 'lesson' | 'quiz';
	index: any;
	courseId: number;
	hasVideo: boolean;
	previewPermalink: string;
}

const Content: React.FC<Props> = (props) => {
	const { id, name, type, index, courseId, hasVideo, previewPermalink } = props;

	const history = useHistory();
	const toast = useToast();
	const queryClient = useQueryClient();
	const { onClose, onOpen, isOpen } = useDisclosure();

	const cancelRef = useRef<any>();

	const lessonAPI = new API(urls.lessons);
	const quizAPI = new API(urls.quizes);

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

	const deleteLesson = useMutation((id: number) => lessonAPI.delete(id), {
		onSuccess: () => {
			onClose();
			toast({
				title: __('Lesson Deleted.', 'masteriyo'),
				isClosable: true,
				status: 'error',
			});
			queryClient.invalidateQueries(`builder${courseId}`);
		},
	});

	const deleteQuiz = useMutation((id: number) => quizAPI.delete(id), {
		onSuccess: () => {
			onClose();
			toast({
				title: __('Quiz Deleted.', 'masteriyo'),
				isClosable: true,
				status: 'error',
			});
			queryClient.invalidateQueries(`builder${courseId}`);
		},
	});

	const onDeletePress = () => {
		onOpen();
	};

	const onDeleteConfirm = () => {
		if (type === 'lesson') {
			deleteLesson.mutate(id);
		} else if (type === 'quiz') {
			deleteQuiz.mutate(id);
		}
	};

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

	return (
		<>
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
							<Icon as={getContentIcon(type, hasVideo)} fontSize="xl" />
							<Text fontSize="sm" onClick={onEditPress}>
								{name}
							</Text>
						</Stack>
						<ButtonGroup color="gray.400" size="xs" p="2">
							<Tooltip label={__('Preview', 'masteriyo')}>
								<Link href={previewPermalink} isExternal>
									<IconButton
										_hover={{ color: 'gray.700' }}
										variant="unstyled"
										icon={<Icon fontSize="xl" as={BiShow} />}
										aria-label={__('Preview', 'masteriyo')}
									/>
								</Link>
							</Tooltip>

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
									onClick={onDeletePress}
									variant="unstyled"
									icon={<Icon fontSize="xl" as={BiTrash} />}
									aria-label={__('Delete', 'masteriyo')}
								/>
							</Tooltip>
						</ButtonGroup>
					</Flex>
				)}
			</Draggable>
			<AlertDialog
				isOpen={isOpen}
				onClose={onClose}
				isCentered
				leastDestructiveRef={cancelRef}>
				<AlertDialogOverlay>
					<AlertDialogContent>
						<AlertDialogHeader>
							{sprintf(__('Delete %s - %s?', 'masteriyo'), type, name)}
						</AlertDialogHeader>
						<AlertDialogBody>
							{__(
								'Are you sure? You can’t restore after deleting.',
								'masteriyo'
							)}
						</AlertDialogBody>
						<AlertDialogFooter>
							<ButtonGroup>
								<Button ref={cancelRef} onClick={onClose} variant="outline">
									{__('Cancel', 'masteriyo')}
								</Button>
								<Button
									colorScheme="red"
									onClick={onDeleteConfirm}
									isLoading={deleteQuiz.isLoading || deleteLesson.isLoading}>
									{__('Delete', 'masteriyo')}
								</Button>
							</ButtonGroup>
						</AlertDialogFooter>
					</AlertDialogContent>
				</AlertDialogOverlay>
			</AlertDialog>
		</>
	);
};

export default Content;
