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
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import Select from 'Components/common/Select';
import React from 'react';
import { Controller, useForm } from 'react-hook-form';
import { useMutation, useQuery } from 'react-query';
import { useHistory } from 'react-router-dom';

import routes from '../../constants/routes';
import urls from '../../constants/urls';
import useImageUpload from '../../hooks/useImageUpload';
import API from '../../utils/api';
import MediaAPI from '../../utils/media';

const AddNewCourse: React.FC = () => {
	interface Inputs {
		name: string;
		description?: string;
		categories?: any;
	}
	const history = useHistory();
	const { register, handleSubmit, control, errors } = useForm<Inputs>();
	const courseAPI = new API(urls.courses);
	const categoryAPI = new API(urls.categories);
	const categoryQuery = useQuery('categoryLists', () => categoryAPI.list());
	const {
		preview,
		ImageUpload,
		deleteImage,
		isUploading,
		isDeleting,
	} = useImageUpload();
	const categoriesOption = categoryQuery?.data?.map((category: any) => {
		return {
			value: category.id,
			label: category.name,
		};
	});

	const addMutation = useMutation((data) => courseAPI.store(data));

	const addCourse = (data: any, media?: any) => {
		const newData: any = {
			name: data.name,
			description: data.description,
			...(data.categories.length && {
				categories: data.categories.map((category: any) => ({
					id: category.value,
				})),
			}),
			...(media && { featured_image: media.id }),
		};
		addMutation.mutate(newData, {
			onSuccess: (data) => {
				history.push(routes.courses.edit.replace(':courseId', data.id));
			},
		});
	};

	const onSubmit = (data: any) => {
		addCourse(data);
	};

	// const onRemoveFeaturedImage = () => {
	// 	setPreview(null);
	// 	setFile(null);
	// };

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
									/>
								</FormControl>
							</Stack>
							<ButtonGroup>
								<Button
									type="submit"
									colorScheme="blue"
									isLoading={addMutation.isLoading}>
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
									{isUploading || isDeleting ? (
										<Center
											border="1px"
											borderColor="gray.100"
											h="36"
											overflow="hidden">
											<Spinner />
										</Center>
									) : null}
									{!isUploading && preview && (
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
												onClick={deleteImage}>
												{__('Remove featured Image', 'masteriyo')}
											</Button>
										</Stack>
									)}

									{!isUploading && !preview && <ImageUpload />}
								</FormControl>
							</Stack>
						</Box>
					</Stack>
				</Stack>
			</form>
		</>
	);
};

export default AddNewCourse;
