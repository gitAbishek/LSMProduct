import {
	Box,
	Button,
	ButtonGroup,
	Center,
	Container,
	Heading,
	Spinner,
	Stack,
	useToast,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import FullScreenLoader from 'Components/layout/FullScreenLoader';
import HeaderBuilder from 'Components/layout/HeaderBuilder';
import React from 'react';
import { FormProvider, useForm } from 'react-hook-form';
import { useMutation, useQuery, useQueryClient } from 'react-query';
import { Link as RouterLink, useHistory, useParams } from 'react-router-dom';

import routes from '../../constants/routes';
import urls from '../../constants/urls';
import API from '../../utils/api';
import { mergeDeep } from '../../utils/mergeDeep';
import Categories from './components/Categories';
import Description from './components/Description';
import FeaturedImage from './components/FeaturedImage';
import Name from './components/Name';
import Price from './components/Price';

const EditCourse = () => {
	const { courseId }: any = useParams();
	const history = useHistory();
	const queryClient = useQueryClient();
	const methods = useForm();
	const toast = useToast();

	const courseAPI = new API(urls.courses);
	const courseQuery = useQuery([`course${courseId}`, courseId], () =>
		courseAPI.get(courseId)
	);

	const updateCourse = useMutation((data) => courseAPI.update(courseId, data), {
		onSuccess: (data) => {
			toast({
				title: data.name + __(' is updated successfully.', 'masteriyo'),
				description: __('You can keep editing it', 'masteriyo'),
				status: 'success',
				isClosable: true,
			});
			queryClient.invalidateQueries(`course${courseId}`);
		},
	});

	const onSubmit = (data: any) => {
		const newData: any = {
			...(data.categories && {
				categories: data.categories.map((category: any) => ({
					id: category.value,
				})),
			}),
			...(data.regular_price && {
				regular_price: data.regular_price.toString(),
			}),
		};

		updateCourse.mutate(mergeDeep(data, newData));
	};

	if (courseQuery.isLoading) {
		return <FullScreenLoader />;
	}
	return (
		<Stack direction="column" spacing="8" alignItems="center">
			<HeaderBuilder
				courseId={courseId}
				previewUrl={courseQuery.data.preview_permalink}
			/>
			<Container maxW="container.xl">
				<FormProvider {...methods}>
					<form onSubmit={methods.handleSubmit(onSubmit)}>
						<Stack direction="column" spacing="8">
							<Heading as="h1" size="xl">
								{__('Edit Course: ', 'masteriyo')} {courseQuery.data.name}
							</Heading>

							<Stack direction="row" spacing="8">
								<Box
									flex="1"
									bg="white"
									p="10"
									shadow="box"
									d="flex"
									flexDirection="column"
									justifyContent="space-between">
									<Stack direction="column" spacing="6">
										<Name defaultValue={courseQuery.data.name} />
										<Description defaultValue={courseQuery.data.description} />
										<Price defaultValue={courseQuery.data.regular_price} />
									</Stack>
									<ButtonGroup>
										<Button
											type="submit"
											colorScheme="blue"
											isLoading={updateCourse.isLoading}>
											{__('Update', 'masteriyo')}
										</Button>
										<RouterLink to={routes.courses.list}>
											<Button variant="outline">
												{__('Cancel', 'masteriyo')}
											</Button>
										</RouterLink>
									</ButtonGroup>
								</Box>
								<Box w="400px" bg="white" p="10" shadow="box">
									<Stack direction="column" spacing="6">
										<Categories defaultValue={courseQuery.data.categories} />
										<FeaturedImage
											defaultValue={courseQuery.data.featured_image}
										/>
									</Stack>
								</Box>
							</Stack>
						</Stack>
					</form>
				</FormProvider>
			</Container>
		</Stack>
	);
};

export default EditCourse;
