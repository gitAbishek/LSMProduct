import {
	Box,
	Container,
	Stack,
	Table,
	Tbody,
	Th,
	Thead,
	Tr,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useState } from 'react';
import { useQuery } from 'react-query';
import Header from '../../components/layout/Header';
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
	const courseAPI = new API(urls.courses);
	const [filterParams, setFilterParams] = useState<FilterParams>({});
	const courseQuery = useQuery(['courseList', filterParams], () =>
		courseAPI.list(filterParams)
	);

	const tableStyles = {
		th: {
			pb: '6',
			borderBottom: 'none',
		},
		'tr:nth-of-type(2n+1) td': {
			bg: '#f8f9fa',
		},

		tr: {
			'th, td': {
				':first-child': {
					pl: '12',
				},
				':last-child': {
					pr: '6',
				},
			},
		},
		td: {
			borderBottom: 'none',
		},
	};

	return (
		<Stack direction="column" spacing="8" alignItems="center">
			<Header hideCoursesMenu />
			<CourseFilter setFilterParams={setFilterParams} />
			<Container maxW="container.xl">
				<Box bg="white" py="12" shadow="box" mx="auto">
					<Stack direction="column" spacing="8">
						<Table size="sm" sx={tableStyles}>
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
