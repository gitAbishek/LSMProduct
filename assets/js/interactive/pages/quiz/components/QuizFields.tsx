import { Heading, Stack } from '@chakra-ui/react';
import React from 'react';
import { useQuery } from 'react-query';
import { useParams } from 'react-router-dom';
import urls from '../../../../back-end/constants/urls';
import { QuestionSchema } from '../../../../back-end/schemas';
import API from '../../../../back-end/utils/api';
import FieldSingleChoice from './FieldSingleChoice';

const QuizFields: React.FC = () => {
	const { quizId }: any = useParams();
	const questionsAPI = new API(urls.questions);

	const questionQuery = useQuery(
		[`interactiveQuestions${quizId}`, quizId],
		() => questionsAPI.list({ parent: quizId, order: 'asc' }),
		{
			enabled: !!quizId,
		}
	);

	if (questionQuery.isSuccess) {
		return (
			<>
				{questionQuery.data.map((question: QuestionSchema) => (
					<Stack direction="column" spacing="8" key={question.id}>
						<Heading fontSize="lg">{question.name}</Heading>

						{question.type === 'single-choice' && (
							<FieldSingleChoice answers={question.answers} />
						)}
					</Stack>
				))}
			</>
		);
	}
	return <div></div>;
};

export default QuizFields;
