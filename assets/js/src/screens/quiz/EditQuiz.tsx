import 'rc-tabs/assets/index.css';

import React, { Fragment, useState } from 'react';
import Tabs, { TabPane } from 'rc-tabs';
import { fetchQuiz, updateQuiz } from '../../utils/api';
import { useHistory, useParams } from 'react-router-dom';
import { useMutation, useQuery } from 'react-query';

import Button from 'Components/common/Button';
import Info from '../lessons/components/Info';
import MainLayout from 'Layouts/MainLayout';
import MainToolbar from 'Layouts/MainToolbar';
import Questions from '../lessons/components/Questions';
import { __ } from '@wordpress/i18n';
import { useForm } from 'react-hook-form';
import { useToasts } from 'react-toast-notifications';

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
								<TabPane tab="Info" key="1">
									<Info
										register={register}
										name={quizQuery?.data?.name}
										description={quizQuery?.data?.description}
									/>
								</TabPane>
								<TabPane tab="Questions" key="2">
									<Questions quizId={quizId} />
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
