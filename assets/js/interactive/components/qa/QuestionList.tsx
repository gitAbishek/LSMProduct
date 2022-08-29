import {
	Alert,
	AlertIcon,
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
import { sprintf, _nx, __ } from '@wordpress/i18n';
import React, { useState } from 'react';
import { useForm } from 'react-hook-form';
import { BiChevronRight, BiSearch } from 'react-icons/bi';
import { useMutation, useQuery, useQueryClient } from 'react-query';
import { useParams } from 'react-router-dom';
import { useOnType } from 'use-ontype';
import urls from '../../../back-end/constants/urls';
import API from '../../../back-end/utils/api';
import { QuestionAnswerSchema } from '../../schemas';
import localized from '../../utils/global';
import QaChat from './QaChat';

/*
TODO: implement infinite scroll on question list
*/

const QuestionList: React.FC = () => {
	const { courseId }: any = useParams();
	const [searchQuery, setSearchQuery] = useState<string>();
	const toast = useToast();
	const queryClient = useQueryClient();
	const [chatData, setChatData] = useState<any>(null);
	const {
		handleSubmit,
		register,
		reset,
		formState: { errors },
	} = useForm<{ content: string; search: string }>();

	const qaAPI = new API(urls.qa);

	const { isOpen: isListOpen, onToggle: onListToggle } = useDisclosure({
		defaultIsOpen: true,
	});
	const { isOpen: isChatOpen, onToggle: onChatToggle } = useDisclosure();

	const qaQuery = useQuery(
		[`qa${courseId}`, courseId, searchQuery],
		() =>
			qaAPI.list({
				course_id: courseId,
				parent: 0,
				per_page: -1,
				search: searchQuery,
			}),
		{
			useErrorBoundary: false,
			retry: false,
			retryOnMount: false,
			// keepPreviousData: true,
			refetchInterval: 10000,
		}
	);

	const onSearchInput = useOnType(
		{
			onTypeFinish: (val: string) => {
				setSearchQuery(val);
			},
		},
		800
	);

	const addNewQuestion = useMutation(
		(data: { content: string; course_id: number }) => qaAPI.store(data),
		{
			onSuccess: () => {
				toast({
					title: __('Your question has been asked.', 'masteriyo'),
					description: __(
						'You will get your answer as soon as possible.',
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
			<Stack direction="row" spacing="0" overflow="hidden" h="full">
				<Stack
					flex="0 0 100%"
					direction="column"
					spacing="1"
					h="full"
					transition="all 0.35s"
					justify="space-between"
					transform={`translateX(${isListOpen ? '0' : '-100%'})`}>
					<Stack direction="column" spacing="0" overflow="hidden">
						<Box p="4">
							<FormControl>
								<InputGroup>
									<Input
										defaultValue={searchQuery}
										placeholder={__('Search a Question', 'masteriyo')}
										{...onSearchInput}
									/>
									<InputRightElement>
										<Icon as={BiSearch} />
									</InputRightElement>
								</InputGroup>
							</FormControl>
						</Box>
						<Stack direction="column" spacing="0" flex="1" overflowY="auto">
							{qaQuery?.data?.data.map(
								(question: QuestionAnswerSchema, index: number) => (
									<Link
										key={index}
										_hover={{
											textDecor: 'none',
											bg: 'primary.50',
											color: 'primary.500',
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
													question?.id,
													question?.content,
													question?.answers_count
												)
											}>
											<Stack direction="column" spacing="2">
												<Heading fontSize="sm">{question.content}</Heading>
												<Text fontSize="x-small" color="gray.500">
													{sprintf(
														_nx(
															'%d Answer',
															'%d Answers',
															question?.answers_count,
															'number of answers',
															'masteriyo'
														),
														question?.answers_count
													)}
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
								)
							)}
						</Stack>
					</Stack>
					<form onSubmit={handleSubmit(onSubmit)}>
						<Stack direction="column" spacing="3" w="full" p="4" pb="6">
							<FormControl isInvalid={!!errors.content}>
								<Input
									type="text"
									fontSize="xs"
									placeholder={__('What is your question?', 'masteriyo')}
									disabled={addNewQuestion.isLoading}
									{...register('content', {
										required: __('Please write your message.', 'masteriyo'),
									})}
								/>
								<FormErrorMessage>
									{errors?.content && errors?.content?.message}
								</FormErrorMessage>
							</FormControl>
							<Button
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
			</Stack>
		);
	}

	if (!localized.isUserLoggedIn) {
		return (
			<Alert mt="6" status="warning">
				<AlertIcon />
				<Text color="gray.600" fontSize="xs">
					{__(
						'You must be logged in to ask question. You can register from',
						'masteriyo'
					)}
					<Link isExternal color="primary.500" href={localized.urls.account}>
						{__(' here.', 'masteriyo')}
					</Link>
				</Text>
			</Alert>
		);
	}

	return (
		<Center h="full">
			<Spinner
				size="lg"
				color="primary.500"
				emptyColor="gray.200"
				thickness="3px"
			/>
		</Center>
	);
};

export default QuestionList;
