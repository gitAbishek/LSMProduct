import {
	Box,
	Button,
	ButtonGroup,
	Divider,
	Flex,
	FormControl,
	FormErrorMessage,
	FormLabel,
	Heading,
	Input,
	Stack,
	Textarea,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import ImageUpload from 'Components/common/ImageUpload';
import Select from 'Components/common/Select';
import React from 'react';
import { Controller, useForm } from 'react-hook-form';
import { useMutation, useQuery } from 'react-query';
import { useHistory } from 'react-router-dom';

import urls from '../../constants/urls';
import API from '../../utils/api';

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

	const categoriesOption = categoryQuery?.data?.map((category: any) => {
		return {
			value: category.id,
			label: category.name,
		};
	});

	const addMutation = useMutation((data) => courseAPI.store(data), {
		onSuccess: (data) => {
			history.push(`/courses/${data?.id}`);
		},
	});

	const onSubmit = (data: any) => {
		const categories = data.categories.map((category: any) => ({
			id: category.value,
		}));

		const newData: any = {
			name: data.name,
			description: data.description,
			categories: categories,
		};

		addMutation.mutate(newData);
	};

	return (
		<>
			<Box p="12" shadow="box" bg="white">
				<form onSubmit={handleSubmit(onSubmit)}>
					<Stack direction="column" spacing="8">
						<Flex justify="space-between" aling="center">
							<Heading as="h1">{__('Add New Course', 'masteriyo')}</Heading>
						</Flex>
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
									ref={register({ required: true })}
								/>
							</FormControl>

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
						</Stack>
						<Divider />
						<ButtonGroup>
							<Button type="submit" colorScheme="blue">
								Add New Course
							</Button>
							<Button variant="outline" onClick={() => history.goBack()}>
								Cancel
							</Button>
						</ButtonGroup>
					</Stack>
				</form>
			</Box>
		</>
	);
};

export default AddNewCourse;
