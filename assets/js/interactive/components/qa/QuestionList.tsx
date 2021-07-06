import {
	Box,
	Button,
	ButtonGroup,
	FormControl,
	Heading,
	Icon,
	Input,
	InputGroup,
	InputRightElement,
	Slide,
	Stack,
	Text,
	Textarea,
	useDisclosure,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { BiChevronLeft, BiChevronRight, BiSearch } from 'react-icons/bi';
import Message from './Message';

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
			<Slide direction="left" in={isListOpen} style={{ position: 'absolute' }}>
				<Stack direction="column" spacing="1" h="full" justify="space-between">
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
					<Stack direction="column" spacing="3" w="full" p="4" pb="6">
						<FormControl>
							<Input type="text" placeholder="What is your question?" />
						</FormControl>
						<Button colorScheme="blue" type="submit" isFullWidth>
							Ask a Question
						</Button>
					</Stack>
				</Stack>
			</Slide>
			<Slide direction="right" in={isChatOpen} style={{ position: 'absolute' }}>
				<Stack direction="column" spacing="8" justify="space-between" h="full">
					<Stack direction="column" spacing="8">
						<Box as="header">
							<ButtonGroup px="4" py="2">
								<Button
									leftIcon={<Icon fontSize="xl" as={BiChevronLeft} />}
									variant="link"
									onClick={() => {
										onChatToggle();
										onListToggle();
									}}>
									{__('Back', 'masteriyo')}
								</Button>
							</ButtonGroup>
							<Stack direction="column" p="4" bg="gray.50" spacing="1">
								<Text fontWeight="bold">What is an instructional video?</Text>
								<Text fontSize="x-small" color="gray.400">
									3 answers
								</Text>
							</Stack>
						</Box>

						<Stack direction="column" spacing="4" px="4">
							<Message
								name="Arlene McCoy"
								avatar="https://i.pravatar.cc/150?img=3"
								message="What is Screencast videos?"
								sender="user"
								time="12 min ago"
							/>
							<Message
								name="Arlene McCoy"
								avatar="https://i.pravatar.cc/150?img=3"
								message="What is Screencast videos?"
								sender="author"
								time="12 min ago"
							/>
						</Stack>
					</Stack>
					<Stack direction="column" spacing="3" w="full" p="4" pb="6">
						<FormControl>
							<Textarea />
						</FormControl>
						<Button colorScheme="blue" type="submit" isFullWidth>
							Send
						</Button>
					</Stack>
				</Stack>
			</Slide>
		</>
	);
};

export default QuestionList;
