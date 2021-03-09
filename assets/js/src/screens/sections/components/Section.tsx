import { Edit, Trash } from '../../../assets/icons';
import React, { useState } from 'react';
import {
	deleteSection,
	fetchContents,
	updateSection,
} from '../../../utils/api';
import { useMutation, useQuery, useQueryClient } from 'react-query';

import AddNewButton from 'Components/common/AddNewButton';
import Button from 'Components/common/Button';
import Content from './Content';
import DragHandle from '../components/DragHandle';
import Dropdown from 'Components/common/Dropdown';
import DropdownOverlay from 'Components/common/DropdownOverlay';
import FormGroup from 'Components/common/FormGroup';
import Icon from 'Components/common/Icon';
import Input from 'Components/common/Input';
import Label from 'Components/common/Label';
import { NavLink } from 'react-router-dom';
import OptionButton from 'Components/common/OptionButton';
import Textarea from 'Components/common/Textarea';
import { __ } from '@wordpress/i18n';
import { useForm } from 'react-hook-form';
import { useToasts } from 'react-toast-notifications';

interface Props {
	id: number;
	name: string;
	editing?: boolean;
	courseId: number;
	description?: any;
}

const Section: React.FC<Props> = (props) => {
	type SectionInputs = {
		name?: string;
		description?: any;
	};
	const { id, name, editing = false, courseId, description } = props;
	const [sectionEditing, setSectionEditing] = useState(editing);

	const queryClient = useQueryClient();
	const { addToast } = useToasts();
	const { register, handleSubmit } = useForm<SectionInputs>();

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

	const updateMutation = useMutation((data: any) => updateSection(id, data), {
		onSuccess: (data) => {
			console.log(data);
			addToast(data?.name + __(' has been updated successfully'), {
				appearance: 'success',
				autoDismiss: true,
			});
			queryClient.invalidateQueries('builderSections');
		},
	});

	const onDeletePress = () => {
		deleteMutation.mutate(id);
	};

	const onUpdate = (data: any) => {
		updateMutation.mutate(data);
	};

	return (
		<div className="mto-bg-white mto-shadow-sm mto-p-8 mto-mt-12 mto-rounded-sm">
			<header className="mto-flex mto-justify-between mto-items-center mto-mb-4">
				<div className="mto-flex mto-items-center">
					<DragHandle />
					<h1>{name}</h1>
				</div>
				<div className="mto-flex">
					<Dropdown
						align="end"
						content={
							<DropdownOverlay>
								<ul className="mto-w-36 mto-text-gray-700 mto-m-4 ">
									<li
										className="mto-flex mto-items-center mto-text-sm mto-mb-4 hover:mto-text-primary mto-cursor-pointer"
										onClick={() => setSectionEditing(true)}>
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
			{sectionEditing && (
				<div className="mto-mt-8">
					<form onSubmit={handleSubmit(onUpdate)}>
						<FormGroup>
							<Label htmlFor="">{__('Section Name', 'masteriyo')}</Label>
							<Input
								placeholder={__('Your Section Name', 'masteriyo')}
								ref={register({ required: true })}
								name="name"
								defaultValue={name}></Input>
						</FormGroup>
						<FormGroup>
							<Label htmlFor="">{__('Section Description', 'masteriyo')}</Label>
							<Textarea
								name="description"
								defaultValue={description}
								ref={register()}
								rows={4}
								placeholder={__('short summary', 'masteriyo')}
							/>
						</FormGroup>
						<div className="mto-mt-9 mto-pt-8 mto-border-t mto-border-solid mto-border-gray-300">
							<div className="mto-flex">
								<Button layout="primary" type="submit">
									{__('Save', 'masteriyo')}
								</Button>
								<Button
									className="mto-mr-4"
									onClick={() => setSectionEditing(false)}>
									{__('Cancel', 'masteriyo')}
								</Button>
							</div>
						</div>
					</form>
				</div>
			)}
			<div>
				{contentQuery?.data?.map((content: any, index: number) => (
					<Content
						key={index}
						id={content.id}
						name={content.name}
						type={content.type}
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
