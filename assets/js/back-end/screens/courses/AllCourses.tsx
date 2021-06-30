import {
	Box,
	Container,
	Flex,
	Heading,
	Stack,
	Table,
	Tbody,
	Th,
	Thead,
	Tr,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import Header from 'Components/layout/Header';
import React, { useRef } from 'react';
import { useQuery } from 'react-query';
import urls from '../../constants/urls';
import { SkeletonCourseList } from '../../skeleton';
import API from '../../utils/api';
import CourseFilter from './components/CourseFilter';
import CourseList from './components/CourseList';

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

						<CourseFilter filterParamsRef={filterParamsRef} />

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
