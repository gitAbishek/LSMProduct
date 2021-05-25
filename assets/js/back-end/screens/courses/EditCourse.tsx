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
import HeaderBuilder from 'Components/layout/HeaderBuilder';
import React from 'react';
import { FormProvider, useForm } from 'react-hook-form';
import { useMutation, useQuery, useQueryClient } from 'react-query';
import { Link as RouterLink } from 'react-router-dom';

import routes from '../../constants/routes';
import urls from '../../constants/urls';
import { CourseDataMap } from '../../types/course';
import API from '../../utils/api';
import { mergeDeep } from '../../utils/mergeDeep';
import Categories from './components/Categories';
import Description from './components/Description';
import FeaturedImage from './components/FeaturedImage';
import Name from './components/Name';
import Price from './components/Price';

interface Props {
	courseData: CourseDataMap | any;
}

const EditCourse: React.FC<Props> = (props) => {
	const { courseData } = props;
	const queryClient = useQueryClient();
	const methods = useForm();
	const toast = useToast();

	const courseAPI = new API(urls.courses);

	const updateCourse = useMutation(
		(data) => courseAPI.update(courseData.id, data),
		{
			onSuccess: (data: CourseDataMap) => {
				toast({
					title: data.name + __(' is updated successfully.', 'masteriyo'),
					description: __('You can keep editing it', 'masteriyo'),
					status: 'success',
					isClosable: true,
				});
				queryClient.invalidateQueries(`courses${data.id}`);
			},
		}
	);

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

	return (
		<FormProvider {...methods}>
			<form onSubmit={methods.handleSubmit(onSubmit)}>
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
							<Name defaultValue={courseData.name} />
							<Description defaultValue={courseData.description} />
							<Price defaultValue={courseData.regular_price} />
						</Stack>
						<ButtonGroup>
							<Button
								type="submit"
								colorScheme="blue"
								isLoading={updateCourse.isLoading}>
								{__('Update', 'masteriyo')}
							</Button>
							<RouterLink to={routes.courses.list}>
								<Button variant="outline">{__('Cancel', 'masteriyo')}</Button>
							</RouterLink>
						</ButtonGroup>
					</Box>
					<Box w="400px" bg="white" p="10" shadow="box">
						<Stack direction="column" spacing="6">
							<Categories defaultValue={courseData.categories} />
							<FeaturedImage defaultValue={courseData.featured_image} />
						</Stack>
					</Box>
				</Stack>
			</form>
		</FormProvider>
	);
};

export default EditCourse;
