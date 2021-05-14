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
	Center,
	Collapse,
	Flex,
	Icon,
	IconButton,
	Menu,
	MenuButton,
	MenuItem,
	MenuList,
	Spinner,
	Stack,
	Text,
	useToast,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import AddNewButton from 'Components/common/AddNewButton';
import React, { useRef, useState } from 'react';
import { Draggable, Droppable } from 'react-beautiful-dnd';
import {
	BiAlignLeft,
	BiDotsVerticalRounded,
	BiEdit,
	BiTimer,
	BiTrash,
} from 'react-icons/bi';
import { useMutation, useQuery, useQueryClient } from 'react-query';
import { useHistory } from 'react-router-dom';

import { Sortable } from '../../../assets/icons';
import routes from '../../../constants/routes';
import urls from '../../../constants/urls';
import API from '../../../utils/api';
import Content from './Content';
import EditSection from './EditSection';

interface Props {
	id: number;
	name: string;
	courseId: number;
	description?: any;
	index: number;
	contents: object[];
}

const Section: React.FC<Props> = (props) => {
	const { id, name, description, index } = props;
	const [isEditing, setIsEditing] = useState(false);
	const [isDeleteModalOpen, setDeleteModalOpen] = useState(false);
	const contentAPI = new API(urls.contents);
	const sectionAPI = new API(urls.sections);
	const history = useHistory();
	const cancelRef = useRef<any>();

	const queryClient = useQueryClient();
	const toast = useToast();

	const contentQuery = useQuery(['contents', id], () =>
		contentAPI.list({ section: id, order: 'asc', orderBy: 'menu_order' })
	);

	const deleteMutation = useMutation((id: number) => sectionAPI.delete(id), {
		onSuccess: (data: any) => {
			toast({
				title: __('Section Deleted', 'masteriyo'),
				description:
					data?.name + __(' has been deleted successfully', 'masteriyo'),
				isClosable: true,
				status: 'error',
			});
			queryClient.invalidateQueries('builderSections');
		},
	});

	const onEditPress = () => {
		setIsEditing(true);
	};

	const onDeletePress = () => {
		setDeleteModalOpen(true);
	};

	const onDeleteConfirm = () => {
		deleteMutation.mutate(id);
	};

	const onAddNewLessonPress = () => {
		history.push(routes.lesson.add.replace(':sectionId', id.toString()));
	};

	const onAddNewQuizPress = () => {
		history.push(routes.quiz.add.replace(':sectionId', id.toString()));
	};

	const onDeleteModalClose = () => {
		setDeleteModalOpen(false);
	};

	return (
		<Draggable draggableId={id.toString()} key={id} index={index}>
			{(draggableProvided) => (
				<Box
					bg="white"
					p="10"
					shadow="box"
					ref={draggableProvided.innerRef}
					{...draggableProvided.draggableProps}>
					<Flex justify="space-between">
						<Stack direction="row" spacing="3" align="center">
							<span {...draggableProvided.dragHandleProps}>
								<Icon as={Sortable} fontSize="lg" color="gray.500" />
							</span>

							<Text fontWeight="semibold" fontSize="xl">
								{name} {id}
							</Text>
						</Stack>
						<Menu placement="bottom-end">
							<MenuButton
								as={IconButton}
								icon={<BiDotsVerticalRounded />}
								variant="outline"
								rounded="sm"
								fontSize="large"
							/>
							<MenuList>
								<MenuItem onClick={onEditPress} icon={<BiEdit />}>
									{__('Edit', 'masteriyo')}
								</MenuItem>
								<MenuItem onClick={onDeletePress} icon={<BiTrash />}>
									{__('Delete', 'masteriyo')}
								</MenuItem>
							</MenuList>
						</Menu>
					</Flex>
					<Box py="8">
						<Collapse in={isEditing} animateOpacity>
							<EditSection
								id={id}
								name={name}
								description={description}
								onSave={() => setIsEditing(false)}
								onCancel={() => setIsEditing(false)}
							/>
						</Collapse>
						{contentQuery.isLoading && (
							<Center minH="12">
								<Spinner />
							</Center>
						)}
						{contentQuery.isSuccess && !!contentQuery.data.length && (
							<Droppable droppableId={index.toString()} type="content">
								{(droppableProvided) => (
									<Stack
										direction="column"
										spacing="4"
										ref={droppableProvided.innerRef}
										{...droppableProvided.droppableProps}>
										{contentQuery.data.map((content: any, index: any) => (
											<Content
												key={content.id}
												id={content.id}
												name={content.name}
												type={content.type}
												index={index}
											/>
										))}
										{droppableProvided.placeholder}
									</Stack>
								)}
							</Droppable>
						)}
					</Box>
					<Box>
						<Menu>
							<MenuButton as={AddNewButton}>
								{__('Add New Content', 'masteriyo')}
							</MenuButton>
							<MenuList>
								<MenuItem
									fontSize="sm"
									fontWeight="medium"
									icon={<Icon as={BiAlignLeft} fontSize="lg" />}
									onClick={onAddNewLessonPress}>
									{__('Add New Lesson', 'masteriyo')}
								</MenuItem>
								<MenuItem
									fontSize="sm"
									fontWeight="medium"
									icon={<Icon as={BiTimer} fontSize="lg" />}
									onClick={onAddNewQuizPress}>
									{__('Add New Quiz', 'masteriyo')}
								</MenuItem>
							</MenuList>
						</Menu>
					</Box>
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
									Are you sure? You can't restore this section
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
											isLoading={deleteMutation.isLoading}>
											{__('Delete', 'masteriyo')}
										</Button>
									</ButtonGroup>
								</AlertDialogFooter>
							</AlertDialogContent>
						</AlertDialogOverlay>
					</AlertDialog>
				</Box>
			)}
		</Draggable>
	);
};

export default Section;
