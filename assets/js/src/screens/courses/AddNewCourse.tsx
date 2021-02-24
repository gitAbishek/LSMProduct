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
import { useHistory } from 'react-router-dom';
import { useMutation } from 'react-query';
import { __ } from '@wordpress/i18n';

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
					<CourseContainer>
						<CourseLeftContainer>
							<FormGroup>
								<Label htmlFor="">{__( 'Course Name', 'masteriyo' ) }</Label>
								<Input
									placeholder={__( 'Your Course Name', 'masteriyo' )}
									ref={register({ required: true })}
									name="name"></Input>
							</FormGroup>

							<FormGroup>
								<Label htmlFor="">{__( 'Course Description', 'masteriyo' )}</Label>
								<Textarea
									placeholder={__( 'Your Course Title', 'masteriyo' )}
									rows={5}
									ref={register}
									name="description"></Textarea>
							</FormGroup>
							<FlexRow>
								<Button appearance="primary" type="submit">
									{__( 'Add Course', 'masteriyo' )}
								</Button>
							</FlexRow>
						</CourseLeftContainer>

						<CourseRightContainer>
							<FormGroup>
								<Label htmlFor="">{__( 'Course Category', 'masteriyo' )}</Label>
								<Select
									options={[
										{ value: 'chocolate', label: __( 'Chocolate', 'masteriyo' ) },
										{ value: 'strawberry', label: __( 'Strawberry', 'masteriyo' ) },
										{ value: 'vanilla', label: __( 'Vanilla', 'masteriyo' ) },
									]}
								/>
							</FormGroup>

							<FormGroup>
								<Label htmlFor="">{__( 'Featured Image', 'masteriyo' )}</Label>
								<ImageUpload title={__( 'Drag image or click to upload', 'masteriyo' )} />
								<FeaturedImageActions>
									<Button>{__( 'Remove Featured Image', 'masteriyo' )}</Button>
									<Button appearance="primary">{__( 'Add New', 'masteriyo' )}</Button>
								</FeaturedImageActions>
							</FormGroup>
						</CourseRightContainer>
					</CourseContainer>
				</form>
			</MainLayout>
		</Fragment>
	);
};

export const CourseContainer = styled(FlexRow)`
	align-items: flex-start;
	margin-left: -${BaseLine * 2}px;
	margin-right: -${BaseLine * 2}px;
`;

export const CourseInner = styled(Flex)`
	padding-left: ${BaseLine * 2}px;
	padding-right: ${BaseLine * 2}px;
`;

export const CourseLeftContainer = styled(CourseInner)`
	flex: 1;
`;

export const CourseRightContainer = styled(CourseInner)`
	flex-basis: 400px;
`;

export const FeaturedImageActions = styled(FlexRow)`
	justify-content: space-between;
	margin-top: ${BaseLine * 3}px;
`;

export default AddNewCourse;
