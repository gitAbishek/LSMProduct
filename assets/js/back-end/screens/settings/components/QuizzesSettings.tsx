import {
	Box,
	Flex,
	FormControl,
	FormLabel,
	Heading,
	NumberDecrementStepper,
	NumberIncrementStepper,
	NumberInput,
	NumberInputField,
	NumberInputStepper,
	Stack,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { Controller } from 'react-hook-form';
import { QuizzesSettingsMap } from '../../../types';

interface Props {
	quizzesData?: QuizzesSettingsMap;
}

const QuizzesSettings: React.FC<Props> = (props) => {
	const { quizzesData: quizzesData } = props;

	return (
		<Stack direction="column" spacing="8">
			<Box>
				<Stack direction="column" spacing="8">
					<Flex
						align="center"
						justify="space-between"
						borderBottom="1px"
						borderColor="gray.100"
						pb="3">
						<Heading fontSize="lg" fontWeight="semibold">
							{__('General', 'masteriyo')}
						</Heading>
					</Flex>

					<FormControl>
						<FormLabel minW="2xs">
							{__('Question Display Per Page', 'masteriyo')}
						</FormLabel>
						<Controller
							name="quizzes.questions_display_per_page"
							defaultValue={quizzesData?.questions_display_per_page}
							render={({ field }) => (
								<NumberInput {...field}>
									<NumberInputField borderRadius="sm" shadow="input" />
									<NumberInputStepper>
										<NumberIncrementStepper />
										<NumberDecrementStepper />
									</NumberInputStepper>
								</NumberInput>
							)}
						/>
					</FormControl>
				</Stack>
			</Box>
		</Stack>
	);
};

export default QuizzesSettings;
