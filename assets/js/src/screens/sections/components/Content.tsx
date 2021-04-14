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
	Menu,
	MenuButton,
	MenuItem,
	MenuList,
	Stack,
	Text,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useRef, useState } from 'react';
import {
	BiAlignLeft,
	BiDotsVerticalRounded,
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
	type: 'lesson' | 'quiz' | string;
}

const Content: React.FC<Props> = (props) => {
	const { id, name, type } = props;
	const [isDeleteModalOpen, setDeleteModalOpen] = useState(false);
	const queryClient = useQueryClient();
	const history = useHistory();
	const cancelRef = useRef<any>();
	const lessonAPI = new API(urls.lessons);
	const quizAPI = new API(urls.quizes);

	const deleteLesson = useMutation((id: number) => lessonAPI.delete(id), {
		onSuccess: () => {
			queryClient.invalidateQueries('contents');
		},
	});
	const deleteQuiz = useMutation((id: number) => quizAPI.delete(id), {
		onSuccess: () => {
			queryClient.invalidateQueries('contents');
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
		setDeleteModalOpen(false);
	};
	const onEditPress = () => {
		if (type === 'lesson') {
			history.push(routes.lesson.edit.replace(':lessonId', id.toString()));
		}
		if (type === 'quiz') {
			history.push(
				routes.quiz.edit.replace(':quizId', id.toString()).replace(':step', '')
			);
		}
	};

	return (
		<Flex
			justify="space-between"
			rounded="sm"
			border="1px"
			borderColor="gray.100"
			p="2">
			<Stack direction="row" spacing="3" align="center">
				<Icon as={Sortable} fontSize="lg" color="gray.500" />
				<Icon as={type === 'lesson' ? BiAlignLeft : BiTimer} fontSize="xl" />
				<Text fontSize="sm">{name}</Text>
			</Stack>
			<Stack direction="row" spacing="3">
				<Button variant="outline" size="sm" onClick={onEditPress}>
					{__('Edit', 'masteriyo')}
				</Button>
				<Menu placement="bottom-end">
					<MenuButton
						as={IconButton}
						icon={<BiDotsVerticalRounded />}
						variant="outline"
						rounded="sm"
						size="sm"
						fontSize="large"
					/>
					<MenuList>
						<MenuItem onClick={onDeletePress} icon={<BiTrash />}>
							{__('Delete', 'masteriyo')}
						</MenuItem>
					</MenuList>
				</Menu>
			</Stack>
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
									isLoading={deleteQuiz.isLoading || deleteLesson.isLoading}>
									{__('Delete', 'masteriyo')}
								</Button>
							</ButtonGroup>
						</AlertDialogFooter>
					</AlertDialogContent>
				</AlertDialogOverlay>
			</AlertDialog>
		</Flex>
	);
};

export default Content;
