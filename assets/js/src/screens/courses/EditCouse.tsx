import {
	CourseContainer,
	CourseLeftContainer,
	CourseRightContainer,
	FeaturedImageActions,
} from './AddNewCourse';
import React, { Fragment, useState } from 'react';
import { __, sprintf } from '@wordpress/i18n';
import { fetchCourse, updateCourse } from '../../utils/api';
import { useMutation, useQuery } from 'react-query';

import Button from 'Components/common/Button';
import FlexRow from 'Components/common/FlexRow';
import FormGroup from 'Components/common/FormGroup';
import ImageUpload from 'Components/common/ImageUpload';
import Input from 'Components/common/Input';
import Label from 'Components/common/Label';
import MainLayout from 'Layouts/MainLayout';
import MainToolbar from 'Layouts/MainToolbar';
import Select from 'Components/common/Select';
import Textarea from 'Components/common/Textarea';
import { createInterpolateElement } from '@wordpress/element';
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

	const { data: courseData, refetch: refectCourseData } = useQuery(
		[`course${courseId}`, courseId],
		() => fetchCourse(courseId)
	);

	const { register, handleSubmit } = useForm<Inputs>();

	const addMutation = useMutation((data) => updateCourse(courseId, data), {
		onSuccess: () => {
			setIsUpdated(true);
			refectCourseData();
		},
	});

	const onSubmit = (data: any) => {
		addMutation.mutate(data);
	};

	return (
		<Fragment>
			<MainToolbar />
			<MainLayout>
				{isUpdated && (
					<div className="mto-p-4 mto-bg-green-100 mto-rounded-sm mto-mb-10 mto-text-green-700">
						{createInterpolateElement(
							sprintf(
								/* translators: %s course name */
								__(
									'Course `<strong>%s</strong>` is successfully updated. You can keep editing.',
									'masteriyo'
								),
								courseData?.name
							),
							{ strong: <strong /> }
						)}
					</div>
				)}
				<form onSubmit={handleSubmit(onSubmit)}>
					<CourseContainer>
						<CourseLeftContainer>
							<FormGroup>
								<Label htmlFor="">{__('Course Name', 'masteriyo')}</Label>
								<Input
									placeholder={__('Your Course Name', 'masteriyo')}
									ref={register({ required: true })}
									name="name"
									defaultValue={courseData?.name}></Input>
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
									defaultValue={courseData?.description}></Textarea>
							</FormGroup>
							<FlexRow>
								<Button appearance="primary" type="submit">
									{__('Add Course', 'masteriyo')}
								</Button>
							</FlexRow>
						</CourseLeftContainer>

						<CourseRightContainer>
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
								<FeaturedImageActions>
									<Button>{__('Remove Featured Image', 'masteriyo')}</Button>
									<Button appearance="primary">
										{__('Add New', 'masteriyo')}
									</Button>
								</FeaturedImageActions>
							</FormGroup>
						</CourseRightContainer>
					</CourseContainer>
				</form>
			</MainLayout>
		</Fragment>
	);
};

export default EditCourse;
