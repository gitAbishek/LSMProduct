import { useMutation, useQueryClient } from 'react-query';

import Button from 'Components/common/Button';
import FormGroup from 'Components/common/FormGroup';
import Input from 'Components/common/Input';
import Label from 'Components/common/Label';
import React from 'react';
import Textarea from 'Components/common/Textarea';
import { __ } from '@wordpress/i18n';
import { updateSection } from '../../../utils/api';
import { useForm } from 'react-hook-form';
import { useToasts } from 'react-toast-notifications';

export interface EditSectionProps {
	id: number;
	name: string;
	description?: string;
	onSave: () => void;
	onCancel: () => void;
}

type SectionInputs = {
	name?: string;
	description?: string;
};

const EditSection: React.FC<EditSectionProps> = (props) => {
	const { id, name, description, onSave, onCancel } = props;
	const { register, handleSubmit } = useForm<SectionInputs>();
	const { addToast } = useToasts();
	const queryClient = useQueryClient();

	const updateMutation = useMutation((data: any) => updateSection(id, data), {
		onSuccess: (data) => {
			addToast(data?.name + __(' has been updated successfully'), {
				appearance: 'success',
				autoDismiss: true,
			});
			queryClient.invalidateQueries('builderSections');
			onSave();
		},
	});

	const onUpdate = (data: any) => {
		updateMutation.mutate(data);
	};

	return (
		<div className="Edit-Section">
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
				<div className="mto-my-9 mto-py-8 mto-border-t mto-border-b mto-border-solid mto-border-gray-200">
					<div className="mto-flex">
						<Button layout="primary" type="submit" className="mto-mr-4">
							{__('Save', 'masteriyo')}
						</Button>
						<Button type="button" onClick={() => onCancel()}>
							{__('Cancel', 'masteriyo')}
						</Button>
					</div>
				</div>
			</form>
		</div>
	);
};

export default EditSection;
