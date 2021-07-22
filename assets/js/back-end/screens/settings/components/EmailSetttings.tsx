import {
	Collapse,
	FormControl,
	FormLabel,
	Input,
	Stack,
	Switch,
	Tab,
	TabList,
	TabPanel,
	TabPanels,
	Tabs,
	Textarea,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { Controller, useFormContext, useWatch } from 'react-hook-form';
import Editor from '../../../components/common/Editor';
import ImageUpload from '../../../components/common/ImageUpload';
import { EmailsSetttingsMap } from '../../../types';

interface Props {
	emailData?: EmailsSetttingsMap;
}
const EmailSetttings: React.FC<Props> = (props) => {
	const { emailData } = props;
	const { register, setValue, control } = useFormContext();
	const showNewOrderOptions = useWatch({
		name: 'emails.new_order.enable',
		defaultValue: emailData?.new_order.enable,
		control,
	});

	const showProcessingOrder = useWatch({
		name: 'emails.processing_order.enable',
		defaultValue: emailData?.processing_order.enable,
		control,
	});

	const showCompletedOrder = useWatch({
		name: 'emails.completed_order.enable',
		defaultValue: emailData?.completed_order.enable,
		control,
	});

	const showOnholdeOrder = useWatch({
		name: 'emails.onhold_order.enable',
		defaultValue: emailData?.onhold_order.enable,
		control,
	});

	const showCancelledOrder = useWatch({
		name: 'emails.cancelled_order.enable',
		defaultValue: emailData?.cancelled_order.enable,
		control,
	});

	const showEnrolledCourse = useWatch({
		name: 'emails.enrolled_course.enable',
		defaultValue: emailData?.enrolled_course.enable,
		control,
	});

	const showCompletedCourse = useWatch({
		name: 'emails.completed_course.enable',
		defaultValue: emailData?.completed_course.enable,
		control,
	});

	const showBecomeAnInstructor = useWatch({
		name: 'emails.become_an_instructor.enable',
		defaultValue: emailData?.become_an_instructor.enable,
		control,
	});

	const tabStyles = {
		justifyContent: 'flex-start',
		w: '180px',
		borderLeft: 0,
		borderRight: '2px solid',
		borderRightColor: 'transparent',
		marginLeft: 0,
		marginRight: '-2px',
		pl: 0,
		fontSize: 'sm',
		textAlign: 'left',
	};

	const tabListStyles = {
		borderLeft: 0,
		borderRight: '2px solid',
		borderRightColor: 'gray.200',
	};

	return (
		<Tabs orientation="vertical">
			<Stack direction="row" flex="1">
				<TabList sx={tabListStyles}>
					<Tab sx={tabStyles}>{__('General', 'masteriyo')}</Tab>
					<Tab sx={tabStyles}>{__('New Order', 'masteriyo')}</Tab>
					<Tab sx={tabStyles}>{__('Processing Order', 'masteriyo')}</Tab>
					<Tab sx={tabStyles}>{__('Completed Order', 'masteriyo')}</Tab>
					<Tab sx={tabStyles}>{__('Onhold Order', 'masteriyo')}</Tab>
					<Tab sx={tabStyles}>{__('Cancelled Order', 'masteriyo')}</Tab>
					<Tab sx={tabStyles}>{__('Enrolled Course', 'masteriyo')}</Tab>
					<Tab sx={tabStyles}>{__('Completed Course', 'masteriyo')}</Tab>
					<Tab sx={tabStyles}>{__('Become and Instructor', 'masteriyo')}</Tab>
				</TabList>
				<TabPanels flex="1">
					<TabPanel>
						<Stack direction="column" spacing="6">
							<FormControl>
								<FormLabel minW="160px">
									{__('From Name', 'masteriyo')}
								</FormLabel>
								<Input
									type="text"
									defaultValue={emailData?.general?.from_name}
									{...register('emails.general.from_name')}
								/>
							</FormControl>

							<FormControl>
								<FormLabel minW="160px">
									{__('From Email', 'masteriyo')}
								</FormLabel>
								<Input
									type="email"
									defaultValue={emailData?.general?.from_email}
									{...register('emails.general.from_email')}
								/>
							</FormControl>

							<FormControl>
								<FormLabel minW="160px">
									{__('Default Content', 'masteriyo')}
								</FormLabel>
								<Textarea
									defaultValue={emailData?.general?.default_content}
									{...register('emails.general.default_content')}
								/>
							</FormControl>

							<FormControl>
								<FormLabel minW="160px">
									{__('Header Image', 'masteriyo')}
								</FormLabel>
								<ImageUpload
									name="emails.general.header_image"
									mediaId={emailData?.general.header_image}
									setValue={setValue}
									register={register}
								/>
							</FormControl>

							<FormControl>
								<FormLabel minW="160px">
									{__('Footer Text', 'masteriyo')}
								</FormLabel>
								<Textarea
									defaultValue={emailData?.general?.footer_text}
									{...register('emails.general.footer_text')}
								/>
							</FormControl>
						</Stack>
					</TabPanel>

					<TabPanel>
						<Stack direction="column" spacing="6">
							<FormControl>
								<Stack direction="row">
									<FormLabel minW="160px">
										{__('Enable', 'masteriyo')}
									</FormLabel>
									<Controller
										name="emails.new_order.enable"
										render={({ field }) => (
											<Switch
												{...field}
												defaultChecked={emailData?.new_order.enable}
											/>
										)}
									/>
								</Stack>
							</FormControl>
							<Collapse in={showNewOrderOptions}>
								<Stack direction="column" spacing="6">
									<FormControl>
										<FormLabel minW="160px">
											{__('Recipients', 'masteriyo')}
										</FormLabel>
										<Input
											type="text"
											defaultValue={emailData?.new_order?.recipients}
											{...register('emails.new_order.recipients')}
										/>
									</FormControl>

									<FormControl>
										<FormLabel minW="160px">
											{__('Subject', 'masteriyo')}
										</FormLabel>
										<Textarea
											defaultValue={emailData?.new_order?.subject}
											{...register('emails.new_order.subject')}
										/>
									</FormControl>

									<FormControl>
										<FormLabel minW="160px">
											{__('Heading', 'masteriyo')}
										</FormLabel>
										<Input
											type="text"
											defaultValue={emailData?.new_order.heading}
											{...register('emails.new_order.heading')}
										/>
									</FormControl>

									<FormControl>
										<FormLabel minW="160px">
											{__('Content', 'masteriyo')}
										</FormLabel>
										<Editor
											name="emails.new_order.content"
											defaultValue={emailData?.new_order.content}
										/>
									</FormControl>
								</Stack>
							</Collapse>
						</Stack>
					</TabPanel>

					<TabPanel>
						<Stack direction="column" spacing="6">
							<FormControl>
								<Stack direction="row">
									<FormLabel minW="160px">
										{__('Enable', 'masteriyo')}
									</FormLabel>
									<Controller
										name="emails.processing_order.enable"
										render={({ field }) => (
											<Switch
												{...field}
												defaultChecked={emailData?.processing_order.enable}
											/>
										)}
									/>
								</Stack>
							</FormControl>
							<Collapse in={showProcessingOrder}>
								<Stack direction="column" spacing="6">
									<FormControl>
										<FormLabel minW="160px">
											{__('Subject', 'masteriyo')}
										</FormLabel>

										<Textarea
											defaultValue={emailData?.processing_order?.subject}
											{...register('emails.processing_order.subject')}
										/>
									</FormControl>

									<FormControl>
										<FormLabel minW="160px">
											{__('Heading', 'masteriyo')}
										</FormLabel>
										<Input
											type="text"
											defaultValue={emailData?.processing_order.heading}
											{...register('emails.processing_order.heading')}
										/>
									</FormControl>

									<FormControl>
										<FormLabel minW="160px">
											{__('Content', 'masteriyo')}
										</FormLabel>
										<Editor
											name="emails.processing_order.content"
											defaultValue={emailData?.processing_order.content}
										/>
									</FormControl>
								</Stack>
							</Collapse>
						</Stack>
					</TabPanel>

					<TabPanel>
						<Stack direction="column" spacing="6">
							<FormControl>
								<Stack direction="row">
									<FormLabel minW="160px">
										{__('Enable', 'masteriyo')}
									</FormLabel>
									<Controller
										name="emails.completed_order.enable"
										render={({ field }) => (
											<Switch
												{...field}
												defaultChecked={emailData?.completed_order.enable}
											/>
										)}
									/>
								</Stack>
							</FormControl>
							<Collapse in={showCompletedOrder}>
								<Stack direction="column" spacing="6">
									<FormControl>
										<FormLabel minW="160px">
											{__('Subject', 'masteriyo')}
										</FormLabel>
										<Textarea
											defaultValue={emailData?.completed_order?.subject}
											{...register('emails.completed_order.subject')}
										/>
									</FormControl>

									<FormControl>
										<FormLabel minW="160px">
											{__('Heading', 'masteriyo')}
										</FormLabel>
										<Input
											type="text"
											defaultValue={emailData?.completed_order.heading}
											{...register('emails.completed_order.heading')}
										/>
									</FormControl>

									<FormControl>
										<FormLabel minW="160px">
											{__('Content', 'masteriyo')}
										</FormLabel>
										<Editor
											name="emails.completed_order.content"
											defaultValue={emailData?.completed_order.content}
										/>
									</FormControl>
								</Stack>
							</Collapse>
						</Stack>
					</TabPanel>

					<TabPanel>
						<Stack direction="column" spacing="6">
							<FormControl>
								<Stack direction="row">
									<FormLabel minW="160px">
										{__('Enable', 'masteriyo')}
									</FormLabel>
									<Controller
										name="emails.onhold_order.enable"
										render={({ field }) => (
											<Switch
												{...field}
												defaultChecked={emailData?.onhold_order.enable}
											/>
										)}
									/>
								</Stack>
							</FormControl>
							<Collapse in={showOnholdeOrder}>
								<Stack direction="column" spacing="6">
									<FormControl>
										<FormLabel minW="160px">
											{__('Subject', 'masteriyo')}
										</FormLabel>
										<Textarea
											defaultValue={emailData?.onhold_order?.subject}
											{...register('emails.onhold_order.subject')}
										/>
									</FormControl>

									<FormControl>
										<FormLabel minW="160px">
											{__('Heading', 'masteriyo')}
										</FormLabel>
										<Input
											type="text"
											defaultValue={emailData?.onhold_order.heading}
											{...register('emails.onhold_order.heading')}
										/>
									</FormControl>

									<FormControl>
										<FormLabel minW="160px">
											{__('Content', 'masteriyo')}
										</FormLabel>
										<Editor
											name="emails.onhold_order.content"
											defaultValue={emailData?.onhold_order.content}
										/>
									</FormControl>
								</Stack>
							</Collapse>
						</Stack>
					</TabPanel>
					<TabPanel>
						<Stack direction="column" spacing="6">
							<FormControl>
								<Stack direction="row">
									<FormLabel minW="160px">
										{__('Enable', 'masteriyo')}
									</FormLabel>
									<Controller
										name="emails.cancelled_order.enable"
										render={({ field }) => (
											<Switch
												{...field}
												defaultChecked={emailData?.cancelled_order.enable}
											/>
										)}
									/>
								</Stack>
							</FormControl>
							<Collapse in={showCancelledOrder}>
								<Stack direction="column" spacing="6">
									<FormControl>
										<FormLabel minW="160px">
											{__('Recipients', 'masteriyo')}
										</FormLabel>
										<Input
											type="text"
											defaultValue={emailData?.cancelled_order?.recipients}
											{...register('emails.cancelled_order.recipients')}
										/>
									</FormControl>

									<FormControl>
										<FormLabel minW="160px">
											{__('Subject', 'masteriyo')}
										</FormLabel>
										<Textarea
											defaultValue={emailData?.cancelled_order?.subject}
											{...register('emails.cancelled_order.subject')}
										/>
									</FormControl>

									<FormControl>
										<FormLabel minW="160px">
											{__('Heading', 'masteriyo')}
										</FormLabel>
										<Input
											type="text"
											defaultValue={emailData?.cancelled_order.heading}
											{...register('emails.cancelled_order.heading')}
										/>
									</FormControl>

									<FormControl>
										<FormLabel minW="160px">
											{__('Content', 'masteriyo')}
										</FormLabel>
										<Editor
											name="emails.cancelled_order.content"
											defaultValue={emailData?.cancelled_order.content}
										/>
									</FormControl>
								</Stack>
							</Collapse>
						</Stack>
					</TabPanel>

					<TabPanel>
						<Stack direction="column" spacing="6">
							<FormControl>
								<Stack direction="row">
									<FormLabel minW="160px">
										{__('Enable', 'masteriyo')}
									</FormLabel>
									<Controller
										name="emails.enrolled_course.enable"
										render={({ field }) => (
											<Switch
												{...field}
												defaultChecked={emailData?.enrolled_course.enable}
											/>
										)}
									/>
								</Stack>
							</FormControl>
							<Collapse in={showEnrolledCourse}>
								<Stack direction="column" spacing="6">
									<FormControl>
										<FormLabel minW="160px">
											{__('Subject', 'masteriyo')}
										</FormLabel>
										<Textarea
											defaultValue={emailData?.enrolled_course?.subject}
											{...register('emails.enrolled_course.subject')}
										/>
									</FormControl>

									<FormControl>
										<FormLabel minW="160px">
											{__('Heading', 'masteriyo')}
										</FormLabel>
										<Input
											type="text"
											defaultValue={emailData?.enrolled_course.heading}
											{...register('emails.enrolled_course.heading')}
										/>
									</FormControl>

									<FormControl>
										<FormLabel minW="160px">
											{__('Content', 'masteriyo')}
										</FormLabel>
										<Editor
											name="emails.enrolled_course.content"
											defaultValue={emailData?.enrolled_course.content}
										/>
									</FormControl>
								</Stack>
							</Collapse>
						</Stack>
					</TabPanel>

					<TabPanel>
						<Stack direction="column" spacing="6">
							<FormControl>
								<Stack direction="row">
									<FormLabel minW="160px">
										{__('Enable', 'masteriyo')}
									</FormLabel>
									<Controller
										name="emails.completed_course.enable"
										render={({ field }) => (
											<Switch
												{...field}
												defaultChecked={emailData?.completed_course.enable}
											/>
										)}
									/>
								</Stack>
							</FormControl>
							<Collapse in={showCompletedCourse}>
								<Stack direction="column" spacing="6">
									<FormControl>
										<FormLabel minW="160px">
											{__('Subject', 'masteriyo')}
										</FormLabel>
										<Textarea
											defaultValue={emailData?.completed_course?.subject}
											{...register('emails.completed_course.subject')}
										/>
									</FormControl>

									<FormControl>
										<FormLabel minW="160px">
											{__('Heading', 'masteriyo')}
										</FormLabel>
										<Input
											type="text"
											defaultValue={emailData?.completed_course.heading}
											{...register('emails.completed_course.heading')}
										/>
									</FormControl>

									<FormControl>
										<FormLabel minW="160px">
											{__('Content', 'masteriyo')}
										</FormLabel>
										<Editor
											name="emails.completed_course.content"
											defaultValue={emailData?.completed_course.content}
										/>
									</FormControl>
								</Stack>
							</Collapse>
						</Stack>
					</TabPanel>

					<TabPanel>
						<Stack direction="column" spacing="6">
							<FormControl>
								<Stack direction="row">
									<FormLabel minW="160px">
										{__('Enable', 'masteriyo')}
									</FormLabel>
									<Controller
										name="emails.become_an_instructor.enable"
										render={({ field }) => (
											<Switch
												{...field}
												defaultChecked={emailData?.become_an_instructor.enable}
											/>
										)}
									/>
								</Stack>
							</FormControl>
							<Collapse in={showBecomeAnInstructor}>
								<Stack direction="column" spacing="6">
									<FormControl>
										<FormLabel minW="160px">
											{__('Subject', 'masteriyo')}
										</FormLabel>
										<Textarea
											defaultValue={emailData?.become_an_instructor?.subject}
											{...register('emails.become_an_instructor.subject')}
										/>
									</FormControl>

									<FormControl>
										<FormLabel minW="160px">
											{__('Heading', 'masteriyo')}
										</FormLabel>
										<Input
											type="text"
											defaultValue={emailData?.become_an_instructor.heading}
											{...register('emails.become_an_instructor.heading')}
										/>
									</FormControl>

									<FormControl>
										<FormLabel minW="160px">
											{__('Content', 'masteriyo')}
										</FormLabel>
										<Editor
											name="emails.become_an_instructor.content"
											defaultValue={emailData?.become_an_instructor.content}
										/>
									</FormControl>
								</Stack>
							</Collapse>
						</Stack>
					</TabPanel>
				</TabPanels>
			</Stack>
		</Tabs>
	);
};

export default EmailSetttings;
