import {
	Box,
	Button,
	ButtonGroup,
	Flex,
	Select,
	Stack,
	Text,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
interface Props {
	setTabIndex?: any;
}

const Pages: React.FC<Props> = (props) => {
	const { setTabIndex } = props;
	return (
		<Box rounded="3px">
			<Box bg="white" p="30" shadow="box">
				<Stack direction="column" spacing="8">
					<Flex justify="space-between" align="center">
						<strong>
							<Text fontSize="sm">{__('Course List', 'masteriyo')}</Text>
						</strong>
						<Select w="md">
							<option value="option1">App Design</option>
							<option value="option2">Software Design</option>
							<option value="option3">Course List</option>
						</Select>
					</Flex>

					<Flex justify="space-between" align="center">
						<strong>
							<Text fontSize="sm">{__('My Account', 'masteriyo')}</Text>
						</strong>
						<Select w="md" />
					</Flex>

					<Flex justify="space-between" align="center">
						<strong>
							<Text fontSize="sm">{__('Checkout', 'masteriyo')}</Text>
						</strong>
						<Select w="md" />
					</Flex>

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
								{__('Skip', 'masteriyo')}
							</Button>
							<Button
								type="submit"
								onClick={() => setTabIndex(5)}
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
