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
	useDisclosure,
	useToast,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useRef, useState } from 'react';
import { BiListUl, BiPlus } from 'react-icons/bi';
import { useMutation, useQuery, useQueryClient } from 'react-query';
import { NavLink, useHistory } from 'react-router-dom';
import { Table, Tbody, Th, Thead, Tr } from 'react-super-responsive-table';
import EmptyInfo from '../../components/common/EmptyInfo';
import Header from '../../components/common/Header';
import MasteriyoPagination from '../../components/common/MasteriyoPagination';
import { navActiveStyles, navLinkStyles } from '../../config/styles';
import routes from '../../constants/routes';
import urls from '../../constants/urls';
import { SkeletonCourseTaxonomy } from '../../skeleton';
import {
	CourseCategoriesResponse,
	CourseCategoryHierarchy,
} from '../../types/course';
import API from '../../utils/api';
import { makeCategoriesHierarchy } from '../../utils/categories';
import { isEmpty } from '../../utils/utils';
import CategoriesFilter from './components/CategoriesFilter';
import CategoryRow from './components/CategoryRow';

interface FilterParams {
	per_page?: number;
	page?: number;
	search?: string;
}

const AllCourseCategories = () => {
	const categoryAPI = new API(urls.categories);
	const queryClient = useQueryClient();
	const toast = useToast();
	const history = useHistory();

	const [filterParams, setFilterParams] = useState<FilterParams>({});

	const categoriesQuery = useQuery<CourseCategoriesResponse>(
		['courseCategoriesList', filterParams],
		() => categoryAPI.list(filterParams)
	);

	let categories: CourseCategoryHierarchy[] = [];

	if (categoriesQuery.isSuccess) {
		if (filterParams.search) {
			categories = categoriesQuery.data?.data?.map((cat) => ({
				...cat,
				depth: 0,
			}));
		} else {
			categories = makeCategoriesHierarchy(categoriesQuery.data?.data);
		}
	}

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
					title: __('Failed to delete category.', 'masteriyo'),
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

	let renderCategories: React.ReactNode = null;

	if (!categoriesQuery.isLoading && categoriesQuery?.data?.data.length) {
		let isCurrentColorEmpty = true;

		renderCategories = categories?.map((cat) => {
			if (cat.depth === 0) {
				isCurrentColorEmpty = !isCurrentColorEmpty;
			}

			return (
				<CategoryRow
					key={cat.id}
					id={cat.id}
					name={'— '.repeat(filterParams.search ? 0 : cat.depth) + cat.name}
					slug={cat.slug}
					count={cat.count}
					link={cat.link}
					onDeletePress={onDeletePress}
					background={isCurrentColorEmpty ? 'transparent' : '#f8f9fa'}
				/>
			);
		});
	}

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
						<Stack direction="column" spacing="10">
							<CategoriesFilter
								filterParams={filterParams}
								setFilterParams={setFilterParams}
							/>
							<Stack direction="column" spacing="8">
								<Table>
									<Thead>
										<Tr>
											<Th>{__('Name', 'masteriyo')}</Th>
											<Th>{__('Slug', 'masteriyo')}</Th>
											<Th>{__('Count', 'masteriyo')}</Th>
											<Th>{__('Actions', 'masteriyo')}</Th>
										</Tr>
									</Thead>
									<Tbody>
										{categoriesQuery.isLoading ? (
											<SkeletonCourseTaxonomy />
										) : isEmpty(categoriesQuery?.data?.data) ? (
											<EmptyInfo
												message={__('No categories found.', 'masteriyo')}
											/>
										) : (
											renderCategories
										)}
									</Tbody>
								</Table>
							</Stack>
						</Stack>
					</Box>
					{categoriesQuery.isSuccess &&
						!isEmpty(categoriesQuery?.data?.data) && (
							<MasteriyoPagination
								metaData={categoriesQuery?.data?.meta}
								setFilterParams={setFilterParams}
								perPageText={__('Categories Per Page:', 'masteriyo')}
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
							{__('Delete Category', 'masteriyo')} {name}
						</AlertDialogHeader>
						<AlertDialogBody>
							{__(
								'Are you sure? You can’t restore after deleting.',
								'masteriyo'
							)}
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
