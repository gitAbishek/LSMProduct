import { Controller, useForm } from 'react-hook-form';

import Button from 'Components/common/Button';
import FormGroup from 'Components/common/FormGroup';
import ImageUpload from 'Components/common/ImageUpload';
import Input from 'Components/common/Input';
import Label from 'Components/common/Label';
import Loader from 'react-loader-spinner';
import MainLayout from 'Layouts/MainLayout';
import MainToolbar from 'Layouts/MainToolbar';
import React from 'react';
import Select from 'Components/common/Select';
import Textarea from 'Components/common/Textarea';
import { __ } from '@wordpress/i18n';
import { addCourse } from '../../utils/api';
import { useHistory } from 'react-router-dom';
import { useMutation } from 'react-query';

const AddNewCourse = () => {
	interface Inputs {
		name: string;
		description?: string;
		categories?: any;
	}
	const history = useHistory();
	const { register, handleSubmit, control } = useForm<Inputs>();

	const addMutation = useMutation((data) => addCourse(data), {
		onSuccess: (data) => {
			history.push(`/courses/${data?.id}`);
		},
	});

	const onSubmit = (data: any) => {
		console.log(data);
		// addMutation.mutate(data);
	};

	return (
		<>
			<MainToolbar />
			<MainLayout>
				<form onSubmit={handleSubmit(onSubmit)}>
					<div className="mto-flex mto-flex-wrap mto--mx-4">
						<div className="mto-w-1/2 mto-px-4">
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
									{addMutation.isLoading ? (
										<Loader
											type="ThreeDots"
											height={14}
											width={20}
											color="#fff"
										/>
									) : (
										__('Add Course', 'masteriyo')
									)}
								</Button>
							</div>
						</div>

						<div className="mto-w-1/2 mto-px-4">
							<FormGroup>
								<Label>{__('Course Category', 'masteriyo')}</Label>
								<Controller
									control={control}
									name="categories"
									defaultValue=""
									render={({ onChange, value }) => (
										<Select
											onChange={onChange}
											value={value}
											options={[
												{
													value: 'chocolate',
													label: __('Chocolate', 'masteriyo'),
												},
												{
													value: 'strawberry',
													label: __('Strawberry', 'masteriyo'),
												},
												{ value: 'vanilla', label: __('Vanilla', 'masteriyo') },
											]}
										/>
									)}
								/>
							</FormGroup>

							<FormGroup>
								<Label>{__('Featured Image', 'masteriyo')}</Label>
								{/* <ImageUpload
									className="mto-mb-8"
									title={__('Drag image or click to upload', 'masteriyo')}
								/> */}
								<div className="mto-flex mto-justify-between">
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

export default AddNewCourse;
