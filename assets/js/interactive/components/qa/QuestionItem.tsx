import { Heading, Stack, Text } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n/build-types';
import React from 'react';

interface Props {
	title: string;
	answer: number;
}
const QuestionItem: React.FC<Props> = (props) => {
	const { title, answer } = props;
	return (
		<Stack direction="row" align="center" justify="space-between" spacing="4">
			<Stack direction="column" spacing="2">
				<Heading fontSize="md">{title}</Heading>
				<Text fontSize="xs">
					{answer} {__('Answers', 'masteriyo')}
				</Text>
			</Stack>
		</Stack>
	);
};

export default QuestionItem;
