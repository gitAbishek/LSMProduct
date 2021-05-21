import {
	Box,
	Button,
	ButtonGroup,
	Flex,
	Heading,
	Select,
	Stack,
	Text,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { useFormContext } from 'react-hook-form';

interface Props {
	setTabIndex?: any;
}

const Currency: React.FC<Props> = (props) => {
	const { setTabIndex } = props;
	const {
		register,
		formState: { errors },
	} = useFormContext();

	return (
		<Box rounded="3px">
			<Box bg="white" p="30" shadow="box">
				<Stack direction="column" spacing="8">
					<Flex justify="space-between" align="center">
						<strong>
							<Text fontSize="sm">{__('Currency', 'masteriyo')}</Text>
						</strong>
						<Select w="md" placeholder="USD" {...register('currency')}>
							<option value="npr">NPR</option>
							<option value="usd">USD</option>
						</Select>
					</Flex>

					<Flex justify="space-between" align="center">
						<strong>
							<Text fontSize="sm">{__('Currency Position', 'masteriyo')}</Text>
						</strong>
						<Select
							defaultValue="left"
							w="md"
							{...register('currency_position')}
						/>
					</Flex>

					<Flex justify="space-between" align="center">
						<strong>
							<Text fontSize="sm">
								{__('Thousands Separator', 'masteriyo')}
							</Text>
						</strong>
						<Select
							w="md"
							defaultValue="."
							{...register('thousand_separator')}
						/>
					</Flex>

					<Flex justify="space-between" align="center">
						<strong>
							{' '}
							<Text fontSize="sm">{__('Decimal Separator', 'masteriyo')}</Text>
						</strong>
						<Select w="md" defaultValue="," />
					</Flex>

					<Flex justify="space-between" align="center">
						<strong>
							<Text fontSize="sm">{__('Number of Decimal', 'masteriyo')}</Text>
						</strong>
						<Select w="md" defaultValue="2" />
					</Flex>

					<Flex justify="space-between" align="center">
						<strong>
							<Text fontSize="sm">{__('Preview', 'masteriyo')}</Text>
						</strong>
						<Box bg="#F9F9F9" rounded="3" boxSizing="border-box" w="md">
							<Heading color="#78A6FF" as="h2" size="md">
								$5400.99
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
								{__('Skip', 'masteriyo')}
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
