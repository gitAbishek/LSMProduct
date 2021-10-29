import { Box, Button, Container, Heading, Stack } from '@chakra-ui/react';
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
			<Container maxW="container.lg">
				<Box py={20}>
					<Heading as="h1" size="xl" px={10}>
						{__('My Courses', 'masteriyo')}
					</Heading>
					<Stack direction="row" spacing="7" m={10} display={{ md: 'flex' }}>
						{CoursesData.map((itemProps, key) => {
							return <CourseItem key={key} {...itemProps} />;
						})}
					</Stack>
					<Box px={10}>
						<Button
							rightIcon={<IoIosArrowForward size={15} color={'gray.500'} />}
							color="gray.500"
							borderRadius="md"
							borderColor="gray.400"
							variant="outline">
							{__('SHOW ALL COURSES', 'masteriyo')}
						</Button>
					</Box>
				</Box>
				<Achievement />
				<Certificates />
			</Container>
		</>
	);
};

export default MyCourses;
