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
		<Stack
			direction="row"
			align="center"
			justify="space-between"
			spacing="4"
			borderBottom="1px"
			borderBottomColor="gray.100"
			px="4"
			py="2">
			<Stack direction="column" spacing="2">
				<Heading fontSize="sm">{title}</Heading>
				<Text fontSize="x-small" color="gray.500">
					{answer} {__('Answers', 'masteriyo')}
				</Text>
			</Stack>
			<Icon as={BiChevronRight} />
		</Stack>
	);
};

export default QuestionItem;
