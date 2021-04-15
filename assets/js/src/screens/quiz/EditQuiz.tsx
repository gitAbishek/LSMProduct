import 'rc-tabs/assets/index.css';

import { __ } from '@wordpress/i18n';
import Button from 'Components/common/Button';
import MainLayout from 'Layouts/MainLayout';
import MainToolbar from 'Layouts/MainToolbar';
import Tabs, { TabPane } from 'rc-tabs';
import React, { Fragment, useState } from 'react';
import { useForm } from 'react-hook-form';
import { useMutation, useQuery } from 'react-query';
import { useHistory, useParams } from 'react-router-dom';
import { useToasts } from 'react-toast-notifications';

import { fetchQuiz, updateQuiz } from '../../utils/api';
import Questions from './components/Questions';

const EditQuiz: React.FC = () => {
	const { quizId, step }: any = useParams();
	const [currentTab, setCurrentTab] = useState(
		step === 'questions' ? '2' : '1'
	);
	interface Inputs {
		name: string;
		description?: string;
		categories?: any;
	}

	const { register, handleSubmit } = useForm<Inputs>();
	const { addToast } = useToasts();
	const { push } = useHistory();

	const quizQuery = useQuery([`quiz${quizId}`, quizId], () =>
		fetchQuiz(quizId)
	);

	const courseId = quizQuery?.data?.parent_id;

	const updateQuizMutation = useMutation(
		(data: object) => updateQuiz(quizId, data),
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
		updateQuizMutation.mutate(data);
	};

	const navigateTab = () => {
		if (currentTab === '1') {
			setCurrentTab('2');
		} else if (currentTab === '2') {
			setCurrentTab('3');
		}
	};

	const onTabChange = (activeKey: string) => {
		setCurrentTab(activeKey);
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
							<Tabs
								defaultActiveKey={'1'}
								activeKey={currentTab}
								animated
								onChange={onTabChange}>
								<TabPane tab="Info" key="1"></TabPane>
								<TabPane tab="Questions" key="2">
									<Questions
										quizId={quizId}
										courseId={quizQuery?.data?.course_id}
									/>
								</TabPane>
							</Tabs>
							<footer className="mto-pt-8 mto-flex mto-border-t mto-border-gray-100 mto-mt-12">
								<Button
									layout="primary"
									className="mto-mr-4"
									type="submit"
									onClick={navigateTab}>
									{__('Next', 'masteriyo')}
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

export default EditQuiz;
