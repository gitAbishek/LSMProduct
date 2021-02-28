import React, { useState } from 'react';
import { fetchCourse, updateCourse } from '../../utils/api';
import { useMutation, useQuery, useQueryClient } from 'react-query';

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
import { useForm } from 'react-hook-form';
import { useParams } from 'react-router-dom';

const EditCourse = () => {
	interface Inputs {
		name: string;
		description?: string;
		categories?: any;
	}

	const { courseId }: any = useParams();
	const [isUpdated, setIsUpdated] = useState(false);
	const queryClient = useQueryClient();

	const courseQuery = useQuery([`course${courseId}`, courseId], () =>
		fetchCourse(courseId)
	);

	const { register, handleSubmit } = useForm<Inputs>();

	const addMutation = useMutation((data) => updateCourse(courseId, data), {
		onSuccess: () => {
			setIsUpdated(true);
			queryClient.invalidateQueries(`course${courseId}`);
		},
	});

	const onSubmit = (data: any) => {
		addMutation.mutate(data);
	};

	return (
		<>
			<MainToolbar />
			<MainLayout>
				{isUpdated && (
					<div className="mto-p-4 mto-bg-green-100 mto-rounded-sm mto-mb-10 mto-text-green-700">
						<strong>{courseQuery?.data?.name}</strong>
						{__(' is successfully updated. You can keep editing.', 'masteriyo')}
					</div>
				)}
				<form onSubmit={handleSubmit(onSubmit)}>
					<div className="mto-flex">
						<div className="mto-w-1/2">
							<FormGroup>
								<Label htmlFor="">{__('Course Name', 'masteriyo')}</Label>
								<Input
									placeholder={__('Your Course Name', 'masteriyo')}
									ref={register({ required: true })}
									name="name"
									defaultValue={courseQuery?.data?.name}></Input>
							</FormGroup>

							<FormGroup>
								<Label htmlFor="">
									{__('Course Description', 'masteriyo')}
								</Label>
								<Textarea
									placeholder={__('Your Course Title', 'masteriyo')}
									rows={5}
									ref={register}
									name="description"
									defaultValue={courseQuery?.data?.description}></Textarea>
							</FormGroup>
							<div className="mto-flex-row">
								<Button layout="primary" type="submit">
									{__('Add Course', 'masteriyo')}
								</Button>
							</div>
						</div>

						<div className="mto-w-1/2">
							<FormGroup>
								<Label htmlFor="">{__('Course Category', 'masteriyo')}</Label>
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
								<Label htmlFor="">{__('Featured Image', 'masteriyo')}</Label>
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
		</>
	);
};

export default EditCourse;
