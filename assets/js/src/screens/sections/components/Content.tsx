import { AlignLeft, Timer, Trash } from '../../../assets/icons';
import {
	Card,
	CardActions,
	CardHeader,
	CardHeading,
} from 'Components/layout/Card';
import React, { useState } from 'react';
import { deleteLesson, deleteQuiz } from '../../../utils/api';
import { useMutation, useQueryClient } from 'react-query';

import { BiTrash } from 'react-icons/bi';
import Button from 'Components/common/Button';
import DragHandle from './DragHandle';
import Dropdown from 'Components/common/Dropdown';
import DropdownOverlay from 'Components/common/DropdownOverlay';
import Icon from 'Components/common/Icon';
import Modal from 'Components/common/Modal';
import ModalBody from 'Components/common/ModalBody';
import ModalFooter from 'Components/common/ModalFooter';
import ModalHeader from 'Components/common/ModalHeader';
import OptionButton from 'Components/common/OptionButton';
import { __ } from '@wordpress/i18n';
import { useHistory } from 'react-router';

interface Props {
	id: number;
	name: string;
	type: string;
}

const Content: React.FC<Props> = (props) => {
	const { id, name, type } = props;
	const [isModalOpen, setIsModalOpen] = useState(false);
	const queryClient = useQueryClient();
	const { push } = useHistory();

	const deleteLessonMutation = useMutation((id: number) => deleteLesson(id), {
		onSuccess: () => {
			queryClient.invalidateQueries('contents');
		},
	});
	const deleteQuizMutation = useMutation((id: number) => deleteQuiz(id), {
		onSuccess: () => {
			queryClient.invalidateQueries('contents');
		},
	});

	const onDeletePress = () => {
		setIsModalOpen(true);
	};

	const deleteContent = () => {
		if (type === 'lesson') {
			deleteLessonMutation.mutate(id);
		} else if (type === 'quiz') {
			deleteQuizMutation.mutate(id);
		}
		setIsModalOpen(false);
	};

	return (
		<>
			<Card>
				<CardHeader>
					<CardHeading>
						<DragHandle />
						<Icon
							className="mto-text-2xl mto-mr-4"
							icon={type === 'lesson' ? <AlignLeft /> : <Timer />}
						/>
						<h5 className="mto-text-base mto-text-gray-800">{name}</h5>
					</CardHeading>
					<CardActions>
						<Button
							className="mto-mr-2 mto-h-10 mto-px-3"
							size="small"
							onClick={() =>
								push(
									type === 'lesson'
										? `/builder/lesson/${id}`
										: `/quiz/${id}/edit`
								)
							}>
							{__('Edit', 'masteriyo')}
						</Button>
						<Dropdown
							align={'end'}
							autoClose
							content={
								<DropdownOverlay>
									<ul className="mto-w-36 mto-text-gray-700 mto-m-4">
										<li
											className="mto-flex mto-items-center mto-text-sm mto-mb-4 hover:mto-text-primary mto-cursor-pointer"
											onClick={() => onDeletePress()}>
											<Icon className="mto-mr-1" icon={<BiTrash />} />
											{__('Delete', 'masteriyo')}
										</li>
									</ul>
								</DropdownOverlay>
							}>
							<OptionButton />
						</Dropdown>
					</CardActions>
				</CardHeader>
			</Card>

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
						onClick={() => deleteContent()}>
						{__('Delete', 'masteriyo')}
					</Button>
				</ModalFooter>
			</Modal>
		</>
	);
};

export default Content;
