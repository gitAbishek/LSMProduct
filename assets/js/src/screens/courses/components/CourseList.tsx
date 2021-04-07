import { Td, Tr } from '@chakra-ui/table';
import Icon from 'Components/common/Icon';
import DeleteModal from 'Components/layout/DeleteModal';
import React, { useState } from 'react';
import { BiEdit, BiTrash } from 'react-icons/bi';
import { useMutation, useQueryClient } from 'react-query';
import { Link, useHistory } from 'react-router-dom';

import { deleteCourse } from '../../../utils/api';

interface Props {
	id: number;
	name: string;
	price?: any;
	categories?: any;
}

const CourseList: React.FC<Props> = (props) => {
	const { id, name, price, categories } = props;
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
			<Tr key={id}>
				<Td>
					<Link to={`/builder/${id}`}>{name}</Link>
				</Td>
				<Td>
					{categories.map((category: any) => (
						<span
							key={category.id}
							className="mto-bg-primary mto-rounded-full mto-text-white mto-text-sm mto-px-3 mto-py-1 mto-inline-block mto-mr-1">
							{category.name}
						</span>
					))}
				</Td>
				<Td>{price}</Td>
				<Td>
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
				</Td>
			</Tr>
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
