import {
	Box,
	Button,
	Icon,
	Image,
	Progress,
	Stack,
	Tag,
	TagLabel,
	Text,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { BiTime } from 'react-icons/bi';
import { shortEnglishHumanizer } from '../../back-end/utils/utils';
import { MyCoursesSchema } from '../schemas';

interface Props {
	course: MyCoursesSchema;
}

const CourseGridItem: React.FC<Props> = (props) => {
	const { course, started_at } = props.course;

	return (
		<Box p="5" border="1px" borderColor="gray.100">
			<Stack direction="row" spacing="10" align="center">
				<Stack direction="row" spacing="4">
					<Image w="80px" src={course?.featured_image_url} />
					<Stack direction="column" spacing="2">
						<Stack direction="row" spacing="1">
							{course?.categories?.map(
								(category: { id: number; name: string; slug: string }) => (
									<Tag
										key={category.id}
										size="sm"
										borderRadius="full"
										colorScheme="blue"
										border="1px"
										borderColor="gray.200">
										<TagLabel>{category?.name}</TagLabel>
									</Tag>
								)
							)}
						</Stack>
						<Text fontSize="md" fontWeight="bold">
							{__('Swift Courses', 'masteriyo')}
						</Text>

						<Stack
							direction="row"
							spacing="0.5"
							color="gray.700"
							align="center">
							<Icon as={BiTime} />
							<Text fontSize="xs" fontWeight="medium">
								{shortEnglishHumanizer(course?.duration * 60 * 1000, {
									units: ['h', 'm'],
								})}
							</Text>
						</Stack>
					</Stack>
				</Stack>

				<Stack direction="row" align="center" justify="space-between" flex="1">
					<Stack
						direction="column"
						justifyContent="center"
						spacing="2"
						flex="1">
						<Stack direction="row" spacing="4" align="center">
							<Progress size="sm" value={20} rounded="full" w="full" />
							<Text fontWeight="medium" fontSize="sm" w="3xs">
								{__('15% Complete', 'masteriyo')}
							</Text>
						</Stack>
						<Text color="gray.500" fontSize="xs">
							{__('Started Jan 5, 2020', 'masteriyo')}
						</Text>
					</Stack>
					<Button
						colorScheme="blue"
						boxShadow="none"
						size="sm"
						borderRadius="full"
						textTransform="uppercase">
						{__('Continue', 'masteriyo')}
					</Button>
				</Stack>
			</Stack>
		</Box>
	);
};

export default CourseGridItem;
