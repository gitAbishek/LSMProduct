import {
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
	BiCheck,
	BiCheckCircle,
	BiFlag,
	BiInfoCircle,
	BiInfoSquare,
	BiRefresh,
	BiTargetLock,
	BiXCircle,
} from 'react-icons/bi';
import { ScoreBoardSchema } from '../../../schemas';
interface Props {
	scoreData: ScoreBoardSchema;
	onStartPress: any;
	isButtonLoading?: boolean;
	isButtonDisabled?: boolean;
	isFinishButtonLoading: boolean;
	onCompletePress: () => void;
	limitReached: boolean;
}

const ScoreBoard: React.FC<Props> = (props) => {
	const {
		scoreData,
		onStartPress,
		isButtonLoading,
		onCompletePress,
		isButtonDisabled,
		isFinishButtonLoading,
		limitReached,
	} = props;

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
					<Text fontWeight="bold" flex="0 0 150px">
						{__('Total Questions: ', 'masteriyo')}
					</Text>
					<Text>{scoreData.total_questions}</Text>
				</ListItem>
				<ListItem d="flex" alignItems="center">
					<ListIcon as={isQuizAnswered} color={checkQuizTotalAnswered} />
					<Text fontWeight="bold" flex="0 0 150px">
						{__('Answered Questions: ', 'masteriyo')}
					</Text>
					<Text>{scoreData.total_answered_questions}</Text>
				</ListItem>
				<ListItem d="flex" alignItems="center">
					<ListIcon as={BiTargetLock} color="green.500" />
					<Text fontWeight="bold" flex="0 0 150px">
						{__('Total Attempts: ', 'masteriyo')}
					</Text>
					<Text>{scoreData.total_attempts}</Text>
				</ListItem>
				<ListItem d="flex" alignItems="center">
					<ListIcon as={BiInfoSquare} color="green.500" />
					<Text fontWeight="bold" flex="0 0 150px">
						{__('Total Points: ', 'masteriyo')}
					</Text>
					<Text>{scoreData.total_marks}</Text>
				</ListItem>
				<ListItem d="flex" alignItems="center">
					<ListIcon as={BiFlag} color="green.500" />
					<Text fontWeight="bold" flex="0 0 150px">
						{__('Earned Points:', 'masteriyo')}
					</Text>
					<Text>{scoreData.earned_marks}</Text>
				</ListItem>
			</List>

			<ButtonGroup
				display="flex"
				gap="3"
				flexDirection={['column', 'column', 'row', 'row']}>
				<Button
					colorScheme="green"
					rounded="full"
					fontWeight="bold"
					leftIcon={<Icon as={BiCheck} fontSize="xl" />}
					isDisabled={isButtonDisabled}
					isLoading={isFinishButtonLoading}
					onClick={onCompletePress}
					textTransform="uppercase">
					{isButtonDisabled
						? __('Completed', 'masteriyo')
						: __('Complete Quiz', 'masteriyo')}
				</Button>
				<Button
					onClick={onStartPress}
					isLoading={isButtonLoading}
					isDisabled={limitReached}
					rounded="full"
					fontWeight="bold"
					colorScheme="primary"
					leftIcon={<Icon as={BiRefresh} fontSize="xl" />}
					textTransform="uppercase">
					{__('Start Quiz Again', 'masteriyo')}
				</Button>
			</ButtonGroup>
		</Stack>
	);
};

export default ScoreBoard;
