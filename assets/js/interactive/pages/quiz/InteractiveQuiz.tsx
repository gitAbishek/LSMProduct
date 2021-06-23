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
import QuizStart from './components/QuizStart';
import FloatingTimer from '../../components/FloatingTimer';

const InteractiveQuiz = () => {
	const { quizId }: any = useParams();
	const quizAPI = new API(urls.quizes);
	const [mediaId, setMediaId] = useState(0);
	const imageAPi = new MediaAPI();

	const time = new Date();
	time.setSeconds(time.getSeconds() + 600); // 10 minutes timer

	const quizQuery = useQuery(
		[`section${quizId}`, quizId],
		() => quizAPI.get(quizId),
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

	if (quizQuery.isLoading) {
		return <FullScreenLoader />;
	}

	return (
		<Container centerContent maxW="container.xl" py="16">
			<Box bg="white" p="14" shadow="box" w="full">
				<Stack direction="column" spacing="8">
					<Heading as="h5">{quizQuery?.data?.name}</Heading>
					<Image src={imageQuery?.data?.source_url} />
					<QuizStart />
					<Text
						dangerouslySetInnerHTML={{ __html: quizQuery?.data?.description }}
					/>
				</Stack>
			</Box>
			<FloatingNavigation
				navigation={quizQuery?.data?.navigation}
				courseId={quizQuery?.data?.course_id}
			/>
			<FloatingTimer expiryTimestamp={time} />
			<ContentNav
				navigation={quizQuery?.data?.navigation}
				courseId={quizQuery?.data?.course_id}
			/>
		</Container>
	);
};

export default InteractiveQuiz;
