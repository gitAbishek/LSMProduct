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
import routes from '../../constants/routes';
import urls from '../../constants/urls';
import API from '../../utils/api';
import DescriptionInput from './components/DescriptionInput';
import Header from './components/Header';
import NameInput from './components/NameInput';
import SlugInput from './components/SlugInput';

const AddNewCourseTag = () => {
	const history = useHistory();
	const methods = useForm();
	const toast = useToast();
	const queryClient = useQueryClient();
	const tagAPI = new API(urls.tags);

	const createTag = useMutation((data: object) => tagAPI.store(data), {
		onSuccess: (data: any) => {
			toast({
				title: __('Tag Added', 'masteriyo'),
				description: data.name + __(' is successfully added.', 'masteriyo'),
				isClosable: true,
				status: 'success',
			});
			history.push(routes.course_tags.list);
			queryClient.invalidateQueries('courseTagsList');
		},
		onError: (error: any) => {
			toast({
				title: __('Failed to create tag', 'masteriyo'),
				description: `${error.response?.data?.message}`,
				isClosable: true,
				status: 'error',
			});
		},
	});

	const onSubmit = (data: object) => {
		createTag.mutate(data);
	};

	return (
		<Stack direction="column" spacing="8" alignItems="center">
			<Header isEmpty />
			<Container maxW="container.xl">
				<FormProvider {...methods}>
					<Box bg="white" p="10" shadow="box">
						<Stack direction="column" spacing="8">
							<Flex aling="center" justify="space-between">
								<Heading as="h1" fontSize="x-large">
									{__('Add New Tag', 'masteriyo')}
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
											isLoading={createTag.isLoading}>
											{__('Create', 'masteriyo')}
										</Button>
										<Button
											variant="outline"
											onClick={() => history.push(routes.course_tags.list)}>
											{__('Cancel', 'masteriyo')}
										</Button>
									</ButtonGroup>
								</Stack>
							</form>
						</Stack>
					</Box>
				</FormProvider>
			</Container>
		</Stack>
	);
};

export default AddNewCourseTag;
