import { Box, Button, Heading, SimpleGrid, Stack } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { IoIosArrowForward } from 'react-icons/io';
import CourseItem from '../../components/CourseItem';
import CoursesData from '../../dummyData/courses';
import Achievement from '../achievements/Achievement';
import Certificates from '../certificates/Certificates';

const MyCourses: React.FC = () => {
	return (
		<>
			<Stack direction="column" spacing="8">
				<Heading as="h1" size="xl">
					{__('My Courses', 'masteriyo')}
				</Heading>
				<SimpleGrid columns={4} spacing="4">
					{CoursesData.map((itemProps, key) => {
						return <CourseItem key={key} {...itemProps} />;
					})}
				</SimpleGrid>
				<Box>
					<Button
						rightIcon={<IoIosArrowForward size={15} color={'gray.500'} />}
						color="gray.500"
						borderRadius="md"
						borderColor="gray.400"
						variant="outline">
						{__('SHOW ALL COURSES', 'masteriyo')}
					</Button>
				</Box>

				<Achievement />
				<Certificates />
			</Stack>
		</>
	);
};

export default MyCourses;
