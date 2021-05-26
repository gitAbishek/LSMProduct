import {
	Box,
	Button,
	ButtonGroup,
	Flex,
	FormControl,
	FormLabel,
	Link,
	NumberDecrementStepper,
	NumberIncrementStepper,
	NumberInput,
	NumberInputField,
	NumberInputStepper,
	Stack,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { useFormContext } from 'react-hook-form';

interface Props {
	setTabIndex?: any;
	dashboardURL: string;
}

const Course: React.FC<Props> = (props) => {
	const { setTabIndex, dashboardURL } = props;
	const {
		register,
		formState: { errors },
	} = useFormContext();
	return (
		<Box rounded="3px">
			<Box bg="white" p="30" shadow="box">
				<Stack direction="column" spacing="8">
					<FormControl id="course-per-row">
						<Flex justify="space-between" align="center">
							<FormLabel style={{ fontWeight: 'bold' }}>
								{__('Course Per Row', 'masteriyo')}
							</FormLabel>
							<NumberInput w="md" defaultValue={4}>
								<NumberInputField {...register('course_per_row')} />
								<NumberInputStepper>
									<NumberIncrementStepper />
									<NumberDecrementStepper />
								</NumberInputStepper>
							</NumberInput>
						</Flex>
					</FormControl>

					<FormControl id="course-per-page">
						<Flex justify="space-between" align="center">
							<FormLabel style={{ fontWeight: 'bold' }}>
								{__('Course Per Page', 'masteriyo')}
							</FormLabel>
							<NumberInput w="md" defaultValue={20}>
								<NumberInputField {...register('course_per_page')} />
								<NumberInputStepper>
									<NumberIncrementStepper />
									<NumberDecrementStepper />
								</NumberInputStepper>
							</NumberInput>
						</Flex>
					</FormControl>

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
								<Link href={dashboardURL ? dashboardURL : '#'}>
									{__('Skip to Dashboard', 'masteriyo')}
								</Link>
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
