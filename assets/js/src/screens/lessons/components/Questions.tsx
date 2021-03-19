import AddNewButton from 'Components/common/AddNewButton';
import Collapsible from 'react-collapsible';
import DragHandle from '../../sections/components/DragHandle';
import Question from './Question';
import React from 'react';
import { __ } from '@wordpress/i18n';
import { fetchQuestions } from '../../../utils/api';
import { useQuery } from 'react-query';

interface QuestionsProps {
	quizId: number;
}

const Questions: React.FC<QuestionsProps> = (props) => {
	const { quizId } = props;

	const questionQuery = useQuery([`questions${quizId}`, quizId], () =>
		fetchQuestions(quizId)
	);

	return (
		<>
			<div className="mto-flex mto-justify-between mto-mb-10 mto-border-b mto-border-gray-100 mto-pb-4">
				<h2 className="mto-text-l mto-m-0 mto-font-medium">
					{__('Questions', 'masteriyo')}
				</h2>
			</div>

			{questionQuery?.data?.map((question: any) => (
				<Collapsible
					trigger={
						<div className="mto-flex mto-items-center mto-border-b mto-border-gray-100 mto-p-4 ">
							<DragHandle />{' '}
							<span className="mto-text-gray-600">{question?.name}</span>
						</div>
					}
					key={question?.id}>
					<Question question={question} />
				</Collapsible>
			))}

			<div className="mto-flex mto-justify-center">
				<AddNewButton>Add New Question</AddNewButton>
			</div>
		</>
	);
};

export default Questions;
