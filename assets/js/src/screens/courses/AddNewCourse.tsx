import {
	Box,
	Button,
	ButtonGroup,
	Container,
	Heading,
	Stack,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import Header from 'Components/layout/Header';
import React from 'react';
import { FormProvider, useForm } from 'react-hook-form';
import { useMutation } from 'react-query';
import { useHistory } from 'react-router-dom';

import routes from '../../constants/routes';
import urls from '../../constants/urls';
import API from '../../utils/api';
import { mergeDeep } from '../../utils/mergeDeep';
import Categories from './components/Categories';
import Description from './components/Description';
import FeaturedImage from './components/FeaturedImage';
import Name from './components/Name';
import Price from './components/Price';

const AddNewCourse: React.FC = () => {
	const history = useHistory();
	const methods = useForm();
	const courseAPI = new API(urls.courses);

	const addMutation = useMutation((data) => courseAPI.store(data), {
		onSuccess: (data) => {
			history.push(routes.courses.edit.replace(':courseId', data.id));
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

		addMutation.mutate(mergeDeep(data, newData));
	};

	return (
		<Stack direction="column" spacing="8" alignItems="center">
			<Header />
			<Container maxW="container.xl">
				<FormProvider {...methods}>
					<form onSubmit={methods.handleSubmit(onSubmit)}>
						<Stack direction="column" spacing="8">
							<Heading as="h1" size="xl">
								{__('Add New Course', 'masteriyo')}
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
											<Button
												variant="outline"
												onClick={() => history.push(routes.courses.list)}>
												{__('Cancel', 'masteriyo')}
											</Button>
										</ButtonGroup>
									</Stack>
								</Box>
								<Box w="400px" bg="white" p="10" shadow="box">
									<Stack direction="column" spacing="6">
										<Categories />
										<FeaturedImage />
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

export default AddNewCourse;
