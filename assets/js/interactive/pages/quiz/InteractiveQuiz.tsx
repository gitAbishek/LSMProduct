import {
	Alert,
	AlertIcon,
	Box,
	Container,
	Heading,
	Stack,
	Text,
	useDisclosure,
	useToast,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import dayjs from 'dayjs';
import React, { useState } from 'react';
import { FormProvider, useForm } from 'react-hook-form';
import { useMutation, useQuery, useQueryClient } from 'react-query';
import { useParams } from 'react-router-dom';
import FullScreenLoader from '../../../back-end/components/layout/FullScreenLoader';
import urls from '../../../back-end/constants/urls';
import { QuizSchema } from '../../../back-end/schemas';
import API from '../../../back-end/utils/api';
import { deepClean } from '../../../back-end/utils/utils';
import ContentNav from '../../components/ContentNav';
import FloatingNavigation from '../../components/FloatingNavigation';
import FloatingTimer from '../../components/FloatingTimer';
import Header from '../../components/Header';
import Sidebar from '../../components/Sidebar';
import { CourseProgressItemsMap, CourseProgressMap } from '../../schemas';
import QuizFields from './components/QuizFields';
import QuizStart from './components/QuizStart';
import ScoreBoard from './components/ScoreBoard';

const InteractiveQuiz = () => {
	const { quizId, courseId }: any = useParams();
	const quizAPI = new API(urls.quizes);
	const quizAttemptsAPI = new API(urls.quizesAttempts);
	const methods = useForm();
	const [scoreBoardData, setScoreBoardData] = useState<any>(null);
	const [quizStartedOn, setQuizStartedOn] = useState<any>(null);
	const [quizAboutToExpire, setQuizAboutToExpire] = useState<boolean>(false);
	const progressItemAPI = new API(urls.courseProgressItem);
	const progressAPI = new API(urls.courseProgress);

	const queryClient = useQueryClient();
	const toast = useToast();
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

	const quizQuery = useQuery<QuizSchema, Error>(
		[`section${quizId}`, quizId],
		() => quizAPI.get(quizId)
	);

	const quizProgress = useQuery<any, Error>(
		[`attempt${quizId}`, quizId],
		() =>
			quizAttemptsAPI.list({
				quiz_id: quizId,
				per_page: 1,
			}),
		{
			onSuccess: (data: any) => {
				setScoreBoardData(data.data[0]);
			},
		}
	);

	const startQuiz = useMutation((quizId: number) => quizAPI.start(quizId));

	const completeMutation = useMutation((data: CourseProgressItemsMap) =>
		progressItemAPI.store(data)
	);

	const checkQuizAnswers = useMutation((data: any) =>
		quizAPI.check(quizId, data)
	);

	const completeQuiz = useQuery<CourseProgressItemsMap>(
		[`completeQuery${quizId}`, quizId],
		() => progressItemAPI.list({ item_id: quizId, course_id: courseId })
	);

	const onStartPress = () => {
		completeMutation.mutate(
			{
				course_id: courseId,
				item_id: quizId,
				item_type: 'quiz',
				completed: false,
			},
			{
				onSuccess: () => {
					completeQuiz.refetch();
					queryClient.invalidateQueries(`courseProgress${courseId}`);
				},
			}
		);

		startQuiz.mutate(quizId, {
			onSuccess: (data: any) => {
				setQuizStartedOn(dayjs(data.attempt_started_at).format());
				setScoreBoardData(null);
			},
		});
		setQuizAboutToExpire(false);
	};

	const onSubmit = (data: any) => {
		checkQuizAnswers.mutate(deepClean(data), {
			onSuccess: (data: any) => {
				queryClient.invalidateQueries(`attempt${quizId}`);
				setQuizStartedOn(null);
				setScoreBoardData(data);
			},
		});
		methods.reset();
	};

	const onCompletePress = () => {
		completeMutation.mutate(
			{
				course_id: courseId,
				item_id: quizId,
				item_type: 'quiz',
				completed: true,
			},
			{
				onSuccess: () => {
					queryClient.invalidateQueries(`completeQuery${quizId}`);
					queryClient.invalidateQueries(`courseProgress${courseId}`);

					toast({
						title: __('Mark as Completed', 'masteriyo'),
						description: __('Quiz has been marked as completed.', 'masteriyo'),
						isClosable: true,
						status: 'success',
					});
				},
			}
		);
	};

	const onQuizeExpire = () => onSubmit(methods.getValues());

	if (
		quizQuery.isSuccess &&
		quizProgress.isSuccess &&
		courseProgressQuery.isSuccess
	) {
		const maxLimitReached =
			quizQuery?.data?.attempts_allowed != 0 &&
			quizProgress?.data.data[0]?.total_attempts >=
				quizQuery?.data?.attempts_allowed;

		return (
			<Box h="full" overflowX="hidden" pos="relative">
				<Sidebar
					isOpen={isSidebarOpen}
					onToggle={onSidebarToggle}
					isHeaderOpen={isHeaderOpen}
					items={courseProgressQuery.data.items}
					name={courseProgressQuery.data.name}
					coursePermalink={courseProgressQuery.data.course_permalink}
					activeIndex={quizQuery?.data?.parent_menu_order}
				/>
				<Box transition="all 0.35s" ml={isSidebarOpen ? '300px' : 0}>
					<Header
						summary={courseProgressQuery.data.summary}
						isOpen={isHeaderOpen}
						onToggle={onHeaderToggle}
					/>

					<Container centerContent maxW="container.xl" py="16">
						<Box bg="white" p={['5', null, '14']} shadow="box" w="full">
							<FormProvider {...methods}>
								<form onSubmit={methods.handleSubmit(onSubmit)}>
									<Stack direction="column" spacing="8">
										<Heading as="h5">{quizQuery?.data?.name}</Heading>

										{quizQuery?.data?.description && (
											<Text
												dangerouslySetInnerHTML={{
													__html: quizQuery?.data?.description,
												}}
											/>
										)}
										{maxLimitReached && (
											<Alert status="error" fontSize="sm" p="2.5">
												<AlertIcon />
												{__(
													'You have reached the maximum limit to start quiz.',
													'masteriyo'
												)}
											</Alert>
										)}

										{quizStartedOn ? (
											<QuizFields
												quizAboutToExpire={quizAboutToExpire}
												quizData={quizQuery.data}
											/>
										) : scoreBoardData ? (
											<ScoreBoard
												scoreData={scoreBoardData}
												onStartPress={onStartPress}
												isButtonLoading={startQuiz.isLoading}
												isFinishButtonLoading={completeMutation.isLoading}
												isButtonDisabled={completeQuiz?.data?.completed}
												onCompletePress={onCompletePress}
												limitReached={maxLimitReached}
											/>
										) : (
											<QuizStart
												quizData={quizQuery.data}
												onStartPress={onStartPress}
												isDisabled={maxLimitReached}
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
							isSidebarOpened={isSidebarOpen}
						/>
						{quizStartedOn && quizQuery.data.duration !== 0 && (
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
				</Box>
			</Box>
		);
	}

	return <FullScreenLoader />;
};

export default InteractiveQuiz;
