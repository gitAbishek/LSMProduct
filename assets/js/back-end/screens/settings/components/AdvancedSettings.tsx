import {
	FormControl,
	FormLabel,
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
import { AdvancedSettingsMap } from '../../../types';

interface Props {
	advanceData?: AdvancedSettingsMap;
}

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

const AdvancedSettings: React.FC<Props> = (props) => {
	const { advanceData } = props;
	const { register } = useFormContext();
	return (
		<Tabs orientation="vertical">
			<Stack direction="row" flex="1">
				<TabList sx={tabListStyles}>
					<Tab sx={tabStyles}>{__('Page Setup', 'masteriyo')}</Tab>
				</TabList>
				<TabPanels flex="1">
					<TabPanel>
						<Stack direction="column" spacing="6">
							<Stack direction="column" spacing="8">
								<FormControl>
									<Stack direction="row">
										<FormLabel minW="3xs">
											{__('Template Debug', 'masteriyo')}
										</FormLabel>

										<Controller
											name="advance.template_debug"
											render={({ field }) => (
												<Switch
													{...field}
													defaultChecked={advanceData?.template_debug}
												/>
											)}
										/>
									</Stack>
								</FormControl>

								<FormControl>
									<Stack direction="row">
										<FormLabel minW="3xs">{__('Debug', 'masteriyo')}</FormLabel>
										<Controller
											name="advance.debug"
											render={({ field }) => (
												<Switch
													{...field}
													defaultChecked={advanceData?.debug}
												/>
											)}
										/>
									</Stack>
								</FormControl>
							</Stack>
						</Stack>
					</TabPanel>
				</TabPanels>
			</Stack>
		</Tabs>
	);
};

export default AdvancedSettings;
