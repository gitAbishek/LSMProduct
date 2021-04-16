import {
	Box,
	Button,
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
import React from 'react';
import { useQuery } from 'react-query';
import { Link as RouterLink } from 'react-router-dom';

import routes from '../../constants/routes';
import urls from '../../constants/urls';
import { SkeletonCourseList } from '../../skeleton';
import API from '../../utils/api';
import CourseList from './components/CourseList';

const AllCourses = () => {
	const courseAPI = new API(urls.courses);
	const courseQuery = useQuery('courseList', () => courseAPI.list());
	return (
		<Box bg="white" p="12" shadow="box">
			<Stack direction="column" spacing="8">
				<Flex justify="space-between" aling="center">
					<Heading as="h1">{__('Courses', 'masteriyo')}</Heading>
					<RouterLink to={routes.courses.add}>
						<Button colorScheme="blue">
							{__('Add New Course', 'masteriyo')}
						</Button>
					</RouterLink>
				</Flex>

				<Table>
					<Thead>
						<Tr>
							<Th>{__('Title', 'masteriyo')}</Th>
							<Th>{__('Categories', 'masteriyo')}</Th>
							<Th>{__('Price', 'masteriyo')}</Th>
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
								/>
							))}
					</Tbody>
				</Table>
			</Stack>
		</Box>
	);
};

export default AllCourses;
