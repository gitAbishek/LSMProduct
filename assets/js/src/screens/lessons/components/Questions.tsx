import 'rc-collapse/assets/index.css';

import Collapse, { Panel } from 'rc-collapse';

import AddNewButton from 'Components/common/AddNewButton';
import DragHandle from '../../sections/components/DragHandle';
import FormGroup from 'Components/common/FormGroup';
import Input from 'Components/common/Input';
import Label from 'Components/common/Label';
import React from 'react';
import Select from 'Components/common/Select';
import Textarea from 'Components/common/Textarea';
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

	console.log(questionQuery?.data);
	return (
		<>
			<div className="mto-flex mto-justify-between mto-mb-10 mto-border-b mto-border-gray-100 mto-pb-4">
				<h2 className="mto-text-l mto-m-0 mto-font-medium">
					{__('Questions', 'masteriyo')}
				</h2>
			</div>
			<Collapse accordion={true}>
				<Panel header="Question Name">
					<h3 className="mto-text-l mto-m-0 mto-font-medium mto-mb-8 mto-border-b mto-border-gray-100 mto-pb-4">
						{__('Question', 'masteriyo')}
					</h3>
					<div className="mto-grid mto-grid-cols-3 mto-gap-4">
						<FormGroup>
							<Label htmlFor="">
								{__('What is your question?', 'masteriyo')}
							</Label>
							<Input placeholder={__('Write your question', 'masteriyo')} />
						</FormGroup>
						<FormGroup>
							<Label>{__('Question Type', 'masteriyo')}</Label>
							<Select
								options={[
									{
										value: 'Yes No',
										label: __('Chocolate', 'masteriyo'),
									},
									{
										value: 'Single Choice',
										label: __('Strawberry', 'masteriyo'),
									},
									{
										value: 'Multiple Choice',
										label: __('Vanilla', 'masteriyo'),
									},
								]}
							/>
						</FormGroup>
						<FormGroup>
							<Label htmlFor="">{__('Points', 'masteriyo')}</Label>
							<Input placeholder={__('Points for answer', 'masteriyo')} />
						</FormGroup>
					</div>
					<FormGroup>
						<Label htmlFor="">{__('Description', 'masteriyo')}</Label>
						<Textarea
							placeholder={__('Write Description about your quiz', 'masteriyo')}
							rows={5}
						/>
					</FormGroup>
					<div className="mto-flex mto-justify-between mto-mb-10 mto-border-b mto-border-gray-100 mto-pb-4">
						<h2 className="mto-text-l mto-m-0 mto-font-medium">
							{__('Answer', 'masteriyo')}
						</h2>
					</div>
					<div>
						<div className="mto-bg-white mto-border mto-border-solid mto-border-gray-200 mto-px-4 mto-py-3 mto-flex mto-justify-between mto-items-center mto-mb-2">
							<div className="mto-flex mto-items-center">
								<DragHandle />
								<h5>True</h5>
							</div>
						</div>
						<div className="mto-bg-white mto-border mto-border-solid mto-border-gray-200 mto-px-4 mto-py-3 mto-flex mto-justify-between mto-items-center mto-mb-2">
							<div className="mto-flex mto-items-center">
								<DragHandle />
								<h5>false</h5>
							</div>
						</div>
					</div>
				</Panel>
			</Collapse>
			<div className="mto-flex mto-justify-center">
				<AddNewButton>Add New Question</AddNewButton>
			</div>
		</>
	);
};

export default Questions;
