import {
	AlertDialog,
	AlertDialogBody,
	AlertDialogContent,
	AlertDialogFooter,
	AlertDialogHeader,
	AlertDialogOverlay,
	Badge,
	Button,
	ButtonGroup,
	IconButton,
	Link,
	Menu,
	MenuButton,
	MenuItem,
	MenuList,
	Stack,
	Text,
	useDisclosure,
	useToast,
	VStack,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useRef } from 'react';
import { BiDotsVerticalRounded, BiTrash } from 'react-icons/bi';
import { useMutation, useQueryClient } from 'react-query';
import { Link as RouterLink } from 'react-router-dom';
import { Td, Tr } from 'react-super-responsive-table';
import routes from '../../../constants/routes';
import urls from '../../../constants/urls';
import { QuizAttempt } from '../../../schemas';
import API from '../../../utils/api';

interface Props {
	data: QuizAttempt;
}

const QuizAttemptList: React.FC<Props> = (props) => {
	const { data } = props;
	const quizAttemptsAPI = new API(urls.quizesAttempts);

	const cancelRef = useRef<any>();
	const queryClient = useQueryClient();
	const { onClose, onOpen, isOpen } = useDisclosure();
	const toast = useToast();

	const deleteQuizAttempt = useMutation(
		(id: number) => quizAttemptsAPI.delete(id),
		{
			onSuccess: () => {
				queryClient.invalidateQueries('quizAttemptsList');
				onClose();
				toast({
					title: __('Quiz attempt deleted', 'masteriyo'),
					status: 'success',
					isClosable: true,
				});
			},
			onError: (error: any) => {
				onClose();
				toast({
					title: __('Failed to delete quiz attempt', 'masteriyo'),
					description: error?.message
						? error?.message
						: error?.response?.data?.message
						? `${error?.response?.data?.message}`
						: null,
					isClosable: true,
					status: 'error',
				});
			},
		}
	);

	const Result = (earnedMarks: number, totalMarks: number) => {
		if (isNaN(earnedMarks) || isNaN(totalMarks)) {
			return;
		}
		const total = (earnedMarks / totalMarks) * 100;

		return Math.round(total) + '%';
	};

	const onDeleteConfirm = () => {
		deleteQuizAttempt.mutate(data?.id);
	};

	return (
		<Tr>
			<Td>
				<Stack direction="column" spacing="2">
					{'attempt_ended' === data?.attempt_status ? (
						<Link
							as={RouterLink}
							to={routes.quiz_attempts.edit.replace(
								':attemptId',
								data?.id.toString()
							)}
							fontWeight="semibold"
							fontSize="sm"
							_hover={{ color: 'blue.500' }}>
							#{data?.id} {data?.user?.first_name} {data?.user?.last_name}
						</Link>
					) : (
						<Text fontWeight="semibold" fontSize="sm">
							#{data?.id} {data?.user?.first_name} {data?.user?.last_name}
						</Text>
					)}
					<Text color="gray.600" fontSize="xs">
						{data?.user?.display_name} ({data?.user?.email})
					</Text>
				</Stack>
			</Td>
			<Td>
				<Text fontWeight="bold" color="gray.600" fontSize="sm">
					{data?.quiz?.name}
				</Text>
				<Text color="gray.600" fontSize="xs">
					{__('Course:', 'masteriyo')} {data?.course?.name}
				</Text>
			</Td>
			<Td>
				<Stack direction="column" spacing="2">
					<Text color="gray.600" fontSize="xs">
						{__('Total Questions:', 'masteriyo')} {data?.total_questions}
					</Text>
					<Text color="gray.600" fontSize="xs">
						{__('Earned Points:', 'masteriyo')} {data?.earned_marks}
					</Text>
					<Text color="gray.600" fontSize="xs">
						{__('Total Points:', 'masteriyo')} {data?.total_marks}
					</Text>
				</Stack>
			</Td>
			<Td>
				{'attempt_ended' !== data?.attempt_status ? (
					<Badge colorScheme="yellow">{__('In progress', 'masteriyo')}</Badge>
				) : (
					<VStack align="flex-start">
						<Text color="gray.600" fontSize="sm">
							{Result(
								parseFloat(data?.earned_marks),
								parseFloat(data?.total_marks)
							)}
						</Text>
						{!isNaN(parseFloat(data?.earned_marks)) &&
							(parseFloat(data?.earned_marks) < data?.quiz?.pass_mark ? (
								<Badge colorScheme="red">{__('Fail', 'masteriyo')}</Badge>
							) : (
								<Badge colorScheme="green">{__('Pass', 'masteriyo')}</Badge>
							))}
					</VStack>
				)}
			</Td>
			<Td>
				<ButtonGroup>
					{'attempt_ended' === data?.attempt_status ? (
						<RouterLink
							to={routes.quiz_attempts.edit.replace(
								':attemptId',
								data?.id.toString()
							)}>
							<Button colorScheme="blue" size="xs">
								{__('View', 'masteriyo')}
							</Button>
						</RouterLink>
					) : null}
					<Menu placement="bottom-end">
						<MenuButton
							as={IconButton}
							icon={<BiDotsVerticalRounded />}
							variant="outline"
							rounded="sm"
							fontSize="large"
							size="xs"
						/>
						<MenuList>
							<MenuItem
								onClick={onOpen}
								icon={<BiTrash />}
								_hover={{ color: 'red.500' }}>
								{__('Delete', 'masteriyo')}
							</MenuItem>
						</MenuList>
					</Menu>
				</ButtonGroup>

				<AlertDialog
					isOpen={isOpen}
					onClose={onClose}
					isCentered
					leastDestructiveRef={cancelRef}>
					<AlertDialogOverlay>
						<AlertDialogContent>
							<AlertDialogHeader>
								{__('Deleting Quiz Attempt', 'masteriyo')}{' '}
								{data?.id ? `#${data.id}` : ''}
							</AlertDialogHeader>
							<AlertDialogBody>
								{__(
									"Are you sure? You can't restore after deleting.",
									'masteriyo'
								)}
							</AlertDialogBody>
							<AlertDialogFooter>
								<ButtonGroup>
									<Button
										onClick={onClose}
										variant="outline"
										ref={cancelRef}
										isDisabled={deleteQuizAttempt.isLoading}>
										{__('Cancel', 'masteriyo')}
									</Button>
									<Button
										colorScheme="red"
										isLoading={deleteQuizAttempt.isLoading}
										onClick={onDeleteConfirm}>
										{__('Delete', 'masteriyo')}
									</Button>
								</ButtonGroup>
							</AlertDialogFooter>
						</AlertDialogContent>
					</AlertDialogOverlay>
				</AlertDialog>
			</Td>
		</Tr>
	);
};

export default QuizAttemptList;
