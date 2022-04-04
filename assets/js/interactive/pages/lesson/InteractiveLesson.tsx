import {
	Box,
	Container,
	Heading,
	Image,
	Stack,
	Text,
	useDisclosure,
	useToast,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { useMutation, useQuery, useQueryClient } from 'react-query';
import { useParams } from 'react-router-dom';
import FullScreenLoader from '../../../back-end/components/layout/FullScreenLoader';
import urls from '../../../back-end/constants/urls';
import { LessonSchema, MediaSchema } from '../../../back-end/schemas';
import API from '../../../back-end/utils/api';
import { getSrcSet } from '../../../back-end/utils/image';
import MediaAPI from '../../../back-end/utils/media';
import ContentNav from '../../components/ContentNav';
import FloatingNavigation from '../../components/FloatingNavigation';
import Header from '../../components/Header';
import Sidebar from '../../components/Sidebar';
import { CourseProgressItemsMap, CourseProgressMap } from '../../schemas';
import VideoPlayer from './VideoPlayer';

const InteractiveLesson = () => {
	const { lessonId, courseId }: any = useParams();
	const lessonAPI = new API(urls.lessons);
	const toast = useToast();
	const queryClient = useQueryClient();

	const imageAPi = new MediaAPI();
	const progressAPI = new API(urls.courseProgress);
	const progressItemAPI = new API(urls.courseProgressItem);

	const { isOpen: isSidebarOpen, onToggle: onSidebarToggle } = useDisclosure({
		defaultIsOpen: true,
	});
	const { isOpen: isHeaderOpen, onToggle: onHeaderToggle } = useDisclosure({
		defaultIsOpen: true,
	});

	const courseProgressQuery = useQuery<CourseProgressMap>(
		[`courseProgress${courseId}`, courseId],
		() => progressAPI.store({ course_id: courseId })
	);

	const lessonQuery = useQuery<LessonSchema>(
		[`interactiveLesson${lessonId}`, lessonId],
		() => lessonAPI.get(lessonId)
	);

	const completeQuery = useQuery<CourseProgressItemsMap>(
		[`completeQuery${lessonId}`, lessonId],
		() => progressItemAPI.list({ item_id: lessonId, course_id: courseId })
	);

	const imageQuery = useQuery<MediaSchema>(
		[
			`interactiveLessonimage${lessonQuery?.data?.featured_image}`,
			lessonQuery?.data?.featured_image,
		],
		() => imageAPi.get(lessonQuery?.data?.featured_image || 0),
		{
			enabled: lessonQuery.isSuccess,
		}
	);

	const completeMutation = useMutation((data: CourseProgressItemsMap) =>
		progressItemAPI.store(data)
	);

	const onCompletePress = () => {
		completeMutation.mutate(
			{
				course_id: courseId,
				item_id: lessonId,
				item_type: 'lesson',
				completed: true,
			},
			{
				onSuccess: () => {
					queryClient.invalidateQueries(`completeQuery${lessonId}`);
					queryClient.invalidateQueries(`courseProgress${courseId}`);

					toast({
						title: __('Mark as Completed', 'masteriyo'),
						description: __(
							'Lesson has been marked as completed.',
							'masteriyo'
						),
						isClosable: true,
						status: 'success',
					});
				},
			}
		);
	};

	if (
		courseProgressQuery.isSuccess &&
		lessonQuery.isSuccess &&
		imageQuery.isSuccess
	) {
		return (
			<Box h="full" overflowX="hidden" pos="relative">
				<Sidebar
					isOpen={isSidebarOpen}
					onToggle={onSidebarToggle}
					isHeaderOpen={isHeaderOpen}
					items={courseProgressQuery.data.items}
					name={courseProgressQuery.data.name}
					coursePermalink={courseProgressQuery.data.course_permalink}
					activeIndex={lessonQuery?.data?.parent_menu_order}
				/>
				<Box transition="all 0.35s" ml={isSidebarOpen ? '300px' : 0}>
					<Header
						summary={courseProgressQuery.data.summary}
						isOpen={isHeaderOpen}
						onToggle={onHeaderToggle}
					/>

					<Container centerContent maxW="container.lg" py="16">
						<Box bg="white" p={['5', null, '14']} shadow="box" w="full">
							<Stack direction="column" spacing="8">
								<Heading as="h5">{lessonQuery?.data?.name}</Heading>
								{lessonQuery?.data?.video_source_url ? (
									<VideoPlayer
										type={lessonQuery?.data?.video_source}
										url={lessonQuery?.data?.video_source_url}
									/>
								) : null}
								<Image
									src={imageQuery?.data?.source_url}
									srcSet={getSrcSet(imageQuery?.data)}
								/>

								<Text
									className="masteriyo-interactive-description"
									dangerouslySetInnerHTML={{
										__html: lessonQuery?.data?.description,
									}}
								/>
							</Stack>
							<FloatingNavigation
								navigation={lessonQuery?.data?.navigation}
								courseId={courseId}
								isSidebarOpened={isSidebarOpen}
							/>
						</Box>
						<ContentNav
							navigation={lessonQuery?.data?.navigation}
							courseId={courseId}
							onCompletePress={onCompletePress}
							isButtonLoading={completeMutation.isLoading}
							isButtonDisabled={completeQuery?.data?.completed}
						/>
					</Container>
				</Box>
			</Box>
		);
	}

	return <FullScreenLoader />;
};

export default InteractiveLesson;
