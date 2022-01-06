import {
	Avatar,
	Box,
	Button,
	Heading,
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
import humanizeDuration from 'humanize-duration';
import React from 'react';
import { BiTime } from 'react-icons/bi';
import { IoMdStar, IoMdStarHalf, IoMdStarOutline } from 'react-icons/io';
import { useQuery } from 'react-query';
import urls from '../../back-end/constants/urls';
import API from '../../back-end/utils/api';
import { getLocalTime } from '../../back-end/utils/utils';
import { CourseProgressMap } from '../../interactive/schemas';
import { MyCoursesSchema } from '../schemas';

interface Props {
	courseData: MyCoursesSchema;
}

const CourseItem: React.FC<Props> = (props) => {
	const { course, started_at } = props.courseData;
	const progressAPI = new API(urls.courseProgress);

	const courseProgressQuery = useQuery<CourseProgressMap>(
		[`courseProgress${course?.id}`, course?.id],
		() => progressAPI.store({ course_id: course?.id }),
		{
			enabled: !!course?.id,
		}
	);

	if (course) {
		return (
			<Box borderWidth="1px" borderColor="gray.100">
				<Box as="figure" pos="relative">
					<Image
						src={course?.featured_image_url}
						alt={course?.name}
						height="180px"
						objectFit="cover"
					/>
					{course?.difficulty && (
						<Tag
							pos="absolute"
							top="3"
							left="3"
							bg="blue.500"
							color="white"
							borderRadius="full"
							fontWeight="medium"
							size="sm">
							<TagLabel>{course?.difficulty?.name}</TagLabel>
						</Tag>
					)}
				</Box>
				<Stack direction="column" p="6" spacing="3">
					<Stack direction="column" spacing="6">
						<Stack direction="column" spacing="3">
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

							<Heading as="h3" fontSize="lg">
								{course?.name}
							</Heading>
							<Stack
								direction="row"
								spacing="3"
								align="center"
								justify="space-between">
								<Stack direction="row" spacing="1" align="center">
									<Avatar src={course?.author?.avatar_url} size="xs" />
									<Text fontSize="xs" fontWeight="bold">
										{course?.author?.display_name}
									</Text>
								</Stack>
								<Stack direction="row" spacing="0">
									<Icon as={IoMdStar} />
									<Icon as={IoMdStar} />
									<Icon as={IoMdStar} />
									<Icon as={IoMdStarHalf} />
									<Icon as={IoMdStarOutline} />
								</Stack>
							</Stack>
						</Stack>

						<Stack
							direction="row"
							justify="space-between"
							align="center"
							fontSize="sm"
							fontWeight="500"
							color="gray.500">
							<Stack direction="row" align="center" spacing="1">
								<Icon as={BiTime} />
								<Text>{humanizeDuration(course?.duration * 60 * 1000)}</Text>
							</Stack>
							{courseProgressQuery.isSuccess && (
								<Text>
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
							)}
						</Stack>
						{courseProgressQuery.isSuccess && (
							<Box mt={5}>
								<Progress
									rounded="full"
									size="xs"
									value={courseProgressQuery?.data?.summary.total.completed}
									max={
										courseProgressQuery?.data?.summary.total.completed +
										courseProgressQuery?.data?.summary.total.pending
									}
								/>
							</Box>
						)}

						<Stack
							direction="row"
							spacing="4"
							justify="space-between"
							align="center"
							color="gray.500"
							fontSize="xs">
							{started_at && (
								<Text>
									{getLocalTime(started_at).toLocaleString().split(', ')[0]}
								</Text>
							)}
							<Link href={course?.start_course_url}>
								<Button colorScheme="blue" size="sm" borderRadius="full">
									{__('Continue', 'masteriyo')}
								</Button>
							</Link>
						</Stack>
					</Stack>
				</Stack>
			</Box>
		);
	}
	return <></>;
};

export default CourseItem;
