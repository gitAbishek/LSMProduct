import React from 'react';
import { Box, Container, Heading, Text } from '@chakra-ui/react';
import { useParams } from 'react-router-dom';
import { useQuery } from 'react-query';
import API from '../../../back-end/utils/api';
import urls from '../../../back-end/constants/urls';
import FullScreenLoader from '../../../back-end/components/layout/FullScreenLoader';

const InteractiveLesson = () => {
	const { lessonId }: any = useParams();
	const lessonAPI = new API(urls.lessons);

	const lessonQuery = useQuery(
		[`section${lessonId}`, lessonId],
		() => lessonAPI.get(lessonId),
		{
			onError: () => {
				return;
			},
		}
	);

	if (lessonQuery.isLoading) {
		return <FullScreenLoader />;
	}

	console.log(lessonQuery.data);

	return (
		<Container centerContent maxW="container.xl" py="8">
			<Box bg="white" p="8" shadow="box" w="full">
				<Heading as="h5">{lessonQuery?.data?.name}</Heading>
				<Text>{lessonQuery?.data?.description}</Text>
			</Box>
		</Container>
	);
};

export default InteractiveLesson;
