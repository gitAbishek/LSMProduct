import {
	Box,
	Button,
	ButtonGroup,
	Flex,
	FormControl,
	FormErrorMessage,
	FormHelperText,
	FormLabel,
	Heading,
	Input,
	InputGroup,
	NumberDecrementStepper,
	NumberIncrementStepper,
	NumberInput,
	NumberInputField,
	NumberInputStepper,
	Slider,
	SliderFilledTrack,
	SliderThumb,
	SliderTrack,
	Stack,
	Textarea,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import ImageUpload from 'Components/common/ImageUpload';
import Select from 'Components/common/Select';
import React from 'react';
import { FormProvider, useForm } from 'react-hook-form';
import { useMutation, useQuery } from 'react-query';
import { useHistory } from 'react-router-dom';

import routes from '../../constants/routes';
import urls from '../../constants/urls';
import API from '../../utils/api';
import { mergeDeep } from '../../utils/mergeDeep';
import Categories from './components/Categories';
import Description from './components/Description';
import Name from './components/Name';
import Price from './components/Price';

const AddNewCourse: React.FC = () => {
	interface Inputs {
		name: string;
		description?: string;
		categories?: any;
	}
	const history = useHistory();
	const methods = useForm();
	const courseAPI = new API(urls.courses);

	const addMutation = useMutation((data) => courseAPI.store(data), {
		onSuccess: (data) => {
			history.push(routes.courses.edit.replace(':courseId', data.id));
		},
	});

	const addCourse = (data: any) => {
		const newData: any = {
			...(data.categories && {
				categories: data.categories.map((category: any) => ({
					id: category.value,
				})),
			}),
		};
		console.log(data);
		addMutation.mutate(mergeDeep(data, newData));
	};

	const onSubmit = (data: any) => {
		console.log(data);
	};

	return (
		<FormProvider {...methods}>
			<form onSubmit={methods.handleSubmit((data) => console.log(data))}>
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
								<Stack direction="column" spacing="6">
									<Name />
									<Description />
									<Price />
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
							</Stack>
						</Box>
						<Box w="400px" bg="white" p="10" shadow="box">
							<Stack direction="column" spacing="6">
								<Categories />
								{/* <FormControl>
									<FormLabel>{__('Featured Image', 'masteriyo')}</FormLabel>
									<ImageUpload
										name="featured_image"
										register={register}
										setValue={setValue}
									/>
								</FormControl> */}
							</Stack>
						</Box>
					</Stack>
				</Stack>
			</form>
		</FormProvider>
	);
};

export default AddNewCourse;
