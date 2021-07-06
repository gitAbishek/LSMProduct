import {
	Avatar,
	Box,
	FormControl,
	Heading,
	Icon,
	Input,
	InputGroup,
	InputRightElement,
	Slide,
	Stack,
	Text,
	useDisclosure,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { BiChevronRight, BiSearch } from 'react-icons/bi';

const QuestionList = () => {
	const { isOpen: isListOpen, onToggle: onListToggle } = useDisclosure({
		defaultIsOpen: true,
	});

	const { isOpen: isChatOpen, onToggle: onChatToggle } = useDisclosure();
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
			<Slide
				direction="left"
				in={isListOpen}
				style={{ position: 'absolute', top: 0, left: 0 }}>
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
						<Stack
							key={index}
							direction="row"
							align="center"
							justify="space-between"
							spacing="4"
							borderBottom="1px"
							borderBottomColor="gray.100"
							px="4"
							py="2"
							onClick={() => {
								onChatToggle();
								onListToggle();
							}}>
							<Stack direction="column" spacing="2">
								<Heading fontSize="sm">{item.title}</Heading>
								<Text fontSize="x-small" color="gray.500">
									{item.answer} {__('Answers', 'masteriyo')}
								</Text>
							</Stack>
							<Icon as={BiChevronRight} fontSize="x-large" color="gray.600" />
						</Stack>
					))}
				</Stack>
			</Slide>
			<Slide
				direction="right"
				in={isChatOpen}
				style={{ position: 'absolute', top: 80 }}>
				<Stack direction="column" spacing="2">
					<Stack direction="row" spacing="1">
						<Avatar size="sm" />
						<Stack direction="column" spacing="1">
							<Text fontSize="sm" fontWeight="medium">
								Arlene McCoy
							</Text>
							<Text fontSize="xs">What is the Screencast videos?</Text>
						</Stack>
					</Stack>
				</Stack>
			</Slide>
		</>
	);
};

export default QuestionList;
