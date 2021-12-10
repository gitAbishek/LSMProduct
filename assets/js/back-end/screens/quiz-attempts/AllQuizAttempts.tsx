import {
	Box,
	Container,
	Link,
	List,
	ListIcon,
	ListItem,
	Stack,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useState } from 'react';
import { BiTargetLock } from 'react-icons/bi';
import { useQuery } from 'react-query';
import { NavLink, useLocation } from 'react-router-dom';
import { Table, Tbody, Th, Thead, Tr } from 'react-super-responsive-table';
import EmptyInfo from '../../components/common/EmptyInfo';
import Header from '../../components/common/Header';
import MasteriyoPagination from '../../components/common/MasteriyoPagination';
import { navActiveStyles, navLinkStyles } from '../../config/styles';
import routes from '../../constants/routes';
import urls from '../../constants/urls';
import { SkeletonQuizAttemptList } from '../../skeleton';
import API from '../../utils/api';
import QuizAttemptFilter from './components/QuizAttemptFilter';
import QuizAttemptList from './components/QuizAttemptList';

interface FilterParams {
	per_page?: number;
	page?: number;
}

const AllQuizAttempts: React.FC = () => {
	const [filterParams, setFilterParams] = useState<FilterParams>({});
	const quizAttemptsAPI = new API(urls.quizesAttempts);
	const quizAttemptsQuery = useQuery(['quizAttemptsList', filterParams], () =>
		quizAttemptsAPI.list(filterParams)
	);
	const location = useLocation();

	return (
		<Stack direction="column" spacing="8" alignItems="center">
			<Header showLinks>
				<List>
					<ListItem>
						<Link
							as={NavLink}
							sx={navLinkStyles}
							_activeLink={navActiveStyles}
							isActive={() => location.pathname.includes('/quiz-attempts')}
							to={routes.quiz_attempts.list}>
							<ListIcon as={BiTargetLock} />
							{__('Quiz Attempts', 'masteriyo')}
						</Link>
					</ListItem>
				</List>
			</Header>
			<Container maxW="container.xl" marginTop="6">
				<Box bg="white" py={{ base: 6, md: 12 }} shadow="box" mx="auto">
					<Stack direction="column" spacing="8">
						<QuizAttemptFilter setFilterParams={setFilterParams} />
						<Stack direction="column" spacing="8">
							<Table>
								<Thead>
									<Tr>
										<Th>{__('Student Info', 'masteriyo')}</Th>
										<Th>{__('Quiz', 'masteriyo')}</Th>
										<Th>{__('Quiz overview', 'masteriyo')}</Th>
										<Th>{__('Result', 'masteriyo')}</Th>
									</Tr>
								</Thead>
								<Tbody>
									{quizAttemptsQuery?.isLoading && <SkeletonQuizAttemptList />}
									{quizAttemptsQuery?.isSuccess &&
									quizAttemptsQuery?.data?.data.length === 0 ? (
										<EmptyInfo message="No quiz attempts found." />
									) : (
										quizAttemptsQuery?.data?.data?.map((quizAttempt: any) => (
											<QuizAttemptList
												key={quizAttempt.id}
												data={quizAttempt}
											/>
										))
									)}
								</Tbody>
							</Table>
						</Stack>
					</Stack>
				</Box>
				{quizAttemptsQuery.isSuccess &&
					quizAttemptsQuery?.data?.data.length > 0 && (
						<MasteriyoPagination
							metaData={quizAttemptsQuery.data.meta}
							setFilterParams={setFilterParams}
							perPageText="Quiz Attempts Per Page:"
						/>
					)}
			</Container>
		</Stack>
	);
};

export default AllQuizAttempts;
