import {
	Box,
	Tabs,
	TabList,
	Tab,
	TabPanels,
	TabPanel,
	Stack,
	Flex,
	Heading,
	FormControl,
	FormLabel,
	Input,
	ButtonGroup,
	Button,
	Container,
	Select,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import FullScreenLoader from 'Components/layout/FullScreenLoader';
import Header from 'Components/layout/Header';
import React, { useEffect, useMemo, useState } from 'react';
import ReactFlagsSelect from 'react-flags-select';
import { useForm } from 'react-hook-form';
import { useQuery } from 'react-query';
import urls from '../../constants/urls';
import API from '../../utils/api';
import { currency } from '../../utils/currency';

const Settings = () => {
	const [country, setCountry] = useState('');
	const { handleSubmit, register, setValue } = useForm();
	const courseApi = new API(urls.settings);
	const tabStyles = {
		fontWeight: 'medium',
		fontSize: 'sm',
		py: '4',
	};

	const tabPanelStyles = {
		px: '0',
		py: 8,
	};

	useEffect(() => {
		setValue('country', country);
	}, [country]);

	const settingsQuery = useQuery('settings', () => courseApi.list(), {});

	if (settingsQuery.isLoading) {
		return <FullScreenLoader />;
	}

	const onSubmit = (data: any) => {
		console.log(data);
	};
	console.log(settingsQuery.data);
	return (
		<Stack direction="column" spacing="8" width="full" alignItems="center">
			<Header />
			<Container maxW="container.xl">
				<Box bg="white" p="10" shadow="box">
					<Tabs>
						<TabList justifyContent="center" borderBottom="1px">
							<Tab sx={tabStyles}>{__('General', 'masteriyo')}</Tab>
							<Tab sx={tabStyles}>{__('Courses', 'masteriyo')}</Tab>
							<Tab sx={tabStyles}>{__('Pages', 'masteriyo')}</Tab>
							<Tab sx={tabStyles}>{__('Payments', 'masteriyo')}</Tab>
							<Tab sx={tabStyles}>{__('Emails', 'masteriyo')}</Tab>
							<Tab sx={tabStyles}>{__('Advanced', 'masteriyo')}</Tab>
						</TabList>
						<TabPanels>
							<TabPanel sx={tabPanelStyles}>
								<form onSubmit={handleSubmit(onSubmit)}>
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
															<option value="left">
																{__('Left', 'masteriyo')}
															</option>
															<option value="right">
																{__('Left', 'masteriyo')}
															</option>
														</Select>
													</FormControl>
												</Stack>
												<Stack direction="row" spacing="8">
													<FormControl>
														<FormLabel minW="xs">Thausand Separator</FormLabel>
														<Input
															type="text"
															{...register('thausand_separator')}
														/>
													</FormControl>
													<FormControl>
														<FormLabel minW="xs">Decimal Separator</FormLabel>
														<Input
															type="text"
															{...register('decimal_separator')}
														/>
													</FormControl>
												</Stack>
												<Stack direction="row" spacing="8">
													<FormControl>
														<FormLabel minW="xs">Number of Decimals</FormLabel>
														<Input
															type="text"
															{...register('number_of_decimals')}
														/>
													</FormControl>
												</Stack>
											</Stack>
										</Box>
										<ButtonGroup>
											<Button colorScheme="blue" type="submit">
												Save Settings
											</Button>
										</ButtonGroup>
									</Stack>
								</form>
							</TabPanel>
						</TabPanels>
					</Tabs>
				</Box>
			</Container>
		</Stack>
	);
};

export default Settings;
