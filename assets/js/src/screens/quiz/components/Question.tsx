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
	Icon,
	IconButton,
	Stack,
	background,
	useToast,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useRef, useState } from 'react';
import { useForm } from 'react-hook-form';
import { BiCopy, BiTrash } from 'react-icons/bi';
import { useMutation, useQueryClient } from 'react-query';

import { Sortable } from '../../../assets/icons';
import urls from '../../../constants/urls';
import API from '../../../utils/api';
import { mergeDeep } from '../../../utils/mergeDeep';

interface Props {
	questionData: any;
	totalQuestionsCount: any;
}

const Question: React.FC<Props> = (props) => {
	const { questionData, totalQuestionsCount } = props;
	const toast = useToast();
	const { register, handleSubmit } = useForm();
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
	console.log(questionData);

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

	return (
		<>
			<AccordionItem
				borderWidth="1px"
				borderColor="gray.100"
				rounded="sm"
				_expanded={{ shadow: 'box' }}
				mb="4"
				py="1"
				px="2">
				<Flex align="center" justify="space-between">
					<Stack direction="row" spacing="2" align="center" flex="1">
						<Icon as={Sortable} fontSize="lg" color="gray.500" />
						<AccordionButton _hover={{ background: 'transparent' }} px="0">
							{questionData.name}
						</AccordionButton>
					</Stack>
					<Stack direction="row" spacing="2">
						<IconButton
							variant="unstyled"
							fontSize="x-large"
							color="gray.500"
							_hover={{ color: 'blue.500' }}
							aria-label={__('Duplicate', 'masteriyo')}
							icon={<BiCopy />}
							textAlign="right"
							minW="auto"
							onClick={onDuplicatePress}
						/>
						<IconButton
							variant="unstyled"
							fontSize="x-large"
							color="gray.500"
							_hover={{ color: 'blue.500' }}
							aria-label={__('Duplicate', 'masteriyo')}
							icon={<BiTrash />}
							textAlign="right"
							minW="auto"
							onClick={onDeletePress}
						/>
					</Stack>
				</Flex>
				<AccordionPanel>
					<form onSubmit={handleSubmit(onSubmit)}></form>
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
