import { Button, ButtonGroup, Heading, Stack } from '@chakra-ui/react';
import React from 'react';
import { FormProvider, useForm } from 'react-hook-form';
import { useQuery } from 'react-query';
import { useParams } from 'react-router-dom';
import urls from '../../../../back-end/constants/urls';
import { QuestionSchema } from '../../../../back-end/schemas';
import API from '../../../../back-end/utils/api';
import FieldSingleChoice from './FieldSingleChoice';

const QuizFields: React.FC = () => {
	const { quizId }: any = useParams();
	const questionsAPI = new API(urls.questions);
	const methods = useForm();

	const questionQuery = useQuery(
		[`interactiveQuestions${quizId}`, quizId],
		() => questionsAPI.list({ parent: quizId, order: 'asc' }),
		{
			enabled: !!quizId,
		}
	);

	console.log(questionQuery?.data);

	const onSubmit = (data: any) => {
		console.log(data);
	};

	if (questionQuery.isSuccess) {
		return (
			<FormProvider {...methods}>
				<form onSubmit={methods.handleSubmit(onSubmit)}>
					{questionQuery.data.map((question: QuestionSchema) => (
						<Stack direction="column" spacing="8" key={question.id}>
							<Heading fontSize="lg">{question.name}</Heading>

							{question.type === 'single-choice' && (
								<FieldSingleChoice answers={question.answers} />
							)}
						</Stack>
					))}
					<ButtonGroup>
						<Button type="submit" colorScheme="blue">
							Submit form
						</Button>
					</ButtonGroup>
				</form>
			</FormProvider>
		);
	}
	return <div></div>;
};

export default QuizFields;
