import {
	Button,
	Flex,
	FormLabel,
	Input,
	Select,
	Spinner,
	Stack,
	Switch,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { useForm } from 'react-hook-form';
import { BiFilter } from 'react-icons/bi';
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
	setFilterParams?: Function;
}

const CourseFilter: React.FC<Props> = (props) => {
	const { setFilterParams } = props;
	const categoryAPI = new API(urls.categories);
	const categoryQuery = useQuery('categoryLists', () => categoryAPI.list(), {
		retry: false,
	});
	const { handleSubmit, register } = useForm();

	const getCategoryOptions = () => {
		if (categoryQuery.isLoading || categoryQuery.isError) {
			return [
				{
					value: '',
					label: __('All Categories', 'masteriyo'),
				},
			];
		}
		return [
			{
				value: '',
				label: __('All Categories', 'masteriyo'),
			},
			...categoryQuery.data.map((category: any) => {
				return {
					value: category.id,
					label: category.name,
				};
			}),
		];
	};
	const onSubmit = (data: FilterParams) => {
		typeof setFilterParams === 'function' &&
			setFilterParams({
				...(data.search ? { search: data.search } : {}),
				...(data.category ? { category: data.category } : {}),
				...(data.status ? { status: data.status } : {}),
				...(data.isOnlyFree ? { price: 0 } : {}),
			});
	};

	return (
		<form onSubmit={handleSubmit(onSubmit)}>
			<Stack direction="row" spacing="8">
				<Flex alignItems="center">
					<FormLabel>{__('Search', 'masteriyo')}</FormLabel>
					<Input {...register('search')} />
				</Flex>
				<Flex alignItems="center">
					<FormLabel>{__('Category', 'masteriyo')}</FormLabel>
					{categoryQuery.isLoading ? (
						<Spinner />
					) : (
						<Select {...register('category')}>
							{getCategoryOptions().map((option: any) => (
								<option key={option.value} value={option.value}>
									{option.label}
								</option>
							))}
						</Select>
					)}
				</Flex>
				<Flex alignItems="center">
					<FormLabel>{__('Status', 'masteriyo')}</FormLabel>
					<Select {...register('status')}>
						{courseStatusList.map((option: any) => (
							<option key={option.value} value={option.value}>
								{option.label}
							</option>
						))}
					</Select>
				</Flex>
				<Flex alignItems="center">
					<FormLabel htmlFor="filter-only-free-courses">
						{__('Only Free', 'masteriyo')}
					</FormLabel>
					<Switch
						id="filter-only-free-courses"
						defaultChecked={false}
						{...register('isOnlyFree')}
					/>
				</Flex>
				<Button
					leftIcon={<BiFilter />}
					colorScheme="blue"
					size="sm"
					type="submit">
					{__('Filter')}
				</Button>
			</Stack>
		</form>
	);
};

export default CourseFilter;
