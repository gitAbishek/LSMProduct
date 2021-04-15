import {
	Accordion,
	AccordionButton,
	AccordionItem,
	AccordionPanel,
	Alert,
	AlertIcon,
	Box,
	Button,
	Center,
	Flex,
	Heading,
	Icon,
	IconButton,
	Menu,
	MenuButton,
	MenuItem,
	MenuList,
	Spinner,
	Stack,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import AddNewButton from 'Components/common/AddNewButton';
import React, { useState } from 'react';
import { BiDotsVerticalRounded, BiTrash } from 'react-icons/bi';
import { useMutation, useQuery, useQueryClient } from 'react-query';

import urls from '../../../constants/urls';
import API from '../../../utils/api';
import Question from './Question';

interface Props {
	quizId: number;
	courseId: any;
}

const QuestionBuilder: React.FC<Props> = (props) => {
	const { quizId, courseId } = props;
	const [isModalOpen, setIsModalOpen] = useState(false);
	const [deletingCourse, setDeletingCourse] = useState(Number);
	const [totalQuestionsCount, setTotalQuestionsCount] = useState<any>('0');
	const questionsAPI = new API(urls.questions);

	const queryClient = useQueryClient();

	const questionQuery = useQuery(
		['questions', quizId],
		() => questionsAPI.list({ parent: quizId }),
		{
			enabled: !!quizId,
			onSuccess: (data) => {
				setTotalQuestionsCount(data.length);
			},
		}
	);

	const addQuestion = useMutation((data: object) => questionsAPI.store(data), {
		onSuccess: () => {
			queryClient.invalidateQueries('questions');
		},
	});

	const onAddNewQuestion = () => {
		addQuestion.mutate({ name: 'New Question' });
	};

	const deleteQuestion = useMutation((id: number) => questionsAPI.delete(id), {
		onSuccess: (data) => {
			queryClient.invalidateQueries('questions');
		},
	});

	const onDeleteQuestion = (id: any) => {
		setDeletingCourse(id);
		setIsModalOpen(true);
	};

	const onDeleteConfirm = (id: any) => {
		deleteQuestion.mutate(id);
		setIsModalOpen(false);
	};

	return (
		<Stack direction="column" spacing="6">
			<Flex
				py="3"
				borderBottom="1px"
				borderColor="gray.100"
				align="center"
				justify="space-between">
				<Heading fontSize="lg" fontWeight="medium">
					Questions
				</Heading>

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
						<MenuItem icon={<BiTrash />}>{__('Delete', 'masteriyo')}</MenuItem>
					</MenuList>
				</Menu>
			</Flex>
			{questionQuery.isLoading && (
				<Center minH="xs">
					<Spinner />
				</Center>
			)}
			{questionQuery.isSuccess && (
				<>
					{questionQuery.data.length == 0 ? (
						<Alert status="info">
							<AlertIcon />
							{__(
								'There are no questions right now, You can add them',
								'masteriyo'
							)}
						</Alert>
					) : (
						<Accordion allowToggle>
							{questionQuery.data.map((question: any) => (
								<Question questionData={question} />
							))}
						</Accordion>
					)}
					<Center>
						<AddNewButton>Add New Question</AddNewButton>
					</Center>
				</>
			)}
		</Stack>
	);
};

export default QuestionBuilder;
