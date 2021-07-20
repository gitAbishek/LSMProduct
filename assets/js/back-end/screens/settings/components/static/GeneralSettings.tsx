import {
	FormControl,
	FormLabel,
	Select,
	Stack,
	Tab,
	TabList,
	TabPanel,
	TabPanels,
	Tabs,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import ColorInput from 'Components/common/ColorInput';
import React, { useState } from 'react';
import { useFormContext } from 'react-hook-form';

const GeneralSettings: React.FC = () => {
	const { register } = useFormContext();

	const [primaryColor, setPrimaryColor] = useState('#787DFF');

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
					<Tab sx={tabStyles}>{__('Styling', 'masteriyo')}</Tab>
				</TabList>
				<TabPanels flex="1">
					<TabPanel>
						<Stack direction="column" spacing="6">
							<FormControl>
								<FormLabel minW="xs">
									{__('Primary Color', 'masteriyo')}
								</FormLabel>
								<input type="hidden" {...register('general.primary_color')} />

								<ColorInput color={primaryColor} setColor={setPrimaryColor} />
							</FormControl>
							<FormControl>
								<FormLabel minW="xs">{__('Theme', 'masteriyo')}</FormLabel>
								<Select {...register('general.theme')}>
									<option value="minimum">
										{__('Minimum Styling', 'masteriyo')}
									</option>
									<option value="custom">
										{__('Custom Styling', 'masteriyo')}
									</option>
								</Select>
							</FormControl>
						</Stack>
					</TabPanel>
				</TabPanels>
			</Stack>
		</Tabs>
	);
};

export default GeneralSettings;
