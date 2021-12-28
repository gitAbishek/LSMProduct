import {
	Box,
	Button,
	ButtonGroup,
	Flex,
	FormControl,
	FormErrorMessage,
	FormLabel,
	Icon,
	Link,
	Slider,
	SliderFilledTrack,
	SliderThumb,
	SliderTrack,
	Stack,
	Text,
	Tooltip,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { Controller, useFormContext, useWatch } from 'react-hook-form';
import { BiInfoCircle } from 'react-icons/bi';
import { infoIconStyles } from '../../../back-end/config/styles';
interface Props {
	dashboardURL: string;
	prevStep: () => void;
	nextStep: () => void;
}

const Quiz: React.FC<Props> = (props) => {
	const { dashboardURL, prevStep, nextStep } = props;
	const {
		control,
		formState: { errors },
	} = useFormContext();

	const watchDisplayPerPage = useWatch({
		name: 'quiz.styling.questions_display_per_page',
		defaultValue: '5',
		control,
	});

	return (
		<Box rounded="3px">
			<Box bg="white" p="30" shadow="box">
				<Stack direction="column" spacing="8">
					<FormControl
						isInvalid={!!errors?.quiz?.styling?.questions_display_per_page}>
						<Flex justify="space-between" align="flex-start">
							<FormLabel sx={{ fontWeight: 'bold' }}>
								{__('Questions Per Page', 'masteriyo')}
								<Tooltip
									label={__(
										'Total number of questions to be shown per page for a quiz.',
										'masteriyo'
									)}
									hasArrow
									fontSize="xs">
									<Box as="span" sx={infoIconStyles}>
										<Icon as={BiInfoCircle} />
									</Box>
								</Tooltip>
							</FormLabel>
							<Stack direction="column">
								<Controller
									name="quiz.styling.questions_display_per_page"
									defaultValue={5}
									rules={{ required: true }}
									render={({ field }) => (
										<Slider
											{...field}
											aria-label="questions-display-per-page"
											defaultValue={5}
											min={1}
											w="md">
											<SliderTrack>
												<SliderFilledTrack />
											</SliderTrack>
											<SliderThumb boxSize="6" bgColor="blue.500">
												<Text fontSize="xs" fontWeight="semibold" color="white">
													{watchDisplayPerPage}
												</Text>
											</SliderThumb>
										</Slider>
									)}
								/>
								<FormErrorMessage>
									{errors?.quiz?.styling?.questions_display_per_page &&
										errors?.quiz?.styling?.questions_display_per_page.message}
								</FormErrorMessage>
							</Stack>
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
							<Button onClick={nextStep} rounded="3px" colorScheme="blue">
								{__('Next', 'masteriyo')}
							</Button>
						</ButtonGroup>
					</Flex>
				</Stack>
			</Box>
		</Box>
	);
};

export default Quiz;
