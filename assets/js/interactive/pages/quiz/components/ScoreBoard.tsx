import {
	Alert,
	AlertIcon,
	Button,
	ButtonGroup,
	Heading,
	Icon,
	List,
	ListIcon,
	ListItem,
	Stack,
	Text,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import {
	BiCheckCircle,
	BiChevronRight,
	BiFlag,
	BiInfoCircle,
	BiInfoSquare,
	BiTargetLock,
	BiXCircle,
} from 'react-icons/bi';
import { ScoreBoardSchema } from '../../../schemas';
interface Props {
	scoreData: ScoreBoardSchema;
	onStartPress: any;
	isButtonLoading?: boolean;
	attemptMessage: string;
}

const ScoreBoard: React.FC<Props> = (props) => {
	const { scoreData, onStartPress, isButtonLoading, attemptMessage } = props;

	const listStyles = {
		li: {
			fontSize: 'sm',
			borderBottom: '1px',
			py: '2',
			borderColor: 'gray.100',
		},
	};

	const isQuizAnswered =
		scoreData.total_answered_questions === 0 ? BiXCircle : BiCheckCircle;
	const checkQuizTotalAnswered =
		scoreData.total_answered_questions === 0 ? 'red.500' : 'green.500';

	return (
		<Stack direction="column" spacing="8">
			{attemptMessage && (
				<Alert status="error" fontSize="sm" p="2.5">
					<AlertIcon />
					{__(attemptMessage, 'masteriyo')}
				</Alert>
			)}
			<Heading fontSize="x-large" d="flex" alignItems="center">
				<Icon
					as={isQuizAnswered}
					fontSize="xx-large"
					color={checkQuizTotalAnswered}
					mr="4"
				/>
				{__('Your Score', 'masteriyo')}
			</Heading>
			<List sx={listStyles}>
				<ListItem d="flex" alignItems="center">
					<ListIcon as={BiInfoCircle} color="green.500" />
					<Text fontWeight="bold" flex="0 0 200px">
						{__('Total Questions: ')}
					</Text>
					<Text>{scoreData.total_questions}</Text>
				</ListItem>
				<ListItem d="flex" alignItems="center">
					<ListIcon as={isQuizAnswered} color={checkQuizTotalAnswered} />
					<Text fontWeight="bold" flex="0 0 200px">
						{__('Total Answered: ')}
					</Text>
					<Text>{scoreData.total_answered_questions}</Text>
				</ListItem>
				<ListItem d="flex" alignItems="center">
					<ListIcon as={BiTargetLock} color="green.500" />
					<Text fontWeight="bold" flex="0 0 200px">
						{__('Total Attempts: ')}
					</Text>
					<Text>{scoreData.total_attempts}</Text>
				</ListItem>
				<ListItem d="flex" alignItems="center">
					<ListIcon as={BiInfoSquare} color="green.500" />
					<Text fontWeight="bold" flex="0 0 200px">
						{__('Total Marks: ')}
					</Text>
					<Text>{scoreData.total_marks}</Text>
				</ListItem>
				<ListItem d="flex" alignItems="center">
					<ListIcon as={BiFlag} color="green.500" />
					<Text fontWeight="bold" flex="0 0 200px">
						{__('Marks Earned: ')}
					</Text>
					<Text>{scoreData.earned_marks}</Text>
				</ListItem>
			</List>

			{attemptMessage.length <= 0 && (
				<ButtonGroup>
					<Button
						onClick={onStartPress}
						colorScheme="blue"
						isLoading={isButtonLoading}
						rounded="full"
						fontWeight="bold"
						rightIcon={<Icon as={BiChevronRight} fontSize="x-large" />}
						textTransform="uppercase">
						{__('Start Quiz Again', 'masteriyo')}
					</Button>
				</ButtonGroup>
			)}
		</Stack>
	);
};

export default ScoreBoard;
