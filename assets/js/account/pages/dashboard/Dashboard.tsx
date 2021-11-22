import {
	Alert,
	Box,
	Button,
	ButtonGroup,
	Heading,
	Image,
	SimpleGrid,
	Stack,
	Text,
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
				<Stack direction="column" spacing="6">
					<Heading as="h1" size="lg">
						{__('	My Achievements', 'masteriyo')}
					</Heading>
					<Box>
						<Alert colorScheme={'blue.200'} color={'blue.400'} borderRadius={5}>
							{__(
								'You have no achievements yet. Enroll in course to get an achievements',
								'masteriyo'
							)}
						</Alert>
					</Box>
				</Stack>
				<Stack direction="column" spacing="6">
					<Stack direction="row" justify="space-between">
						<Stack direction="row" spacing={3}>
							<Image
								src="https://api.lorem.space/image/fashion?w=150&h=150"
								alt="Book"
							/>
							<Text fontWeight={'semibold'}>Certificate 1</Text>
						</Stack>

						<Button
							fontSize={'sm'}
							rounded={'full'}
							bg={'blue.600'}
							color={'white'}
							_hover={{
								bg: 'blue.500',
							}}
							_focus={{
								bg: 'blue.500',
							}}>
							Download
						</Button>
					</Stack>
				</Stack>
			</Stack>
		</>
	);
};

export default Dashboard;
