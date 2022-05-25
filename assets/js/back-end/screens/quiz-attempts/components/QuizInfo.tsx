import { Stack, Text } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { QuizAttempt } from '../../../schemas';

interface Props {
	quizAttemptData: QuizAttempt;
}

const QuizInfo: React.FC<Props> = (props) => {
	const { quizAttemptData } = props;
	return (
		<Stack direction="column">
			<Text fontSize="md" fontWeight="bold">
				{quizAttemptData.quiz?.name}
			</Text>
			<Text color="gray.600" fontSize="xs" fontWeight="medium">
				{__('Course: ', 'masteriyo')}
				{quizAttemptData.course?.name}
			</Text>
		</Stack>
	);
};

export default QuizInfo;
