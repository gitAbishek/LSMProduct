import { Heading, List, ListItem } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { ScoreBoardSchema } from '../../../schemas';

interface Props {
	scoreData: ScoreBoardSchema;
}
const ScoreBoard: React.FC<Props> = (props) => {
	const { scoreData } = props;

	return (
		<>
			<Heading>{__('Your Score', 'masteriyo')}</Heading>
			<List>
				<ListItem>
					{__('Total Questions: ')}
					{scoreData.total_questions}
				</ListItem>
				<ListItem>
					{__('Total Answered: ')}
					{scoreData.total_answered_questions}
				</ListItem>
				<ListItem>
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
		</>
	);
};

export default ScoreBoard;
