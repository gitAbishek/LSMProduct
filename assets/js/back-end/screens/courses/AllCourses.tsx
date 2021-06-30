import {
	Box,
	Container,
	Flex,
	FormLabel,
	Heading,
	Input,
	Select,
	Spinner,
	Stack,
	Switch,
	Table,
	Tbody,
	Th,
	Thead,
	Tr,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import Header from 'Components/layout/Header';
import React, { useRef } from 'react';
import { useQuery, useQueryClient } from 'react-query';
import urls from '../../constants/urls';
import { SkeletonCourseList } from '../../skeleton';
import API from '../../utils/api';
import CourseList from './components/CourseList';

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

const AllCourses = () => {
	const filterParamsRef = useRef<FilterParams>({});
	const courseAPI = new API(urls.courses);
	const queryClient = useQueryClient();
	const courseQuery = useQuery('courseList', () => {
		const filterParams: FilterParams = {};

		if (filterParamsRef.current.search) {
			filterParams.search = filterParamsRef.current.search;
		}
		if (filterParamsRef.current.category) {
			filterParams.category = filterParamsRef.current.category;
		}
		if (filterParamsRef.current.status) {
			filterParams.status = filterParamsRef.current.status;
		}
		if (filterParamsRef.current.isOnlyFree) {
			filterParams.price = 0;
		}
		return courseAPI.list(filterParams);
	});
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
		<Stack direction="column" spacing="8" alignItems="center">
			<Header />
			<Container maxW="container.xl">
				<Box bg="white" p="12" shadow="box" mx="auto">
					<Stack direction="column" spacing="8">
						<Flex justify="space-between" aling="center">
							<Heading as="h1" size="lg">
								{__('Courses', 'masteriyo')}
							</Heading>
						</Flex>

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

						<Table>
							<Thead>
								<Tr>
									<Th>{__('Title', 'masteriyo')}</Th>
									<Th>{__('Categories', 'masteriyo')}</Th>
									<Th>{__('Author', 'masteriyo')}</Th>
									<Th>{__('Price', 'masteriyo')}</Th>
									<Th>{__('Date', 'masteriyo')}</Th>
									<Th>{__('Actions', 'masteriyo')}</Th>
								</Tr>
							</Thead>
							<Tbody>
								{(courseQuery.isLoading || courseQuery.isFetching) && (
									<SkeletonCourseList />
								)}
								{courseQuery.isSuccess &&
									!courseQuery.isFetching &&
									courseQuery.data.map((course: any) => (
										<CourseList
											id={course.id}
											name={course.name}
											price={course.price}
											categories={course.categories}
											key={course.id}
											createdOn={course.date_created}
											permalink={course.permalink}
											author={course.author}
										/>
									))}
							</Tbody>
						</Table>
					</Stack>
				</Box>
			</Container>
		</Stack>
	);
};

export default AllCourses;
