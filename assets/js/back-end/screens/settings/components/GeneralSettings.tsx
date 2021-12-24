import {
	Box,
	FormControl,
	FormLabel,
	Icon,
	Stack,
	Tab,
	TabList,
	TabPanel,
	TabPanels,
	Tabs,
	Tooltip,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useEffect, useState } from 'react';
import { useFormContext } from 'react-hook-form';
import { BiInfoCircle } from 'react-icons/bi';
import ColorInput from '../../../components/common/ColorInput';
import {
	infoIconStyles,
	tabListStyles,
	tabStyles,
} from '../../../config/styles';
import { GeneralSettingsMap } from '../../../types';

interface Props {
	generalData?: GeneralSettingsMap;
}

const GeneralSettings: React.FC<Props> = (props) => {
	const { generalData } = props;
	const { register, setValue } = useFormContext();

	const [primaryColor, setPrimaryColor] = useState(
		generalData?.styling?.primary_color || '#787DFF'
	);

	useEffect(() => {
		setValue('general.styling.primary_color', primaryColor);
	}, [primaryColor, setValue]);

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
								<FormLabel>
									{__('Primary Color', 'masteriyo')}
									<Tooltip
										label={__(
											'Choose a color to match your brand or site. This color reflects on buttons, links and few other elements.',
											'masteriyo'
										)}
										hasArrow
										fontSize="xs">
										<Box as="span" sx={infoIconStyles}>
											<Icon as={BiInfoCircle} />
										</Box>
									</Tooltip>
								</FormLabel>
								<input
									type="hidden"
									{...register('general.styling.primary_color')}
									defaultValue={generalData?.styling?.primary_color}
								/>

								<ColorInput color={primaryColor} setColor={setPrimaryColor} />
							</FormControl>
						</Stack>
					</TabPanel>
				</TabPanels>
			</Stack>
		</Tabs>
	);
};

export default GeneralSettings;
