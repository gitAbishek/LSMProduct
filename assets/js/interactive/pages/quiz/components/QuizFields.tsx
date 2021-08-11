import {
	Alert,
	AlertIcon,
	Divider,
	Heading,
	SkeletonText,
	Stack,
	Text,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { useQuery } from 'react-query';
import { useParams } from 'react-router-dom';
import urls from '../../../../back-end/constants/urls';
import { QuestionSchema } from '../../../../back-end/schemas';
import API from '../../../../back-end/utils/api';
import FieldMultipleChoice from './FieldMultipleChoice';
import FieldShortAnswer from './FieldShortAnswer';
import FieldSingleChoice from './FieldSingleChoice';
import FieldTrueFalse from './FieldTrueFalse';

interface Props {
	quizAboutToExpire: boolean;
}

const QuizFields: React.FC<Props> = (props) => {
	const { quizAboutToExpire } = props;
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
				<Stack direction="column" spacing="16">
					{quizAboutToExpire && (
						<Alert status="error" fontSize="sm" p="2.5">
							<AlertIcon />
							{__('Your quize time is about to expire!', 'masteriyo')}
						</Alert>
					)}
					{questionQuery.data.map((question: QuestionSchema, index: string) => (
						<Stack direction="column" spacing="8" key={question.id}>
							<Stack direction="column">
								<Text fontWeight="medium" color="gray.400">
									{__('Question ') +
										(index + 1) +
										'/' +
										questionQuery.data.length}
								</Text>

								<Divider variant="dashed" />
							</Stack>
							<Heading fontSize="lg">{question.name}</Heading>

							{question.type === 'true-false' && (
								<FieldTrueFalse
									answers={question.answers}
									questionId={`${question.id.toString()}`}
								/>
							)}

							{question.type === 'single-choice' && (
								<FieldSingleChoice
									answers={question.answers}
									questionId={`${question.id.toString()}`}
								/>
							)}

							{question.type === 'multiple-choice' && (
								<FieldMultipleChoice
									answers={question.answers}
									questionId={`${question.id.toString()}`}
								/>
							)}

							{question.type === 'short-answer' && (
								<FieldShortAnswer questionId={`${question.id.toString()}`} />
							)}
						</Stack>
					))}
				</Stack>
			</>
		);
	}
	return <SkeletonText noOfLines={4} />;
};

export default QuizFields;
