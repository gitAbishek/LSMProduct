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
	Container,
	Icon,
	Link,
	List,
	ListIcon,
	ListItem,
	Stack,
	Table,
	Tbody,
	Th,
	Thead,
	Tr,
	useDisclosure,
	useToast,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useRef, useState } from 'react';
import { BiListUl, BiPlus } from 'react-icons/bi';
import { useMutation, useQuery, useQueryClient } from 'react-query';
import { NavLink, useHistory } from 'react-router-dom';
import EmptyInfo from '../../components/common/EmptyInfo';
import Header from '../../components/common/Header';
import MasteriyoPagination from '../../components/common/MasteriyoPagination';
import {
	navActiveStyles,
	navLinkStyles,
	tableStyles,
} from '../../config/styles';
import routes from '../../constants/routes';
import urls from '../../constants/urls';
import { SkeletonCourseTaxonomy } from '../../skeleton';
import API from '../../utils/api';
import CategoryRow from './components/CategoryRow';

interface FilterParams {
	per_page?: number;
	page?: number;
}

const AllCourseCategories = () => {
	const categoryAPI = new API(urls.categories);
	const queryClient = useQueryClient();
	const toast = useToast();
	const history = useHistory();

	const [filterParams, setFilterParams] = useState<FilterParams>({});
	const categoriesQuery = useQuery(['courseCategoriesList', filterParams], () =>
		categoryAPI.list(filterParams)
	);
	const [deleteCategoryId, setDeleteCategoryId] = useState<number>();
	const { onClose, onOpen, isOpen } = useDisclosure();

	const cancelRef = useRef<any>();

	const deleteCategory = useMutation(
		(id: number) => categoryAPI.delete(id, { force: true }),
		{
			onSuccess: () => {
				onClose();
				queryClient.invalidateQueries('courseCategoriesList');
			},
			onError: (error: any) => {
				onClose();
				toast({
					title: __('Failed to delete category', 'masteriyo'),
					description: `${error.response?.data?.message}`,
					isClosable: true,
					status: 'error',
				});
			},
		}
	);

	const onDeletePress = (categoryId: number) => {
		onOpen();
		setDeleteCategoryId(categoryId);
	};

	const onDeleteConfirm = () => {
		deleteCategoryId && deleteCategory.mutate(deleteCategoryId);
	};

	return (
		<Stack direction="column" spacing="8" alignItems="center">
			<Header
				showLinks
				thirdBtn={{
					label: __('Add New Category', 'masteriyo'),
					action: () => history.push(routes.course_categories.add),
					icon: <Icon as={BiPlus} fontSize="md" />,
				}}>
				<List>
					<ListItem>
						<Link
							as={NavLink}
							sx={navLinkStyles}
							_activeLink={navActiveStyles}
							to={routes.course_categories.list}>
							<ListIcon as={BiListUl} />
							{__('Categories', 'masteriyo')}
						</Link>
					</ListItem>
				</List>
			</Header>
			<Container maxW="container.xl">
				<Stack direction="column" spacing="6">
					<Box bg="white" py="12" shadow="box" mx="auto" w="full">
						<Stack direction="column" spacing="8">
							<Stack direction="column" spacing="8">
								<Table size="sm" sx={tableStyles}>
									<Thead>
										<Tr>
											<Th>{__('Name', 'masteriyo')}</Th>
											<Th>{__('Description', 'masteriyo')}</Th>
											<Th>{__('Slug', 'masteriyo')}</Th>
											<Th>{__('Count', 'masteriyo')}</Th>
											<Th>{__('Actions', 'masteriyo')}</Th>
										</Tr>
									</Thead>
									<Tbody>
										{categoriesQuery.isLoading && <SkeletonCourseTaxonomy />}
										{categoriesQuery.isSuccess &&
										categoriesQuery?.data?.data.length === 0 ? (
											<EmptyInfo message="No categories found." />
										) : (
											categoriesQuery?.data?.data.map((cat: any) => (
												<CategoryRow
													key={cat.id}
													id={cat.id}
													name={cat.name}
													description={cat.description}
													slug={cat.slug}
													count={cat.count}
													link={cat.link}
													onDeletePress={onDeletePress}
												/>
											))
										)}
									</Tbody>
								</Table>
							</Stack>
						</Stack>
					</Box>
					{categoriesQuery.isSuccess &&
						categoriesQuery?.data?.data.length > 0 && (
							<MasteriyoPagination
								metaData={categoriesQuery.data.meta}
								setFilterParams={setFilterParams}
								perPageText="Categories Per Page:"
							/>
						)}
				</Stack>
			</Container>
			<AlertDialog
				isOpen={isOpen}
				onClose={onClose}
				isCentered
				leastDestructiveRef={cancelRef}>
				<AlertDialogOverlay>
					<AlertDialogContent>
						<AlertDialogHeader>
							{__('Delete Category')} {name}
						</AlertDialogHeader>
						<AlertDialogBody>
							{__("Are you sure? You can't restore this category", 'masteriyo')}
						</AlertDialogBody>
						<AlertDialogFooter>
							<ButtonGroup>
								<Button ref={cancelRef} onClick={onClose} variant="outline">
									{__('Cancel', 'masteriyo')}
								</Button>
								<Button
									colorScheme="red"
									onClick={onDeleteConfirm}
									isLoading={deleteCategory.isLoading}>
									{__('Delete', 'masteriyo')}
								</Button>
							</ButtonGroup>
						</AlertDialogFooter>
					</AlertDialogContent>
				</AlertDialogOverlay>
			</AlertDialog>
		</Stack>
	);
};

export default AllCourseCategories;
