import { usePagination } from '@ajna/pagination';
import {
	Alert,
	AlertIcon,
	Heading,
	SkeletonText,
	Stack,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useState } from 'react';
import { useQuery } from 'react-query';
import { useParams } from 'react-router-dom';
import MasteriyoPagination from '../../../../back-end/components/common/MasteriyoPagination';
import urls from '../../../../back-end/constants/urls';
import { QuestionSchema, QuizSchema } from '../../../../back-end/schemas';
import API from '../../../../back-end/utils/api';
import { isEmpty } from '../../../../back-end/utils/utils';
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
	const questionsAPI = new API(urls.questions);
	// If individual quiz questions_display_per_page is 0 then set global settings value.
	const perPage =
		0 === quizData?.questions_display_per_page
			? quizData?.questions_display_per_page_global
			: quizData?.questions_display_per_page;
	const [filterParams, setFilterParams] = useState<FilterParams>({
		per_page: perPage,
	});

	const questionQuery = useQuery(
		[`interactiveQuestions${quizId}`, quizId, filterParams],
		() =>
			questionsAPI.list({
				parent: quizId,
				order: 'asc',
				orderby: 'menu_order',
				...filterParams,
			}),
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

	const handlePageChange = (nextPage: number): void => {
		setFilterParams({
			page: nextPage,
			per_page: pageSize,
		});
		setCurrentPage(nextPage);
	};

	// Current page highest value. For e.g if 1 - 10, 10 is highest.
	const currentPageHighest = perPage * currentPage;

	// Current page lowest value. For e.g if 1 - 10, 1 is lowest.
	const displayCurrentPageLowest = currentPageHighest - perPage + 1;

	// Setting highest value depending on current page is last page or not.
	const displayCurrentPageHighest =
		currentPage === pagesCount ? quizData?.questions_count : currentPageHighest;

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
					{questionQuery?.data?.data?.map((question: QuestionSchema) => (
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
					{!isEmpty(questionQuery?.data?.data) ? (
						<MasteriyoPagination
							metaData={questionQuery?.data?.meta}
							setFilterParams={setFilterParams}
							perPageText={__('Questions Per Page:', 'masteriyo')}
							showPerPage={false}
						/>
					) : null}
				</Stack>
			</>
		);
	}
	return <SkeletonText noOfLines={4} />;
};

export default QuizFields;
