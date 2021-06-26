import {
	Alert,
	AlertIcon,
	Box,
	Button,
	ButtonGroup,
	Flex,
	FormControl,
	FormLabel,
	Heading,
	Input,
	Link,
	NumberDecrementStepper,
	NumberIncrementStepper,
	NumberInput,
	NumberInputField,
	NumberInputStepper,
	Select,
	Stack,
	Text,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import getSymbolFromCurrency from 'currency-symbol-map';
import React from 'react';
import CurrencyInput from 'react-currency-input-field';
import { Controller, useFormContext, useWatch } from 'react-hook-form';
import { currency } from '../../../back-end/utils/currency';
interface Props {
	dashboardURL: string;
	prevStep: () => void;
	nextStep: () => void;
}

function validateData(generalWatchData?: any) {
	if ('undefined' != typeof generalWatchData) {
		// To check for number in input.
		const regex: any = /\d/;

		const { decimal_separator, thousands_separator } = generalWatchData;

		if (regex.test(decimal_separator) || regex.test(thousands_separator)) {
			return {
				message: "Thousand and decimal separator can't be number.",
				isBtnEnable: false,
			};
		} else if (
			'undefined' != typeof thousands_separator &&
			decimal_separator === thousands_separator
		) {
			return {
				message: "Thousand and decimal separator can't be same.",
				isBtnEnable: false,
			};
		} else {
			return {
				message: 'Validate successfully',
				isBtnEnable: true,
			};
		}
	}
}

const Preview = ({ watchData }) => {
	if ('undefined' != typeof watchData.general) {
		let currencySymbol: any;

		const {
			currency,
			currency_position,
			thousands_separator,
			decimal_separator,
			number_of_decimals,
		} = watchData.general;

		currencySymbol = getSymbolFromCurrency(currency);

		let validationData = validateData(watchData.general);

		if (!validationData?.isBtnEnable) {
			return (
				<Alert fontSize="sm" status="error">
					<AlertIcon />
					{validationData?.message}
				</Alert>
			);
		}

		if ('left' === currency_position) {
			return (
				<CurrencyInput
					prefix={currencySymbol}
					groupSeparator={thousands_separator}
					decimalSeparator={decimal_separator}
					decimalScale={number_of_decimals}
					value={9999.99}
				/>
			);
		} else if ('right' === currency_position) {
			return (
				<CurrencyInput
					suffix={currencySymbol}
					groupSeparator={thousands_separator}
					decimalSeparator={decimal_separator}
					decimalScale={number_of_decimals}
					value={9999.99}
				/>
			);
		}
	}

	// Default value.
	return <CurrencyInput prefix={'$'} value={9999.99} />;
};

const Currency: React.FC<Props> = (props) => {
	const { dashboardURL, prevStep, nextStep } = props;
	const { register, control } = useFormContext();
	// Watch entire currency form.
	const watchData = useWatch({
		control,
	});

	let enableBtn: boolean = true;

	if ('undefined' != typeof watchData.general) {
		let validationData = validateData(watchData.general);
		enableBtn = validationData?.isBtnEnable;
	}

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

					<FormControl>
						<Flex justify="space-between" align="center">
							<FormLabel sx={{ fontWeight: 'bold' }}>
								{__('Thousands Separator', 'masteriyo')}
							</FormLabel>
							<Input
								defaultValue=","
								w="md"
								{...register('general.thousands_separator')}
							/>
						</Flex>
					</FormControl>

					<FormControl>
						<Flex justify="space-between" align="center">
							<FormLabel sx={{ fontWeight: 'bold' }}>
								{__('Decimal Separator', 'masteriyo')}
							</FormLabel>
							<Input
								defaultValue="."
								w="md"
								{...register('general.decimal_separator')}
							/>
						</Flex>
					</FormControl>

					<FormControl>
						<Flex justify="space-between" align="center">
							<FormLabel sx={{ fontWeight: 'bold' }}>
								{__('Number of Decimal', 'masteriyo')}
							</FormLabel>
							<Controller
								name="general.number_of_decimals"
								render={({ field }) => (
									<NumberInput
										{...field}
										w="md"
										min={1}
										max={4}
										defaultValue="2">
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
						<strong>
							<Text fontSize="sm">{__('Preview', 'masteriyo')}</Text>
						</strong>
						<Box rounded="3" boxSizing="border-box" w="md">
							<Heading color="blue.500" as="h2" size="md">
								<Preview watchData={watchData} />
							</Heading>
						</Box>
					</Flex>

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
								isDisabled={!enableBtn}
								onClick={nextStep}
								rounded="3px"
								colorScheme="blue">
								{__('Next', 'masteriyo')}
							</Button>
						</ButtonGroup>
					</Flex>
				</Stack>
			</Box>
		</Box>
	);
};

export default Currency;
