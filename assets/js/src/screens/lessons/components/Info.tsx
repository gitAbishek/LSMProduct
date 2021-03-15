import FormGroup from 'Components/common/FormGroup';
import Input from 'Components/common/Input';
import Label from 'Components/common/Label';
import React from 'react';
import Textarea from 'Components/common/Textarea';
import { __ } from '@wordpress/i18n';
import { useForm } from 'react-hook-form';

interface InfoProps {
	name?: string;
	description?: string;
}

interface Inputs {
	name: string;
	description?: string;
	categories?: any;
}

const Info: React.FC<InfoProps> = (props) => {
	const { name, description } = props;
	const { register, handleSubmit } = useForm<Inputs>();

	return (
		<>
			<FormGroup>
				<Label htmlFor="">{__('Quiz Name', 'masteriyo')}</Label>
				<Input
					placeholder={__('Your quiz title', 'masteriyo')}
					ref={register({ required: true })}
					name="name"
					defaultValue={name}
				/>
			</FormGroup>
			<FormGroup>
				<Label htmlFor="">{__('Description', 'masteriyo')}</Label>
				<Textarea
					placeholder={__('Your quiz description', 'masteriyo')}
					rows={5}
					ref={register}
					name="description"
					defaultValue={description}
				/>
			</FormGroup>
		</>
	);
};

export default Info;
