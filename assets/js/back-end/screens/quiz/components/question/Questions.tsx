import {
	Accordion,
	Alert,
	AlertIcon,
	Center,
	Spinner,
	Stack,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { DragDropContext, Droppable, DropResult } from 'react-beautiful-dnd';
import { useMutation, useQuery, useQueryClient } from 'react-query';
import AddNewButton from '../../../../components/common/AddNewButton';
import { whileDraggingStyles } from '../../../../config/styles';
import urls from '../../../../constants/urls';
import QuestionProvider from '../../../../context/QuestionProvider';
import { QuestionSchema } from '../../../../schemas';
import API from '../../../../utils/api';
import Question from './Question';

interface Props {
	quizId: number;
	courseId: number;
	questionList: QuestionSchema[];
	setQuestionList: any;
}

const Questions: React.FC<Props> = (props) => {
	const { quizId, courseId, questionList, setQuestionList } = props;

	const questionsAPI = new API(urls.questions);
	const quizBuilder = new API(urls.quizBuilder);
	const queryClient = useQueryClient();

	const questionQuery = useQuery<QuestionSchema[]>(
		[`questions${quizId}`, quizId],
		() => quizBuilder.get(quizId),
		{
			onSuccess: (data: QuestionSchema[]) => {
				setQuestionList(data);
			},
			enabled: !!quizId,
		}
	);

	const addQuestion = useMutation(
		(data: QuestionSchema | any) => questionsAPI.store(data),
		{
			onSuccess: () => {
				queryClient.invalidateQueries(`questions${quizId}`);
			},
		}
	);

	const onAddNewQuestionPress = () => {
		addQuestion.mutate({
			name: __('Untitled Question', 'masteriyo'),
			course_id: courseId,
			parent_id: quizId,
			type: 'true-false',
			answers: [
				{
					name: 'True',
					correct: true,
				},
				{
					name: 'False',
					correct: false,
				},
			],
		});
	};

	const onDrapEnd = (result: DropResult) => {
		if (!result.destination) {
			return;
		}

		if (
			result.destination.droppableId === result.source.droppableId &&
			result.destination.index === result.source.index
		) {
			return;
		}

		const items = Array.from(questionList);
		const [reorderedItem] = items.splice(result.source.index, 1);
		items.splice(result.destination.index, 0, reorderedItem);
		setQuestionList(items);
	};

	return (
		<QuestionProvider>
			<Stack direction="column" spacing="6" py="8">
				{questionQuery.isLoading && (
					<Center minH="xs">
						<Spinner />
					</Center>
				)}
				{questionQuery.isSuccess && (
					<>
						{questionQuery.data.length == 0 ? (
							<Alert status="info" fontSize="sm" p="2.5">
								<AlertIcon />
								{__('No questions found.', 'masteriyo')}
							</Alert>
						) : (
							<DragDropContext onDragEnd={onDrapEnd}>
								<Droppable droppableId="quiz-question" type="question">
									{(droppableProvided, snapshot) => (
										<Accordion
											allowToggle
											sx={snapshot.isDraggingOver ? whileDraggingStyles : {}}
											p={['0', '0', '3']}
											w="100%"
											ref={droppableProvided.innerRef}
											{...droppableProvided.droppableProps}>
											{questionList.map(
												(question: QuestionSchema, index: number) => (
													<Question
														key={question.id}
														questionData={question}
														index={index}
													/>
												)
											)}
											{droppableProvided.placeholder}
										</Accordion>
									)}
								</Droppable>
							</DragDropContext>
						)}
						<Center>
							<AddNewButton
								onClick={onAddNewQuestionPress}
								isLoading={addQuestion.isLoading}>
								{__('Add New Question', 'masteriyo')}
							</AddNewButton>
						</Center>
					</>
				)}
			</Stack>
		</QuestionProvider>
	);
};

export default Questions;
