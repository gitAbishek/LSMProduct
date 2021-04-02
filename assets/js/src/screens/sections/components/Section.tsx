import { BiAlignLeft, BiTimer, BiTrash } from 'react-icons/bi';
import Box, {
	BoxContent,
	BoxFooter,
	BoxHeader,
	BoxTitle,
} from 'Components/layout/Box';
import Dropdown, {
	DropdownMenu,
	DropdownMenuItem,
} from 'Components/common/Dropdown';
import React, { useState } from 'react';
import { deleteSection, fetchContents } from '../../../utils/api';
import { useMutation, useQuery, useQueryClient } from 'react-query';

import AddNewButton from 'Components/common/AddNewButton';
import { Collapse } from 'react-collapse';
import Content from './Content';
import DeleteModal from 'Components/layout/DeleteModal';
import DragHandle from '../components/DragHandle';
import { Edit } from '../../../assets/icons';
import EditSection from './EditSection';
import Icon from 'Components/common/Icon';
import { NavLink } from 'react-router-dom';
import OptionButton from 'Components/common/OptionButton';
import Spinner from 'Components/common/Spinner';
import { __ } from '@wordpress/i18n';
import { useToasts } from 'react-toast-notifications';

interface Props {
	id: number;
	name: string;
	courseId: number;
	description?: any;
}

const Section: React.FC<Props> = (props) => {
	const { id, name, description } = props;
	const [isEditing, setIsEditing] = useState(false);
	const [isModalOpen, setIsModalOpen] = useState(false);

	const queryClient = useQueryClient();
	const { addToast } = useToasts();

	const contentQuery = useQuery(['contents', id], () => fetchContents(id));

	const deleteMutation = useMutation((id: number) => deleteSection(id), {
		onSuccess: (data) => {
			addToast(data?.name + __(' has been deleted successfully'), {
				appearance: 'error',
				autoDismiss: true,
			});
			queryClient.invalidateQueries('builderSections');
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

	return (
		<Box>
			<BoxHeader>
				<div className="mto-flex mto-items-center">
					<DragHandle />
					<BoxTitle>{name}</BoxTitle>
				</div>
				<div className="mto-flex">
					<Dropdown
						align="end"
						autoClose
						content={
							<DropdownMenu>
								<DropdownMenuItem onClick={() => setIsEditing(true)}>
									<Icon className="mto-mr-1" icon={<Edit />} />
									{__('Edit', 'masteriyo')}
								</DropdownMenuItem>
								<DropdownMenuItem onClick={onDeletePress}>
									<Icon className="mto-mr-1" icon={<BiTrash />} />
									{__('Delete', 'masteriyo')}
								</DropdownMenuItem>
							</DropdownMenu>
						}>
						<OptionButton />
					</Dropdown>
				</div>
			</BoxHeader>
			<BoxContent>
				<Collapse isOpened={isEditing}>
					<EditSection
						id={id}
						name={name}
						description={description}
						onSave={() => setIsEditing(false)}
						onCancel={() => setIsEditing(false)}
					/>
				</Collapse>
				{contentQuery.isLoading ? (
					<Spinner />
				) : (
					contentQuery?.data?.map((content: any) => (
						<Content
							key={content.id}
							id={content.id}
							name={content.name}
							type={content.type}
						/>
					))
				)}
			</BoxContent>
			<BoxFooter>
				<Dropdown
					content={
						<DropdownMenu>
							<DropdownMenuItem>
								<NavLink
									className="mto-flex mto-items-center"
									to={`/courses/${id}/add-new-lesson`}>
									<Icon className="mto-mr-1" icon={<BiAlignLeft />} />
									{__('Lesson', 'masteriyo')}
								</NavLink>
							</DropdownMenuItem>
							<DropdownMenuItem>
								<NavLink
									className="mto-flex mto-items-center"
									to={`/courses/${id}/add-new-quiz`}>
									<Icon className="mto-mr-1" icon={<BiTimer />} />
									{__('Quiz', 'masteriyo')}
								</NavLink>
							</DropdownMenuItem>
						</DropdownMenu>
					}>
					<AddNewButton>{__('Add New Content', 'masteriyo')}</AddNewButton>
				</Dropdown>
			</BoxFooter>
			<DeleteModal
				isOpen={isModalOpen}
				onDeletePress={onDeleteConfirm}
				onClose={onModalClose}
				title={name}
			/>
		</Box>
	);
};

export default Section;
