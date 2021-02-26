import React, { Fragment } from 'react';

import Button from 'Components/common/Button';
import FormGroup from 'Components/common/FormGroup';
import ImageUpload from 'Components/common/ImageUpload';
import Input from 'Components/common/Input';
import Label from 'Components/common/Label';
import MainLayout from 'Layouts/MainLayout';
import MainToolbar from 'Layouts/MainToolbar';
import Select from 'Components/common/Select';
import Textarea from 'Components/common/Textarea';
import { __ } from '@wordpress/i18n';
import { addCourse } from '../../utils/api';
import { useForm } from 'react-hook-form';
import { useHistory } from 'react-router-dom';
import { useMutation } from 'react-query';

const AddNewCourse = () => {
	interface Inputs {
		name: string;
		description?: string;
		categories?: any;
	}
	const history = useHistory();
	const { register, handleSubmit } = useForm<Inputs>();

	const addMutation = useMutation((data) => addCourse(data), {
		onSuccess: (data) => {
			history.push(`/courses/${data?.id}`);
		},
	});

	const onSubmit = (data: any) => {
		addMutation.mutate(data);
	};

	return (
		<Fragment>
			<MainToolbar />
			<MainLayout>
				<form onSubmit={handleSubmit(onSubmit)}>
					<div className="mto-flex">
						<div className="mto-w-1/2">
							<FormGroup>
								<Label>{__('Course Name', 'masteriyo')}</Label>
								<Input
									placeholder={__('Your Course Name', 'masteriyo')}
									ref={register({ required: true })}
									name="name"></Input>
							</FormGroup>

							<FormGroup>
								<Label>{__('Course Description', 'masteriyo')}</Label>
								<Textarea
									placeholder={__('Your Course Title', 'masteriyo')}
									rows={5}
									ref={register}
									name="description"></Textarea>
							</FormGroup>
							<div className="mto-flex-row">
								<Button layout="primary" type="submit">
									{__('Add Course', 'masteriyo')}
								</Button>
							</div>
						</div>

						<div className="mto-w-1/2">
							<FormGroup>
								<Label>{__('Course Category', 'masteriyo')}</Label>
								<Select
									options={[
										{ value: 'chocolate', label: __('Chocolate', 'masteriyo') },
										{
											value: 'strawberry',
											label: __('Strawberry', 'masteriyo'),
										},
										{ value: 'vanilla', label: __('Vanilla', 'masteriyo') },
									]}
								/>
							</FormGroup>

							<FormGroup>
								<Label>{__('Featured Image', 'masteriyo')}</Label>
								<ImageUpload
									title={__('Drag image or click to upload', 'masteriyo')}
								/>
								<div className="mto-flex-row">
									<Button>{__('Remove Featured Image', 'masteriyo')}</Button>
									<Button layout="primary">{__('Add New', 'masteriyo')}</Button>
								</div>
							</FormGroup>
						</div>
					</div>
				</form>
			</MainLayout>
		</Fragment>
	);
};

export default AddNewCourse;
