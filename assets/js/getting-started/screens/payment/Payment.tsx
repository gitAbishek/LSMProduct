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
	Skeleton,
	Stack,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { Controller, useFormContext, useWatch } from 'react-hook-form';
import { useQuery } from 'react-query';
import urls from '../../../back-end/constants/urls';
import { SetttingsMap } from '../../../back-end/types';
import API from '../../../back-end/utils/api';
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
		name: 'payments.currency',
		defaultValue: {
			currency: 'USD',
			currency_position: 'left',
			thousand_separator: ',',
			decimal_separator: '.',
			number_of_decimals: 2,
		},
	});
	const currenciesAPI = new API(urls.currencies);
	const currenciesQuery = useQuery('currencies', () => currenciesAPI.list());

	return (
		<Box rounded="3px">
			<Box bg="white" p="30" shadow="box">
				<Stack direction="column" spacing="8">
					<FormControl>
						<Flex justify="space-between" align="center">
							<FormLabel sx={{ fontWeight: 'bold' }}>
								{__('Currency', 'masteriyo')}
							</FormLabel>
							{currenciesQuery.isLoading ? (
								<Skeleton h="6" w="md" />
							) : (
								<Select
									w="md"
									{...register('payments.currency.currency')}
									defaultValue="USD">
									{currenciesQuery.data.map(
										(currency: {
											code: string;
											name: string;
											symbol: string;
										}) => (
											<option value={currency.code} key={currency.code}>
												{currency.name} ({currency.symbol})
											</option>
										)
									)}
								</Select>
							)}
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
								{...register('payments.currency.currency_position')}>
								<option value="left">{__('Left', 'masteriyo')}</option>
								<option value="right">{__('Right', 'masteriyo')}</option>
							</Select>
						</Flex>
					</FormControl>

					<FormControl
						isInvalid={!!errors?.payments?.currency?.thousand_separator}>
						<Flex justify="space-between" align="center">
							<FormLabel sx={{ fontWeight: 'bold' }}>
								{__('Thousand Separator', 'masteriyo')}
							</FormLabel>
							<Box>
								<Input
									defaultValue=","
									w="md"
									{...register('payments.currency.thousand_separator', {
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
									{errors?.payments?.currency?.thousand_separator &&
										errors?.payments?.currency?.thousand_separator.message}

									{errors?.payments?.currency?.thousand_separator &&
										errors?.payments?.currency?.thousand_separator.type ===
											'validate' &&
										__(
											`Thousand and Decimal separator can't be same.`,
											'masteriyo'
										)}
								</FormErrorMessage>
							</Box>
						</Flex>
					</FormControl>

					<FormControl
						isInvalid={!!errors?.payments?.currency?.decimal_separator}>
						<Flex justify="space-between" align="center">
							<FormLabel sx={{ fontWeight: 'bold' }}>
								{__('Decimal Separator', 'masteriyo')}
							</FormLabel>
							<Box>
								<Input
									defaultValue="."
									w="md"
									{...register('payments.currency.decimal_separator', {
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
									{errors?.payments?.currency?.decimal_separator &&
										errors?.payments?.currency?.decimal_separator.message}

									{errors?.payments?.currency?.decimal_separator &&
										errors?.payments?.currency?.decimal_separator.type ===
											'validate' &&
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
								name="payments.currency.number_of_decimals"
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
