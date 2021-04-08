import {
	Box,
	Flex,
	FormControl,
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

const AddNewCourse: React.FC = () => {
	interface Inputs {
		name: string;
		description?: string;
		categories?: any;
	}
	const history = useHistory();
	const { register, handleSubmit, control } = useForm<Inputs>();

	const categoryQuery = useQuery('categoryLists', () => fetchCategories());

	const categoriesOption = categoryQuery?.data?.map((category: any) => {
		return {
			value: category.id,
			label: category.name,
		};
	});

	const addMutation = useMutation((data) => addCourse(data), {
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
				<Stack direction="column" spacing="8">
					<Flex justify="space-between" aling="center">
						<Heading as="h1">{__('Add New Course', 'masteriyo')}</Heading>
					</Flex>
					<form onSubmit={handleSubmit(onSubmit)}>
						<Stack direction="column" spacing="6">
							<FormControl borderColor="gray.100">
								<FormLabel>{__('Course Name', 'masteriyo')}</FormLabel>
								<Input
									placeholder={__('Your Course Name', 'masteriyo')}
									ref={register({
										required: __(
											'You must provide name for the course',
											'masteriyo'
										),
									})}
									name="name"
								/>
							</FormControl>

							<FormControl borderColor="gray.100">
								<FormLabel>{__('Course Description', 'masteriyo')}</FormLabel>
								<Textarea
									placeholder={__('Your Course Description', 'masteriyo')}
									ref={register({ required: true })}
									name="name"
								/>
							</FormControl>

							<FormControl borderColor="gray.100">
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
					</form>
				</Stack>
			</Box>
		</>
	);
};

export default AddNewCourse;
