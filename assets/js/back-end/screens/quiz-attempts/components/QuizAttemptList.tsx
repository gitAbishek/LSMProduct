import { Badge, Stack, Text, VStack } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { Td, Tr } from 'react-super-responsive-table';
import { QuizAttempt } from '../../../schemas';

interface Props {
	data: QuizAttempt;
}

const QuizAttemptList: React.FC<Props> = (props) => {
	const { data } = props;

	const Result = (earned_marks: number, total_marks: number) => {
		if (isNaN(earned_marks) || isNaN(total_marks)) {
			return;
		}
		const total = (earned_marks / total_marks) * 100;

		return Math.round(total) + '%';
	};

	return (
		<Tr>
			<Td>
				<Stack direction="column" spacing="2">
					<Text fontWeight="bold" color="gray.600" fontSize="sm">
						#{data?.user?.id} {data?.user?.first_name} {data?.user?.last_name}
					</Text>
					<Text color="gray.600" fontSize="xs">
						{data?.user?.display_name} ({data?.user?.email})
					</Text>
				</Stack>
			</Td>
			<Td>
				<Text fontWeight="bold" color="gray.600" fontSize="sm">
					{data?.quiz?.name}
				</Text>
				<Text color="gray.600" fontSize="xs">
					{__('Course:', 'masteriyo')} {data?.course?.name}
				</Text>
			</Td>
			<Td>
				<Stack direction="column" spacing="2">
					<Text color="gray.600" fontSize="xs">
						{__('Total Questions:', 'masteriyo')} {data?.total_questions}
					</Text>
					<Text color="gray.600" fontSize="xs">
						{__('Earned Points:', 'masteriyo')} {data?.earned_marks}
					</Text>
					<Text color="gray.600" fontSize="xs">
						{__('Total Points:', 'masteriyo')} {data?.total_marks}
					</Text>
				</Stack>
			</Td>
			<Td>
				<VStack align="flex-start">
					<Text color="gray.600" fontSize="sm">
						{Result(
							parseFloat(data?.earned_marks),
							parseFloat(data?.total_marks)
						)}
					</Text>
					{!isNaN(parseFloat(data?.earned_marks)) &&
						(parseFloat(data?.earned_marks) < data?.quiz?.pass_mark ? (
							<Badge colorScheme="red">{__('Fail', 'masteriyo')}</Badge>
						) : (
							<Badge colorScheme="green">{__('Pass', 'masteriyo')}</Badge>
						))}
				</VStack>
			</Td>
		</Tr>
	);
};

export default QuizAttemptList;
