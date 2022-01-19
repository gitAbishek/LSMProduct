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
import React, { useState } from 'react';
import { useMutation, useQuery, useQueryClient } from 'react-query';
import { useParams } from 'react-router-dom';
import FullScreenLoader from '../../../back-end/components/layout/FullScreenLoader';
import urls from '../../../back-end/constants/urls';
import API from '../../../back-end/utils/api';
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
	const [mediaId, setMediaId] = useState(0);
	const toast = useToast();
	const queryClient = useQueryClient();

	const imageAPi = new MediaAPI();
	const progressAPI = new API(urls.courseProgress);
	const progressItemAPI = new API(urls.courseProgressItem);

	const { isOpen: isSidebarOpen, onToggle: onSidebarToggle } = useDisclosure();
	const { isOpen: isHeaderOpen, onToggle: onHeaderToggle } = useDisclosure({
		defaultIsOpen: true,
	});

	const courseProgressQuery = useQuery<CourseProgressMap>(
		[`courseProgressItem${courseId}`, courseId],
		() => progressAPI.store({ course_id: courseId })
	);

	const lessonQuery = useQuery(
		[`interactiveLesson${lessonId}`, lessonId],
		() => lessonAPI.get(lessonId),
		{
			onSuccess: (data: any) => {
				setMediaId(data.featured_image);
			},
			onError: () => {
				return;
			},
		}
	);

	const completeQuery = useQuery<CourseProgressItemsMap>(
		[`completeQuery${lessonId}`, lessonId],
		() => progressAPI.list({ item_id: lessonId, course_id: courseId })
	);

	const imageQuery = useQuery(
		[`interactiveLessonimage${mediaId}`, mediaId],
		() => imageAPi.get(mediaId),
		{
			enabled: mediaId !== 0,
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

	if (courseProgressQuery.isSuccess && lessonQuery.isSuccess) {
		return (
			<Box h="full" overflowX="hidden" pos="relative">
				<Box transition="all 0.35s" ml={isSidebarOpen ? '300px' : 0}></Box>
				<Header
					summary={courseProgressQuery.data.summary}
					isOpen={isHeaderOpen}
					onToggle={onHeaderToggle}
				/>

				<Sidebar
					isOpen={isSidebarOpen}
					onToggle={onSidebarToggle}
					isHeaderOpen={isHeaderOpen}
					items={courseProgressQuery.data.items}
					name={courseProgressQuery.data.name}
					coursePermalink={courseProgressQuery.data.course_permalink}
				/>
				<Container centerContent maxW="container.lg" py="16">
					<Box bg="white" p={['5', null, '14']} shadow="box" w="full">
						<Stack direction="column" spacing="8">
							<Heading as="h5">{lessonQuery?.data?.name}</Heading>
							{lessonQuery?.data?.video_source_url && (
								<VideoPlayer
									type={lessonQuery?.data?.video_source}
									url={lessonQuery?.data?.video_source_url}
								/>
							)}
							<Image src={imageQuery?.data?.source_url} />

							<Text
								className="masteriyo-interactive-description"
								dangerouslySetInnerHTML={{
									__html: lessonQuery?.data?.description,
								}}
							/>

							{/* {!isEmpty(lessonQuery?.data?.attachments) && (
						<LessonAttachment lessonQuery={lessonQuery?.data} />
					)} */}
						</Stack>
						<FloatingNavigation
							navigation={lessonQuery?.data?.navigation}
							courseId={courseId}
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
		);
	}

	return <FullScreenLoader />;
};

export default InteractiveLesson;
