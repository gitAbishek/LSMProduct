import { Box, Container, Heading, Stack, Text } from '@chakra-ui/react';
import React, { useState } from 'react';
import { FormProvider, useForm } from 'react-hook-form';
import { useMutation, useQuery } from 'react-query';
import { useParams } from 'react-router-dom';
import FullScreenLoader from '../../../back-end/components/layout/FullScreenLoader';
import urls from '../../../back-end/constants/urls';
import { QuizSchema } from '../../../back-end/schemas';
import API from '../../../back-end/utils/api';
import { deepClean, getLocalTime } from '../../../back-end/utils/utils';
import ContentNav from '../../components/ContentNav';
import FloatingNavigation from '../../components/FloatingNavigation';
import FloatingTimer from '../../components/FloatingTimer';
import QuizFields from './components/QuizFields';
import QuizStart from './components/QuizStart';
import ScoreBoard from './components/ScoreBoard';

const InteractiveQuiz = () => {
	const { quizId }: any = useParams();
	const quizAPI = new API(urls.quizes);
	const quizAttemptsAPI = new API(urls.quizesAttempts);
	const methods = useForm();
	const [scoreBoardData, setScoreBoardData] = useState<any>(null);
	const [quizStartedOn, setQuizStartedOn] = useState<any>(null);
	const [quizAboutToExpire, setQuizAboutToExpire] = useState<boolean>(false);
	const [attemptMessage, setAttemptMessage] = useState<string>('');

	const quizQuery = useQuery<QuizSchema, Error>(
		[`section${quizId}`, quizId],
		() => quizAPI.get(quizId)
	);

	useQuery<QuizSchema, Error>(
		[`attempt${quizId}`, quizId],
		() =>
			quizAttemptsAPI.list({
				quiz_id: quizId,
				status: 'attempt_started',
				per_page: 1,
			}),
		{
			onSuccess: (data: any) => {
				if (data[0]?.attempt_started_at) {
					setQuizStartedOn(getLocalTime(data[0].attempt_started_at));
				}
			},
		}
	);

	const startQuiz = useMutation((quizId: number) => quizAPI.start(quizId));

	const checkQuizAnswers = useMutation((data: any) =>
		quizAPI.check(quizId, data)
	);

	const onStartPress = () => {
		startQuiz.mutate(quizId, {
			onSuccess: (data: any) => {
				setQuizStartedOn(getLocalTime(data.attempt_started_at));
				setScoreBoardData(null);
			},
			onError: (error: any) => setAttemptMessage(error.response?.data?.message),
		});
		setQuizAboutToExpire(false);
	};

	const onSubmit = (data: any) => {
		checkQuizAnswers.mutate(deepClean(data), {
			onSuccess: (data: any) => {
				setQuizStartedOn(null);
				setScoreBoardData(data);
			},
		});
	};

	const onQuizeExpire = () => onSubmit(methods.getValues());

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

								{quizStartedOn ? (
									<QuizFields quizAboutToExpire={quizAboutToExpire} />
								) : scoreBoardData ? (
									<ScoreBoard
										scoreData={scoreBoardData}
										onStartPress={onStartPress}
										isButtonLoading={startQuiz.isLoading}
										attemptMessage={attemptMessage}
									/>
								) : (
									<QuizStart
										quizData={quizQuery.data}
										onStartPress={onStartPress}
										attemptMessage={attemptMessage}
										isButtonLoading={startQuiz.isLoading}
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
				{quizStartedOn && (
					<FloatingTimer
						startedOn={quizStartedOn}
						duration={quizQuery?.data?.duration}
						quizId={quizQuery?.data?.id}
						onQuizeExpire={onQuizeExpire}
						quizeAboutToExpire={setQuizAboutToExpire}
					/>
				)}

				<ContentNav
					type="quiz"
					onCompletePress={methods.handleSubmit(onSubmit)}
					navigation={quizQuery?.data?.navigation}
					courseId={quizQuery?.data?.course_id}
					isButtonDisabled={scoreBoardData}
					isButtonLoading={checkQuizAnswers.isLoading}
					quizStarted={quizStartedOn}
				/>
			</Container>
		);
	}

	return <FullScreenLoader />;
};

export default InteractiveQuiz;
