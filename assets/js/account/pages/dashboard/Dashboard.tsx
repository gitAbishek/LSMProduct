import {
	Button,
	ButtonGroup,
	Heading,
	SimpleGrid,
	Stack,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { IoIosArrowForward } from 'react-icons/io';
import CourseItem from '../../components/CourseItem';
import CoursesData from '../../dummyData/courses';

const Dashboard: React.FC = () => {
	return (
		<>
			<Stack direction="column" spacing="10">
				<Stack direction="column" spacing="6">
					<Heading as="h1" size="lg">
						{__('My Courses', 'masteriyo')}
					</Heading>
					<SimpleGrid columns={3} spacing="6">
						{CoursesData.map((itemProps, key) => {
							return <CourseItem key={key} {...itemProps} />;
						})}
					</SimpleGrid>
					<ButtonGroup>
						<Button
							rightIcon={<IoIosArrowForward size={15} color={'gray.500'} />}
							color="gray.500"
							size="sm"
							borderRadius="full"
							borderColor="gray.400"
							variant="outline">
							{__('SHOW ALL COURSES', 'masteriyo')}
						</Button>
					</ButtonGroup>
				</Stack>
			</Stack>
		</>
	);
};

export default Dashboard;
