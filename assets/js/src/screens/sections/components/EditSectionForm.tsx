import Button from 'Components/common/Button';
import FormGroup from 'Components/common/FormGroup';
import Input from 'Components/common/Input';
import Label from 'Components/common/Label';
import React from 'react';
import Textarea from 'Components/common/Textarea';
import { __ } from '@wordpress/i18n/build-types';

export interface EditSectionFormProps {
	handleSubmit: any;
	register: any;
	section: any;
}

const EditSectionForm: React.FC<EditSectionFormProps> = (props) => {
	const { handleSubmit, register, section } = props;

	const onUpdate = (data: any) => {};
	return (
		<div className="mto-mt-8">
			<form onSubmit={handleSubmit(onUpdate)}>
				<FormGroup>
					<Label htmlFor="">{__('Section Name', 'masteriyo')}</Label>
					<Input
						placeholder={__('Your Section Name', 'masteriyo')}
						ref={register({ required: true })}
						name="name"
						defaultValue={section.name}></Input>
				</FormGroup>
				<FormGroup>
					<Label htmlFor="">{__('Section Description', 'masteriyo')}</Label>
					<Textarea
						name="description"
						defaultValue={section.description}
						ref={register()}
						rows={4}
						placeholder={__('short summary', 'masteriyo')}
					/>
				</FormGroup>
				<div className="mto-mt-9 mto-pt-8 mto-border-t mto-border-solid mto-border-gray-300">
					<div className="mto-flex">
						<Button layout="primary" type="submit" className="mto-mr-4">
							{__('Save', 'masteriyo')}
						</Button>
						<Button onClick={() => setSectionEditing(false)}>
							{__('Cancel', 'masteriyo')}
						</Button>
					</div>
				</div>
			</form>
		</div>
	);
};

export default EditSectionForm;
