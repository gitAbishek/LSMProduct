import { Heading, List, ListItem } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';

interface Props {
	scoreData: any;
}
const ScoreBoard: React.FC<Props> = (props) => {
	const { scoreData } = props;

	return (
		<>
			<Heading>{__('Your Score', 'masteriyo')}</Heading>
			<List>
				<ListItem>
					{__('Total Attempts: ')}
					{scoreData.total_attempts}
				</ListItem>
			</List>
		</>
	);
};

export default ScoreBoard;
