import {
	Box,
	Button,
	ButtonGroup,
	Flex,
	FormControl,
	FormErrorMessage,
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
import { SetttingsMap } from '../../../back-end/types';
import { currency } from '../../../back-end/utils/currency';
import { hasNumber } from '../../../back-end/utils/helper';
interface Props {
	dashboardURL: string;
	prevStep: () => void;
	nextStep: () => void;
}

const Preview = (props: any) => {
	if ('undefined' != typeof props.general) {
		const { general } = props;

		const currencySymbol = getSymbolFromCurrency(general.currency);

		if ('left' === general.currency_position) {
			return (
				<CurrencyInput
					prefix={currencySymbol}
					groupSeparator={general.thousands_separator}
					decimalSeparator={general.decimal_separator}
					decimalScale={general.number_of_decimals}
					value={9999.99}
				/>
			);
		} else if ('right' === general.currency_position) {
			return (
				<CurrencyInput
					suffix={currencySymbol}
					groupSeparator={general.thousands_separator}
					decimalSeparator={general.decimal_separator}
					decimalScale={general.number_of_decimals}
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
	const {
		register,
		formState: { errors },
	} = useFormContext();
	// Watch entire currency form.
	const watchGeneralData = useWatch<SetttingsMap>({
		name: 'general',
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
						<strong>
							<Text fontSize="sm">{__('Preview', 'masteriyo')}</Text>
						</strong>
						<Box rounded="3" boxSizing="border-box" w="md">
							<Heading color="blue.500" as="h2" size="md">
								<Preview general={watchGeneralData} />
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
								isDisabled={!!errors?.general}
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
