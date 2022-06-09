import {
	AlertDialog,
	AlertDialogBody,
	AlertDialogContent,
	AlertDialogFooter,
	AlertDialogHeader,
	AlertDialogOverlay,
	Avatar,
	Button,
	ButtonGroup,
	Collapse,
	Divider,
	FormControl,
	FormErrorMessage,
	FormLabel,
	Heading,
	IconButton,
	Menu,
	MenuButton,
	MenuItem,
	MenuList,
	Stack,
	Text,
	Textarea,
	Tooltip,
	useDisclosure,
	useToast,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import dayjs from 'dayjs';
import React from 'react';
import { useForm } from 'react-hook-form';
import { BiDotsVerticalRounded, BiEdit, BiTrash } from 'react-icons/bi';
import { useMutation, useQueryClient } from 'react-query';
import { Td, Tr } from 'react-super-responsive-table';
import urls from '../../../constants/urls';
import { CourseReviewSchema } from '../../../schemas';
import API from '../../../utils/api';
import { deepClean } from '../../../utils/utils';

interface Props {
	reply: CourseReviewSchema;
}

const ReplyList: React.FC<Props> = (props) => {
	const {
		reply: { id, author_email, description, date_created },
	} = props;

	const cancelRef = React.useRef<any>();

	const { onClose, isOpen, onOpen } = useDisclosure();
	const reviewAPI = new API(urls.reviews);
	const queryClient = useQueryClient();
	const toast = useToast();
	const [open, setOpen] = React.useState(false);

	const {
		handleSubmit,
		register,
		formState: { errors },
	} = useForm({
		defaultValues: {
			content: description,
		},
	});

	const deleteReviewReply = useMutation(
		() => reviewAPI.delete(id, { force: true, children: true }),
		{
			onSuccess: () => {
				queryClient.invalidateQueries('reviewList');
				toast({
					title: __('Reply Deleted', 'masteriyo'),
					isClosable: true,
					status: 'success',
				});
			},
		}
	);

	const updateReview = useMutation(
		(data: CourseReviewSchema) => reviewAPI.update(data.id, data),
		{
			onSuccess: () => {
				setOpen(false);
				toast({
					title: __('Review Reply updated', 'masteriyo'),
					isClosable: true,
					status: 'success',
				});
				queryClient.invalidateQueries(`reviewList`);
			},
			onError: (error: any) => {
				toast({
					description: `${error?.response?.data?.message}`,
					isClosable: true,
					status: 'error',
				});
			},
		}
	);

	const onSubmit = (data: { content: string }) => {
		const { content } = data;
		updateReview.mutate(deepClean({ ...props.reply, content }));
	};

	return (
		<>
			<Tr>
				<Td>
					<Stack direction="row" spacing="3">
						<Avatar size="xs" />
						<Text>{author_email}</Text>
					</Stack>
				</Td>
				<Td width="46%">
					<Tooltip label={description}>
						<Text width={'fit-content'} isTruncated={false} noOfLines={1}>
							{description}
						</Text>
					</Tooltip>
				</Td>
				<Td>{dayjs(date_created).format('MM/DD/YYYY, hh:mm:ss A')}</Td>
				<Td>
					<ButtonGroup>
						<Button
							leftIcon={<BiEdit />}
							colorScheme="blue"
							size="xs"
							onClick={() => setOpen(true)}>
							{__('Edit', 'masteriyo')}
						</Button>
						<Menu placement="bottom-end">
							<MenuButton
								as={IconButton}
								icon={<BiDotsVerticalRounded />}
								variant="outline"
								rounded="sm"
								fontSize="large"
								size="xs"
							/>
							<MenuList>
								<MenuItem
									onClick={() => onOpen()}
									icon={<BiTrash />}
									_hover={{ color: 'red.500' }}>
									{__('Delete', 'masteriyo')}
								</MenuItem>
							</MenuList>
						</Menu>
					</ButtonGroup>
				</Td>
				<AlertDialog
					isOpen={isOpen}
					onClose={onClose}
					isCentered
					leastDestructiveRef={cancelRef}>
					<AlertDialogOverlay>
						<AlertDialogContent>
							<AlertDialogHeader>
								{__('Deleting Reply', 'masteriyo')} {name}
							</AlertDialogHeader>
							<AlertDialogBody>
								{__(
									'Are you sure? You can’t restore after deleting.',
									'masteriyo'
								)}
							</AlertDialogBody>
							<AlertDialogFooter>
								<ButtonGroup>
									<Button onClick={onClose} variant="outline" ref={cancelRef}>
										{__('Cancel', 'masteriyo')}
									</Button>
									<Button
										colorScheme="red"
										isLoading={deleteReviewReply.isLoading}
										onClick={() => deleteReviewReply.mutate()}>
										{__('Delete', 'masteriyo')}
									</Button>
								</ButtonGroup>
							</AlertDialogFooter>
						</AlertDialogContent>
					</AlertDialogOverlay>
				</AlertDialog>
			</Tr>
			<Tr>
				<Td colSpan={4}>
					<Collapse in={open}>
						<Stack textAlign="left" direction="column" spacing="3">
							<Heading as="h4" size="md">
								{__('Edit', 'masteriyo')}
							</Heading>
							<form onSubmit={handleSubmit(onSubmit)}>
								<FormControl isInvalid={!!errors?.content}>
									<FormLabel>{__('Description', 'masteriyo')}</FormLabel>
									<Textarea
										placeholder={__('Your Content', 'masteriyo')}
										{...register('content', {
											required: __(
												'You must provide a content for the review.',
												'masteriyo'
											),
										})}
									/>
									<FormErrorMessage>
										{errors && errors.content && errors.content.message}
									</FormErrorMessage>
								</FormControl>
								<Divider />
								<Stack direction={'row'} mt={4}>
									<Button
										type="submit"
										w="18%"
										colorScheme="blue"
										isLoading={updateReview.isLoading}>
										{__('Update Reply', 'masteriyo')}
									</Button>
									<Button
										w="18%"
										variant="outline"
										onClick={() => setOpen(false)}>
										{__('Cancel', 'masteriyo')}
									</Button>
								</Stack>
							</form>
						</Stack>
					</Collapse>
				</Td>
			</Tr>
		</>
	);
};

export default ReplyList;
