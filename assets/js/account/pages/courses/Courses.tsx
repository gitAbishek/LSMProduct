import { ChevronRightIcon } from '@chakra-ui/icons';
import { Box, Button, Container, Heading, Stack } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import Course from '../../components/Course';
import Achievement from '../achievements/Achievement';
import Certificates from '../certificates/Certificates';
import data from './CoursesData';

const Courses: React.FC = () => {
	return (
		<>
			<Container maxW="container.lg">
				<Box py={20}>
					<Heading as="h1" size="xl" px={10}>
						{__('My Courses', 'masteriyo')}
					</Heading>
					<Stack direction="row" spacing="7" m={10} display={{ md: 'flex' }}>
						{data.map((itemProps, key) => {
							return <Course key={key} {...itemProps} />;
						})}
					</Stack>
					<Box px={10}>
						<Button
							rightIcon={<ChevronRightIcon boxSize={6} color={'gray.500'} />}
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

export default Courses;
