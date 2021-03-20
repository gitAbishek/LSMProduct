import Button from 'Components/common/Button';
import FormGroup from 'Components/common/FormGroup';
import Input from 'Components/common/Input';
import Label from 'Components/common/Label';
import React from 'react';
import Select from 'Components/common/Select';
import Textarea from 'Components/common/Textarea';
import { __ } from '@wordpress/i18n';
import { updateQuestion } from '../../../utils/api';
import { useForm } from 'react-hook-form';
import { useMutation } from 'react-query';
import { useToasts } from 'react-toast-notifications';

interface Props {
	question?: any;
}

const Question: React.FC<Props> = (props) => {
	const { question } = props;
	const { addToast } = useToasts();
	const { register, handleSubmit } = useForm();

	const updateQuestionMutation = useMutation(
		(data: object) => updateQuestion(question.id, data),
		{
			onSuccess: (data: any) => {
				addToast(data?.name + __(' has been updated successfully'), {
					appearance: 'success',
					autoDismiss: true,
				});
			},
		}
	);
	const onSubmit = (data: object) => {
		updateQuestionMutation.mutate(data);
	};

	return (
		<div className="mto-p-4">
			<header className="mto-flex mto-justify-between mto-mb-8 mto-border-b mto-border-gray-100">
				<h3 className="mto-text-l mto-font-medium mto-pb-4 ">
					{__('Question', 'masteriyo')}
				</h3>
			</header>
			<form onSubmit={handleSubmit(onSubmit)}>
				<div className="mto-grid mto-grid-cols-3 mto-gap-4">
					<FormGroup>
						<Label htmlFor="">
							{__('What is your question?', 'masteriyo')}
						</Label>
						<Input
							placeholder={__('Write your question', 'masteriyo')}
							name="name"
							ref={register}
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
							name="points"
							ref={register}
						/>
					</FormGroup>
				</div>
				<FormGroup>
					<Label htmlFor="">{__('Description', 'masteriyo')}</Label>
					<Textarea
						defaultValue={question?.description}
						placeholder={__('Write Description about your quiz', 'masteriyo')}
						name="description"
						rows={5}
						ref={register}
					/>
				</FormGroup>

				<div className="mto-flex mto-justify-between mto-mb-10 mto-border-b mto-border-gray-100 mto-pb-4">
					<h2 className="mto-text-l mto-m-0 mto-font-medium">
						{__('Answer', 'masteriyo')}
					</h2>
				</div>
				<footer className="mto-pt-8 mto-flex mto-border-t mto-border-gray-100 mto-mt-12">
					<Button layout="primary" className="mto-mr-4">
						{__('Update', 'masteriyo')}
					</Button>
				</footer>
			</form>
		</div>
	);
};

export default Question;
