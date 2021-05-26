import {
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
import React, { useEffect, useState } from 'react';
import { Controller, useFormContext } from 'react-hook-form';
interface Props {
	setTabIndex?: any;
	dashboardURL: string;
}

declare var masteriyo: any;

const Currency: React.FC<Props> = (props) => {
	const { setTabIndex, dashboardURL } = props;
	const [previewPrice, SetPreviewPrice] = useState('$999.6789');
	const {
		register,
		formState: { errors },
		control,
	} = useFormContext();

	const [data, setData] = useState<any>({
		currencyValue: 'USD',
		currencyLabel: 'United State Dollar ($)',
		position: 'left',
		thousandSeparator: ',',
		decimalSeparator: '.',
		decimalNumber: '4',
	});

	useEffect(() => {
		handlePreview();
	}, [data]);

	// options for dev server.
	const options = [
		{ label: 'United Arab Emirates dirham (د.إ)', value: 'AED' },
		{ label: 'Afghan afghani (؋)', value: 'AFN' },
		{ label: 'Albanian lek (L)', value: 'ALL' },
		{ label: 'United State Dollar ($)', value: 'USD' },
		{ label: 'Nepalese rupee (₨)', value: 'NPR' },
		{ label: 'Turkish lira (₺)', value: 'TRY' },
	];

	// Currencies option for live server.
	const currencies: any =
		'undefined' != typeof masteriyo && masteriyo.currencies;

	const handleChangeSelect = (e?: any) => {
		let name = e.target.name;
		let index = e.target.selectedIndex;
		let label = e.target[index].text;
		let value = e.target.value;

		if ('currencyLabel' === name) {
			setData((prevState?: any) => ({
				...prevState,
				[name]: label,
				['currencyValue']: value,
			}));
		} else {
			setData((prevState?: any) => ({
				...prevState,
				[name]: value,
			}));
		}
	};

	const handleChangeInput = (e?: any) => {
		const { name, value } = e.target;

		setData((prevState?: any) => ({
			...prevState,
			[name]: value,
		}));
	};

	const handleChangeNumber = (e?: any) => {
		const value = e;

		setData((prevState?: any) => ({
			...prevState,
			['decimalNumber']: value,
		}));
	};

	/**
	 * Handles the price preview.
	 */
	function handlePreview() {
		let preview: any;
		let regExp = /\(([^)]+)\)/;
		let currencySymbolMatch = '$';

		if ('undefined' != typeof data.currencyLabel) {
			let matches = regExp.exec(data.currencyLabel);

			//matches[1] contains the value between the parentheses
			currencySymbolMatch = matches[1];
		}

		let currencyPosition =
			'undefined' != typeof data.position ? data.position : 'left';

		let price = parseFloat(`999${data.decimalSeparator}6789`).toFixed(
			data.decimalNumber
		);
		price = price.toLocaleString();

		if ('left' == currencyPosition) {
			preview = currencySymbolMatch + price;
		} else if ('right' == currencyPosition) {
			preview = price + currencySymbolMatch;
		}

		SetPreviewPrice(preview);
	}

	return (
		<Box rounded="3px">
			<Box bg="white" p="30" shadow="box">
				<Stack direction="column" spacing="8">
					<FormControl id="currency">
						<Flex justify="space-between" align="center">
							<FormLabel style={{ fontWeight: 'bold' }}>
								{__('Currency', 'masteriyo')}
							</FormLabel>
							<Controller
								name="currency"
								control={control}
								render={({ field }) => (
									<Select
										name="currencyLabel"
										value={data.currencyValue}
										onChange={(e) => {
											handleChangeSelect(e);
											field.onChange(e);
										}}
										w="md">
										{'undefined' != typeof masteriyo
											? currencies.map((data: any, index: number) => {
													return (
														<option key={index} value={data.value}>
															{data.label}
														</option>
													);
											  })
											: options.map((data: any, index: number) => {
													return (
														<option key={index} value={data.value}>
															{data.label}
														</option>
													);
											  })}
									</Select>
								)}
							/>
						</Flex>
					</FormControl>

					<FormControl id="currency-position">
						<Flex justify="space-between" align="center">
							<FormLabel style={{ fontWeight: 'bold' }}>
								{__('Currency Position', 'masteriyo')}
							</FormLabel>
							<Controller
								name="currency_position"
								control={control}
								render={({ field }) => (
									<Select
										w="md"
										name="position"
										value={data.position}
										onChange={(e) => {
											handleChangeSelect(e);
											field.onChange(e);
										}}>
										<option value="left">{__('Left', 'masteriyo')}</option>
										<option value="right">{__('Right', 'masteriyo')}</option>
									</Select>
								)}
							/>
						</Flex>
					</FormControl>

					<FormControl id="thousand-separator">
						<Flex justify="space-between" align="center">
							<FormLabel style={{ fontWeight: 'bold' }}>
								{__('Thousands Separator', 'masteriyo')}
							</FormLabel>
							<Controller
								name="thousand_separator"
								control={control}
								render={({ field }) => (
									<Input
										value={data.thousandSeparator}
										w="md"
										onChange={(e) => {
											handleChangeInput(e);
											field.onChange(e);
										}}
										name="thousandSeparator"
									/>
								)}
							/>
						</Flex>
					</FormControl>

					<FormControl id="decimal-separator">
						<Flex justify="space-between" align="center">
							<FormLabel style={{ fontWeight: 'bold' }}>
								{__('Decimal Separator', 'masteriyo')}
							</FormLabel>
							<Controller
								name="decimal_separator"
								control={control}
								render={({ field }) => (
									<Input
										value={data.decimalSeparator}
										w="md"
										onChange={(e) => {
											handleChangeInput(e);
											field.onChange(e);
										}}
										name="decimalSeparator"
									/>
								)}
							/>
						</Flex>
					</FormControl>

					<FormControl id="no-of-decimal">
						<Flex justify="space-between" align="center">
							<FormLabel style={{ fontWeight: 'bold' }}>
								{__('Number of Decimal', 'masteriyo')}
							</FormLabel>
							<Controller
								name="number_of_decimals"
								control={control}
								render={({ field }) => (
									<NumberInput
										w="md"
										min={1}
										max={4}
										value={data.decimalNumber}
										name="decimalNumber"
										onChange={(e) => {
											handleChangeNumber(e);
											field.onChange(e);
										}}>
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
						<Box bg="#F9F9F9" rounded="3" boxSizing="border-box" w="md">
							<Heading color="#78A6FF" as="h2" size="md">
								{previewPrice}
							</Heading>
						</Box>
					</Flex>

					<Flex justify="space-between" align="center">
						<Button
							onClick={() => setTabIndex(0)}
							rounded="3px"
							colorScheme="blue"
							variant="outline">
							{__('Back', 'masteriyo')}
						</Button>
						<ButtonGroup>
							<Button onClick={() => setTabIndex(2)} variant="ghost">
								<Link href={dashboardURL ? dashboardURL : '#'}>
									{__('Skip to Dashboard', 'masteriyo')}
								</Link>
							</Button>
							<Button
								onClick={() => setTabIndex(2)}
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
