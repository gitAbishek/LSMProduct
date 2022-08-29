import {
	Box,
	Button,
	ButtonGroup,
	Center,
	FormControl,
	Icon,
	Input,
	Spinner,
	Stack,
	Text,
} from '@chakra-ui/react';
import { sprintf, _nx, __ } from '@wordpress/i18n';
import React from 'react';
import { useForm } from 'react-hook-form';
import { BiChevronLeft } from 'react-icons/bi';
import { useMutation, useQuery, useQueryClient } from 'react-query';
import { useParams } from 'react-router-dom';
import urls from '../../../back-end/constants/urls';
import API from '../../../back-end/utils/api';
import { QuestionAnswerSchema } from '../../schemas';
import Message from './Message';

/*
TODO: implement infinite scroll on chat
*/
interface Props {
	isOpen: boolean;
	onBackPress: any;
	chatData: { parentId: number; name: string; answerCount: number };
}
const QaChat: React.FC<Props> = (props) => {
	const { isOpen, onBackPress, chatData } = props;
	const { courseId }: any = useParams();

	const queryClient = useQueryClient();

	const { handleSubmit, register, reset } = useForm<{ content: string }>();

	const qaAPI = new API(urls.qa);
	const chatQuery = useQuery(
		[`chat${chatData.parentId}`, chatData.parentId],
		() =>
			qaAPI.list({
				course_id: courseId,
				parent: chatData.parentId,
				per_page: -1,
			}),
		{
			refetchInterval: 60000,
		}
	);

	const addNewChat = useMutation(
		(data: { content: string; course_id: number; parent: number }) =>
			qaAPI.store(data),
		{
			onSuccess: () => {
				reset({});
				queryClient.invalidateQueries(`chat${chatData.parentId}`);
			},
		}
	);

	const onSubmit = (data: { content: string }) => {
		addNewChat.mutate({
			content: data.content,
			course_id: courseId,
			parent: chatData.parentId,
		});
	};

	if (chatQuery.isSuccess) {
		return (
			<Stack
				flex="0 0 100%"
				direction="column"
				spacing="8"
				justify="space-between"
				h="full"
				transition="all 0.35s"
				transform={`translateX(${isOpen ? '-100%' : '0'})`}>
				<Stack direction="column" spacing="8" overflowY="hidden">
					<Box as="header">
						<ButtonGroup px="4" py="2">
							<Button
								leftIcon={<Icon fontSize="xl" as={BiChevronLeft} />}
								variant="link"
								onClick={onBackPress}>
								{__('Back', 'masteriyo')}
							</Button>
						</ButtonGroup>
						<Stack direction="column" p="4" bg="gray.50" spacing="1">
							<Text fontWeight="bold">{chatData.name}</Text>
							<Text fontSize="x-small" color="gray.400">
								{sprintf(
									_nx(
										'%d Answer',
										'%d Answers',
										chatData.answerCount,
										'number of answers',
										'masteriyo'
									),
									chatData.answerCount
								)}
							</Text>
						</Stack>
					</Box>

					<Stack direction="column-reverse" spacing="4" px="4" overflowY="auto">
						{chatQuery?.data?.data.map((chat: QuestionAnswerSchema) => (
							<Message
								key={chat?.id}
								name={chat?.user_name}
								avatar={chat?.user_avatar}
								message={chat?.content}
								sender={chat?.sender}
								time={chat?.created_at}
								byCurrentUser={chat?.by_current_user}
							/>
						))}
					</Stack>
				</Stack>
				<form onSubmit={handleSubmit(onSubmit)}>
					<Stack direction="column" spacing="3" w="full" px="4" pb="6">
						<FormControl>
							<Input
								type="text"
								fontSize="xs"
								{...register('content', { required: true })}
								disabled={addNewChat.isLoading}
							/>
						</FormControl>
						<Button type="submit" isFullWidth isLoading={addNewChat.isLoading}>
							{__('Send', 'masteriyo')}
						</Button>
					</Stack>
				</form>
			</Stack>
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

export default QaChat;
