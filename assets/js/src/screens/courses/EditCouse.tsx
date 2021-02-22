import {
	CourseContainer,
	CourseLeftContainer,
	CourseRightContainer,
	FeaturedImageActions,
} from './AddNewCourse';
import React, { Fragment, useState } from 'react';
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
						Course `<strong>{courseData?.name}</strong>` is successfully
						updated. You can keep editing.
					</div>
				)}
				<form onSubmit={handleSubmit(onSubmit)}>
					<CourseContainer>
						<CourseLeftContainer>
							<FormGroup>
								<Label htmlFor="">Course Name</Label>
								<Input
									placeholder="Your Course Name"
									ref={register({ required: true })}
									name="name"
									defaultValue={courseData?.name}></Input>
							</FormGroup>

							<FormGroup>
								<Label htmlFor="">Course Description</Label>
								<Textarea
									placeholder="Your Course Title"
									rows={5}
									ref={register}
									name="description"
									defaultValue={courseData?.description}></Textarea>
							</FormGroup>
							<FlexRow>
								<Button appearance="primary" type="submit">
									Add Course
								</Button>
							</FlexRow>
						</CourseLeftContainer>

						<CourseRightContainer>
							<FormGroup>
								<Label htmlFor="">Course Category</Label>
								<Select
									options={[
										{ value: 'chocolate', label: 'Chocolate' },
										{ value: 'strawberry', label: 'Strawberry' },
										{ value: 'vanilla', label: 'Vanilla' },
									]}
								/>
							</FormGroup>

							<FormGroup>
								<Label htmlFor="">Featured Image</Label>
								<ImageUpload title="Drag image or click to upload" />
								<FeaturedImageActions>
									<Button>Remove Featured Image</Button>
									<Button appearance="primary">Add New</Button>
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
