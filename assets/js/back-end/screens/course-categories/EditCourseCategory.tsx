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
	Flex,
	Heading,
	Icon,
	IconButton,
	Link,
	List,
	ListIcon,
	ListItem,
	Menu,
	MenuButton,
	MenuItem,
	MenuList,
	Stack,
	useToast,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useRef, useState } from 'react';
import { FormProvider, useForm } from 'react-hook-form';
import {
	BiCategory,
	BiChevronLeft,
	BiDotsVerticalRounded,
	BiTrash,
} from 'react-icons/bi';
import { useMutation, useQuery, useQueryClient } from 'react-query';
import { useHistory, useParams } from 'react-router';
import { Link as RouterLink, NavLink } from 'react-router-dom';
import Header from '../../components/common/Header';
import { navActiveStyles, navLinkStyles } from '../../config/styles';
import routes from '../../constants/routes';
import urls from '../../constants/urls';
import CategorySkeleton from '../../skeleton/CategorySkeleton';
import API from '../../utils/api';
import { makeSlug } from '../../utils/categories';
import { deepClean } from '../../utils/utils';
import FeaturedImage from '../courses/components/FeaturedImage';
import DescriptionInput from './components/DescriptionInput';
import NameInput from './components/NameInput';
import ParentCategory from './components/ParentCategory';
import SlugInput from './components/SlugInput';

interface CategoryOption {
	value: string | number;
	label: string;
}

interface EditFormData {
	name: string;
	slug: string;
	parentId?: CategoryOption;
	description: string;
	featuredImage?: number;
}

const EditCourseCategory: React.FC = () => {
	const { categoryId }: any = useParams();
	const history = useHistory();
	const methods = useForm();
	const toast = useToast();
	const cancelRef = useRef<any>();
	const categoryAPI = new API(urls.categories);
	const [isDeleteModalOpen, setDeleteModalOpen] = useState(false);
	const queryClient = useQueryClient();

	const categoryQuery = useQuery(
		[`courseCategory${categoryId}`, categoryId],
		() => categoryAPI.get(categoryId)
	);

	const updateCategoryMutation = useMutation(
		(data: object) => categoryAPI.update(categoryId, data),
		{
			onSuccess: () => {
				toast({
					title: __('Course Category Updated', 'masteriyo'),
					isClosable: true,
					status: 'success',
				});
				categoryQuery.refetch();
				queryClient.invalidateQueries('courseCategoriesList');
				history.push(routes.course_categories.list);
			},
			onError: (error: any) => {
				toast({
					title: __('Failed to update course category.', 'masteriyo'),
					description: `${error.response?.data?.message}`,
					isClosable: true,
					status: 'error',
				});
			},
		}
	);

	const deleteCategory = useMutation(
		(categoryId: number) => categoryAPI.delete(categoryId, { force: true }),
		{
			onSuccess: () => {
				toast({
					title: __('Course Category Deleted', 'masteriyo'),
					isClosable: true,
					status: 'error',
				});
				history.push(routes.course_categories.list);
				queryClient.invalidateQueries('courseCategoriesList');
			},
			onError: (error: any) => {
				toast({
					title: __('Failed to delete course category.', 'masteriyo'),
					description: `${error.response?.data?.message}`,
					isClosable: true,
					status: 'error',
				});
			},
		}
	);

	const onSubmit = (data: EditFormData) => {
		updateCategoryMutation.mutate(
			deepClean({
				...data,
				parent_id: data.parentId?.value,
				slug: data.slug ? data.slug : makeSlug(data.name),
				featured_image: data.featuredImage,
			})
		);
	};

	const onDeletePress = () => {
		setDeleteModalOpen(true);
	};

	const onDeleteConfirm = () => {
		deleteCategory.mutate(categoryId);
	};

	const onDeleteModalClose = () => {
		setDeleteModalOpen(false);
	};

	return (
		<Stack direction="column" spacing="8" alignItems="center">
			<Header showLinks>
				<List>
					<ListItem>
						<Link
							as={NavLink}
							sx={navLinkStyles}
							_activeLink={navActiveStyles}
							to={routes.course_categories.list}>
							<ListIcon as={BiCategory} />
							{__('Course Category', 'masteriyo')}
						</Link>
					</ListItem>
				</List>
			</Header>
			<Container maxW="container.xl">
				<Stack direction="column" spacing="6">
					<ButtonGroup>
						<RouterLink to={routes.course_categories.list}>
							<Button
								variant="link"
								_hover={{ color: 'primary.500' }}
								leftIcon={<Icon fontSize="xl" as={BiChevronLeft} />}>
								{__('Back to Course Categories', 'masteriyo')}
							</Button>
						</RouterLink>
					</ButtonGroup>
					<FormProvider {...methods}>
						{categoryQuery.isLoading ? (
							<CategorySkeleton />
						) : (
							<form onSubmit={methods.handleSubmit(onSubmit)}>
								<Stack direction={['column', null, 'row']} spacing="8">
									<Box
										flex="1"
										bg="white"
										p={['4', null, '10']}
										shadow="box"
										d="flex"
										flexDirection="column"
										justifyContent="space-between">
										<Stack direction="column" spacing="8">
											<Flex aling="center" justify="space-between">
												<Heading as="h1" fontSize="x-large">
													{__('Edit Course Category', 'masteriyo')}
												</Heading>
												<Menu placement="bottom-end">
													<MenuButton
														as={IconButton}
														icon={<BiDotsVerticalRounded />}
														variant="outline"
														rounded="sm"
														fontSize="large"
													/>
													<MenuList>
														<MenuItem
															icon={<BiTrash />}
															onClick={onDeletePress}>
															{__('Delete', 'masteriyo')}
														</MenuItem>
													</MenuList>
												</Menu>
											</Flex>

											<Stack direction="column" spacing="6">
												<NameInput defaultValue={categoryQuery.data.name} />

												<DescriptionInput
													defaultValue={categoryQuery.data.description}
												/>

												<ButtonGroup>
													<Button
														colorScheme="primary"
														type="submit"
														isLoading={updateCategoryMutation.isLoading}>
														{__('Update', 'masteriyo')}
													</Button>
													<Button
														variant="outline"
														onClick={() =>
															history.push(routes.course_categories.list)
														}>
														{__('Cancel', 'masteriyo')}
													</Button>
												</ButtonGroup>
											</Stack>
										</Stack>
									</Box>
									<Box
										w={['100%', null, '400px']}
										bg="white"
										p={['4', null, '10']}
										shadow="box">
										<Stack direction="column" spacing="6">
											<SlugInput
												defaultValue={categoryQuery?.data?.slug}
												defaultNameValue={categoryQuery?.data?.name}
											/>
											<ParentCategory
												defaultValue={categoryQuery.data.parent_id}
											/>
											<FeaturedImage
												defaultValue={categoryQuery.data?.featured_image}
											/>
										</Stack>
									</Box>
								</Stack>
							</form>
						)}
						<AlertDialog
							isOpen={isDeleteModalOpen}
							onClose={onDeleteModalClose}
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
											<Button
												ref={cancelRef}
												onClick={onDeleteModalClose}
												variant="outline">
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
					</FormProvider>
				</Stack>
			</Container>
		</Stack>
	);
};

export default EditCourseCategory;
