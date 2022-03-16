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
	HStack,
	Icon,
	IconButton,
	Link,
	Menu,
	MenuButton,
	MenuItem,
	MenuList,
	Radio,
	RadioGroup,
	Select,
	Stack,
	Text,
	Tooltip,
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
import { FaCheckCircle } from 'react-icons/fa';
import { useMutation, useQuery, useQueryClient } from 'react-query';
import { Link as RouterLink } from 'react-router-dom';
import { Td, Tr } from 'react-super-responsive-table';
import { infoIconStyles } from '../../../../config/styles';
import routes from '../../../../constants/routes';
import urls from '../../../../constants/urls';
import { UserSchema } from '../../../../schemas';
import API from '../../../../utils/api';
import { getLocalTime } from '../../../../utils/utils';

interface Props {
	data: UserSchema;
}

const InstructorList: React.FC<Props> = (props) => {
	const { data } = props;
	const [isDeleteModalOpen, setDeleteModalOpen] = useState(false);
	const queryClient = useQueryClient();
	const usersAPI = new API(urls.instructors);
	const allUsersAPI = new API(urls.users);
	const cancelDeleteModalRef = useRef<any>();
	const toast = useToast();
	const [reassignID, setReassignID] = useState(0);
	const usersQuery = useQuery(
		'allUsers',
		() =>
			allUsersAPI.list({
				per_page: -1,
				order: 'asc',
				orderby: 'display_name',
			}),
		{
			onSuccess: (data: any) => {
				// Set first user id to reassign ID as it is selected on top of option.
				setReassignID(data?.data?.[0]?.id || 0);
			},
		}
	);

	const renderUsersOption = () => {
		try {
			return usersQuery?.data?.data.map(
				(user: { id: number; display_name: string; nicename: string }) => {
					// Do not include same user in the users option.
					if (user.id === data.id) {
						return;
					}
					return (
						<option value={user.id} key={user.id}>
							{user.display_name} ({user.nicename})
						</option>
					);
				}
			);
		} catch (error) {
			return;
		}
	};

	const deleteUser = useMutation(
		(id: number) => usersAPI.delete(id, { reassign: reassignID }),
		{
			onSuccess: () => {
				setDeleteModalOpen(false);
				toast({
					title: __('User deleted successfully.', 'masteriyo'),
					description: `#${data.id} ${__(
						' has been deleted successfully.',
						'masteriyo'
					)}`,
					isClosable: true,
					status: 'success',
				});
				queryClient.invalidateQueries('instructorsList');
			},
		}
	);

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
							to={routes.users.instructors.edit.replace(
								':userId',
								data?.id.toString()
							)}
							fontWeight="semibold"
							fontSize="sm"
							_hover={{ color: 'blue.500' }}>
							{`#${data?.id} ${data?.first_name} ${data?.last_name}`}{' '}
						</Link>
						{data?.approved ? (
							<Tooltip
								label={__('Approved Instructor', 'masteriyo')}
								hasArrow
								fontSize="xs">
								<Box as="span" sx={infoIconStyles}>
									<Icon boxSize="3" as={FaCheckCircle} color="green" />
								</Box>
							</Tooltip>
						) : (
							<Tooltip
								label={__('Pending Approval', 'masteriyo')}
								hasArrow
								fontSize="xs">
								<Box as="span" sx={infoIconStyles}>
									<Icon boxSize="3" as={FaCheckCircle} />
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
				<ButtonGroup>
					<RouterLink
						to={routes.users.instructors.edit.replace(
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

				{/* Delete User Dialog */}
				<AlertDialog
					isOpen={isDeleteModalOpen}
					onClose={onDeleteModalClose}
					isCentered
					size="xl"
					leastDestructiveRef={cancelDeleteModalRef}>
					<AlertDialogOverlay>
						<AlertDialogContent>
							<AlertDialogHeader>
								{__('Delete User', 'masteriyo')} {data?.display_name}
							</AlertDialogHeader>
							<AlertDialogBody>
								<Text fontSize="14px">
									{__(
										'What should be done with content owned by this user?',
										'masteriyo'
									)}
								</Text>
								<RadioGroup defaultValue="reassign" mt="4" mb="4">
									<Stack spacing="3">
										<HStack>
											<Radio
												value="reassign"
												onChange={() =>
													queryClient.invalidateQueries('allUsers')
												}>
												<Text>
													{__('Attribute all content to:', 'masteriyo')}
												</Text>
											</Radio>
											<Select
												size="sm"
												w="xs"
												value={reassignID}
												bg="none !important"
												onChange={(e: any) => setReassignID(e.target.value)}>
												{renderUsersOption()}
											</Select>
										</HStack>
										<Radio value="" onChange={() => setReassignID(0)}>
											<Text color="red.400">
												{__('Delete all content.', 'masteriyo')}
											</Text>
										</Radio>
									</Stack>
								</RadioGroup>
								<Text fontSize="12px" color="gray.500">
									{__(
										'*Please note that all course progresses and quiz attempts of this user will be deleted and cannot be transfer.',
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
										isLoading={deleteUser.isLoading || usersQuery.isFetching}>
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
