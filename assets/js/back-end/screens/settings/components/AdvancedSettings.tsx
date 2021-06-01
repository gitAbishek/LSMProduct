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
	advanceData?: AdvancedSettingsMap;
}

const AdvancedSettings: React.FC<Props> = (props) => {
	const { advanceData } = props;
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
										<Switch {...field} defaultChecked={advanceData?.debug} />
									)}
								/>
							</Stack>
						</FormControl>

						<FormControl>
							<Stack direction="row">
								<FormLabel minW="3xs">{__('Debug', 'masteriyo')}</FormLabel>
								<Select
									defaultValue={advanceData?.style}
									{...register('advance.style')}>
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
