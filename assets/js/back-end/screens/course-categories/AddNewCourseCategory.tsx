import {
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
	useToast,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { FormProvider, useForm } from 'react-hook-form';
import { BiCategory, BiChevronLeft } from 'react-icons/bi';
import { useMutation, useQueryClient } from 'react-query';
import { useHistory } from 'react-router';
import { Link as RouterLink, NavLink } from 'react-router-dom';
import Header from '../../components/common/Header';
import { navActiveStyles, navLinkStyles } from '../../config/styles';
import routes from '../../constants/routes';
import urls from '../../constants/urls';
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

interface AddCatData {
	name: string;
	slug: string;
	description: string;
	parentId?: CategoryOption;
	featuredImage?: number;
}

const AddNewCourseCategory: React.FC = () => {
	const history = useHistory();
	const methods = useForm();
	const toast = useToast();
	const queryClient = useQueryClient();
	const categoryAPI = new API(urls.categories);

	const createCategory = useMutation(
		(data: object) => categoryAPI.store(data),
		{
			onSuccess: () => {
				toast({
					title: __('Course Category Added', 'masteriyo'),
					isClosable: true,
					status: 'success',
				});
				history.push(routes.course_categories.list);
				queryClient.invalidateQueries('courseCategoriesList');
				queryClient.invalidateQueries('categoryLists');
				queryClient.invalidateQueries('allCategories');
			},
			onError: (error: any) => {
				toast({
					title: __('Failed to create course category.', 'masteriyo'),
					description: `${error.response?.data?.message}`,
					isClosable: true,
					status: 'error',
				});
			},
		}
	);

	const onSubmit = (data: AddCatData) => {
		createCategory.mutate(
			deepClean({
				...data,
				parent_id: data.parentId?.value,
				slug: data.slug ? data.slug : makeSlug(data.name),
				featured_image: data.featuredImage,
			})
		);
	};

	return (
		<Stack direction="column" spacing="8" alignItems="center">
			<Header>
				<List>
					<ListItem>
						<Link
							as={NavLink}
							sx={navLinkStyles}
							_activeLink={navActiveStyles}
							to={routes.course_categories.add}>
							<ListIcon as={BiCategory} />
							{__('Add New Category', 'masteriyo')}
						</Link>
					</ListItem>
				</List>
			</Header>
			<Container maxW="container.xl" marginTop="6">
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
										<Stack direction="column" spacing="6">
											<NameInput />

											<DescriptionInput />

											<ButtonGroup>
												<Button
													colorScheme="primary"
													type="submit"
													isLoading={createCategory.isLoading}>
													{__('Create', 'masteriyo')}
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
										<SlugInput />
										<ParentCategory />
										<FeaturedImage />
									</Stack>
								</Box>
							</Stack>
						</form>
					</FormProvider>
				</Stack>
			</Container>
		</Stack>
	);
};

export default AddNewCourseCategory;
