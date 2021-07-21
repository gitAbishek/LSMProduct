import {
	AlertDialog,
	AlertDialogBody,
	AlertDialogContent,
	AlertDialogFooter,
	AlertDialogHeader,
	AlertDialogOverlay,
	Avatar,
	Badge,
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
	Td,
	Text,
	Tr,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useRef, useState } from 'react';
import {
	BiCalendar,
	BiDotsVerticalRounded,
	BiEdit,
	BiShow,
	BiTrash,
} from 'react-icons/bi';
import { useMutation, useQueryClient } from 'react-query';
import { Link as RouterLink } from 'react-router-dom';
import routes from '../../../constants/routes';
import urls from '../../../constants/urls';
import API from '../../../utils/api';

interface Props {
	id: number;
	name: string;
	price?: any;
	categories?: any;
	permalink: string;
	createdOn: string;
	author: { id: number; display_name: string; avatar_url: string };
}

const CourseList: React.FC<Props> = (props) => {
	const { id, name, price, categories, permalink, createdOn, author } = props;
	const queryClient = useQueryClient();
	const [isDeleteModalOpen, setDeleteModalOpen] = useState(false);
	const courseAPI = new API(urls.courses);
	const cancelRef = useRef<any>();
	const createdOnDate = createdOn.split(' ')[0];

	const deleteCourse = useMutation((id: number) => courseAPI.delete(id), {
		onSuccess: () => {
			setDeleteModalOpen(false);
			queryClient.invalidateQueries('courseList');
		},
	});

	const onDeletePress = () => {
		setDeleteModalOpen(true);
	};

	const onDeleteModalClose = () => {
		setDeleteModalOpen(false);
	};

	const onDeleteConfirm = () => {
		deleteCourse.mutate(id);
	};

	return (
		<Tr>
			<Td>
				<Link
					as={RouterLink}
					to={routes.courses.edit.replace(':courseId', id.toString())}
					fontWeight="semibold"
					_hover={{ color: 'blue.500' }}>
					{name}
				</Link>
			</Td>
			<Td>
				<Stack direction="row">
					{categories.map((category: any, index: any) => (
						<Badge key={index}>{category.name}</Badge>
					))}
				</Stack>
			</Td>
			<Td>
				<Stack direction="row" spacing="2" alignItems="center">
					<Avatar src={author.avatar_url} size="xs" />
					<Text fontSize="xs" fontWeight="medium" color="gray.600">
						{author.display_name}
					</Text>
				</Stack>
			</Td>
			<Td>{price === undefined || price < 1 ? <Badge>Free</Badge> : price}</Td>
			<Td>
				<Stack direction="row" spacing="2" alignItems="center" color="gray.600">
					<Icon as={BiCalendar} />
					<Text fontSize="xs" fontWeight="medium">
						{createdOnDate}
					</Text>
				</Stack>
			</Td>
			<Td>
				<ButtonGroup>
					<RouterLink
						to={routes.courses.edit.replace(':courseId', id.toString())}>
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
							<Link href={permalink} isExternal>
								<MenuItem icon={<BiShow />}>
									{__('Preview', 'masteriyo')}
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
								{__('Delete Lesson')} {name}
							</AlertDialogHeader>
							<AlertDialogBody>
								{__(
									"Are you sure? You can't restore this section",
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
										isLoading={deleteCourse.isLoading}>
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

export default CourseList;
