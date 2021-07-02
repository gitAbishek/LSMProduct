import {
	Box,
	FormControl,
	Icon,
	Input,
	InputGroup,
	InputRightElement,
	Stack,
} from '@chakra-ui/react';
import React from 'react';
import { BiSearch } from 'react-icons/bi';
import QuestionItem from './QuestionItem';

const QuestionList = () => {
	const dummyContent = [
		{
			title: 'What is an Instructional video?',
			answer: 4,
		},
		{
			title: 'What is Editing Process?',
			answer: 1,
		},
		{
			title: 'How can I add voice in Videos?',
			answer: 2,
		},
		{
			title: 'Can you advise me on the filming Process?',
			answer: 1,
		},
	];
	return (
		<>
			<Stack direction="column" spacing="1">
				<Box as="form" action="" p="4">
					<FormControl>
						<InputGroup>
							<Input placeholder="Search a Question" />
							<InputRightElement>
								<Icon as={BiSearch} />
							</InputRightElement>
						</InputGroup>
					</FormControl>
				</Box>
				{dummyContent.map((item: any, index: number) => (
					<QuestionItem title={item.title} answer={item.answer} key={index} />
				))}
			</Stack>
		</>
	);
};

export default QuestionList;
