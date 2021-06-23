import React, { useState } from 'react';
import { Box, Container, Heading, Text, Image, Stack } from '@chakra-ui/react';
import { useParams } from 'react-router-dom';
import { useQuery } from 'react-query';
import API from '../../../back-end/utils/api';
import urls from '../../../back-end/constants/urls';
import FullScreenLoader from '../../../back-end/components/layout/FullScreenLoader';
import MediaAPI from '../../../back-end/utils/media';
import FloatingNavigation from '../../components/FloatingNavigation';
import ContentNav from '../../components/ContentNav';

const InteractiveLesson = () => {
	const { lessonId }: any = useParams();
	const lessonAPI = new API(urls.lessons);
	const [mediaId, setMediaId] = useState(0);
	const imageAPi = new MediaAPI();

	const lessonQuery = useQuery(
		[`section${lessonId}`, lessonId],
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
		[`image${mediaId}`, mediaId],
		() => imageAPi.get(mediaId),
		{
			enabled: mediaId !== 0,
		}
	);

	if (lessonQuery.isLoading) {
		return <FullScreenLoader />;
	}

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
					courseId={lessonQuery?.data?.course_id}
				/>
			</Box>
			<ContentNav
				navigation={lessonQuery?.data?.navigation}
				courseId={lessonQuery?.data?.course_id}
			/>
		</Container>
	);
};

export default InteractiveLesson;
