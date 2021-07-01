import { Heading, Icon, Stack, Text } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { BiChevronRight } from 'react-icons/bi';

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
			<Icon as={BiChevronRight} />
		</Stack>
	);
};

export default QuestionItem;
