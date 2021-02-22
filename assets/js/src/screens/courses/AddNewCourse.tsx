import React, { Fragment, useState } from 'react';

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
import styled from 'styled-components';

const AddNewCourse = () => {
	const handleSubmit = (e: any) => {
		e.preventDefault();
	};

	const [formData, setFormData] = useState(Object);

	const handleChange = (e: any) => {
		setFormData({ ...setFormData, [e.target.name]: e.target.value });
	};

	return (
		<Fragment>
			<MainToolbar />
			<MainLayout>
				<form>
					<CourseContainer>
						<CourseLeftContainer>
							<FormGroup>
								<Label htmlFor="">Course Name</Label>
								<Input
									placeholder="Your Course Name"
									value={formData?.name}></Input>
							</FormGroup>

							<FormGroup>
								<Label htmlFor="">Course Description</Label>
								<Textarea placeholder="Your Course Title" rows={5}></Textarea>
							</FormGroup>
							<FlexRow>
								<Button appearance="primary">Add Course</Button>
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
								<Label htmlFor="">Categories</Label>
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
