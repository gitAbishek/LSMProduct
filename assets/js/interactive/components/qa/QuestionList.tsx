import {
	Box,
	Button,
	Center,
	FormControl,
	Heading,
	Icon,
	Input,
	InputGroup,
	InputRightElement,
	Slide,
	Spinner,
	Stack,
	Text,
	useDisclosure,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useState } from 'react';
import { BiChevronRight, BiSearch } from 'react-icons/bi';
import { useQuery } from 'react-query';
import { useParams } from 'react-router-dom';
import urls from '../../../back-end/constants/urls';
import API from '../../../back-end/utils/api';
import { QuestionAnswerSchema } from '../../schemas';
import QaChat from './QaChat';

const QuestionList: React.FC = () => {
	const { courseId }: any = useParams();
	const [chatId, setChatId] = useState(null);

	const qaAPI = new API(urls.qa);

	const { isOpen: isListOpen, onToggle: onListToggle } = useDisclosure({
		defaultIsOpen: true,
	});
	const { isOpen: isChatOpen, onToggle: onChatToggle } = useDisclosure();

	const qaQuery = useQuery([`qa${courseId}`, courseId], () => qaAPI.list());

	const onBackPress = () => {
		onChatToggle();
		onListToggle();
	};

	const onQuestionPress = (id: number) => {
		setChatId(id);
		onChatToggle();
		onListToggle();
	};

	if (qaQuery.isSuccess) {
		return (
			<>
				<Slide
					direction="left"
					in={isListOpen}
					style={{ position: 'absolute' }}>
					<Stack
						direction="column"
						spacing="1"
						h="full"
						justify="space-between">
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
							{qaQuery.data.map((question: QuestionAnswerSchema) => (
								<Stack
									key={question.id}
									direction="row"
									align="center"
									justify="space-between"
									spacing="4"
									borderBottom="1px"
									borderBottomColor="gray.100"
									px="4"
									py="2"
									onClick={() => onQuestionPress(question.id)}>
									<Stack direction="column" spacing="2">
										<Heading fontSize="sm">{question.content}</Heading>
										<Text fontSize="x-small" color="gray.500">
											{__('2 Answers', 'masteriyo')}
										</Text>
									</Stack>
									<Icon
										as={BiChevronRight}
										fontSize="x-large"
										color="gray.600"
									/>
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
				{chatId && (
					<QaChat
						isOpen={isChatOpen}
						onBackPress={onBackPress}
						chatId={chatId}
					/>
				)}
			</>
		);
	}

	return (
		<Center h="full">
			<Spinner
				size="lg"
				color="blue.500"
				emptyColor="gray.200"
				thickness="3px"
			/>
		</Center>
	);
};

export default QuestionList;
