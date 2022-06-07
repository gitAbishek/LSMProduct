import { Badge, HStack, Stack, Text } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { Td, Tr } from 'react-super-responsive-table';
import { QuizAttempt } from '../../../schemas';
import { getLocalTime } from '../../../utils/utils';

interface Props {
	quizAttemptData: QuizAttempt;
}

const QuizAttemptInfo: React.FC<Props> = (props) => {
	const { quizAttemptData } = props;

	/**
	 * Convert minutes to hours and minutes.
	 *
	 * @param minutes Minutes.
	 * @return string
	 */
	const getHrsAndMins = (minutes: number) => {
		const hrs = Math.floor(minutes / 60);
		const mins = minutes % 60;

		return hrs + ' hrs ' + mins + ' mins';
	};

	/**
	 * Get total percentage.
	 *
	 * @param earnedMarks Earned marks.
	 * @param totalMarks Total marks.
	 * @returns string
	 */
	const Result = (earnedMarks: number, totalMarks: number) => {
		if (isNaN(earnedMarks) || isNaN(totalMarks)) {
			return;
		}
		const total = (earnedMarks / totalMarks) * 100;

		return Math.round(total) + '%';
	};

	/**
	 *
	 * Get total attempt time of quiz.
	 *
	 * @param attemptStarted Attempt started date and time.
	 * @param attemptEnded Attempt ended date and time.
	 * @returns string
	 */
	const getTotalAttemptTime = (attemptStarted: any, attemptEnded: any) => {
		attemptStarted = new Date(attemptStarted);
		attemptEnded = new Date(attemptEnded);

		let seconds = Math.floor((attemptEnded - attemptStarted) / 1000);
		const hours = Math.floor(seconds / (60 * 60));

		seconds -= hours * 60 * 60;

		const minutes = Math.floor(seconds / 60);

		seconds -= minutes * 60;

		return hours + ' hrs ' + minutes + ' min ' + seconds + ' sec';
	};

	return (
		<Tr>
			<Td>
				<Stack direction="column" spacing="2">
					<Text fontWeight="semibold" fontSize="sm">
						{quizAttemptData?.user?.first_name}{' '}
						{quizAttemptData?.user?.last_name}
					</Text>
					<Text color="gray.600" fontSize="xs">
						{quizAttemptData?.user?.display_name} (
						{quizAttemptData?.user?.email})
					</Text>
				</Stack>
			</Td>
			<Td>
				<Stack direction="column" spacing="2">
					<Text color="gray.600" fontSize="xs" fontWeight="bold">
						{getLocalTime(quizAttemptData?.attempt_started_at).toLocaleString()}
					</Text>

					<Stack direction="row">
						<Text color="gray.600" fontSize="xs">
							{__('Total Attempts:', 'masteriyo')}
						</Text>
						<Text color="gray.600" fontSize="xs" fontWeight="bold">
							{quizAttemptData?.total_attempts}
						</Text>
					</Stack>
					<Stack direction="row">
						<Text color="gray.600" fontSize="xs">
							{__('Quiz Time:', 'masteriyo')}
						</Text>
						<Text color="gray.600" fontSize="xs" fontWeight="bold">
							{getHrsAndMins(quizAttemptData?.quiz?.duration)}
						</Text>
					</Stack>
					<Stack direction="row">
						<Text color="gray.600" fontSize="xs">
							{__('Attempt Time:', 'masteriyo')}
						</Text>
						<Text color="gray.600" fontSize="xs" fontWeight="bold">
							{getTotalAttemptTime(
								quizAttemptData?.attempt_started_at,
								quizAttemptData?.attempt_ended_at
							)}
						</Text>
					</Stack>
				</Stack>
			</Td>
			<Td>
				<Stack direction="column" spacing="2">
					<HStack>
						{!isNaN(parseFloat(quizAttemptData?.earned_marks)) &&
							(parseFloat(quizAttemptData?.earned_marks) <
							quizAttemptData?.quiz?.pass_mark ? (
								<Badge colorScheme="red">{__('Fail', 'masteriyo')}</Badge>
							) : (
								<Badge colorScheme="green">{__('Pass', 'masteriyo')}</Badge>
							))}
						<Text color="gray.600" fontSize="xs" fontWeight="bold">
							{Result(
								parseFloat(quizAttemptData?.earned_marks),
								parseFloat(quizAttemptData?.total_marks)
							)}
						</Text>
					</HStack>

					<Stack direction="row">
						<Text color="gray.600" fontSize="xs">
							{__('Earned Points:', 'masteriyo')}
						</Text>
						<Text color="gray.600" fontSize="xs" fontWeight="bold">
							{quizAttemptData?.earned_marks} / {quizAttemptData?.total_marks}
						</Text>
					</Stack>
					<Stack direction="row">
						<Text color="gray.600" fontSize="xs">
							{__('Correct Answers:', 'masteriyo')}
						</Text>
						<Text color="gray.600" fontSize="xs" fontWeight="bold">
							{quizAttemptData?.total_correct_answers} /{' '}
							{quizAttemptData?.total_questions}
						</Text>
					</Stack>

					<Stack direction="row">
						<Text color="gray.600" fontSize="xs">
							{__('Attempt Questions:', 'masteriyo')}
						</Text>
						<Text color="gray.600" fontSize="xs" fontWeight="bold">
							{quizAttemptData?.total_answered_questions} /{' '}
							{quizAttemptData?.total_questions}
						</Text>
					</Stack>
				</Stack>
			</Td>
			<Td></Td>
		</Tr>
	);
};

export default QuizAttemptInfo;
