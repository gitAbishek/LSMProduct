import {
	Box,
	Button,
	ButtonGroup,
	Flex,
	FormControl,
	FormLabel,
	Link,
	Select,
	Stack,
	Text,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { useFormContext } from 'react-hook-form';
interface Props {
	setTabIndex?: any;
	mutationLoading: boolean;
	dashboardURL: string;
}

const Pages: React.FC<Props> = (props) => {
	const { setTabIndex, mutationLoading, dashboardURL } = props;
	const {
		register,
		formState: { errors },
	} = useFormContext();

	// Options for dev server.
	const options = [
		{ label: 'Select a page..', value: '' },
		{ label: 'Masteriyo Checkout', value: '' },
		{ label: 'Masteriyo Course List', value: 'AFN' },
		{ label: 'Masteriyo My Account', value: 'ALL' },
	];

	const pagesOptions: any =
		//@ts-ignore
		'undefined' != typeof masteriyo && masteriyo.pagesOptions;

	return (
		<Box rounded="3px">
			<Box bg="white" p="30" shadow="box">
				<Stack direction="column" spacing="8">
					<FormControl id="course-list-page">
						<Flex justify="space-between" align="center">
							<FormLabel style={{ fontWeight: 'bold' }}>
								{__('Course List', 'masteriyo')}
							</FormLabel>
							<Select w="md" {...register('course_list_page_id')}>
								{
									//@ts-ignore
									'undefined' != typeof masteriyo
										? pagesOptions.map((data: any, index: number) => {
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
										  })
								}
							</Select>
						</Flex>
					</FormControl>

					<FormControl id="my-account-page">
						<Flex justify="space-between" align="center">
							<FormLabel style={{ fontWeight: 'bold' }}>
								<Text fontSize="sm">{__('My Account', 'masteriyo')}</Text>
							</FormLabel>
							<Select w="md" {...register('myaccount_page_id')}>
								{
									//@ts-ignore
									'undefined' != typeof masteriyo
										? pagesOptions.map((data: any, index: number) => {
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
										  })
								}
							</Select>
						</Flex>
					</FormControl>

					<FormControl id="checkout-page">
						<Flex justify="space-between" align="center">
							<FormLabel style={{ fontWeight: 'bold' }}>
								{__('Checkout', 'masteriyo')}
							</FormLabel>
							<Select w="md" {...register('checkout_page_id')}>
								{
									//@ts-ignore
									'undefined' != typeof masteriyo
										? pagesOptions.map((data: any, index: number) => {
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
										  })
								}
							</Select>
						</Flex>
					</FormControl>

					<Flex justify="space-between" align="center">
						<Button
							onClick={() => setTabIndex(3)}
							rounded="3px"
							colorScheme="blue"
							variant="outline">
							{__('Back', 'masteriyo')}
						</Button>
						<ButtonGroup>
							<Button onClick={() => setTabIndex(5)} variant="ghost">
								<Link href={dashboardURL ? dashboardURL : '#'}>
									{__('Skip to Dashboard', 'masteriyo')}
								</Link>
							</Button>
							<Button
								type="submit"
								// onClick={() => setTabIndex(5)}
								isLoading={mutationLoading}
								rounded="3px"
								colorScheme="blue">
								{__('Continue', 'masteriyo')}
							</Button>
						</ButtonGroup>
					</Flex>
				</Stack>
			</Box>
		</Box>
	);
};

export default Pages;
