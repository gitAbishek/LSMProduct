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
	Flex,
	FormControl,
	FormErrorMessage,
	FormLabel,
	Heading,
	Icon,
	IconButton,
	Input,
	Menu,
	MenuButton,
	MenuItem,
	MenuList,
	Stack,
	useToast,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import Editor from 'Components/common/Editor';
import Select from 'Components/common/Select';
import React, { useRef, useState } from 'react';
import { Controller, useForm } from 'react-hook-form';
import { BiCopy, BiDotsVerticalRounded, BiTrash } from 'react-icons/bi';
import { useMutation, useQueryClient } from 'react-query';

import { Sortable } from '../../../../assets/icons';
import urls from '../../../../constants/urls';
import API from '../../../../utils/api';
import Answers from '../answer/Answers';

interface Props {
	questionData: any;
	totalQuestionsCount: any;
}

const Question: React.FC<Props> = (props) => {
	const { questionData, totalQuestionsCount } = props;
	const toast = useToast();
	const {
		register,
		handleSubmit,
		formState: { errors },
		control,
	} = useForm();
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
			onSuccess: (data: any) => {},
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

	const categoryList = [
		{ value: 'true-false', label: 'True False' },
		{ value: 'single-choice', label: 'Single Choice' },
		{ value: 'multiple-choice', label: 'Multi Choice' },
		{ value: 'short-answer', label: 'Short Answer' },
	];

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
					<form onSubmit={handleSubmit(onSubmit)}>
						<Stack direction="column" spacing="8">
							<Stack direction="column" spacing="6">
								<Flex
									align="center"
									justify="space-between"
									borderBottom="1px"
									borderColor="gray.100"
									pb="3">
									<Heading fontSize="lg" fontWeight="semibold">
										Question
									</Heading>
									<Menu placement="bottom-end">
										<MenuButton
											as={IconButton}
											icon={<BiDotsVerticalRounded />}
											variant="outline"
											rounded="xs"
											fontSize="large"
											size="sm"
										/>
										<MenuList>
											<MenuItem icon={<BiTrash />} onClick={onDeletePress}>
												{__('Delete', 'masteriyo')}
											</MenuItem>
										</MenuList>
									</Menu>
								</Flex>
								<Stack direction="row" spacing="6">
									<FormControl isInvalid={!!errors?.name}>
										<FormLabel>{__('Question Name', 'masteriyo')}</FormLabel>
										<Input
											defaultValue={questionData.name}
											placeholder={__('Your Question Name', 'masteriyo')}
											{...register('name', {
												required: __(
													'You must provide name for the question',
													'masteriyo'
												),
											})}
										/>
										<FormErrorMessage>
											{errors?.name && errors?.name?.message}
										</FormErrorMessage>
									</FormControl>
									<FormControl>
										<FormLabel>
											{__('Question Description', 'masteriyo')}
										</FormLabel>
										<Controller
											defaultValue={questionData.type}
											render={({ field }) => (
												<Select
													{...field}
													closeMenuOnSelect={false}
													options={categoryList}
												/>
											)}
											control={control}
											name="type"
											rules={{
												required: __(
													'Please select question type',
													'masteriyo'
												),
											}}
										/>
										<FormErrorMessage>
											{errors?.type && errors?.type?.message}
										</FormErrorMessage>
									</FormControl>
								</Stack>
								<FormControl>
									<FormLabel>
										{__('Question Description', 'masteriyo')}
									</FormLabel>
									<Editor
										name="description"
										defaultValue={questionData.description}
										control={control}
									/>
								</FormControl>
							</Stack>
							<Answers questionData={questionData} />
						</Stack>
					</form>
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
