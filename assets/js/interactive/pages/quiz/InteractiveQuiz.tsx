import { Box, Container, Heading, Stack, Text } from '@chakra-ui/react';
import React from 'react';
import { useQuery } from 'react-query';
import { useParams } from 'react-router-dom';
import FullScreenLoader from '../../../back-end/components/layout/FullScreenLoader';
import urls from '../../../back-end/constants/urls';
import { QuizSchema } from '../../../back-end/schemas';
import API from '../../../back-end/utils/api';
import ContentNav from '../../components/ContentNav';
import FloatingNavigation from '../../components/FloatingNavigation';
import FloatingTimer from '../../components/FloatingTimer';
import QuizStart from './components/QuizStart';

const InteractiveQuiz = () => {
	const { quizId }: any = useParams();
	const quizAPI = new API(urls.quizes);

	// 10 minutes timer

	const quizQuery = useQuery<QuizSchema>([`section${quizId}`, quizId], () =>
		quizAPI.get(quizId)
	);

	if (quizQuery.isLoading) {
		return <FullScreenLoader />;
	}

	return (
		<Container centerContent maxW="container.xl" py="16">
			<Box bg="white" p="14" shadow="box" w="full">
				<Stack direction="column" spacing="8">
					<Heading as="h5">{quizQuery?.data?.name}</Heading>
					<QuizStart quizData={quizQuery?.data} />
					<Text
						dangerouslySetInnerHTML={{ __html: quizQuery?.data?.description }}
					/>
				</Stack>
			</Box>
			<FloatingNavigation
				navigation={quizQuery?.data?.navigation}
				courseId={quizQuery?.data?.course_id}
			/>
			<FloatingTimer duration={quizQuery?.data?.duration} />
			<ContentNav
				navigation={quizQuery?.data?.navigation}
				courseId={quizQuery?.data?.course_id}
			/>
		</Container>
	);
};

export default InteractiveQuiz;
