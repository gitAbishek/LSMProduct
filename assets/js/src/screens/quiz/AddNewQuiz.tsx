import Collapse, { Panel } from 'rc-collapse';
import React, { Fragment, useState } from 'react';
import { addQuiz, fetchSection } from '../../utils/api';
import { useHistory, useParams } from 'react-router';
import { useMutation, useQuery } from 'react-query';

import AddNewButton from 'Components/common/AddNewButton';
import Button from 'Components/common/Button';
import DragHandle from '../sections/components/DragHandle';
import FormGroup from 'Components/common/FormGroup';
import Icon from 'Components/common/Icon';
import Input from 'Components/common/Input';
import Label from 'Components/common/Label';
import MainLayout from 'Layouts/MainLayout';
import MainToolbar from 'Layouts/MainToolbar';
import Select from 'Components/common/Select';
import Textarea from 'Components/common/Textarea';
import { __ } from '@wordpress/i18n';
import { useForm } from 'react-hook-form';
import { useToasts } from 'react-toast-notifications';

const AddNewQuiz: React.FC = () => {
	const [currentTab, setCurrentTab] = useState('info');
	const [defaultValue, setDefaultValue] = useState(Object);
	const { sectionId }: any = useParams();
	interface Inputs {
		name: string;
		description?: string;
		categories?: any;
	}

	const { register, handleSubmit } = useForm<Inputs>();
	const { addToast } = useToasts();
	const { push } = useHistory();

	const sectionQuery = useQuery([`section${sectionId}`, sectionId], () =>
		fetchSection(sectionId)
	);

	const courseId = sectionQuery?.data?.parent_id;

	const addQuizMutation = useMutation(
		(data: object) =>
			addQuiz({
				...data,
				parent_id: sectionId,
				course_id: courseId,
			}),
		{
			onSuccess: (data: any) => {
				addToast(data?.name + __(' has been added successfully'), {
					appearance: 'success',
					autoDismiss: true,
				});
				setDefaultValue(data);
				setCurrentTab('questions');
			},
		}
	);
	const onSubmit = (data: object) => {
		addQuizMutation.mutate(data);
	};

	return (
		<Fragment>
			<MainToolbar />
			<MainLayout>
				<div>
					<div className="mto-flex mto-justify-between mto-mb-10">
						<h1 className="mto-text-xl mto-m-0 mto-font-medium">
							{__('Add New Quiz', 'masteriyo')}
						</h1>
					</div>
					<div>
						<form onSubmit={handleSubmit(onSubmit)}>
							<header className="mto-flex mto-items-center mto-justify-center mto-border-b mto-border-solid mto-border-gray-100 mto-mb-10">
								<ul className="mto-flex">
									<li
										className={`mto-cursor-pointer mto-p-4 mto-border-b-2 mto-border-transparent ${
											currentTab === 'info' &&
											`mto-text-primary mto-border-primary`
										}`}
										onClick={() => setCurrentTab('info')}>
										Info
									</li>
									<li
										className={`mto-cursor-pointer mto-p-4 mto-border-b-2 mto-border-transparent ${
											currentTab === 'questions' &&
											`mto-text-primary mto-border-primary`
										}`}
										onClick={() => setCurrentTab('questions')}>
										Questions
									</li>
									<li
										className={`mto-cursor-pointer mto-p-4 mto-border-b-2 mto-border-transparent ${
											currentTab === 'settings' &&
											`mto-text-primary mto-border-primary`
										}`}
										onClick={() => setCurrentTab('settings')}>
										Settings
									</li>
								</ul>
							</header>
							<div>
								{currentTab === 'info' && (
									<>
										<FormGroup>
											<Label htmlFor="">{__('Quiz Name', 'masteriyo')}</Label>
											<Input
												placeholder={__('Your quiz title', 'masteriyo')}
												ref={register({ required: true })}
												name="name"
												defaultValue={defaultValue?.name}
											/>
										</FormGroup>
										<FormGroup>
											<Label htmlFor="">{__('Description', 'masteriyo')}</Label>
											<Textarea
												placeholder={__('Your quiz description', 'masteriyo')}
												rows={5}
												ref={register}
												name="description"
												defaultValue={defaultValue?.description}
											/>
										</FormGroup>
									</>
								)}
								{currentTab === 'questions' && (
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
														<Input
															placeholder={__(
																'Write your question',
																'masteriyo'
															)}
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
														<Label htmlFor="">
															{__('Points', 'masteriyo')}
														</Label>
														<Input
															placeholder={__('Points for answer', 'masteriyo')}
														/>
													</FormGroup>
												</div>
												<FormGroup>
													<Label htmlFor="">
														{__('Description', 'masteriyo')}
													</Label>
													<Textarea
														placeholder={__(
															'Write Description about your quiz',
															'masteriyo'
														)}
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
								)}
							</div>
							<footer className="mto-pt-8 mto-flex mto-border-t mto-border-gray-100 mto-mt-12">
								<Button layout="primary" className="mto-mr-4" type="submit">
									{__('Add', 'masteriyo')}
								</Button>
								<Button onClick={() => push(`/builder/${courseId}`)}>
									{__('Cancel', 'masteriyo')}
								</Button>
							</footer>
						</form>
					</div>
				</div>
			</MainLayout>
		</Fragment>
	);
};

export default AddNewQuiz;
