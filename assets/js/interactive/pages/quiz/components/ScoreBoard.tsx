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
	BiCheckCircle,
	BiChevronRight,
	BiFlag,
	BiInfoCircle,
	BiInfoSquare,
	BiTargetLock,
} from 'react-icons/bi';
import { ScoreBoardSchema } from '../../../schemas';
interface Props {
	scoreData: ScoreBoardSchema;
	onStartPress: any;
}

const ScoreBoard: React.FC<Props> = (props) => {
	const { scoreData, onStartPress } = props;

	const listStyles = {
		li: {
			fontSize: 'sm',
			borderBottom: '1px',
			py: '2',
			borderColor: 'gray.100',
		},
	};
	return (
		<Stack direction="column" spacing="8">
			<Heading fontSize="x-large" d="flex" alignItems="center">
				<Icon as={BiCheckCircle} fontSize="xx-large" color="green.400" mr="4" />
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
					<ListIcon as={BiCheckCircle} color="green.500" />
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

			<ButtonGroup>
				<Button
					onClick={onStartPress}
					colorScheme="blue"
					rounded="full"
					fontWeight="bold"
					rightIcon={<Icon as={BiChevronRight} fontSize="x-large" />}
					textTransform="uppercase">
					{__('Start Quiz Again', 'masteriyo')}
				</Button>
			</ButtonGroup>
		</Stack>
	);
};

export default ScoreBoard;
