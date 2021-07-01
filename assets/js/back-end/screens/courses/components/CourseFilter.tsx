import {
	Box,
	Button,
	ButtonGroup,
	Container,
	Input,
	Select,
	Stack,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import { pickBy } from 'object-pickby';
import React from 'react';
import { useForm } from 'react-hook-form';
import { useQuery } from 'react-query';
import urls from '../../../constants/urls';
import API from '../../../utils/api';

const courseStatusList = [
	{
		label: __('All', 'masteriyo'),
		value: '',
	},
	{
		label: __('Published', 'masteriyo'),
		value: 'publish',
	},
	{
		label: __('Draft', 'masteriyo'),
		value: 'draft',
	},
];

interface FilterParams {
	category?: string | number;
	search?: string;
	status?: string;
	isOnlyFree?: boolean;
}

interface Props {
	setFilterParams: any;
}

const CourseFilter: React.FC<Props> = (props) => {
	const { setFilterParams } = props;
	const categoryAPI = new API(urls.categories);
	const categoryQuery = useQuery('categoryLists', () => categoryAPI.list(), {
		retry: false,
	});
	const { handleSubmit, register } = useForm();

	const onSubmit = (data: FilterParams) => {
		// only pick parameters that are not empty
		const newData = pickBy(data, (param) => param.length > 0);

		setFilterParams(newData);
	};

	return (
		<Container maxW="container.xl">
			<Box bg="white" px="12" py="8" shadow="box" mx="auto">
				<form onSubmit={handleSubmit(onSubmit)}>
					<Stack direction="row" spacing="4">
						<Input
							placeholder={__('Search courses', 'masteriyo')}
							{...register('search')}
						/>

						<Select {...register('category')}>
							<option value="">{__('All Categories', 'masteriyo')}</option>
							{categoryQuery?.data?.map(
								(category: { id: number; name: string }) => (
									<option key={category.id} value={category.id}>
										{category.name}
									</option>
								)
							)}
						</Select>

						<Select {...register('status')}>
							{courseStatusList.map((option: any) => (
								<option key={option.value} value={option.value}>
									{option.label}
								</option>
							))}
						</Select>

						<Select {...register('isOnlyFree')}>
							<option value="">{__('Pricing', 'masteriyo')}</option>
							<option value="0">{__('Free', 'masteriyo')}</option>
							<option value="">{__('Paid', 'masteriyo')}</option>
						</Select>
						<ButtonGroup>
							<Button type="submit" colorScheme="blue">
								{__('Filter', 'masteriyo')}
							</Button>
						</ButtonGroup>
					</Stack>
				</form>
			</Box>
		</Container>
	);
};

export default CourseFilter;
