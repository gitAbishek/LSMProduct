import React, { useState } from 'react';
import { Box, Container, Heading, Text, Image } from '@chakra-ui/react';
import { useParams } from 'react-router-dom';
import { useQuery } from 'react-query';
import API from '../../../back-end/utils/api';
import urls from '../../../back-end/constants/urls';
import FullScreenLoader from '../../../back-end/components/layout/FullScreenLoader';
import MediaAPI from '../../../back-end/utils/media';

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
				console.log(data);
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

	console.log(imageQuery.data);

	return (
		<Container centerContent maxW="container.xl" py="8">
			<Box bg="white" p="8" shadow="box" w="full">
				<Heading as="h5">{lessonQuery?.data?.name}</Heading>
				<Text>{lessonQuery?.data?.description}</Text>
				<Image src={imageQuery?.data?.source_url} />
			</Box>
		</Container>
	);
};

export default InteractiveLesson;
