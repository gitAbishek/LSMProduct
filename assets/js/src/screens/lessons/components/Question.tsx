import DragHandle from '../../sections/components/DragHandle';
import FormGroup from 'Components/common/FormGroup';
import Input from 'Components/common/Input';
import Label from 'Components/common/Label';
import React from 'react';
import Select from 'Components/common/Select';
import Textarea from 'Components/common/Textarea';
import { __ } from '@wordpress/i18n';
import { useForm } from 'react-hook-form';

interface Props {
	question?: any;
}

const Question: React.FC<Props> = (props) => {
	const { question } = props;
	const { register } = useForm();

	return (
		<div className="mto-p-4">
			<header className="mto-flex mto-justify-between mto-mb-8 mto-border-b mto-border-gray-100">
				<h3 className="mto-text-l mto-font-medium mto-pb-4 ">
					{__('Question', 'masteriyo')}
				</h3>
			</header>
			<div className="mto-grid mto-grid-cols-3 mto-gap-4">
				<FormGroup>
					<Label htmlFor="">{__('What is your question?', 'masteriyo')}</Label>
					<Input
						placeholder={__('Write your question', 'masteriyo')}
						defaultValue={question?.name}
					/>
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
					<Input
						placeholder={__('Points for answer', 'masteriyo')}
						defaultValue={question?.points}
					/>
				</FormGroup>
			</div>
			<FormGroup>
				<Label htmlFor="">{__('Description', 'masteriyo')}</Label>
				<Textarea
					defaultValue={question?.description}
					placeholder={__('Write Description about your quiz', 'masteriyo')}
					rows={5}
				/>
			</FormGroup>
			<div className="mto-flex mto-justify-between mto-mb-10 mto-border-b mto-border-gray-100 mto-pb-4">
				<h2 className="mto-text-l mto-m-0 mto-font-medium">
					{__('Answer', 'masteriyo')}
				</h2>
			</div>
			<div></div>
		</div>
	);
};

export default Question;
