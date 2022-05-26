import {
	Box,
	Button,
	ButtonGroup,
	Container,
	Icon,
	Link,
	List,
	ListItem,
	Stack,
	Text,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { BiChevronLeft } from 'react-icons/bi';
import { useQuery } from 'react-query';
import { useHistory, useParams } from 'react-router';
import { Link as RouterLink, NavLink } from 'react-router-dom';
import { Table, Tbody, Th, Thead, Tr } from 'react-super-responsive-table';
import Header from '../../components/common/Header';
import FullScreenLoader from '../../components/layout/FullScreenLoader';
import { navActiveStyles, navLinkStyles } from '../../config/styles';
import routes from '../../constants/routes';
import urls from '../../constants/urls';
import { QuizAttempt } from '../../schemas';
import API from '../../utils/api';
import { isEmpty } from '../../utils/utils';
import NoQuestionAttemptNotice from './components/NoQuestionAttemptNotice';
import QuizAttemptInfo from './components/QuizAttemptInfo';
import QuizInfo from './components/QuizInfo';
import QuizOverview from './components/QuizOverview';

const ReviewQuizAttempt = () => {
	const { attemptId }: any = useParams();
	const history = useHistory();
	const quizAttemptsAPI = new API(urls.quizesAttempts);

	const quizAttemptQuery = useQuery<QuizAttempt>(
		[`quizAttempt${attemptId}`, attemptId],
		() => quizAttemptsAPI.get(attemptId),
		{
			onError: () => {
				history.push(routes.notFound);
			},
		}
	);

	if (quizAttemptQuery.isSuccess) {
		return (
			<Stack direction="column" spacing="8" alignItems="center">
				<Header>
					<List d="flex">
						<ListItem mb="0">
							<Link
								as={NavLink}
								sx={navLinkStyles}
								_activeLink={navActiveStyles}
								to="#">
								{__('Quiz Details', 'masteriyo')}
							</Link>
						</ListItem>
					</List>
				</Header>
				<Container maxW="container.xl">
					<Stack direction="column" spacing="8">
						<Stack direction="column" spacing="6">
							<ButtonGroup>
								<RouterLink to={routes.quiz_attempts.list}>
									<Button
										variant="link"
										_hover={{ color: 'blue.500' }}
										leftIcon={<Icon fontSize="xl" as={BiChevronLeft} />}>
										{__('Back', 'masteriyo')}
									</Button>
								</RouterLink>
							</ButtonGroup>
						</Stack>

						<QuizInfo quizAttemptData={quizAttemptQuery?.data} />

						<Box bg="white" py={{ base: 6, md: 12 }} shadow="box" mx="auto">
							<Stack direction="column" spacing="8">
								<Table>
									<Thead>
										<Tr>
											<Th>{__('Student Info', 'masteriyo')}</Th>
											<Th>{__('Quiz Summary', 'masteriyo')}</Th>
											<Th>{__('Result', 'masteriyo')}</Th>
											<Th></Th>
										</Tr>
									</Thead>
									<Tbody>
										{
											<QuizAttemptInfo
												quizAttemptData={quizAttemptQuery?.data}
											/>
										}
									</Tbody>
								</Table>
							</Stack>
						</Box>
						<Stack>
							<Text fontSize="md" fontWeight="bold">
								{__('Quiz Overview', 'masteriyo')}
							</Text>
						</Stack>
						<Box bg="white" py={{ base: 6, md: 12 }} shadow="box" mx="auto">
							<Stack direction="column" spacing="8">
								<Table>
									<Thead>
										<Tr>
											<Th>{__('Question', 'masteriyo')}</Th>
											<Th>{__('Answer', 'masteriyo')}</Th>
											<Th>{__('Result', 'masteriyo')}</Th>
											<Th>{__('Points', 'masteriyo')}</Th>
										</Tr>
									</Thead>
									<Tbody>
										{isEmpty(quizAttemptQuery?.data?.answers) ? (
											<NoQuestionAttemptNotice />
										) : (
											Object.keys(quizAttemptQuery?.data?.answers).map(
												(questionId) => (
													<QuizOverview
														answersData={
															quizAttemptQuery?.data?.answers[questionId]
														}
														key={questionId}
													/>
												)
											)
										)}
									</Tbody>
								</Table>
							</Stack>
						</Box>
					</Stack>
				</Container>
			</Stack>
		);
	}

	return <FullScreenLoader />;
};

export default ReviewQuizAttempt;
