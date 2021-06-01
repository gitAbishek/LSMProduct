import {
	Stack,
	Box,
	Flex,
	Heading,
	FormControl,
	FormLabel,
	Switch,
	Select,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { useFormContext, Controller } from 'react-hook-form';
import { AdvancedSettingsMap } from '../../../types';

interface Props {
	advancedData?: AdvancedSettingsMap;
}

const AdvancedSettings: React.FC<Props> = (props) => {
	const { advancedData } = props;
	const { register } = useFormContext();
	return (
		<Stack direction="column" spacing="10">
			<Box>
				<Stack direction="column" spacing="6">
					<Flex
						align="center"
						justify="space-between"
						borderBottom="1px"
						borderColor="gray.100"
						pb="3">
						<Heading fontSize="lg" fontWeight="semibold">
							{__('Debug', 'masteriyo')}
						</Heading>
					</Flex>
					<Stack direction="column" spacing="8">
						<FormControl>
							<Stack direction="row">
								<FormLabel minW="3xs">
									{__('Template Debug', 'masteriyo')}
								</FormLabel>

								<Controller
									name="advanced.template_debug_enable"
									render={({ field }) => (
										<Switch
											{...field}
											defaultChecked={advancedData?.template_debug_enable}
										/>
									)}
								/>
							</Stack>
						</FormControl>

						<FormControl>
							<Stack direction="row">
								<FormLabel minW="3xs">{__('Debug', 'masteriyo')}</FormLabel>
								<Controller
									name="advanced.debug_enable"
									render={({ field }) => (
										<Switch
											{...field}
											defaultChecked={advancedData?.debug_enable}
										/>
									)}
								/>
							</Stack>
						</FormControl>

						<FormControl>
							<Stack direction="row">
								<FormLabel minW="3xs">{__('Debug', 'masteriyo')}</FormLabel>
								<Select
									defaultValue={advancedData?.styles_mode}
									{...register('advanced.styles_mode')}>
									<option value="none">{__('None', 'masteriyo')}</option>
									<option value="simple">{__('Simple', 'masteriyo')}</option>
									<option value="advanced">
										{__('Advanced', 'masteriyo')}
									</option>
								</Select>
							</Stack>
						</FormControl>
					</Stack>
				</Stack>
			</Box>
		</Stack>
	);
};

export default AdvancedSettings;
