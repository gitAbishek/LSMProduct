import {
	Alert,
	AlertIcon,
	Box,
	FormControl,
	FormLabel,
	Icon,
	Stack,
	Switch,
	Tab,
	TabList,
	TabPanel,
	TabPanels,
	Tabs,
	Tooltip,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { useFormContext } from 'react-hook-form';
import { BiInfoCircle } from 'react-icons/bi';
import {
	infoIconStyles,
	tabListStyles,
	tabStyles,
} from '../../../config/styles';
import { SingleCourseSettingsMap } from '../../../types';

interface Props {
	singleCourseData?: SingleCourseSettingsMap;
}

const SingleCourseSettings: React.FC<Props> = (props) => {
	const { singleCourseData } = props;
	const { register } = useFormContext();

	return (
		<Tabs orientation="vertical">
			<Stack direction="row" flex="1">
				<TabList sx={tabListStyles}>
					<Tab sx={tabStyles}>{__('Display', 'masteriyo')}</Tab>
					<Tab sx={tabStyles}>{__('Related Courses', 'masteriyo')}</Tab>
				</TabList>
				<TabPanels flex="1">
					<TabPanel>
						<Stack direction="column" spacing="8">
							<FormControl>
								<Stack direction="row" spacing="4">
									<FormLabel>
										{__('Enable Review', 'masteriyo')}
										<Tooltip
											label={__(
												'Display review tab in single course page.',
												'masteriyo'
											)}
											hasArrow
											fontSize="xs">
											<Box as="span" sx={infoIconStyles}>
												<Icon as={BiInfoCircle} />
											</Box>
										</Tooltip>
									</FormLabel>
									<Switch
										{...register('single_course.display.enable_review')}
										defaultChecked={singleCourseData?.display?.enable_review}
									/>
								</Stack>
							</FormControl>
						</Stack>
					</TabPanel>
					<TabPanel>
						<Stack spacing={4}>
							<FormControl>
								<Stack direction="row" spacing="4">
									<FormLabel>
										{__('Enable Related Courses', 'masteriyo')}
										<Tooltip
											label={__(
												'Enable related courses section in single course page.',
												'masteriyo'
											)}
											hasArrow
											fontSize="xs">
											<Box as="span" sx={infoIconStyles}>
												<Icon as={BiInfoCircle} />
											</Box>
										</Tooltip>
									</FormLabel>
									<Switch
										{...register('single_course.related_courses.enable')}
										defaultChecked={singleCourseData?.related_courses?.enable}
									/>
								</Stack>
							</FormControl>
							<Alert status="info">
								<AlertIcon />
								{__(
									'The related courses will be shown based on categories.',
									'masteriyo'
								)}
							</Alert>
						</Stack>
					</TabPanel>
				</TabPanels>
			</Stack>
		</Tabs>
	);
};

export default SingleCourseSettings;
