import {
	Box,
	Button,
	ButtonGroup,
	FormControl,
	FormErrorMessage,
	FormLabel,
	Heading,
	Img,
	Stack,
	Textarea,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import ImageUpload from 'Components/common/ImageUpload';
import Input from 'Components/common/Input';
import Select from 'Components/common/Select';
import React, { useState } from 'react';
import { Controller, useForm } from 'react-hook-form';
import { useMutation, useQuery, useQueryClient } from 'react-query';
import { useHistory, useParams } from 'react-router-dom';

import urls from '../../constants/urls';
import API from '../../utils/api';

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
		onSuccess: () => {
			queryClient.invalidateQueries(`course${courseId}`);
		},
	});

	const onSubmit = (data: any) => {
		updateCourse.mutate(data);
	};

	return (
		<>
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
									<FormLabel>{__('Course Description', 'masteriyo')}</FormLabel>
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
										control={control}
										name="categories"
										defaultValue=""
										render={({ onChange, value }) => (
											<Select
												closeMenuOnSelect={false}
												isMulti
												onChange={onChange}
												value={value}
												options={categoriesOption}
											/>
										)}
									/>
								</FormControl>
								<FormControl>
									<FormLabel>{__('Featured Image', 'masteriyo')}</FormLabel>
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
		</>
	);
};

export default EditCourse;
