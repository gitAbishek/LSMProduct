import {
	Box,
	Button,
	Center,
	FormControl,
	FormErrorMessage,
	Heading,
	Icon,
	Input,
	InputGroup,
	InputRightElement,
	Link,
	Spinner,
	Stack,
	Text,
	useDisclosure,
	useToast,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useState } from 'react';
import { useForm } from 'react-hook-form';
import { BiChevronRight, BiSearch } from 'react-icons/bi';
import { useMutation, useQuery, useQueryClient } from 'react-query';
import { useParams } from 'react-router-dom';
import urls from '../../../back-end/constants/urls';
import API from '../../../back-end/utils/api';
import { QuestionAnswerSchema } from '../../schemas';
import QaChat from './QaChat';

const QuestionList: React.FC = () => {
	const { courseId }: any = useParams();
	const toast = useToast();
	const queryClient = useQueryClient();
	const [chatData, setChatData] = useState<any>(null);
	const {
		handleSubmit,
		register,
		reset,
		formState: { errors },
	} = useForm<{ content: string }>();

	const qaAPI = new API(urls.qa);

	const { isOpen: isListOpen, onToggle: onListToggle } = useDisclosure({
		defaultIsOpen: true,
	});
	const { isOpen: isChatOpen, onToggle: onChatToggle } = useDisclosure();

	const qaQuery = useQuery([`qa${courseId}`, courseId], () =>
		qaAPI.list({
			course_id: courseId,
			parent: 0,
		})
	);

	const addNewQuestion = useMutation(
		(data: { content: string; course_id: number }) => qaAPI.store(data),
		{
			onSuccess: () => {
				toast({
					title: __('Your question has been asked', 'masteriyo'),
					description: __(
						'You will get your answer as soon as possible',
						'masteriyo'
					),
					status: 'success',
					isClosable: true,
				});
				reset({});
				queryClient.invalidateQueries(`qa${courseId}`);
			},
		}
	);
	const onBackPress = () => {
		onChatToggle();
		onListToggle();
	};

	const onQuestionPress = (id: number, name: string, answerCount: number) => {
		setChatData({ parentId: id, name: name, answerCount: answerCount });
		onChatToggle();
		onListToggle();
	};

	const onSubmit = (data: { content: string }) => {
		addNewQuestion.mutate({ content: data.content, course_id: courseId });
	};

	if (qaQuery.isSuccess) {
		return (
			<>
				<Stack direction="column" spacing="1" h="full" justify="space-between">
					<Stack direction="column" spacing="0">
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
							<Link
								key={question.id}
								_hover={{
									textDecor: 'none',
									bg: 'blue.50',
									color: 'blue.500',
									'.chakra-icon': {
										transform: 'translateX(5px)',
									},
								}}>
								<Stack
									direction="row"
									align="center"
									justify="space-between"
									spacing="4"
									borderBottom="1px"
									borderBottomColor="gray.100"
									px="4"
									py="2"
									onClick={() =>
										onQuestionPress(
											question.id,
											question.content,
											question.answers_count
										)
									}>
									<Stack direction="column" spacing="2">
										<Heading fontSize="sm">{question.content}</Heading>
										<Text fontSize="x-small" color="gray.500">
											{question.answers_count + __(' Answers', 'masteriyo')}
										</Text>
									</Stack>
									<Icon
										transition="all 0.35s ease-in-out"
										as={BiChevronRight}
										fontSize="x-large"
										color="gray.600"
									/>
								</Stack>
							</Link>
						))}
					</Stack>
					<form onSubmit={handleSubmit(onSubmit)}>
						<Stack direction="column" spacing="3" w="full" p="4" pb="6">
							<FormControl isInvalid={!!errors.content}>
								<Input
									type="text"
									fontSize="xs"
									placeholder="What is your question?"
									disabled={addNewQuestion.isLoading}
									{...register('content', {
										required: __('Please write your message'),
									})}
								/>
								<FormErrorMessage>
									{errors?.content && errors?.content?.message}
								</FormErrorMessage>
							</FormControl>
							<Button
								colorScheme="blue"
								type="submit"
								isFullWidth
								isLoading={addNewQuestion.isLoading}>
								{__('Ask a Question', 'masteriyo')}
							</Button>
						</Stack>
					</form>
				</Stack>

				{chatData && (
					<QaChat
						isOpen={isChatOpen}
						onBackPress={onBackPress}
						chatData={chatData}
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
