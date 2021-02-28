import { Edit, Trash } from '../../../assets/icons';
import React, { useState } from 'react';
import { deleteSection, fetchLessons } from '../../../utils/api';
import { useMutation, useQuery, useQueryClient } from 'react-query';

import AddNewButton from 'Components/common/AddNewButton';
import Button from 'Components/common/Button';
import DragHandle from '../components/DragHandle';
import Dropdown from 'Components/common/Dropdown';
import DropdownOverlay from 'Components/common/DropdownOverlay';
import FormGroup from 'Components/common/FormGroup';
import Icon from 'Components/common/Icon';
import Input from 'Components/common/Input';
import Label from 'Components/common/Label';
import Lesson from './Lesson';
import { NavLink } from 'react-router-dom';
import OptionButton from 'Components/common/OptionButton';
import Textarea from 'Components/common/Textarea';
import { __ } from '@wordpress/i18n';
import { useToasts } from 'react-toast-notifications';

interface Props {
	id: number;
	title: string;
	contents?: any;
	editing?: boolean;
	index: number;
	courseId: number;
}

const Section: React.FC<Props> = (props) => {
	const { id, title, contents, index, editing, courseId } = props;
	const [mode, setMode] = useState(editing ? 'editing' : 'normal');

	const queryClient = useQueryClient();
	const { addToast } = useToasts();

	const lessonQuery = useQuery(['lessons', courseId], () =>
		fetchLessons(courseId)
	);
	const deleteMutation = useMutation((id: number) => deleteSection(id), {
		onSuccess: (data) => {
			console.log(data);
		},
	});

	const onDeletePress = () => {
		console.log('clicked');
		deleteMutation.mutate(id, {
			onSuccess: () => {
				addToast(title + __('has been deleted successfully'), {
					appearance: 'success',
				});
				queryClient.invalidateQueries('builderSections');
			},
		});
	};

	return (
		<div className="mto-bg-white mto-shadow-sm mto-p-8 mto-mt-12 mto-rounded-sm">
			<header className="mto-flex mto-justify-between mto-items-center">
				<div className="mto-flex mto-items-center">
					<DragHandle />
					<h1>{title}</h1>
				</div>
				<div className="mto-flex">
					<Dropdown
						align="end"
						content={
							<DropdownOverlay>
								<ul className="mto-w-36 mto-text-gray-700 mto-m-4 ">
									<li
										className="mto-flex mto-items-center mto-text-sm mto-mb-4 hover:mto-text-primary mto-cursor-pointer"
										onClick={() => setMode('editing')}>
										<Icon className="mto-mr-1" icon={<Edit />} />
										{__('Edit', 'masteriyo')}
									</li>
									<li
										className="mto-flex mto-items-center mto-text-sm hover:mto-text-primary mto-cursor-pointer"
										onClick={() => onDeletePress()}>
										<Icon className="mto-mr-1" icon={<Trash />} />
										{__('Delete', 'masteriyo')}
									</li>
								</ul>
							</DropdownOverlay>
						}>
						<OptionButton />
					</Dropdown>
				</div>
			</header>
			{mode === 'editing' && (
				<>
					<div className="mto-mt-8">
						<form action="">
							<FormGroup>
								<Label htmlFor="">{__('Section Name', 'masteriyo')}</Label>
								<Input
									placeholder={__('Your Section Name', 'masteriyo')}></Input>
							</FormGroup>
							<FormGroup>
								<Label htmlFor="">
									{__('Section Description', 'masteriyo')}
								</Label>
								<Textarea
									rows={4}
									placeholder={__('short summary', 'masteriyo')}
								/>
							</FormGroup>
						</form>
					</div>

					<div className="mto-mt-9 mto-pt-8 mto-border-t mto-border-solid mto-border-gray-300">
						<div className="mto-flex">
							<Button layout="primary" onClick={() => setMode('normal')}>
								{__('Save', 'masteriyo')}
							</Button>
							<Button className="mto-mr-4">{__('Cancel', 'masteriyo')}</Button>
						</div>
					</div>
				</>
			)}

			<div className="mto-h-8">
				{lessonQuery?.data?.map((content: any, index: number) => (
					<Lesson
						key={content.id}
						id={content.id}
						title={content.name}
						index={index}
					/>
				))}
				<AddNewButton>
					<NavLink to={`/courses/${courseId}/add-new-lesson`}>
						{__('Add New Content', 'masteriyo')}
					</NavLink>
				</AddNewButton>
			</div>
		</div>
	);
};

export default Section;
