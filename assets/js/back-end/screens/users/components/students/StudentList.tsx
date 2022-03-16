import {
	AlertDialog,
	AlertDialogBody,
	AlertDialogContent,
	AlertDialogFooter,
	AlertDialogHeader,
	AlertDialogOverlay,
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
	Text,
	useToast,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useRef, useState } from 'react';
import {
	BiCalendar,
	BiDotsVerticalRounded,
	BiEdit,
	BiTrash,
} from 'react-icons/bi';
import { useMutation, useQueryClient } from 'react-query';
import { Link as RouterLink } from 'react-router-dom';
import { Td, Tr } from 'react-super-responsive-table';
import routes from '../../../../constants/routes';
import urls from '../../../../constants/urls';
import { UserSchema } from '../../../../schemas';
import API from '../../../../utils/api';
import { getLocalTime } from '../../../../utils/utils';

interface Props {
	data: UserSchema;
}

const StudentList: React.FC<Props> = (props) => {
	const { data } = props;
	const [isDeleteModalOpen, setDeleteModalOpen] = useState(false);
	const queryClient = useQueryClient();
	const usersAPI = new API(urls.users);
	const cancelDeleteModalRef = useRef<any>();
	const toast = useToast();

	const deleteUser = useMutation((id: number) => usersAPI.delete(id), {
		onSuccess: () => {
			setDeleteModalOpen(false);
			toast({
				title: __('User deleted', 'masteriyo'),
				description: `#${data.id} ${__(
					' has been deleted successfully.',
					'masteriyo'
				)}`,
				isClosable: true,
				status: 'success',
			});
			queryClient.invalidateQueries('usersList');
		},
	});

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
					<Link
						as={RouterLink}
						to={routes.users.students.edit.replace(
							':userId',
							data?.id.toString()
						)}
						fontWeight="semibold"
						fontSize="sm"
						_hover={{ color: 'blue.500' }}>
						{`#${data?.id} ${data?.first_name} ${data?.last_name}`}
					</Link>
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
				<ButtonGroup>
					<RouterLink
						to={routes.users.students.edit.replace(
							':userId',
							data?.id.toString()
						)}>
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

				{/* Delete student Dialog */}
				<AlertDialog
					isOpen={isDeleteModalOpen}
					onClose={onDeleteModalClose}
					isCentered
					leastDestructiveRef={cancelDeleteModalRef}>
					<AlertDialogOverlay>
						<AlertDialogContent>
							<AlertDialogHeader>
								{__('Delete User', 'masteriyo')} {data?.display_name}
							</AlertDialogHeader>
							<AlertDialogBody>
								{__(
									'Are you sure? You can’t restore after deleting.',
									'masteriyo'
								)}
								<Text fontSize="12px" color="gray.500" mt="4">
									{__(
										'*Please note that all course progresses and quiz attempts of this user will be deleted.',
										'masteriyo'
									)}
								</Text>
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

export default StudentList;
