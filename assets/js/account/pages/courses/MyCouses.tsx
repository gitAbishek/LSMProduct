import { Heading, SimpleGrid, Stack } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import CourseItem from '../../components/CourseItem';
import CoursesData from '../../dummyData/courses';

const MyCourses: React.FC = () => {
	return (
		<>
			<Stack direction="column" spacing="8">
				<Heading as="h1" size="xl">
					{__('My Courses', 'masteriyo')}
				</Heading>
				<SimpleGrid columns={3} spacing="6">
					{CoursesData.map((itemProps, key) => {
						return <CourseItem key={key} {...itemProps} />;
					})}
				</SimpleGrid>
			</Stack>
		</>
	);
};

export default MyCourses;
