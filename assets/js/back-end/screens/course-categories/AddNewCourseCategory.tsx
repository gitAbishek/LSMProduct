import {
	Box,
	Button,
	ButtonGroup,
	Container,
	Divider,
	Flex,
	Heading,
	Stack,
	useToast,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { FormProvider, useForm } from 'react-hook-form';
import { useMutation, useQueryClient } from 'react-query';
import { useHistory } from 'react-router';
import PageNav from '../../components/common/PageNav';
import routes from '../../constants/routes';
import urls from '../../constants/urls';
import API from '../../utils/api';
import { deepClean } from '../../utils/utils';
import DescriptionInput from './components/DescriptionInput';
import NameInput from './components/NameInput';
import SlugInput from './components/SlugInput';

const AddNewCourseCategory = () => {
	const history = useHistory();
	const methods = useForm();
	const toast = useToast();
	const queryClient = useQueryClient();
	const categoryAPI = new API(urls.categories);

	const createCategory = useMutation(
		(data: object) => categoryAPI.store(data),
		{
			onSuccess: (data: any) => {
				toast({
					title: __('Category Added', 'masteriyo'),
					description: data.name + __(' is successfully added.', 'masteriyo'),
					isClosable: true,
					status: 'success',
				});
				history.push(routes.course_categories.list);
				queryClient.invalidateQueries('courseCategoriesList');
			},
			onError: (error: any) => {
				toast({
					title: __('Failed to create category', 'masteriyo'),
					description: `${error.response?.data?.message}`,
					isClosable: true,
					status: 'error',
				});
			},
		}
	);

	const onSubmit = (data: object) => {
		createCategory.mutate(deepClean(data));
	};

	return (
		<Container maxW="container.xl" marginTop="6">
			<Stack direction="column" spacing="6">
				<PageNav
					currentTitle={__('Add New Category', 'masteriyo')}
					hasCategoryName
				/>
				<FormProvider {...methods}>
					<Box bg="white" p="10" shadow="box">
						<Stack direction="column" spacing="8">
							<Flex aling="center" justify="space-between">
								<Heading as="h1" fontSize="x-large">
									{__('Add New Category', 'masteriyo')}
								</Heading>
							</Flex>

							<form onSubmit={methods.handleSubmit(onSubmit)}>
								<Stack direction="column" spacing="6">
									<NameInput />
									<SlugInput />
									<DescriptionInput />

									<Box py="3">
										<Divider />
									</Box>

									<ButtonGroup>
										<Button
											colorScheme="blue"
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
							</form>
						</Stack>
					</Box>
				</FormProvider>
			</Stack>
		</Container>
	);
};

export default AddNewCourseCategory;
