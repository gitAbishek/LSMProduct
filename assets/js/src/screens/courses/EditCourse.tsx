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
	Input,
	Spinner,
	Stack,
	Textarea,
	useToast,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import ImageUpload from 'Components/common/ImageUpload';
import Select from 'Components/common/Select';
import React from 'react';
import { Controller, useForm } from 'react-hook-form';
import { useMutation, useQuery, useQueryClient } from 'react-query';
import { useHistory, useParams } from 'react-router-dom';

import urls from '../../constants/urls';
import API from '../../utils/api';
import { mergeDeep } from '../../utils/mergeDeep';

const EditCourse = () => {
	interface Inputs {
		name: string;
		description?: string;
		categories?: any;
	}

	const { courseId }: any = useParams();
	const history = useHistory();
	const queryClient = useQueryClient();
	const {
		register,
		handleSubmit,
		errors,
		control,
		setValue,
	} = useForm<Inputs>();
	const toast = useToast();

	const courseAPI = new API(urls.courses);
	const categoryAPI = new API(urls.categories);

	const categoryQuery = useQuery('categoryLists', () => categoryAPI.list());
	const courseQuery = useQuery([`course${courseId}`, courseId], () =>
		courseAPI.get(courseId)
	);

	const categoriesOption = categoryQuery?.data?.map((category: any) => {
		return {
			value: category.id,
			label: category.name,
		};
	});

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
		};

		updateCourse.mutate(mergeDeep(data, newData));
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
						<Heading as="h1">
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
										{__('Update', 'masteriyo')}
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
										<ImageUpload
											name="featured_image"
											register={register}
											setValue={setValue}
											mediaId={courseQuery?.data?.featured_image}
										/>
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
