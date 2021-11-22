import {
	AlertDialog,
	AlertDialogBody,
	AlertDialogContent,
	AlertDialogFooter,
	AlertDialogHeader,
	AlertDialogOverlay,
	Box,
	Button,
	ButtonGroup,
	Icon,
	IconButton,
	Link,
	Menu,
	MenuButton,
	MenuItem,
	MenuList,
	Stack,
	Switch,
	Text,
	Tooltip,
	useToast,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useRef, useState } from 'react';
import { useForm } from 'react-hook-form';
import {
	BiCalendar,
	BiDotsVerticalRounded,
	BiEdit,
	BiTrash,
} from 'react-icons/bi';
import { FaCheckCircle } from 'react-icons/fa';
import { useMutation, useQueryClient } from 'react-query';
import { Link as RouterLink } from 'react-router-dom';
import { Td, Tr } from 'react-super-responsive-table';
import { infoIconStyles } from '../../../../config/styles';
import routes from '../../../../constants/routes';
import urls from '../../../../constants/urls';
import { UserSchema } from '../../../../schemas';
import API from '../../../../utils/api';
import { deepClean, getLocalTime } from '../../../../utils/utils';

interface Props {
	data: UserSchema;
}

const InstructorList: React.FC<Props> = (props) => {
	const { data } = props;
	const [isDeleteModalOpen, setDeleteModalOpen] = useState(false);
	const [isStatusModalOpen, setStatusModalOpen] = useState(false);
	const queryClient = useQueryClient();
	const usersAPI = new API(urls.instructors);
	const cancelDeleteModalRef = useRef<any>();
	const cancelStatusModalRef = useRef<any>();
	const toast = useToast();
	const { register, handleSubmit } = useForm();
	const [isChecked, setIsChecked] = useState(data?.approved);

	const deleteUser = useMutation((id: number) => usersAPI.delete(id), {
		onSuccess: () => {
			setDeleteModalOpen(false);
			toast({
				title: __('User deleted successfully', 'masteriyo'),
				description: `#${data.id} ${__(
					' has been deleted successfully',
					'masteriyo'
				)}`,
				isClosable: true,
				status: 'success',
			});
			queryClient.invalidateQueries('instructorsList');
		},
	});

	const updateUser = useMutation(
		(datas: object) => usersAPI.update(data?.id, datas),
		{
			onSuccess: (data: any) => {
				setStatusModalOpen(false);
				toast({
					title: data?.approved
						? __('User approved successfully', 'masteriyo')
						: __('User unapproved successfully', 'masteriyo'),
					description: data?.approved
						? `${data.display_name} ${__(
								' has been approved successfully',
								'masteriyo'
						  )}`
						: `${data.display_name} ${__(
								' has been unapproved successfully',
								'masteriyo'
						  )}`,
					isClosable: true,
					status: 'success',
				});
				queryClient.invalidateQueries('instructorsList');
			},
			onError: (error: any) => {
				toast({
					description: `${error.response?.data?.message}`,
					isClosable: true,
					status: 'error',
				});
			},
		}
	);

	const onStatusChange = (e: any) => {
		setIsChecked(e.target.checked);
		setStatusModalOpen(true);
	};

	const onStatusModalClose = () => {
		setStatusModalOpen(false);
	};

	const onSubmit = (data: any) => {
		updateUser.mutate(deepClean(data));
	};

	const onDeletePress = () => {
		setDeleteModalOpen(true);
	};
	const onDeleteModalClose = () => {
		setDeleteModalOpen(false);
	};
	const onDeleteConfirm = () => {
		deleteUser.mutate(data?.id);
	};

	return (
		<Tr>
			<Td>
				<Stack direction="column">
					<Stack direction="row" spacing="1">
						<Link
							as={RouterLink}
							to={routes.users.edit.replace(':userId', data?.id.toString())}
							fontWeight="semibold"
							fontSize="sm"
							_hover={{ color: 'blue.500' }}>
							{`#${data?.id} ${data?.first_name} ${data?.last_name}`}{' '}
						</Link>
						{data?.approved && (
							<Tooltip
								label={__('Approved Instructor', 'masteriyo')}
								hasArrow
								fontSize="xs">
								<Box as="span" sx={infoIconStyles}>
									<Icon boxSize="3" as={FaCheckCircle} color="green" />
								</Box>
							</Tooltip>
						)}
					</Stack>

					<Text fontSize="xs" color="gray.600">
						{data?.display_name}
					</Text>
				</Stack>
			</Td>
			<Td>
				<Text fontSize="sm" color="gray.600">
					{data?.email}
				</Text>
			</Td>
			<Td>
				<Stack direction="row" spacing="2" alignItems="center" color="gray.600">
					<Icon as={BiCalendar} />
					<Text fontSize="sm" color="gray.600">
						{getLocalTime(data?.date_created).toLocaleString()}
					</Text>
				</Stack>
			</Td>
			<Td>
				<form>
					<Stack direction="row" spacing="2">
						<Tooltip
							label={__(
								'Approve or Unapproved the user as instructor.',
								'masteriyo'
							)}
							hasArrow
							fontSize="xs">
							<Box as="span" sx={infoIconStyles}>
								<Switch
									colorScheme="green"
									{...register('approved')}
									onChangeCapture={onStatusChange}
									isChecked={isChecked}
								/>
							</Box>
						</Tooltip>

						{/* Status Change Dialog */}
						<AlertDialog
							isOpen={isStatusModalOpen}
							onClose={onStatusModalClose}
							isCentered
							leastDestructiveRef={cancelStatusModalRef}>
							<AlertDialogOverlay>
								<AlertDialogContent>
									<AlertDialogHeader>
										{isChecked
											? `${__('Approve', 'masteriyo')} ${data?.display_name}`
											: `${__('Unapproved', 'masteriyo')} ${
													data?.display_name
											  }`}
									</AlertDialogHeader>
									<AlertDialogBody>
										{isChecked
											? __(
													'Are you sure? You want to approve the user as instructor.',
													'masteriyo'
											  )
											: __(
													'Are you sure? You want to unapproved the user as instructor.',
													'masteriyo'
											  )}
									</AlertDialogBody>
									<AlertDialogFooter>
										<ButtonGroup>
											<Button
												ref={cancelStatusModalRef}
												onClick={() => {
													onStatusModalClose();
													setIsChecked(data?.approved);
												}}
												variant="outline">
												{__('Cancel', 'masteriyo')}
											</Button>
											<Button
												onClick={handleSubmit(onSubmit)}
												colorScheme="blue"
												isLoading={updateUser.isLoading}>
												{isChecked
													? __('Approve', 'masteriyo')
													: __('Unapprove', 'masteriyo')}
											</Button>
										</ButtonGroup>
									</AlertDialogFooter>
								</AlertDialogContent>
							</AlertDialogOverlay>
						</AlertDialog>
					</Stack>
				</form>
			</Td>
			<Td>
				<ButtonGroup>
					<RouterLink
						to={routes.users.edit.replace(':userId', data?.id.toString())}>
						<Button leftIcon={<BiEdit />} colorScheme="blue" size="xs">
							{__('Edit', 'masteriyo')}
						</Button>
					</RouterLink>
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
							<MenuItem onClick={onDeletePress} icon={<BiTrash />}>
								{__('Delete', 'masteriyo')}
							</MenuItem>
						</MenuList>
					</Menu>
				</ButtonGroup>

				{/* Delete User Dialog */}
				<AlertDialog
					isOpen={isDeleteModalOpen}
					onClose={onDeleteModalClose}
					isCentered
					leastDestructiveRef={cancelDeleteModalRef}>
					<AlertDialogOverlay>
						<AlertDialogContent>
							<AlertDialogHeader>
								{__('Delete User')} {data?.display_name}
							</AlertDialogHeader>
							<AlertDialogBody>
								{__("Are you sure? You can't restore the user.", 'masteriyo')}
							</AlertDialogBody>
							<AlertDialogFooter>
								<ButtonGroup>
									<Button
										ref={cancelDeleteModalRef}
										onClick={onDeleteModalClose}
										variant="outline">
										{__('Cancel', 'masteriyo')}
									</Button>
									<Button
										colorScheme="red"
										onClick={onDeleteConfirm}
										isLoading={deleteUser.isLoading}>
										{__('Delete', 'masteriyo')}
									</Button>
								</ButtonGroup>
							</AlertDialogFooter>
						</AlertDialogContent>
					</AlertDialogOverlay>
				</AlertDialog>
			</Td>
		</Tr>
	);
};

export default InstructorList;
