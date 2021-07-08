import {
	Box,
	Button,
	ButtonGroup,
	Center,
	FormControl,
	Icon,
	Slide,
	Spinner,
	Stack,
	Text,
	Textarea,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { useForm } from 'react-hook-form';
import { BiChevronLeft } from 'react-icons/bi';
import { useMutation, useQuery, useQueryClient } from 'react-query';
import { useParams } from 'react-router-dom';
import urls from '../../../back-end/constants/urls';
import API from '../../../back-end/utils/api';
import { QuestionAnswerSchema } from '../../schemas';
import Message from './Message';

interface Props {
	isOpen: boolean;
	onBackPress: any;
	parentId: number;
}
const QaChat: React.FC<Props> = (props) => {
	const { isOpen, onBackPress, parentId } = props;
	const { courseId }: any = useParams();

	const queryClient = useQueryClient();

	const { handleSubmit, register, reset } = useForm<{ content: string }>();

	const qaAPI = new API(urls.qa);
	const chatQuery = useQuery(
		[`chat${parentId}`, parentId],
		() =>
			qaAPI.list({
				course_id: courseId,
				parent: parentId,
			}),
		{
			refetchInterval: 1000,
		}
	);

	const addNewChat = useMutation(
		(data: { content: string; course_id: number; parent: number }) =>
			qaAPI.store(data),
		{
			onSuccess: () => {
				reset({});
				queryClient.invalidateQueries(`chat${parentId}`);
			},
		}
	);

	const onSubmit = (data: { content: string }) => {
		addNewChat.mutate({
			content: data.content,
			course_id: courseId,
			parent: parentId,
		});
	};

	if (chatQuery.isSuccess) {
		return (
			<Slide direction="right" in={isOpen} style={{ position: 'absolute' }}>
				<Stack direction="column" spacing="8" justify="space-between" h="full">
					<Stack direction="column" spacing="8">
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
								<Text fontWeight="bold">What is an instructional video?</Text>
								<Text fontSize="x-small" color="gray.400">
									3 answers
								</Text>
							</Stack>
						</Box>

						<Stack direction="column" spacing="4" px="4">
							{chatQuery.data.map((chat: QuestionAnswerSchema) => (
								<Message
									key={chat.id}
									name={chat.user_name}
									avatar="https://i.pravatar.cc/150?img=3"
									message={chat.content}
									sender={chat.sender}
									time={chat.created_at}
									byCurrentUser={chat.by_current_user}
								/>
							))}
						</Stack>
					</Stack>
					<form onSubmit={handleSubmit(onSubmit)}>
						<Stack direction="column" spacing="3" w="full" p="4" pb="6">
							<FormControl>
								<Textarea
									{...register('content', { required: true })}
									disabled={addNewChat.isLoading}
								/>
							</FormControl>
							<Button
								colorScheme="blue"
								type="submit"
								isFullWidth
								isLoading={addNewChat.isLoading}>
								{__('Send', 'masteriyo')}
							</Button>
						</Stack>
					</form>
				</Stack>
			</Slide>
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

export default QaChat;
