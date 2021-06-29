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
		label: __('Published', 'masteriyo'),
		value: 'publish',
	},
	{
		label: __('Draft', 'masteriyo'),
		value: 'draft',
	},
];

interface FilterParams {
	category_id?: string | number;
	searchString?: string;
	status?: string;
	isOnlyFree?: boolean;
}

const AllCourses = () => {
	const filterParamsRef = useRef<FilterParams>({});
	const courseAPI = new API(urls.courses);
	const queryClient = useQueryClient();
	const courseQuery = useQuery('courseList', () =>
		courseAPI.list({
			search: filterParamsRef.current.searchString,
			category: filterParamsRef.current.category_id,
			status: filterParamsRef.current.status,
			only_free: filterParamsRef.current.isOnlyFree ? 'yes' : '',
		})
	);
	const categoryAPI = new API(urls.categories);
	const categoryQuery = useQuery('categoryLists', () => categoryAPI.list(), {
		retry: false,
	});

	const getCategoryOptions = () => {
		if (categoryQuery.isLoading || categoryQuery.isError) {
			return [];
		}
		return [
			{
				value: '',
				label: __('-- Select Category --', 'masteriyo'),
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
				filterParamsRef.current.searchString = e.target.value;
				queryClient.invalidateQueries('courseList');
			}, 800);
		};
	};
	const onChangeCategoryFilter = (e: any) => {
		filterParamsRef.current.category_id = e.target.value;
		queryClient.invalidateQueries('courseList');
	};
	const onChangeStatusFilter = (e: any) => {
		filterParamsRef.current.status = e.target.value;
		queryClient.invalidateQueries('courseList');
	};
	const onChangeOnlyFreeFilter = (e: any) => {
		filterParamsRef.current.isOnlyFree = e.target.checked;
		queryClient.invalidateQueries('courseList');
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
								<Select defaultValue="publish" onChange={onChangeStatusFilter}>
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
								{courseQuery.isLoading && <SkeletonCourseList />}
								{courseQuery.isSuccess &&
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
