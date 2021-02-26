import React, { Fragment, useState } from 'react';

import Button from 'Components/common/Button';
import Dropdown from 'rc-dropdown';
import FormGroup from 'Components/common/FormGroup';
import ImageUpload from 'Components/common/ImageUpload';
import Input from 'Components/common/Input';
import Label from 'Components/common/Label';
import MainLayout from 'Layouts/MainLayout';
import MainToolbar from 'Layouts/MainToolbar';
import OptionButton from 'Components/common/OptionButton';
import Select from 'Components/common/Select';
import Slider from 'rc-slider';
import Textarea from 'Components/common/Textarea';
import { __ } from '@wordpress/i18n';

interface Props {}

const AddNewLesson: React.FC<Props> = (props) => {
	const [playBackTime, setPlayBackTime] = useState(3);

	const playBackTimeOnChange = (value: number) => {
		setPlayBackTime(value);
	};

	return (
		<Fragment>
			<MainToolbar />
			<MainLayout>
				<div>
					{/* <SectionHeader>
						<SectionTitle>{__('Add New Lesson', 'masteriyo')}</SectionTitle>
						<SectionAction>
							<Dropdown
								trigger={'click'}
								placement={'bottomRight'}
								animation={'slide-up'}
								overlay={
									<DropdownOverlay>
										<ul>
											<li>{__('Delete', 'masteriyo')}</li>
										</ul>
									</DropdownOverlay>
								}>
								<OptionButton />
							</Dropdown>
						</SectionAction>
					</SectionHeader>
					<SectionBody>
						<form action="">
							<FormGroup>
								<Label htmlFor="">{__('Lesson Name', 'masteriyo')}</Label>
								<Input placeholder={__('Your topic title', 'masteriyo')} />
							</FormGroup>

							<FormGroup>
								<Label htmlFor="">{__('Description', 'masteriyo')}</Label>
								<Textarea
									placeholder={__('Your course description', 'masteriyo')}
									rows={5}
								/>
							</FormGroup>

							<FormGroup>
								<Label htmlFor="">{__('Featured Image', 'masteriyo')}</Label>
								<ImageUpload
									title={__('Drag image or click to upload', 'masteriyo')}
								/>
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
										<Input placeholder="video url" />
									</FormGroup>
								</div>
							</div>

							<FormGroup>
								<Label htmlFor="">
									{__('Video Playback Time', 'masteriyo')}
								</Label>
								<DeviceRotationRate>
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
								</DeviceRotationRate>
							</FormGroup>
							<SectionFooter>
								<FlexRow>
									<Button appearance="primary" style={{ marginRight: 16 }}>
										{__('Add New Lesson', 'masteriyo')}
									</Button>
									<Button>{__('Cancel', 'masteriyo')}</Button>
								</FlexRow>
							</SectionFooter>
						</form>
					</SectionBody> */}
				</div>
			</MainLayout>
		</Fragment>
	);
};

export default AddNewLesson;
