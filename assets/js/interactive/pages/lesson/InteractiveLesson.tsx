import {
	Box,
	Container,
	Heading,
	Image,
	Stack,
	Text,
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
import { CourseProgressItemMap } from '../../schemas';

const InteractiveLesson = () => {
	const { lessonId, courseId }: any = useParams();
	const lessonAPI = new API(urls.lessons);
	const [mediaId, setMediaId] = useState(0);
	const toast = useToast();
	const queryClient = useQueryClient();

	const imageAPi = new MediaAPI();
	const progressAPI = new API(urls.interactiveProgress);

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

	const imageQuery = useQuery(
		[`interactiveLessonimage${mediaId}`, mediaId],
		() => imageAPi.get(mediaId),
		{
			enabled: mediaId !== 0,
		}
	);

	type CompletedDataMap = {
		course_id: number | any;
		items: [CourseProgressItemMap];
	};

	const completeMutation = useMutation((data: CompletedDataMap) =>
		progressAPI.store(data)
	);

	if (lessonQuery.isLoading) {
		return <FullScreenLoader />;
	}

	const onCompletePress = () => {
		completeMutation.mutate(
			{
				course_id: courseId,
				items: [{ item_id: lessonId, item_type: 'lesson', completed: true }],
			},
			{
				onSuccess: () => {
					queryClient.invalidateQueries(`courseProgress${courseId}`);
					toast({
						title: __('Mark as completed', 'masteriyo'),
						description: __('Lesson has been marked as completed', 'masteriyo'),
						isClosable: true,
						status: 'success',
					});
				},
			}
		);
	};

	return (
		<Container centerContent maxW="container.xl" py="16">
			<Box bg="white" p="14" shadow="box" w="full">
				<Stack direction="column" spacing="8">
					<Heading as="h5">{lessonQuery?.data?.name}</Heading>
					<Image src={imageQuery?.data?.source_url} />
					<Text
						dangerouslySetInnerHTML={{ __html: lessonQuery?.data?.description }}
					/>
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
			/>
		</Container>
	);
};

export default InteractiveLesson;
