import {
	Box,
	FormControl,
	FormLabel,
	Icon,
	Input,
	Select,
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
import { infoIconStyles } from '../../../config/styles';
import { CountrySchema } from '../../../schemas';
import { GeneralSettingsMap } from '../../../types';
import countries from '../../../utils/countires';

interface Props {
	generalData?: GeneralSettingsMap;
}
const GeneralSettings: React.FC<Props> = (props) => {
	const { generalData } = props;
	const { register, setValue } = useFormContext();

	const [primaryColor, setPrimaryColor] = useState(
		generalData?.primary_color || '#787DFF'
	);

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

	useEffect(() => {
		setValue('general.primary_color', primaryColor);
	}, [primaryColor, setValue]);

	return (
		<Tabs orientation="vertical">
			<Stack direction="row" flex="1">
				<TabList sx={tabListStyles}>
					<Tab sx={tabStyles}>{__('Store', 'masteriyo')}</Tab>
					<Tab sx={tabStyles}>{__('Currency Options', 'masteriyo')}</Tab>
					<Tab sx={tabStyles}>{__('Styling', 'masteriyo')}</Tab>
				</TabList>
				<TabPanels flex="1">
					<TabPanel>
						<Stack direction="column" spacing="6">
							<Stack direction="row" spacing="8">
								<FormControl>
									<FormLabel>
										{__('Country', 'masteriyo')}
										<Tooltip
											label={__('Country where you live', 'masteriyo')}
											hasArrow
											fontSize="xs">
											<Box as="span" sx={infoIconStyles}>
												<Icon as={BiInfoCircle} />
											</Box>
										</Tooltip>
									</FormLabel>

									<Select
										{...register('general.country')}
										defaultValue={generalData?.country}>
										{countries.map((country: CountrySchema) => (
											<option
												value={country.countryCode}
												key={country.countryCode}>
												{country.countryName}
											</option>
										))}
									</Select>
								</FormControl>
								<FormControl>
									<FormLabel>
										{__('City', 'masteriyo')}
										<Tooltip
											label={__(
												'Your city where you are residing',
												'masteriyo'
											)}
											hasArrow
											fontSize="xs">
											<Box as="span" sx={infoIconStyles}>
												<Icon as={BiInfoCircle} />
											</Box>
										</Tooltip>
									</FormLabel>

									<Input
										type="text"
										{...register('general.city')}
										defaultValue={generalData?.city}
									/>
								</FormControl>
							</Stack>
							<Stack direction="row" spacing="8">
								<FormControl>
									<FormLabel>
										{__('Adress Line 1', 'masteriyo')}
										<Tooltip
											label={__('Your street address')}
											hasArrow
											fontSize="xs">
											<Box as="span" sx={infoIconStyles}>
												<Icon as={BiInfoCircle} />
											</Box>
										</Tooltip>
									</FormLabel>
									<Input
										type="text"
										{...register('general.address_line1')}
										defaultValue={generalData?.address_line1}
									/>
								</FormControl>
								<FormControl>
									<FormLabel>
										{__('Adress Line 2', 'masteriyo')}
										<Tooltip
											label={__('Your street address 2')}
											hasArrow
											fontSize="xs">
											<Box as="span" sx={infoIconStyles}>
												<Icon as={BiInfoCircle} />
											</Box>
										</Tooltip>
									</FormLabel>
									<Input
										type="text"
										{...register('general.address_line2')}
										defaultValue={generalData?.address_line2}
									/>
								</FormControl>
							</Stack>
						</Stack>
					</TabPanel>
					<TabPanel>
						<Stack direction="column" spacing="6">
							<Stack direction="row" spacing="8">
								<FormControl>
									<FormLabel minW="xs">{__('Currency', 'masteriyo')}</FormLabel>
									<Select
										{...register('general.currency')}
										defaultValue={generalData?.currency}>
										{countries.map((country: CountrySchema) => (
											<option
												value={country.countryCode}
												key={country.countryCode}>
												{country.countryName} ({country.currencyCode})
											</option>
										))}
									</Select>
								</FormControl>
								<FormControl>
									<FormLabel minW="xs">
										{__('Currency Position', 'masteriyo')}
									</FormLabel>
									<Select
										{...register('general.currency_position')}
										defaultValue={generalData?.currency_position}>
										<option value="left">{__('Left', 'masteriyo')}</option>
										<option value="right">{__('Right', 'masteriyo')}</option>
									</Select>
								</FormControl>
							</Stack>
							<Stack direction="row" spacing="8">
								<FormControl>
									<FormLabel minW="xs">
										{__('Thousand Separator', 'masteriyo')}
									</FormLabel>
									<Input
										type="text"
										{...register('general.thousand_separator')}
										defaultValue={generalData?.thousand_separator}
									/>
								</FormControl>
								<FormControl>
									<FormLabel minW="xs">
										{__('Decimal Separator', 'masteriyo')}
									</FormLabel>
									<Input
										type="text"
										{...register('general.decimal_separator')}
										defaultValue={generalData?.decimal_separator}
									/>
								</FormControl>
							</Stack>
							<Stack direction="row" spacing="8">
								<FormControl>
									<FormLabel minW="xs">
										{__('Number of Decimals', 'masteriyo')}
									</FormLabel>
									<Input
										type="text"
										{...register('general.number_of_decimals')}
										defaultValue={generalData?.number_of_decimals}
									/>
								</FormControl>
							</Stack>
						</Stack>
					</TabPanel>
					<TabPanel>
						<Stack direction="column" spacing="6">
							<FormControl>
								<FormLabel minW="xs">
									{__('Primary Color', 'masteriyo')}
								</FormLabel>
								<input
									type="hidden"
									{...register('general.primary_color')}
									defaultValue={generalData?.primary_color}
								/>

								<ColorInput color={primaryColor} setColor={setPrimaryColor} />
							</FormControl>
							<FormControl>
								<FormLabel minW="xs">{__('Theme', 'masteriyo')}</FormLabel>
								<Select
									{...register('general.theme')}
									defaultValue={generalData?.theme}>
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
