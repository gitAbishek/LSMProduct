import { BiEdit, BiTrash } from 'react-icons/bi';
import { Link, useHistory } from 'react-router-dom';
import React, { useState } from 'react';
import { useMutation, useQueryClient } from 'react-query';

import DeleteModal from 'Components/layout/DeleteModal';
import Icon from 'Components/common/Icon';
import { deleteCourse } from '../../../utils/api';

interface Props {
	id: number;
	name: string;
	price?: any;
}
const CourseList: React.FC<Props> = (props) => {
	const { id, name, price } = props;
	const history = useHistory();
	const queryClient = useQueryClient();
	const [isModalOpen, setIsModalOpen] = useState(false);

	const deleteMutation = useMutation((id: number) => deleteCourse(id), {
		onSuccess: () => {
			queryClient.invalidateQueries('courseList');
		},
	});

	const onDeletePress = () => {
		setIsModalOpen(true);
	};

	const onModalClose = () => {
		setIsModalOpen(false);
	};

	const onDeleteConfirm = () => {
		deleteMutation.mutate(id);
	};

	const onEditPress = (id: any) => {
		history.push(`/courses/edit/${id}`);
	};

	return (
		<>
			<tr key={id}>
				<td className="mto-px-6 mto-py-4 mto-whitespace-nowrap mto-transition-colors hover:mto-text-blue-500 mto-text-base">
					<Link to={`/builder/${id}`}>{name}</Link>
				</td>
				<td className="mto-px-6 mto-py-4 mto-whitespace-nowrap mto-transition-colors hover:mto-text-blue-500 mto-text-base"></td>
				<td className="mto-px-6 mto-py-4 mto-whitespace-nowrap mto-transition-colors hover:mto-text-blue-500 mto-text-base">
					{price}
				</td>
				<td className="mto-px-6 mto-py-4 mto-whitespace-nowrap mto-transition-colors hover:mto-text-blue-500 mto-text-base">
					<ul className="mto-flex mto-list-none mto-text-base mto-justify-end">
						<li
							onClick={() => onEditPress(id)}
							className="mto-text-gray-800 hover:mto-text-blue-500 mto-cursor-pointer mto-ml-4">
							<Icon icon={<BiEdit />} />
						</li>
						<li
							onClick={() => onDeletePress()}
							className="mto-text-gray-800 hover:mto-text-red-600 mto-cursor-pointer mto-ml-4">
							<Icon icon={<BiTrash />} />
						</li>
					</ul>
				</td>
			</tr>
			<DeleteModal
				isOpen={isModalOpen}
				onClose={onModalClose}
				onDeletePress={onDeleteConfirm}
				title={name}
			/>
		</>
	);
};

export default CourseList;
