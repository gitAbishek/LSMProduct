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
	Divider,
	Flex,
	Icon,
	IconButton,
	Stack,
	useToast,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useRef, useState } from 'react';
import { FormProvider, useForm } from 'react-hook-form';
import { BiCopy, BiTrash } from 'react-icons/bi';
import { useMutation, useQueryClient } from 'react-query';
import { useHistory } from 'react-router';

import { Sortable } from '../../../../assets/icons';
import urls from '../../../../constants/urls';
import API from '../../../../utils/api';
import Answers from '../answer/Answers';
import EditQuestion from './EditQuestion';

interface Props {
	questionData: any;
	totalQuestionsCount: any;
}

const Question: React.FC<Props> = (props) => {
	const { questionData, totalQuestionsCount } = props;
	const toast = useToast();
	const methods = useForm();
	const history = useHistory();
	const [isDeleteModalOpen, setDeleteModalOpen] = useState(false);
	const questionAPI = new API(urls.questions);
	const cancelRef = useRef<any>();
	const queryClient = useQueryClient();

	const duplicateQuestion = useMutation(
		(data: object) => questionAPI.store(data),
		{
			onSuccess: (data) => {
				queryClient.invalidateQueries(`questions${data.parent_id}`);
			},
		}
	);

	const updateQuestion = useMutation(
		(data: object) => questionAPI.update(questionData.id, data),
		{
			onSuccess: (data: any) => {
				toast({
					title: __('Question Updated', 'masteriyo'),
					description: data.name + __(' has been updated successfully.'),
					isClosable: true,
					status: 'success',
				});
				queryClient.invalidateQueries(`questions${data.parent_id}`);
			},
		}
	);

	const deleteQuestion = useMutation((id: any) => questionAPI.delete(id), {
		onSuccess: (data: any) => {
			toast({
				title: __('Question Deleted', 'masteriyo'),
				description: data.name + __(' has been deleted successfully.'),
				isClosable: true,
				status: 'error',
			});
			queryClient.invalidateQueries(`questions${data.parent_id}`);
		},
	});

	const onDuplicatePress = () => {
		const data = {
			name: questionData.name,
			description: questionData.description,
			parent_id: questionData.parent_id,
			course_id: questionData.course_id,
			menu_order: totalQuestionsCount + 1,
		};
		duplicateQuestion.mutate(data);
	};

	const onSubmit = (data: object) => {
		updateQuestion.mutate(data);
	};

	const onDeletePress = () => {
		setDeleteModalOpen(true);
	};

	const onDeleteModalClose = () => {
		setDeleteModalOpen(false);
	};
	const onDeleteConfirm = () => {
		deleteQuestion.mutate(questionData.id);
		setDeleteModalOpen(false);
	};

	const iconStyles = {
		fontSize: 'x-large',
		color: 'gray.500',
		minW: 'auto',
		_hover: { color: 'blue.500' },
	};

	return (
		<>
			<AccordionItem
				borderWidth="1px"
				borderColor="gray.100"
				rounded="sm"
				mb="4"
				p="0">
				<Flex align="center" justify="space-between" px="2" py="1">
					<Stack direction="row" spacing="2" align="center" flex="1">
						<Icon as={Sortable} fontSize="lg" color="gray.500" />
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
								<EditQuestion questionData={questionData} />
								<Answers questionData={questionData} />
								<Divider />
								<ButtonGroup>
									<Button
										colorScheme="blue"
										type="submit"
										isLoading={updateQuestion.isLoading}>
										{__('Update', 'masteriyo')}
									</Button>
									<Button variant="outline" onClick={() => history.goBack()}>
										{__('Cancel', 'masteriyo')}
									</Button>
								</ButtonGroup>
							</Stack>
						</form>
					</FormProvider>
				</AccordionPanel>
			</AccordionItem>
			<AlertDialog
				isOpen={isDeleteModalOpen}
				onClose={onDeleteModalClose}
				isCentered
				leastDestructiveRef={cancelRef}>
				<AlertDialogOverlay>
					<AlertDialogContent>
						<AlertDialogHeader>
							{__('Delete ')} {questionData.name}
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