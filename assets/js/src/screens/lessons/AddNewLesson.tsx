import { Col, Row } from 'react-grid-system';
import React, { Fragment, useState } from 'react';
import {
	SectionAction,
	SectionBody,
	SectionFooter,
	SectionHeader,
	SectionTitle,
} from 'Components/common/GlobalComponents';

import Button from 'Components/common/Button';
import Dropdown from 'rc-dropdown';
import DropdownOverlay from 'Components/common/DropdownOverlay';
import FlexRow from 'Components/common/FlexRow';
import FormGroup from 'Components/common/FormGroup';
import ImageUpload from 'Components/common/ImageUpload';
import Input from 'Components/common/Input';
import Label from 'Components/common/Label';
import MainLayout from 'Layouts/MainLayout';
import MainToolbar from 'Layouts/MainToolbar';
import OptionButton from 'Components/common/OptionButton';
import PropTypes from 'prop-types';
import Select from 'Components/common/Select';
import Slider from 'rc-slider';
import Textarea from 'Components/common/Textarea';
import styled from 'styled-components';

const AddNewLesson = (props) => {
	const { id } = props;
	const [playBackTime, setPlayBackTime] = useState(3);

	const playBackTimeOnChange = (value) => {
		setPlayBackTime(value);
	};

	return (
		<Fragment>
			<MainToolbar />
			<MainLayout>
				<AddNewLessonContainer>
					<SectionHeader>
						<SectionTitle>Add New Lesson</SectionTitle>
						<SectionAction>
							<Dropdown
								trigger={'click'}
								placement={'bottomRight'}
								animation={'slide-up'}
								overlay={
									<DropdownOverlay>
										<ul>
											<li>Delete</li>
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
								<Label htmlFor="">Lesson Name</Label>
								<Input placeholder="Your topic title" />
							</FormGroup>

							<FormGroup>
								<Label htmlFor="">Description</Label>
								<Textarea placeholder="Your course description" rows="5" />
							</FormGroup>

							<FormGroup>
								<Label htmlFor="">Featured Image</Label>
								<ImageUpload title="Drag image or click to upload" />
							</FormGroup>
							<Row>
								<Col>
									<FormGroup>
										<Label htmlFor="">Video Source</Label>
										<Select
											options={[
												{ value: 'source', label: 'Select Video Source' },
												{ value: 'youtube', label: 'Youtube' },
												{ value: 'vimeo', label: 'Vimeo' },
												{ value: 'custom', label: 'Custom' },
											]}
										/>
									</FormGroup>
								</Col>
								<Col>
									<FormGroup>
										<Label htmlFor="">Video URL</Label>
										<Input placeholder="video url" />
									</FormGroup>
								</Col>
							</Row>

							<FormGroup>
								<Label htmlFor="">Video Playback Time</Label>
								<Row align="center">
									<Col sm={10}>
										<Slider
											min={0}
											max={20}
											defaultValue={playBackTime}
											onChange={playBackTimeOnChange}
										/>
									</Col>
									<Col sm={2}>
										<Input
											type="number"
											value={playBackTime}
											onChange={playBackTimeOnChange}
										/>
									</Col>
								</Row>
							</FormGroup>
							<SectionFooter>
								<FlexRow>
									<Button primary style={{ marginRight: 16 }}>
										Add New Lesson
									</Button>
									<Button>Cancel</Button>
								</FlexRow>
							</SectionFooter>
						</form>
					</SectionBody>
				</AddNewLessonContainer>
			</MainLayout>
		</Fragment>
	);
};

AddNewLesson.propTypes = {
	id: PropTypes.string,
};

const AddNewLessonContainer = styled.div``;

export default AddNewLesson;
