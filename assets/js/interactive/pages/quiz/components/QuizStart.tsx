import {
	Alert,
	AlertIcon,
	Button,
	ButtonGroup,
	Icon,
	List,
	ListIcon,
	ListItem,
	Stack,
	Text,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import humanizeDuration from 'humanize-duration';
import React from 'react';
import {
	BiCheckCircle,
	BiCheckDouble,
	BiChevronRight,
	BiInfoCircle,
	BiTime,
} from 'react-icons/bi';
import { QuizSchema } from '../../../../back-end/schemas';
interface Props {
	quizData: QuizSchema;
	onStartPress: any;
	attemptMessage: string;
	isButtonLoading?: boolean;
}

const QuizStart: React.FC<Props> = (props) => {
	const { quizData, onStartPress, attemptMessage, isButtonLoading } = props;

	const listItemStyles = {
		d: 'flex',
		alignItems: 'center',
		borderRight: '1px',
		borderRightColor: 'rgba(255, 255, 255, 0.2)',
		px: '3',
		'.chakra-icon': {
			fontSize: 'lg',
		},
		_last: {
			borderRightColor: 'transparent',
		},
	};

	return (
		<Stack direction="column" spacing="8">
			{attemptMessage && (
				<Alert status="error" fontSize="sm" p="2.5">
					<AlertIcon />
					{__(attemptMessage, 'masteriyo')}
				</Alert>
			)}
			<List
				bg="blue.500"
				rounded="sm"
				d="flex"
				flexDirection="row"
				alignItems="center"
				py="3"
				color="white"
				fontSize="xs">
				<ListItem sx={listItemStyles}>
					<ListIcon as={BiTime} />
					<Text as="strong">{__('Duration: ')}</Text>
					<Text ml="1">
						{quizData?.duration === 0
							? __('No time limit', 'masteriyo')
							: humanizeDuration(quizData?.duration * 60 * 1000)}
					</Text>
				</ListItem>
				<ListItem sx={listItemStyles}>
					<ListIcon as={BiInfoCircle} />
					<Text as="strong">{__('Questions: ')}</Text>
					<Text ml="1">{quizData?.questions_count}</Text>
				</ListItem>
				<ListItem sx={listItemStyles}>
					<ListIcon as={BiCheckCircle} />
					<Text as="strong">{__('Mark: ')}</Text>
					<Text ml="1">{quizData?.full_mark}</Text>
				</ListItem>
				<ListItem sx={listItemStyles}>
					<ListIcon as={BiCheckDouble} />
					<Text as="strong">{__('Pass Mark: ')}</Text>
					<Text ml="1">{quizData?.pass_mark}</Text>
				</ListItem>
			</List>

			{attemptMessage.length <= 0 && (
				<ButtonGroup>
					<Button
						onClick={onStartPress}
						isLoading={isButtonLoading}
						colorScheme="blue"
						rounded="full"
						fontWeight="bold"
						rightIcon={<Icon as={BiChevronRight} fontSize="x-large" />}
						textTransform="uppercase">
						{__('Start Quiz', 'masteriyo')}
					</Button>
				</ButtonGroup>
			)}
		</Stack>
	);
};

export default QuizStart;
