import {
	Alert,
	AlertIcon,
	Box,
	Container,
	Heading,
	Stack,
	Text,
	useDisclosure,
	useMediaQuery,
	useToast,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { FormProvider, useForm } from 'react-hook-form';
import useFormPersist from 'react-hook-form-persist';
import { useMutation, useQuery, useQueryClient } from 'react-query';
import { useParams } from 'react-router-dom';
import FullScreenLoader from '../../../back-end/components/layout/FullScreenLoader';
import urls from '../../../back-end/constants/urls';
import { QuizAttempt, QuizSchema } from '../../../back-end/schemas';
import API from '../../../back-end/utils/api';
import { deepClean, isEmpty } from '../../../back-end/utils/utils';
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
	const lastQuizAttemptAPI = new API(urls.lastQuizAttemp);
	const methods = useForm();

	const { watch, setValue } = methods;
	useFormPersist(`quiz-${quizId}`, {
		watch,
		setValue,
		storage: window.sessionStorage,
	});

	const progressItemAPI = new API(urls.courseProgressItem);
	const progressAPI = new API(urls.courseProgress);

	const queryClient = useQueryClient();
	const toast = useToast();
	const [largerThan768] = useMediaQuery('(min-width: 768px)');

	const { isOpen: isSidebarOpen, onToggle: onSidebarToggle } = useDisclosure({
		defaultIsOpen: largerThan768 ? true : false,
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

	const lastAttemptQuery = useQuery<QuizAttempt, Error>(
		[`lastAttempt${quizId}`, quizId],
		() => lastQuizAttemptAPI.list({ quiz_id: quizId }),
		{
			retry: false,
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
					// methods.reset();
				},
			}
		);

		startQuiz.mutate(quizId, {
			onSuccess: () => lastAttemptQuery.refetch(),
		});
	};

	const onSubmit = (data: any) => {
		checkQuizAnswers.mutate(deepClean(data), {
			onSuccess: () => {
				courseProgressQuery.refetch();
				quizQuery.refetch();
				lastAttemptQuery.refetch();
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
					completeQuiz.refetch();
					courseProgressQuery.refetch();
					lastAttemptQuery.refetch();

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

	const onQuizeExpire = () => {
		toast({
			title: __('Quiz has been submitted', 'masteriyo'),
			description: __(
				'You ran out of time, quiz has been automatically submitted.',
				'masteriyo'
			),
			isClosable: true,
			status: 'warning',
		});
		onSubmit(methods.getValues());
	};

	if (
		quizQuery.isSuccess &&
		courseProgressQuery.isSuccess &&
		lastAttemptQuery.isFetched &&
		completeQuiz.isFetched
	) {
		const maxLimitReached =
			quizQuery?.data?.attempts_allowed != 0 &&
			(lastAttemptQuery.data?.total_attempts || 0) >=
				quizQuery?.data?.attempts_allowed;

		const isQuizStarted =
			lastAttemptQuery?.data?.attempt_status === 'attempt_started' || false;
		const quizAttemptData = lastAttemptQuery.data || false;
		const isUnlimitedTime = quizQuery.data.duration === 0 || false;

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

										{!isEmpty(quizQuery?.data?.description) && (
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

										{isQuizStarted ? (
											<QuizFields quizData={quizQuery.data} />
										) : quizAttemptData ? (
											<ScoreBoard
												scoreData={quizAttemptData}
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
						{isQuizStarted && !isUnlimitedTime && (
							<FloatingTimer
								startedOn={lastAttemptQuery?.data?.attempt_started_at}
								duration={quizQuery?.data?.duration}
								quizId={quizQuery?.data?.id}
								onQuizeExpire={onQuizeExpire}
							/>
						)}

						<ContentNav
							type="quiz"
							onCompletePress={methods.handleSubmit(onSubmit)}
							navigation={quizQuery?.data?.navigation}
							courseId={quizQuery?.data?.course_id}
							isButtonDisabled={!isQuizStarted}
							isButtonLoading={checkQuizAnswers.isLoading}
							quizStarted={isQuizStarted}
						/>
					</Container>
				</Box>
			</Box>
		);
	}

	return <FullScreenLoader />;
};

export default InteractiveQuiz;
