import { Heading, List, ListIcon, ListItem, Stack } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { BiCheckCircle, BiInfoCircle, BiTargetLock } from 'react-icons/bi';
import { ScoreBoardSchema } from '../../../schemas';

interface Props {
	scoreData: ScoreBoardSchema;
}
const ScoreBoard: React.FC<Props> = (props) => {
	const { scoreData } = props;

	return (
		<Stack align="center" direction="column" spacing="6">
			<Heading>{__('Your Score', 'masteriyo')}</Heading>
			<List>
				<ListItem>
					<ListIcon as={BiInfoCircle} color="green.500" />
					{__('Total Questions: ')}
					{scoreData.total_questions}
				</ListItem>
				<ListItem>
					<ListIcon as={BiCheckCircle} color="green.500" />
					{__('Total Answered: ')}
					{scoreData.total_answered_questions}
				</ListItem>
				<ListItem>
					<ListIcon as={BiTargetLock} color="green.500" />
					{__('Total Attempts: ')}
					{scoreData.total_attempts}
				</ListItem>
				<ListItem>
					{__('Total Marks: ')}
					{scoreData.total_marks}
				</ListItem>
				<ListItem>
					{__('Marks Earned: ')}
					{scoreData.earned_marks}
				</ListItem>
			</List>
		</Stack>
	);
};

export default ScoreBoard;
