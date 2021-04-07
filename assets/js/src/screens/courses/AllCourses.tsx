import {
	Box,
	Button,
	Flex,
	Heading,
	Stack,
	Table,
	Tbody,
	Text,
	Th,
	Thead,
	Tr,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { useQuery } from 'react-query';
import { Link } from 'react-router-dom';

import { fetchCourses } from '../../utils/api';
import CourseList from './components/CourseList';

const AllCourses = () => {
	const courseQuery = useQuery('courseList', fetchCourses);

	return (
		<Box bg="white" p="12" shadow="box">
			<Stack direction="column" spacing="8">
				<Flex justify="space-between" aling="center">
					<Heading as="h1">{__('Courses', 'masteriyo')}</Heading>
					<Button colorScheme="blue">
						<Link to="/courses/add-new-course">
							{__('Add New Course', 'masteriyo')}
						</Link>
					</Button>
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
						{courseQuery?.data?.map((course: any) => (
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
