import {
	Pagination,
	PaginationContainer,
	PaginationNext,
	PaginationPage,
	PaginationPageGroup,
	PaginationPrevious,
	PaginationSeparator,
	usePagination,
} from '@ajna/pagination';
import {
	Alert,
	AlertIcon,
	Heading,
	SkeletonText,
	Stack,
	Text,
} from '@chakra-ui/react';
import { sprintf, __ } from '@wordpress/i18n';
import React, { useMemo } from 'react';
import { FaChevronLeft, FaChevronRight } from 'react-icons/fa';
import { useQuery } from 'react-query';
import { useParams } from 'react-router-dom';
import axios from 'redaxios';
import urls from '../../../../back-end/constants/urls';
import { QuestionSchema, QuizSchema } from '../../../../back-end/schemas';
import localized from '../../../../back-end/utils/global';
import FieldMultipleChoice from './FieldMultipleChoice';
import FieldShortAnswer from './FieldShortAnswer';
import FieldSingleChoice from './FieldSingleChoice';
import FieldTrueFalse from './FieldTrueFalse';

interface Props {
	quizAboutToExpire: boolean;
	quizData: QuizSchema;
}

interface FilterParams {
	per_page?: number;
	page?: number;
}

const QuizFields: React.FC<Props> = (props) => {
	const { quizAboutToExpire, quizData } = props;
	const { quizId }: any = useParams();
	// If individual quiz questions_display_per_page is 0 then set global settings value.
	const perPage =
		0 === quizData?.questions_display_per_page
			? quizData?.questions_display_per_page_global
			: quizData?.questions_display_per_page;

	const questionQuery = useQuery(
		[`interactiveQuestions${quizId}`, quizId],
		() =>
			axios
				.get(localized.rootApiUrl + urls.questions, {
					headers: {
						'Content-Type': 'application/json',
						'X-WP-Nonce': localized.nonce,
					},

					params: {
						parent: quizId,
						order: 'asc',
						orderby: 'menu_order',
						per_page: -1,
					},
				})
				.then((res: any) => res.data),
		{
			enabled: !!quizId,
		}
	);

	const { pages, pagesCount, currentPage, setCurrentPage, pageSize } =
		usePagination({
			total: quizData?.questions_count,
			limits: {
				outer: 2,
				inner: 2,
			},
			initialState: {
				pageSize: perPage,
				isDisabled: false,
				currentPage: 1,
			},
		});

	const paginatedQuestionsData = useMemo(() => {
		const firstPageIndex = (currentPage - 1) * pageSize;
		const lastPageIndex = firstPageIndex + pageSize;
		return questionQuery?.data?.slice(firstPageIndex, lastPageIndex);
	}, [currentPage, pageSize, questionQuery?.data]);

	const handlePageChange = (nextPage: number): void => {
		setCurrentPage(nextPage);
	};

	// Current page highest value. For e.g if 1 - 10, 10 is highest.
	const currentPageHighest = perPage * currentPage;

	// Current page lowest value. For e.g if 1 - 10, 1 is lowest.
	const displayCurrentPageLowest = currentPageHighest - perPage + 1;

	// Setting highest value depending on current page is last page or not.
	const displayCurrentPageHighest =
		currentPage === pagesCount ? quizData?.questions_count : currentPageHighest;

	if (questionQuery.isFetching) {
		return <SkeletonText noOfLines={4} />;
	}

	if (questionQuery.isSuccess) {
		return (
			<>
				<Stack direction="column" spacing="16">
					{quizAboutToExpire && (
						<Alert status="error" fontSize="sm" p="2.5">
							<AlertIcon />
							{__('Your quiz time is about to expire!', 'masteriyo')}
						</Alert>
					)}
					{paginatedQuestionsData?.map((question: QuestionSchema) => (
						<Stack direction="column" spacing="8" key={question.id}>
							<Heading fontSize="lg">{question.name}</Heading>

							{question.type === 'true-false' && (
								<FieldTrueFalse
									answers={question.answers}
									questionId={`${question.id.toString()}`}
								/>
							)}

							{question.type === 'single-choice' && (
								<FieldSingleChoice
									answers={question.answers}
									questionId={`${question.id.toString()}`}
								/>
							)}

							{question.type === 'multiple-choice' && (
								<FieldMultipleChoice
									answers={question.answers}
									questionId={`${question.id.toString()}`}
								/>
							)}

							{question.type === 'short-answer' && (
								<FieldShortAnswer questionId={`${question.id.toString()}`} />
							)}
						</Stack>
					))}
					<Stack
						w="full"
						direction="row"
						justifyContent="space-between"
						align="center"
						fontSize="sm">
						<Text color="gray.500">
							{sprintf(
								/* translators: %1$d: shown results from, %2$d shown results to, %3$d total count */
								__('Showing %d - %d out of %d Questions', 'masteriyo'),
								displayCurrentPageLowest,
								displayCurrentPageHighest,
								quizData?.questions_count
							)}
						</Text>

						<Pagination
							pagesCount={pagesCount}
							currentPage={currentPage}
							onPageChange={handlePageChange}>
							<PaginationContainer>
								<Stack direction="row" spacing="1">
									<PaginationPrevious size="sm" shadow="none">
										<FaChevronLeft />
									</PaginationPrevious>
									<PaginationPageGroup
										isInline
										align="center"
										separator={
											<PaginationSeparator fontSize="sm" w={7} jumpSize={3} />
										}>
										{pages.map((page: number) => (
											<PaginationPage
												shadow="none"
												h="8"
												w="8"
												key={`pagination_page_${page}`}
												page={page}
												_hover={{
													bg: 'blue.400',
												}}
												_current={{
													bg: 'blue.400',
													fontSize: 'sm',
													color: 'white',
												}}
											/>
										))}
									</PaginationPageGroup>
									<PaginationNext size="sm" shadow="none">
										<FaChevronRight />
									</PaginationNext>
								</Stack>
							</PaginationContainer>
						</Pagination>
					</Stack>
				</Stack>
			</>
		);
	}
	return <SkeletonText noOfLines={4} />;
};

export default QuizFields;
