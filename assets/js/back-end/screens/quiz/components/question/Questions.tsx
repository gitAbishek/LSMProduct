import {
	Accordion,
	Alert,
	AlertIcon,
	Center,
	Spinner,
	Stack,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import AddNewButton from 'Components/common/AddNewButton';
import React, { useState } from 'react';
import { useMutation, useQuery, useQueryClient } from 'react-query';

import urls from '../../../../constants/urls';
import API from '../../../../utils/api';
import Question from './Question';

interface Props {
	quizId: number;
	courseId: any;
}

const Questions: React.FC<Props> = (props) => {
	const { quizId, courseId } = props;

	const [totalQuestionsCount, setTotalQuestionsCount] = useState<any>('0');
	const questionsAPI = new API(urls.questions);

	const queryClient = useQueryClient();

	const questionQuery = useQuery(
		[`questions${quizId}`, quizId],
		() => questionsAPI.list({ parent: quizId, order: 'asc' }),
		{
			enabled: !!quizId,
			onSuccess: (data) => {
				setTotalQuestionsCount(data.length);
			},
		}
	);

	const addQuestion = useMutation((data: object) => questionsAPI.store(data), {
		onSuccess: () => {
			queryClient.invalidateQueries(`questions${quizId}`);
		},
	});

	const onAddNewQuestionPress = () => {
		addQuestion.mutate({
			name: 'New Question',
			course_id: courseId,
			parent_id: quizId,
			menu_order: totalQuestionsCount + 1,
		});
	};

	return (
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
							{__(
								'There are no questions right now, You can add them by clicking on Add New Question',
								'masteriyo'
							)}
						</Alert>
					) : (
						<Accordion allowToggle>
							{questionQuery.data.map((question: any) => (
								<Question
									key={question.id}
									questionData={question}
									totalQuestionsCount={totalQuestionsCount}
								/>
							))}
						</Accordion>
					)}
					<Center>
						<AddNewButton onClick={onAddNewQuestionPress}>
							Add New Question
						</AddNewButton>
					</Center>
				</>
			)}
		</Stack>
	);
};

export default Questions;
