import {
	Flex,
	FormControl,
	FormErrorMessage,
	FormLabel,
	Heading,
	Input,
	Stack,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import Editor from 'Components/common/Editor';
import Select from 'Components/common/Select';
import React from 'react';
import { Controller, useFormContext } from 'react-hook-form';

interface Props {
	questionData: any;
}

const EditQuestion: React.FC<Props> = (props) => {
	const { questionData } = props;
	const {
		register,
		control,
		formState: { errors },
	} = useFormContext();

	const questionType = [
		{ value: 'true-false', label: 'True False', icon: 'YesNo' },
		{ value: 'single-choice', label: 'Single Choice', icon: 'SingleChoice' },
		{ value: 'multiple-choice', label: 'Multi Choice', icon: 'MultipleChoice' },
		{ value: 'short-answer', label: 'Short Answer', icon: 'OpenEndedEssay' },
	];

	return (
		<Stack direction="column" spacing="6">
			<Flex
				align="center"
				justify="space-between"
				borderBottom="1px"
				borderColor="gray.100"
				pb="3">
				<Heading fontSize="lg" fontWeight="semibold">
					Question
				</Heading>
			</Flex>
			<Stack direction="row" spacing="6">
				<FormControl isInvalid={!!errors?.name}>
					<FormLabel>{__('Question Name', 'masteriyo')}</FormLabel>
					<Input
						defaultValue={questionData.name}
						placeholder={__('Your Question Name', 'masteriyo')}
						{...register('name', {
							required: __(
								'You must provide name for the question',
								'masteriyo'
							),
						})}
					/>
					<FormErrorMessage>
						{errors?.name && errors?.name?.message}
					</FormErrorMessage>
				</FormControl>
				<FormControl>
					<FormLabel>{__('Question Description', 'masteriyo')}</FormLabel>
					<Controller
						defaultValue={questionData.type}
						render={({ field }) => (
							<Select
								{...field}
								closeMenuOnSelect={false}
								options={questionType}
							/>
						)}
						control={control}
						name="type"
						rules={{
							required: __('Please select question type', 'masteriyo'),
						}}
					/>
					<FormErrorMessage>
						{errors?.type && errors?.type?.message}
					</FormErrorMessage>
				</FormControl>
			</Stack>
			<FormControl>
				<FormLabel>{__('Question Description', 'masteriyo')}</FormLabel>
				<Editor
					name="description"
					defaultValue={questionData.description}
					control={control}
				/>
			</FormControl>
		</Stack>
	);
};

export default EditQuestion;
