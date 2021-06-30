import {
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
import { useQuery, useQueryClient } from 'react-query';
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
	price?: string | number;
}

interface Props {
	filterParamsRef: {
		current: FilterParams;
	};
}

const CourseFilter: React.FC<Props> = (props) => {
	const { filterParamsRef } = props;
	const queryClient = useQueryClient();
	const categoryAPI = new API(urls.categories);
	const categoryQuery = useQuery('categoryLists', () => categoryAPI.list(), {
		retry: false,
	});

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
	const getSearchInputHandler = () => {
		let timer: any = 0;

		return function (e: any) {
			clearTimeout(timer);
			timer = setTimeout(() => {
				filterParamsRef.current.search = e.target.value;
				queryClient.cancelQueries('courseList');
				queryClient.refetchQueries('courseList');
			}, 800);
		};
	};
	const onChangeCategoryFilter = (e: any) => {
		filterParamsRef.current.category = e.target.value;
		queryClient.cancelQueries('courseList');
		queryClient.refetchQueries('courseList');
	};
	const onChangeStatusFilter = (e: any) => {
		filterParamsRef.current.status = e.target.value;
		queryClient.cancelQueries('courseList');
		queryClient.refetchQueries('courseList');
	};
	const onChangeOnlyFreeFilter = (e: any) => {
		filterParamsRef.current.isOnlyFree = e.target.checked;
		queryClient.cancelQueries('courseList');
		queryClient.refetchQueries('courseList');
	};

	return (
		<Stack direction="row" spacing="8">
			<Flex alignItems="center">
				<FormLabel>{__('Search', 'masteriyo')}</FormLabel>
				<Input onKeyUp={getSearchInputHandler()} />
			</Flex>
			<Flex alignItems="center">
				<FormLabel>{__('Category', 'masteriyo')}</FormLabel>
				{categoryQuery.isLoading ? (
					<Spinner />
				) : (
					<Select onChange={onChangeCategoryFilter}>
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
				<Select onChange={onChangeStatusFilter}>
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
					onChange={onChangeOnlyFreeFilter}
				/>
			</Flex>
		</Stack>
	);
};

export default CourseFilter;
