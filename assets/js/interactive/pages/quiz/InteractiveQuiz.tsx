import { Box, Container, Heading, Stack, Text } from '@chakra-ui/react';
import { useStateMachine } from 'little-state-machine';
import React from 'react';
import { FormProvider, useForm } from 'react-hook-form';
import { useQuery } from 'react-query';
import { useParams } from 'react-router-dom';
import FullScreenLoader from '../../../back-end/components/layout/FullScreenLoader';
import urls from '../../../back-end/constants/urls';
import { QuizSchema } from '../../../back-end/schemas';
import API from '../../../back-end/utils/api';
import { updateQuizProgress } from '../../actions';
import ContentNav from '../../components/ContentNav';
import FloatingNavigation from '../../components/FloatingNavigation';
import FloatingTimer from '../../components/FloatingTimer';
import QuizFields from './components/QuizFields';
import QuizStart from './components/QuizStart';

const InteractiveQuiz = () => {
	const { quizId }: any = useParams();
	const quizAPI = new API(urls.quizes);
	const methods = useForm();

	const { state, actions } = useStateMachine({
		updateQuizProgress,
	});

	const startedOn = state?.quizProgress[quizId]?.startedOn || false;

	const quizQuery = useQuery<QuizSchema, Error>(
		[`section${quizId}`, quizId],
		() => quizAPI.get(quizId),
		{}
	);

	const onStartPress = () => {
		actions.updateQuizProgress({
			quizProgress: { [quizId]: { startedOn: Date.now() } },
		});
	};
	const onSubmit = (data: any) => {
		console.log(data);
	};
	if (quizQuery.isSuccess) {
		return (
			<Container centerContent maxW="container.xl" py="16">
				<Box bg="white" p="14" shadow="box" w="full">
					<FormProvider {...methods}>
						<form onSubmit={methods.handleSubmit(onSubmit)}>
							<Stack direction="column" spacing="8">
								<Heading as="h5">{quizQuery?.data?.name}</Heading>
								<Text
									dangerouslySetInnerHTML={{
										__html: quizQuery?.data?.description,
									}}
								/>
								{startedOn ? (
									<QuizFields />
								) : (
									<QuizStart
										quizData={quizQuery.data}
										onStartPress={onStartPress}
									/>
								)}
							</Stack>
						</form>
					</FormProvider>
				</Box>
				<FloatingNavigation
					navigation={quizQuery?.data?.navigation}
					courseId={quizQuery?.data?.course_id}
				/>
				{startedOn && (
					<FloatingTimer
						startedOn={startedOn}
						duration={quizQuery?.data?.duration}
						quizId={quizQuery?.data?.id}
					/>
				)}

				<ContentNav
					onCompletePress={methods.handleSubmit(onSubmit)}
					navigation={quizQuery?.data?.navigation}
					courseId={quizQuery?.data?.course_id}
				/>
			</Container>
		);
	}

	return <FullScreenLoader />;
};

export default InteractiveQuiz;
