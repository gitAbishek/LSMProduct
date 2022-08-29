import { Badge, Box, HStack, Stack, Text, Tooltip } from '@chakra-ui/react';
import React from 'react';
import { Td, Tr } from 'react-super-responsive-table';
import {
	MultiChoice,
	SingleChoice,
	TrueFalse,
} from '../../../constants/images';
import { IconType } from '../../../enums/Enum';

const QuizOverview = (props: any) => {
	const { answersData } = props;

	const getQuestionTypeIcon = (iconType: string) =>
		iconType === IconType.TrueFalse ? (
			<TrueFalse />
		) : iconType === IconType.SingleChoice ? (
			<SingleChoice />
		) : iconType === IconType.MultipleChoice ? (
			<MultiChoice />
		) : null;

	return (
		<Tr>
			<Td>
				<Stack direction="row" alignItems="center">
					<Tooltip label={answersData?.type}>
						<Box>{getQuestionTypeIcon(answersData?.type)}</Box>
					</Tooltip>
					<Text wordBreak="break-word">{answersData?.question}</Text>
				</Stack>
			</Td>
			<Td>
				<Stack direction="column" spacing="2">
					<Stack direction="row">
						<Text color="gray.600" fontSize="xs">
							Correct:
						</Text>
						<Text color="gray.600" fontSize="xs" wordBreak="break-word">
							{'multiple-choice' === answersData?.type
								? answersData?.correct_answer.join(', ')
								: answersData?.correct_answer}
						</Text>
					</Stack>
					<Stack direction="row">
						<Text color="gray.600" fontSize="xs">
							Given:
						</Text>
						<Text color="gray.600" fontSize="xs" wordBreak="break-word">
							{'multiple-choice' === answersData?.type
								? answersData?.answered.join(', ')
								: answersData?.answered}
						</Text>
					</Stack>
				</Stack>
			</Td>
			<Td>
				<HStack>
					<Badge
						w="fit-content"
						colorScheme={answersData?.correct ? 'green' : 'red'}>
						<Text fontSize="xs">
							{answersData?.correct ? 'Correct' : 'Incorrect'}
						</Text>
					</Badge>
					{/* <Menu>
								<MenuButton color="primary.500">
									<BiEdit size="16" />
								</MenuButton>
								<MenuList>
									<MenuItem
										color="red.500"
										icon={<BiX size="16" />}>
										Mark as Incorrect
									</MenuItem>
								</MenuList>
							</Menu> */}
				</HStack>
			</Td>
			<Td>
				<Text color="gray.600" fontSize="xs" fontWeight="bold">
					{
						/**
						 * #TODO Need to enhance later.
						 */
						answersData?.correct ? answersData?.points : 0
					}
				</Text>
			</Td>
		</Tr>
	);
};

export default QuizOverview;
