import 'rc-tabs/assets/index.css';

import React, { Fragment, useState } from 'react';
import Tabs, { TabPane } from 'rc-tabs';
import { addQuiz, fetchSection } from '../../utils/api';
import { useHistory, useParams } from 'react-router';
import { useMutation, useQuery } from 'react-query';

import Button from 'Components/common/Button';
import Info from '../lessons/components/Info';
import MainLayout from 'Layouts/MainLayout';
import MainToolbar from 'Layouts/MainToolbar';
import Questions from '../lessons/components/Questions';
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
							<Tabs defaultActiveKey="1" animated>
								<TabPane tab="Info" key="1">
									<Info
										name={defaultValue?.name}
										description={defaultValue?.description}
									/>
								</TabPane>
								<TabPane tab="Questions" key="2">
									<Questions />
								</TabPane>
							</Tabs>
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
