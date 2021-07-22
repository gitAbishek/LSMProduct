import {
	FormControl,
	FormLabel,
	NumberDecrementStepper,
	NumberIncrementStepper,
	NumberInput,
	NumberInputField,
	NumberInputStepper,
	Select,
	Stack,
	Switch,
	Tab,
	TabList,
	TabPanel,
	TabPanels,
	Tabs,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { Controller, useFormContext } from 'react-hook-form';

const CourseArchiveSettings: React.FC = () => {
	const { register, setValue } = useFormContext();

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
					<Tab sx={tabStyles}>{__('Display', 'masteriyo')}</Tab>
				</TabList>
				<TabPanels flex="1">
					<TabPanel>
						<Stack direction="column" spacing="8">
							<FormControl>
								<Stack direction="row" spacing="4">
									<FormLabel minW="2xs">
										{__('Show/Hide Search', 'masteriyo')}
									</FormLabel>
									<Switch
										{...register('courses.enable_search')}
										defaultChecked={true}
									/>
								</Stack>
							</FormControl>

							<FormControl>
								<FormLabel minW="2xs">
									{__('Course Per Page', 'masteriyo')}
								</FormLabel>
								<Controller
									name="courses.per_page"
									render={({ field }) => (
										<NumberInput {...field}>
											<NumberInputField borderRadius="sm" shadow="input" />
											<NumberInputStepper>
												<NumberIncrementStepper />
												<NumberDecrementStepper />
											</NumberInputStepper>
										</NumberInput>
									)}
								/>
							</FormControl>

							<FormControl>
								<FormLabel minW="2xs">
									{__('Course Per Row', 'masteriyo')}
								</FormLabel>
								<Controller
									name="courses.per_row"
									render={({ field }) => (
										<NumberInput {...field}>
											<NumberInputField borderRadius="sm" shadow="input" />
											<NumberInputStepper>
												<NumberIncrementStepper />
												<NumberDecrementStepper />
											</NumberInputStepper>
										</NumberInput>
									)}
								/>
							</FormControl>

							<FormControl>
								<FormLabel minW="2xs">
									{__('Thumbnail Size', 'masteriyo')}
								</FormLabel>
								<Select
									defaultValue="thumbnail"
									{...register('courses.thumbnail_size')}>
									<option value="thumbnail">Thumbnail</option>
									<option value="medium">Medium</option>
									<option value="medium_large">Medium Large</option>
									<option value="large">large</option>
								</Select>
							</FormControl>
						</Stack>
					</TabPanel>
				</TabPanels>
			</Stack>
		</Tabs>
	);
};

export default CourseArchiveSettings;