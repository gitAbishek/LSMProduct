import { Controller, useForm } from 'react-hook-form';
import React, { Fragment } from 'react';

import { BaseLine } from 'Config/defaultStyle';
import Button from 'Components/common/Button';
import Flex from 'Components/common/Flex';
import FlexRow from 'Components/common/FlexRow';
import FormGroup from 'Components/common/FormGroup';
import ImageUpload from 'Components/common/ImageUpload';
import Input from 'Components/common/Input';
import Label from 'Components/common/Label';
import MainLayout from 'Layouts/MainLayout';
import MainToolbar from 'Layouts/MainToolbar';
import Select from 'Components/common/Select';
import Textarea from 'Components/common/Textarea';
import { addCourse } from '../../utils/api';
import styled from 'styled-components';
import { useMutation } from 'react-query';

const AddNewCourse = () => {
	interface Inputs {
		name: string;
		description?: string;
		categories?: any;
	}

	const { register, handleSubmit } = useForm<Inputs>();
	const addMutation = useMutation((data) => addCourse(data), {
		onSuccess: () => {
			console.log('successfully added');
		},
	});
	const onSubmit = (data: any) => {
		console.log(data);
		addMutation.mutate(data);
	};

	return (
		<Fragment>
			<MainToolbar />
			<MainLayout>
				<form onSubmit={handleSubmit(onSubmit)}>
					<CourseContainer>
						<CourseLeftContainer>
							<FormGroup>
								<Label htmlFor="">Course Name</Label>
								<Input
									placeholder="Your Course Name"
									ref={register({ required: true })}
									name="name"></Input>
							</FormGroup>

							<FormGroup>
								<Label htmlFor="">Course Description</Label>
								<Textarea
									placeholder="Your Course Title"
									rows={5}
									ref={register}
									name="description"></Textarea>
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

const CourseContainer = styled(FlexRow)`
	align-items: flex-start;
	margin-left: -${BaseLine * 2}px;
	margin-right: -${BaseLine * 2}px;
`;

const CourseInner = styled(Flex)`
	padding-left: ${BaseLine * 2}px;
	padding-right: ${BaseLine * 2}px;
`;

const CourseLeftContainer = styled(CourseInner)`
	flex: 1;
`;

const CourseRightContainer = styled(CourseInner)`
	flex-basis: 400px;
`;

const FeaturedImageActions = styled(FlexRow)`
	justify-content: space-between;
	margin-top: ${BaseLine * 3}px;
`;

export default AddNewCourse;
