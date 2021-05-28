import {
	Stack,
	Box,
	Flex,
	Heading,
	FormControl,
	FormLabel,
	Input,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import Select from 'Components/common/Select';
import React, { useEffect, useState } from 'react';
import ReactFlagsSelect from 'react-flags-select';
import { useForm } from 'react-hook-form';
import { currency } from '../../../utils/currency';

const GeneralSettings: React.FC = () => {
	const [country, setCountry] = useState('');
	const { register, setValue } = useForm();

	useEffect(() => {
		setValue('country', country);
	}, [country]);

	return (
		<form>
			<Stack direction="column" spacing="8">
				<Box>
					<Stack direction="column" spacing="6">
						<Flex
							align="center"
							justify="space-between"
							borderBottom="1px"
							borderColor="gray.100"
							pb="3">
							<Heading fontSize="lg" fontWeight="semibold">
								Store
							</Heading>
						</Flex>
						<Stack direction="row" spacing="8">
							<FormControl>
								<FormLabel minW="xs">Country</FormLabel>
								<input
									type="hidden"
									{...register('country')}
									defaultValue={country}
								/>
								<ReactFlagsSelect
									selected={country}
									onSelect={(code) => setCountry(code)}
								/>
							</FormControl>
							<FormControl>
								<FormLabel minW="xs">City</FormLabel>
								<Input type="text" {...register('city')} />
							</FormControl>
						</Stack>
						<Stack direction="row" spacing="8">
							<FormControl>
								<FormLabel minW="xs">Adress Line 1</FormLabel>
								<Input type="text" {...register('address_line1')} />
							</FormControl>
							<FormControl>
								<FormLabel minW="xs">Adress Line 2</FormLabel>
								<Input type="text" {...register('address_line2')} />
							</FormControl>
						</Stack>
					</Stack>
				</Box>
				<Box>
					<Stack direction="column" spacing="6">
						<Flex
							align="center"
							justify="space-between"
							borderBottom="1px"
							borderColor="gray.100"
							pb="3">
							<Heading fontSize="lg" fontWeight="semibold">
								Currency Options
							</Heading>
						</Flex>
						<Stack direction="row" spacing="8">
							<FormControl>
								<FormLabel minW="xs">Currency</FormLabel>
								<Select {...register('currency')}>
									{Object.entries(currency).map(([code, name]) => (
										<option value={code} key={code}>
											{name}
										</option>
									))}
								</Select>
							</FormControl>
							<FormControl>
								<FormLabel minW="xs">Currency Position</FormLabel>
								<Select {...register('currency_position')}>
									<option value="left">{__('Left', 'masteriyo')}</option>
									<option value="right">{__('Left', 'masteriyo')}</option>
								</Select>
							</FormControl>
						</Stack>
						<Stack direction="row" spacing="8">
							<FormControl>
								<FormLabel minW="xs">Thausand Separator</FormLabel>
								<Input type="text" {...register('thausand_separator')} />
							</FormControl>
							<FormControl>
								<FormLabel minW="xs">Decimal Separator</FormLabel>
								<Input type="text" {...register('decimal_separator')} />
							</FormControl>
						</Stack>
						<Stack direction="row" spacing="8">
							<FormControl>
								<FormLabel minW="xs">Number of Decimals</FormLabel>
								<Input type="text" {...register('number_of_decimals')} />
							</FormControl>
						</Stack>
					</Stack>
				</Box>
			</Stack>
		</form>
	);
};

export default GeneralSettings;
