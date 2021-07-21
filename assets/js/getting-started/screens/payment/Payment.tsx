import {
	Box,
	Button,
	ButtonGroup,
	Flex,
	FormControl,
	FormErrorMessage,
	FormLabel,
	Input,
	Link,
	NumberDecrementStepper,
	NumberIncrementStepper,
	NumberInput,
	NumberInputField,
	NumberInputStepper,
	Select,
	Stack,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import getSymbolFromCurrency from 'currency-symbol-map';
import React from 'react';
import { Controller, useFormContext, useWatch } from 'react-hook-form';
import { SetttingsMap } from '../../../back-end/types';
import { currency } from '../../../back-end/utils/currency';
import { hasNumber } from '../../../back-end/utils/helper';
interface Props {
	isButtonLoading: boolean;
	dashboardURL: string;
	prevStep: () => void;
}

const Payment: React.FC<Props> = (props) => {
	const { isButtonLoading, dashboardURL, prevStep } = props;
	const {
		register,
		formState: { errors },
	} = useFormContext();
	// Watch entire currency form.
	const watchGeneralData = useWatch<SetttingsMap>({
		name: 'general',
		defaultValue: {
			currency: 'USD',
			currency_position: 'left',
			thousand_separator: ',',
			decimal_separator: '.',
			number_of_decimals: 2,
		},
	});

	return (
		<Box rounded="3px">
			<Box bg="white" p="30" shadow="box">
				<Stack direction="column" spacing="8">
					<FormControl>
						<Flex justify="space-between" align="center">
							<FormLabel sx={{ fontWeight: 'bold' }}>
								{__('Currency', 'masteriyo')}
							</FormLabel>
							<Select
								w="md"
								{...register('general.currency')}
								defaultValue="USD">
								{Object.entries(currency).map(([code, name]) => (
									<option value={code} key={code}>
										{name} ({getSymbolFromCurrency(code)})
									</option>
								))}
							</Select>
						</Flex>
					</FormControl>

					<FormControl>
						<Flex justify="space-between" align="center">
							<FormLabel sx={{ fontWeight: 'bold' }}>
								{__('Currency Position', 'masteriyo')}
							</FormLabel>
							<Select
								w="md"
								defaultValue="left"
								{...register('general.currency_position')}>
								<option value="left">{__('Left', 'masteriyo')}</option>
								<option value="right">{__('Right', 'masteriyo')}</option>
							</Select>
						</Flex>
					</FormControl>

					<FormControl isInvalid={!!errors?.general?.thousand_separator}>
						<Flex justify="space-between" align="center">
							<FormLabel sx={{ fontWeight: 'bold' }}>
								{__('Thousand Separator', 'masteriyo')}
							</FormLabel>
							<Box>
								<Input
									defaultValue=","
									w="md"
									{...register('general.thousand_separator', {
										required: true,
										pattern: {
											value: hasNumber,
											message: 'Numbers are not allowed.',
										},
										validate: (value) =>
											value != watchGeneralData.decimal_separator,
									})}
								/>
								<FormErrorMessage>
									{errors?.general?.thousand_separator &&
										errors?.general?.thousand_separator.message}

									{errors?.general?.thousand_separator &&
										errors?.general?.thousand_separator.type === 'validate' &&
										__(
											`Thousand and Decimal separator can't be same.`,
											'masteriyo'
										)}
								</FormErrorMessage>
							</Box>
						</Flex>
					</FormControl>

					<FormControl isInvalid={!!errors?.general?.decimal_separator}>
						<Flex justify="space-between" align="center">
							<FormLabel sx={{ fontWeight: 'bold' }}>
								{__('Decimal Separator', 'masteriyo')}
							</FormLabel>
							<Box>
								<Input
									defaultValue="."
									w="md"
									{...register('general.decimal_separator', {
										required: true,
										pattern: {
											value: hasNumber,
											message: 'Numbers are not allowed.',
										},
										validate: (value) =>
											value != watchGeneralData.thousand_separator,
									})}
								/>
								<FormErrorMessage>
									{errors?.general?.decimal_separator &&
										errors?.general?.decimal_separator.message}

									{errors?.general?.decimal_separator &&
										errors?.general?.decimal_separator.type === 'validate' &&
										__(
											`Thousand and Decimal separator can't be same.`,
											'masteriyo'
										)}
								</FormErrorMessage>
							</Box>
						</Flex>
					</FormControl>

					<FormControl>
						<Flex justify="space-between" align="center">
							<FormLabel sx={{ fontWeight: 'bold' }}>
								{__('Number of Decimal', 'masteriyo')}
							</FormLabel>
							<Controller
								name="general.number_of_decimals"
								defaultValue="2"
								render={({ field }) => (
									<NumberInput {...field} w="md" min={1} max={4}>
										<NumberInputField />
										<NumberInputStepper>
											<NumberIncrementStepper />
											<NumberDecrementStepper />
										</NumberInputStepper>
									</NumberInput>
								)}
							/>
						</Flex>
					</FormControl>

					<Flex justify="space-between" align="center">
						<Button
							onClick={prevStep}
							rounded="3px"
							colorScheme="blue"
							variant="outline">
							{__('Back', 'masteriyo')}
						</Button>
						<ButtonGroup>
							<Link href={dashboardURL ? dashboardURL : '#'}>
								<Button variant="ghost">
									{__('Skip to Dashboard', 'masteriyo')}
								</Button>
							</Link>
							<Button
								type="submit"
								isLoading={isButtonLoading}
								isDisabled={!!errors?.general}
								rounded="3px"
								colorScheme="blue">
								{__('Finish', 'masteriyo')}
							</Button>
						</ButtonGroup>
					</Flex>
				</Stack>
			</Box>
		</Box>
	);
};

export default Payment;
