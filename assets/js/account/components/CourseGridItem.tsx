import {
	Box,
	Button,
	Icon,
	Image,
	Link,
	Progress,
	Stack,
	Tag,
	TagLabel,
	Text,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { BiTime } from 'react-icons/bi';
<<<<<<< HEAD
import { useQuery } from 'react-query';
import urls from '../../back-end/constants/urls';
import API from '../../back-end/utils/api';
import {
	getLocalTime,
	shortEnglishHumanizer,
} from '../../back-end/utils/utils';
import { CourseProgressMap } from '../../interactive/schemas';
=======
import { shortEnglishHumanizer } from '../../back-end/utils/utils';
>>>>>>> e58395ab (Implement course api for the course list on dashboard)
import { MyCoursesSchema } from '../schemas';

interface Props {
	course: MyCoursesSchema;
}
<<<<<<< HEAD

const CourseGridItem: React.FC<Props> = (props) => {
	const { course, started_at } = props.course;
	const progressAPI = new API(urls.courseProgress);

	const courseProgressQuery = useQuery<CourseProgressMap>(
		[`courseProgress${course?.id}`, course?.id],
		() => progressAPI.store({ course_id: course?.id }),
		{
			enabled: !!course?.id,
		}
	);
=======

const CourseGridItem: React.FC<Props> = (props) => {
	const { course, started_at } = props.course;

>>>>>>> e58395ab (Implement course api for the course list on dashboard)
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
							{course?.name}
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
						{courseProgressQuery.isSuccess && (
							<Stack direction="row" spacing="4" align="center">
								<Progress
									size="sm"
									rounded="full"
									w="full"
									value={courseProgressQuery?.data?.summary.total.completed}
									max={
										courseProgressQuery?.data?.summary.total.completed +
										courseProgressQuery?.data?.summary.total.pending
									}
								/>
								<Text fontWeight="medium" fontSize="sm" w="3xs">
									{courseProgressQuery?.data?.summary?.total?.completed === 0 &&
									courseProgressQuery?.data?.summary?.total?.pending === 0
										? 0
										: Math?.round(
												(courseProgressQuery?.data?.summary?.total?.completed /
													(courseProgressQuery?.data?.summary?.total?.pending +
														courseProgressQuery?.data?.summary?.total
															?.completed)) *
													100
										  )}
									{__('% Complete', 'masteriyo')}
								</Text>
							</Stack>
						)}
						{started_at && (
							<Text color="gray.500" fontSize="xs">
								{__('Started on ', 'masteriyo')}
								{getLocalTime(started_at).toLocaleString()}
							</Text>
						)}
					</Stack>
					<Link href={course?.start_course_url}>
						<Button
							colorScheme="blue"
							boxShadow="none"
							size="sm"
							borderRadius="full"
							textTransform="uppercase">
							{__('Continue', 'masteriyo')}
						</Button>
					</Link>
				</Stack>
			</Stack>
		</Box>
	);
};

export default CourseGridItem;
