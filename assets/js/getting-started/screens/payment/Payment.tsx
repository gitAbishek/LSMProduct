import {
	Box,
	Button,
	ButtonGroup,
	Flex,
	FormControl,
	FormLabel,
	Link,
	Select,
	Skeleton,
	Stack,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { useFormContext } from 'react-hook-form';
import { useQuery } from 'react-query';
import urls from '../../../back-end/constants/urls';
import API from '../../../back-end/utils/api';
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

	const currenciesAPI = new API(urls.currencies);
	const currenciesQuery = useQuery('currencies', () => currenciesAPI.list());

	return (
		<Box rounded="3px">
			<Box bg="white" p="30" shadow="box">
				<Stack direction="column" spacing="8">
					{currenciesQuery.isLoading ? (
						<Stack spacing="8">
							<Skeleton h="6" />
							<Skeleton h="6" />
						</Stack>
					) : (
						<>
							<FormControl>
								<Flex justify="space-between" align="center">
									<FormLabel sx={{ fontWeight: 'bold' }}>
										{__('Currency', 'masteriyo')}
									</FormLabel>

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
										<option value="left">
											{__('Left ($99.99)', 'masteriyo')}
										</option>
										<option value="right">
											{__('Right (99.99$)', 'masteriyo')}
										</option>
										<option value="left_space">
											{__('Left Space ($ 99.99)', 'masteriyo')}
										</option>
										<option value="right_space">
											{__('Right Space  (99.99 $)', 'masteriyo')}
										</option>
									</Select>
								</Flex>
							</FormControl>

							<Flex justify="space-between" align="center">
								<Button onClick={prevStep} rounded="3px" variant="outline">
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
										rounded="3px">
										{__('Finish', 'masteriyo')}
									</Button>
								</ButtonGroup>
							</Flex>
						</>
					)}
				</Stack>
			</Box>
		</Box>
	);
};

export default Payment;
