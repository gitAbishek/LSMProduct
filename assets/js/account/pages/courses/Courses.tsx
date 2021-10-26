import { ChevronRightIcon } from '@chakra-ui/icons';
import { Box, Button, Container, Heading, Stack } from '@chakra-ui/react';
import React from 'react';
import CourseCard from '../../components/CourseCard';
import Achievement from '../achievements/Achievement';
import Certificate from '../certificates/Certificate';
import data from './CoursesData';

const Courses: React.FC = () => {
	return (
		<>
			<Container maxW="container.lg">
				<Box py={20}>
					<Heading as="h1" size="xl" px={10}>
						My Courses
					</Heading>
					<Stack direction="row" spacing="7" m={10} display={{ md: 'flex' }}>
						{data.map((itemProps, key) => {
							return <CourseCard key={key} {...itemProps} />;
						})}
					</Stack>
					<Box px={10}>
						<Button
							rightIcon={<ChevronRightIcon boxSize={6} />}
							colorScheme="gray.100"
							color="#7C7D8F"
							borderRadius="20px"
							variant="outline">
							SHOW ALL COURSES
						</Button>
					</Box>
				</Box>
				<Achievement />
				<Certificate />
			</Container>
		</>
	);
};

export default Courses;
