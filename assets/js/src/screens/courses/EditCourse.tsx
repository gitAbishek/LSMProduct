import {
	Box,
	Button,
	ButtonGroup,
	Center,
	FormControl,
	FormErrorMessage,
	FormLabel,
	Heading,
	Img,
	Spinner,
	Stack,
	Textarea,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import ImageUpload from 'Components/common/ImageUpload';
import Input from 'Components/common/Input';
import Select from 'Components/common/Select';
import React from 'react';
import { Controller, useForm } from 'react-hook-form';
import { useMutation, useQuery, useQueryClient } from 'react-query';
import { useHistory, useParams } from 'react-router-dom';

import urls from '../../constants/urls';
import API from '../../utils/api';
import MediaAPI from '../../utils/media';

const EditCourse = () => {
	interface Inputs {
		name: string;
		description?: string;
		categories?: any;
	}

	const { courseId }: any = useParams();
	const history = useHistory();
	const queryClient = useQueryClient();
	const { register, handleSubmit, errors, control } = useForm<Inputs>();

	const courseAPI = new API(urls.courses);
	const categoryAPI = new API(urls.categories);
	const imageAPI = new MediaAPI();

	const categoryQuery = useQuery('categoryLists', () => categoryAPI.list());
	const courseQuery = useQuery([`course${courseId}`, courseId], () =>
		courseAPI.get(courseId)
	);

	const featuredImageId = courseQuery?.data?.featured_image;
	const imageQuery = useQuery(
		[
			`image${courseQuery?.data?.featured_image}`,
			courseQuery?.data?.featured_image,
		],
		() => imageAPI.get(courseQuery?.data?.featured_image),
		{
			enabled: !!courseQuery?.data?.featured_image,
		}
	);

	const categoriesOption = categoryQuery?.data?.map((category: any) => {
		return {
			value: category.id,
			label: category.name,
		};
	});

	const updateCourse = useMutation((data) => courseAPI.update(courseId, data), {
		onSuccess: () => {
			queryClient.invalidateQueries(`course${courseId}`);
		},
	});

	const onSubmit = (data: any) => {
		updateCourse.mutate(data);
	};

	return (
		<>
			{courseQuery.isLoading || categoryQuery.isLoading ? (
				<Center h="xs">
					<Spinner />
				</Center>
			) : (
				<form onSubmit={handleSubmit(onSubmit)}>
					<Stack direction="column" spacing="8">
						<Heading as="h1">{__('Add New Course', 'masteriyo')}</Heading>

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
									<FormControl isInvalid={!!errors.name}>
										<FormLabel>{__('Course Name', 'masteriyo')}</FormLabel>
										<Input
											placeholder={__('Your Course Name', 'masteriyo')}
											name="name"
											ref={register({
												required: __(
													'You must provide name for the course',
													'masteriyo'
												),
											})}
											defaultValue={courseQuery?.data?.name}
										/>
										<FormErrorMessage>
											{errors.name && errors.name.message}
										</FormErrorMessage>
									</FormControl>

									<FormControl>
										<FormLabel>
											{__('Course Description', 'masteriyo')}
										</FormLabel>
										<Textarea
											name="description"
											placeholder={__('Your Course Description', 'masteriyo')}
											ref={register()}
											defaultValue={courseQuery?.data?.description}
										/>
									</FormControl>
								</Stack>
								<ButtonGroup>
									<Button
										type="submit"
										colorScheme="blue"
										isLoading={updateCourse.isLoading}>
										{__('Add Course', 'masteriyo')}
									</Button>
									<Button variant="outline" onClick={() => history.goBack()}>
										{__('Cancel', 'masteriyo')}
									</Button>
								</ButtonGroup>
							</Box>
							<Box w="400px" bg="white" p="10" shadow="box">
								<Stack direction="column" spacing="6">
									<FormControl>
										<FormLabel>{__('Categories', 'masteriyo')}</FormLabel>

										<Controller
											as={Select}
											name="categories"
											closeMenuOnSelect={false}
											isMulti
											options={categoriesOption}
											defaultValue={
												courseQuery?.data?.categories.length &&
												courseQuery?.data?.categories.map((category: any) => {
													return {
														value: category.id,
														label: category.name,
													};
												})
											}
											control={control}
										/>
									</FormControl>
									<FormControl>
										<FormLabel>{__('Featured Image', 'masteriyo')}</FormLabel>
										{courseQuery?.data?.featured_image &&
											(imageQuery.isLoading ? (
												<Center
													border="1px"
													borderColor="gray.100"
													h="36"
													overflow="hidden">
													<Spinner />
												</Center>
											) : (
												<Stack direction="column" spacing="4">
													<Box
														border="1px"
														borderColor="gray.100"
														h="36"
														overflow="hidden">
														<Img
															src={
																imageQuery.data.media_details.sizes.medium
																	.source_url
															}
															objectFit="cover"
															w="full"
														/>
													</Box>
													<Button colorScheme="red" variant="outline">
														{__('Remove featured Image', 'masteriyo')}
													</Button>
												</Stack>
											))}

										{/* {preview ? (
										<Stack direction="column" spacing="4">
											<Box
												border="1px"
												borderColor="gray.100"
												h="36"
												overflow="hidden">
												<Img src={preview} objectFit="cover" w="full" />
											</Box>
											<Button
												colorScheme="red"
												variant="outline"
												onClick={onRemoveFeaturedImage}>
												{__('Remove featured Image', 'masteriyo')}
											</Button>
										</Stack>
									) : (
										<ImageUpload setFile={setFile} setPreview={setPreview} />
									)} */}
									</FormControl>
								</Stack>
							</Box>
						</Stack>
					</Stack>
				</form>
			)}
		</>
	);
};

export default EditCourse;
