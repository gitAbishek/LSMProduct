import React, { useState } from 'react';
import {
	addQuestion,
	deleteQuestion,
	fetchQuestions,
} from '../../../utils/api';
import { useMutation, useQuery, useQueryClient } from 'react-query';

import AddNewButton from 'Components/common/AddNewButton';
import { BiTrash } from 'react-icons/bi';
import Button from 'Components/common/Button';
import Collapsible from 'react-collapsible';
import DragHandle from '../../sections/components/DragHandle';
import Icon from 'Components/common/Icon';
import Modal from 'Components/common/Modal';
import ModalBody from 'Components/common/ModalBody';
import ModalFooter from 'Components/common/ModalFooter';
import ModalHeader from 'Components/common/ModalHeader';
import Question from './Question';
import { __ } from '@wordpress/i18n';
import { useToasts } from 'react-toast-notifications';

interface QuestionsProps {
	quizId: number;
	courseId: any;
}

const Questions: React.FC<QuestionsProps> = (props) => {
	const { quizId, courseId } = props;
	const [isModalOpen, setIsModalOpen] = useState(false);
	const [deletingCourse, setDeletingCourse] = useState(Number);

	const queryClient = useQueryClient();
	const { addToast } = useToasts();

	const questionQuery = useQuery(['questions', quizId], () =>
		fetchQuestions(quizId)
	);

	const addQuestionMutation = useMutation(
		(data: object) =>
			addQuestion({
				...data,
				parent_id: quizId,
				course_id: courseId,
			}),
		{
			onSuccess: () => {
				queryClient.invalidateQueries('questions');
			},
		}
	);

	const onAddNewQuestion = () => {
		addQuestionMutation.mutate({ name: 'New Question' });
	};

	const deleteQuestionMutation = useMutation(
		(id: number) => deleteQuestion(id),
		{
			onSuccess: (data) => {
				addToast(data?.name + __(' has been deleted successfully'), {
					appearance: 'error',
					autoDismiss: true,
				});
				queryClient.invalidateQueries('questions');
			},
		}
	);

	const onDeleteQuestion = (id: any) => {
		setDeletingCourse(id);
		setIsModalOpen(true);
	};

	const onDeleteConfirm = (id: any) => {
		deleteQuestionMutation.mutate(id);
		setIsModalOpen(false);
	};

	const renderHeader = (question: any) => {
		return (
			<div className="mto-flex mto-justify-between mto-items-center mto-border-b mto-border-gray-100 mto-p-4">
				<div className="mto-flex mto-items-center">
					<DragHandle />{' '}
					<span className="mto-text-gray-600">{question?.name}</span>
				</div>
				<Icon
					icon={<BiTrash />}
					onClick={() => onDeleteQuestion(question?.id)}
					className="mto-text-xl mto-text-gray-500"
				/>
			</div>
		);
	};
	return (
		<>
			<div className="mto-flex mto-justify-between mto-mb-10 mto-border-b mto-border-gray-100 mto-pb-4">
				<h2 className="mto-text-l mto-m-0 mto-font-medium">
					{__('Questions', 'masteriyo')}
				</h2>
			</div>

			{questionQuery?.data?.map((question: any) => (
				<Collapsible trigger={renderHeader(question)} key={question?.id}>
					<Question question={question} />
				</Collapsible>
			))}

			<div className="mto-flex mto-justify-center">
				<AddNewButton type="button" onClick={onAddNewQuestion}>
					Add New Question
				</AddNewButton>
			</div>

			<Modal isOpen={isModalOpen} onClose={() => setIsModalOpen(false)}>
				<ModalHeader>
					{__('Delete Course', 'masteriyo')} {name}
				</ModalHeader>
				<ModalBody>
					<p className="mto-text-md mto-text-gray-500">
						{__(
							"Are you sure want to delete this course. You won't be able to recover it back",
							'masteriyo'
						)}
					</p>
				</ModalBody>
				<ModalFooter>
					<Button
						className="mto-w-full sm:mto-w-auto"
						onClick={() => setIsModalOpen(false)}>
						{__('Cancel', 'masteriyo')}
					</Button>
					<Button
						layout="accent"
						className="mto-w-full sm:mto-w-auto"
						onClick={() => onDeleteConfirm(deletingCourse)}>
						{__('Delete', 'masteriyo')}
					</Button>
				</ModalFooter>
			</Modal>
		</>
	);
};

export default Questions;
