import {
	AlertDialog,
	AlertDialogBody,
	AlertDialogContent,
	AlertDialogFooter,
	AlertDialogHeader,
	AlertDialogOverlay,
	Button,
	ButtonGroup,
	IconButton,
	Link,
	Menu,
	MenuButton,
	MenuItem,
	MenuList,
	Td,
	Tr,
	useToast,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useRef, useState } from 'react';
import { BiDotsVerticalRounded, BiEdit, BiShow, BiTrash } from 'react-icons/bi';
import { useMutation, useQueryClient } from 'react-query';
import { Link as RouterLink } from 'react-router-dom';

import routes from '../../../constants/routes';
import urls from '../../../constants/urls';
import API from '../../../utils/api';

interface Props {
	id: number;
	name: string;
	description: string;
	slug: string;
	count: Number;
	link: string;
}

const ListRow: React.FC<Props> = (props) => {
	const { id, name, description, slug, count, link } = props;
	const queryClient = useQueryClient();
	const [isDeleteModalOpen, setDeleteModalOpen] = useState(false);
	const toast = useToast();
	const difficultyAPI = new API(urls.difficulties);
	const cancelRef = useRef<any>();

	const deleteDifficulty = useMutation(
		(id: number) => difficultyAPI.delete(id, { force: true }),
		{
			onSuccess: () => {
				setDeleteModalOpen(false);
				queryClient.invalidateQueries('courseDifficultiesList');
			},
			onError: (error: any) => {
				setDeleteModalOpen(false);
				toast({
					title: __('Failed to delete difficulty', 'masteriyo'),
					description: `${error.response?.data?.message}`,
					isClosable: true,
					status: 'error',
				});
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
		deleteDifficulty.mutate(id);
	};

	return (
		<Tr>
			<Td>
				<Link
					as={RouterLink}
					to={routes.course_difficulties.edit.replace(
						':difficultyId',
						id.toString()
					)}
					fontWeight="semibold"
					_hover={{ color: 'blue.500' }}>
					{name}
				</Link>
			</Td>
			<Td
				dangerouslySetInnerHTML={{ __html: description ? description : '—' }}
			/>
			<Td>{slug}</Td>
			<Td>
				<Link href={link} isExternal>
					{count}
				</Link>
			</Td>
			<Td>
				<ButtonGroup>
					<RouterLink
						to={routes.course_difficulties.edit.replace(
							':difficultyId',
							id.toString()
						)}>
						<Button leftIcon={<BiEdit />} colorScheme="blue" size="sm">
							{__('Edit')}
						</Button>
					</RouterLink>
					<Menu placement="bottom-end">
						<MenuButton
							as={IconButton}
							icon={<BiDotsVerticalRounded />}
							variant="outline"
							rounded="sm"
							fontSize="large"
							size="sm"
						/>
						<MenuList>
							<Link href={link} isExternal>
								<MenuItem icon={<BiShow />}>
									{__('View Difficulty', 'masteriyo')}
								</MenuItem>
							</Link>
							<MenuItem onClick={onDeletePress} icon={<BiTrash />}>
								{__('Delete', 'masteriyo')}
							</MenuItem>
						</MenuList>
					</Menu>
				</ButtonGroup>
				<AlertDialog
					isOpen={isDeleteModalOpen}
					onClose={onDeleteModalClose}
					isCentered
					leastDestructiveRef={cancelRef}>
					<AlertDialogOverlay>
						<AlertDialogContent>
							<AlertDialogHeader>
								{__('Delete Difficulty')} {name}
							</AlertDialogHeader>
							<AlertDialogBody>
								{__(
									"Are you sure? You can't restore this difficulty",
									'masteriyo'
								)}
							</AlertDialogBody>
							<AlertDialogFooter>
								<ButtonGroup>
									<Button
										ref={cancelRef}
										onClick={onDeleteModalClose}
										variant="outline">
										{__('Cancel', 'masteriyo')}
									</Button>
									<Button
										colorScheme="red"
										onClick={onDeleteConfirm}
										isLoading={deleteDifficulty.isLoading}>
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

export default ListRow;
