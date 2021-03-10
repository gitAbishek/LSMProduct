import React, { Fragment, useState } from 'react';
import {
	addLesson,
	fetchLesson,
	fetchLessons,
	fetchSection,
	updateLesson,
} from '../../utils/api';
import { useHistory, useParams } from 'react-router';
import { useMutation, useQuery } from 'react-query';

import Button from 'Components/common/Button';
import Dropdown from 'Components/common/Dropdown';
import DropdownOverlay from 'Components/common/DropdownOverlay';
import FormGroup from 'Components/common/FormGroup';
import Input from 'Components/common/Input';
import Label from 'Components/common/Label';
import MainLayout from 'Layouts/MainLayout';
import MainToolbar from 'Layouts/MainToolbar';
import OptionButton from 'Components/common/OptionButton';
import Select from 'Components/common/Select';
import Slider from 'rc-slider';
import Textarea from 'Components/common/Textarea';
import { __ } from '@wordpress/i18n';
import { useForm } from 'react-hook-form';
import { useToasts } from 'react-toast-notifications';

const EditLesson: React.FC = () => {
	const { lessonId }: any = useParams();
	const { push } = useHistory();
	interface Inputs {
		name: string;
		description?: string;
		categories?: any;
	}

	const { register, handleSubmit } = useForm<Inputs>();
	const [playBackTime, setPlayBackTime] = useState(3);
	const { addToast } = useToasts();

	const playBackTimeOnChange = (value: number) => {
		setPlayBackTime(value);
	};

	const lessonQuery = useQuery([`section${lessonId}`, lessonId], () =>
		fetchLesson(lessonId)
	);

	const addLessonMutation = useMutation((data: object) => addLesson(data), {
		onSuccess: (data: any) => {
			addToast(data?.name + __(' has been updated successfully'), {
				appearance: 'success',
				autoDismiss: true,
				onDismiss: () => {
					push(`/builder/${lessonQuery?.data?.course_id}`);
				},
			});
		},
	});
	const onSubmit = (data: object) => {
		addLessonMutation.mutate(data);
	};

	return (
		<Fragment>
			<MainToolbar />
			<MainLayout>
				<div>
					<div className="mto-flex mto-justify-between mto-mb-10">
						<h1 className="mto-text-xl mto-m-0 mto-font-medium">
							{__('Add New Lesson', 'masteriyo')}
						</h1>
						<div>
							<Dropdown
								align="end"
								content={
									<DropdownOverlay>
										<ul>
											<li>{__('Delete', 'masteriyo')}</li>
										</ul>
									</DropdownOverlay>
								}>
								<OptionButton />
							</Dropdown>
						</div>
					</div>
					<div>
						<form onSubmit={handleSubmit(onSubmit)}>
							<FormGroup>
								<Label htmlFor="">{__('Lesson Name', 'masteriyo')}</Label>
								<Input
									placeholder={__('Your topic title', 'masteriyo')}
									ref={register({ required: true })}
									defaultValue={lessonQuery?.data?.name}
									name="name"
								/>
							</FormGroup>

							<FormGroup>
								<Label htmlFor="">{__('Description', 'masteriyo')}</Label>
								<Textarea
									placeholder={__('Your course description', 'masteriyo')}
									rows={5}
									ref={register}
									name="description"
									defaultValue={lessonQuery?.data?.description}
								/>
							</FormGroup>

							<FormGroup>
								<Label htmlFor="">{__('Featured Image', 'masteriyo')}</Label>
							</FormGroup>
							<div>
								<div>
									<FormGroup>
										<Label htmlFor="">{__('Video Source', 'masteriyo')}</Label>
										<Select
											options={[
												{
													value: 'source',
													label: __('Select Video Source', 'masteriyo'),
												},
												{ value: 'youtube', label: __('Youtube', 'masteriyo') },
												{ value: 'vimeo', label: __('Vimeo', 'masteriyo') },
												{ value: 'custom', label: __('Custom', 'masteriyo') },
											]}
										/>
									</FormGroup>
								</div>
								<div>
									<FormGroup>
										<Label htmlFor="">{__('Video URL', 'masteriyo')}</Label>
										<Input
											placeholder="video url"
											ref={register}
											name="video_source_url"
										/>
									</FormGroup>
								</div>
							</div>

							<FormGroup>
								<Label htmlFor="">
									{__('Video Playback Time', 'masteriyo')}
								</Label>

								<div>
									<Slider
										min={0}
										max={20}
										defaultValue={playBackTime}
										onChange={playBackTimeOnChange}
									/>
								</div>
								<div>
									<Input
										type="number"
										value={playBackTime}
										onChange={() => playBackTimeOnChange}
									/>
								</div>
							</FormGroup>
							<div>
								<div className="mto-flex">
									<Button
										type="submit"
										layout="primary"
										style={{ marginRight: 16 }}>
										{__('Update', 'masteriyo')}
									</Button>
									<Button
										onClick={() =>
											push(`/builder/${lessonQuery?.data?.course_id}`)
										}>
										{__('Cancel', 'masteriyo')}
									</Button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</MainLayout>
		</Fragment>
	);
};

export default EditLesson;
