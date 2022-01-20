import {
	Box,
	FormControl,
	FormErrorMessage,
	FormLabel,
	Icon,
	Slider,
	SliderFilledTrack,
	SliderThumb,
	SliderTrack,
	Stack,
	Tab,
	TabList,
	TabPanel,
	TabPanels,
	Tabs,
	Text,
	Tooltip,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { Controller, useFormContext, useWatch } from 'react-hook-form';
import { BiInfoCircle } from 'react-icons/bi';
import {
	infoIconStyles,
	tabListStyles,
	tabStyles,
} from '../../../config/styles';
import { QuizSettingsMap } from '../../../types';

interface Props {
	quizData?: QuizSettingsMap;
}

const QuizSettings: React.FC<Props> = (props) => {
	const { quizData } = props;
	const {
		formState: { errors },
		control,
	} = useFormContext();

	const watchQuestionsPerPage = useWatch({
		name: 'quiz.styling.questions_display_per_page',
		defaultValue: quizData?.styling.questions_display_per_page,
		control,
	});

	return (
		<Tabs orientation="vertical">
			<Stack direction="row" flex="1">
				<TabList sx={tabListStyles}>
					<Tab sx={tabStyles}>{__('Styling', 'masteriyo')}</Tab>
				</TabList>
				<TabPanels flex="1">
					<TabPanel>
						<Stack direction="column" spacing="8">
							<FormControl
								isInvalid={!!errors?.quiz?.styling?.questions_display_per_page}>
								<FormLabel>
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
								<Controller
									name="quiz.styling.questions_display_per_page"
									defaultValue={quizData?.styling?.questions_display_per_page}
									rules={{
										required: __(
											'Questions per page is required.',
											'masteriyo'
										),
									}}
									render={({ field }) => (
										<Slider
											{...field}
											aria-label="questions-per-page"
											defaultValue={watchQuestionsPerPage || 12}
											max={24}
											min={1}>
											<SliderTrack>
												<SliderFilledTrack />
											</SliderTrack>
											<SliderThumb boxSize="6" bgColor="blue.500">
												<Text fontSize="xs" fontWeight="semibold" color="white">
													{watchQuestionsPerPage || 12}
												</Text>
											</SliderThumb>
										</Slider>
									)}
								/>
								<FormErrorMessage>
									{errors?.quiz?.styling?.questions_display_per_page &&
										errors?.quiz?.styling?.questions_display_per_page.message}
								</FormErrorMessage>
							</FormControl>
						</Stack>
					</TabPanel>
				</TabPanels>
			</Stack>
		</Tabs>
	);
};

export default QuizSettings;
