import {
	AccordionButton,
	AccordionItem,
	AccordionPanel,
	AlertDialog,
	AlertDialogBody,
	AlertDialogContent,
	AlertDialogFooter,
	AlertDialogHeader,
	AlertDialogOverlay,
	Button,
	ButtonGroup,
	Center,
	Divider,
	Flex,
	Icon,
	IconButton,
	Spinner,
	Stack,
	useToast,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useContext, useRef, useState } from 'react';
import { Draggable } from 'react-beautiful-dnd';
import { FormProvider, useForm } from 'react-hook-form';
import { BiCopy, BiTrash } from 'react-icons/bi';
import { useMutation, useQueryClient } from 'react-query';
import { Sortable } from '../../../../assets/icons';
import urls from '../../../../constants/urls';
import { QuestionContext } from '../../../../context/QuestionProvider';
import { QuestionSchema } from '../../../../schemas';
import API from '../../../../utils/api';
import { deepClean, deepMerge } from '../../../../utils/utils';
import Answers from '../answer/Answers';
import EditQuestion from './EditQuestion';

interface Props {
	questionData: QuestionSchema;
	index: number;
}

export type QuestionType =
	| 'true-false'
	| 'single-choice'
	| 'multiple-choice'
	| 'short-answer'
	| 'image-matching';

const Question: React.FC<Props> = (props) => {
	const { questionData, index } = props;
	const toast = useToast();
	const methods = useForm();
	const { submitQuestionDisabled } = useContext(QuestionContext);
	const [isDeleteModalOpen, setDeleteModalOpen] = useState(false);
	const [answerData, setAnswerData] = useState<any>(
		questionData?.answers || null
	);
	const [questionType, setQuestionType] = useState<QuestionType>(
		questionData.type
	);
	const questionAPI = new API(urls.questions);
	const cancelRef = useRef<any>();
	const queryClient = useQueryClient();

	const duplicateQuestion = useMutation(
		(data: any) => questionAPI.store(data),
		{
			onSuccess: (data) => {
				queryClient.invalidateQueries(`questions${data.parent_id}`);
			},
		}
	);

	const updateQuestion = useMutation(
		(data: any) => questionAPI.update(questionData.id, data),
		{
			onSuccess: (data: QuestionSchema) => {
				toast({
					title: __('Question Updated', 'masteriyo'),
					isClosable: true,
					status: 'success',
				});
				queryClient.invalidateQueries(`questions${data.parent_id}`);
			},
		}
	);

	const deleteQuestion = useMutation(
		(id: number) => questionAPI.delete(id, { force: true }),
		{
			onSuccess: (data: QuestionSchema) => {
				toast({
					title: __('Question Deleted', 'masteriyo'),
					isClosable: true,
					status: 'error',
				});
				queryClient.invalidateQueries(`questions${data.parent_id}`);
				setDeleteModalOpen(false);
			},
		}
	);

	const onDuplicatePress = () => {
		const data = {
			name: questionData.name,
			description: questionData.description,
			parent_id: questionData.parent_id,
			course_id: questionData.course_id,
		};
		duplicateQuestion.mutate(data);
	};

	const onSubmit = (data: any) => {
		const cleanData = deepClean(data);
		const newData = {
			...(cleanData.type && {
				type: cleanData.type.value,
			}),
		};
		updateQuestion.mutate(deepMerge(cleanData, newData));
	};

	const onDeletePress = () => {
		setDeleteModalOpen(true);
	};

	const onDeleteModalClose = () => {
		setDeleteModalOpen(false);
	};
	const onDeleteConfirm = () => {
		deleteQuestion.mutate(questionData.id);
	};

	const iconStyles = {
		fontSize: 'x-large',
		color: 'gray.500',
		minW: 'auto',
		_hover: { color: 'primary.500' },
	};

	return (
		<>
			<Draggable draggableId={questionData.id.toString()} index={index}>
				{(draggableProvided) => (
					<AccordionItem
						ref={draggableProvided.innerRef}
						{...draggableProvided.draggableProps}
						borderWidth="1px"
						borderColor="gray.100"
						rounded="sm"
						mb="4"
						bg="white"
						p="0">
						<Flex align="center" justify="space-between" px="2" py="1">
							<Stack direction="row" spacing="2" align="center" flex="1">
								<span {...draggableProvided.dragHandleProps}>
									<Icon as={Sortable} fontSize="lg" color="gray.500" />
								</span>
								<AccordionButton _hover={{ background: 'transparent' }} px="0">
									{questionData.name}
								</AccordionButton>
							</Stack>
							<Stack direction="row" spacing="2">
								<IconButton
									variant="unstyled"
									sx={iconStyles}
									aria-label={__('Duplicate', 'masteriyo')}
									icon={<BiCopy />}
									onClick={onDuplicatePress}
								/>
								<IconButton
									variant="unstyled"
									sx={iconStyles}
									aria-label={__('Delete', 'masteriyo')}
									icon={<BiTrash />}
									minW="auto"
									onClick={onDeletePress}
								/>
							</Stack>
						</Flex>
						<AccordionPanel borderTop="1px" borderColor="gray.100" p="5">
							<FormProvider {...methods}>
								<form onSubmit={methods.handleSubmit(onSubmit)}>
									<Stack direction="column" spacing="8">
										<EditQuestion
											questionData={questionData}
											setQuestionType={setQuestionType}
											setAnswerData={setAnswerData}
										/>
										<Answers answers={answerData} questionType={questionType} />
										<Divider />
										<ButtonGroup>
											<Button
												colorScheme="primary"
												type="submit"
												isDisabled={submitQuestionDisabled}
												isLoading={updateQuestion.isLoading}>
												{__('Update', 'masteriyo')}
											</Button>
										</ButtonGroup>
									</Stack>
								</form>
							</FormProvider>
						</AccordionPanel>
					</AccordionItem>
				)}
			</Draggable>
			{duplicateQuestion.isLoading && (
				<Center>
					<Spinner />
				</Center>
			)}
			<AlertDialog
				isOpen={isDeleteModalOpen}
				onClose={onDeleteModalClose}
				isCentered
				leastDestructiveRef={cancelRef}>
				<AlertDialogOverlay>
					<AlertDialogContent>
						<AlertDialogHeader>
							{__('Delete', 'masteriyo') + ' ' + questionData.name}
						</AlertDialogHeader>
						<AlertDialogBody>
							{__(
								'Are you sure? You can’t restore after deleting.',
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
									isLoading={deleteQuestion.isLoading}>
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

export default Question;
