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

const Course: React.FC<Props> = (props) => {
	const { setTabIndex } = props;

	return (
		<Box rounded="3px">
			<Box bg="white" p="30" shadow="box">
				<Stack direction="column" spacing="8">
					<Flex justify="space-between" align="center">
						<strong>
							<Text fontSize="sm">{__('Course Per Row', 'masteriyo')}</Text>
						</strong>
						<Select w="md" placeholder="4" />
					</Flex>

					<Flex justify="space-between" align="center">
						<strong>
							<Text fontSize="sm">{__('Course Per Page', 'masteriyo')}</Text>
						</strong>
						<Select w="md" placeholder="20" />
					</Flex>

					<Flex justify="space-between" align="center">
						<Button
							onClick={() => setTabIndex(1)}
							rounded="3px"
							colorScheme="blue"
							variant="outline">
							{__('Back', 'masteriyo')}
						</Button>
						<ButtonGroup>
							<Button onClick={() => setTabIndex(3)} variant="ghost">
								{__('Skip', 'masteriyo')}
							</Button>
							<Button
								onClick={() => setTabIndex(3)}
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

export default Course;
