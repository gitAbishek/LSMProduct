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
import React, { Dispatch, SetStateAction } from 'react';
import { Controller, useFormContext } from 'react-hook-form';

interface Props {
	questionData: any;
	setQuestionType: Dispatch<SetStateAction<string>>;
}

const EditQuestion: React.FC<Props> = (props) => {
	const { questionData, setQuestionType } = props;
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

	const getQuestionTypeDefaultValue = () => {
		switch (questionData.type) {
			case 'true-false':
				return questionType[0];
			case 'single-choice':
				return questionType[1];
			case 'multiple-choice':
				return questionType[2];
			case 'short-answer':
				return questionType[3];

			default:
				return;
		}
	};

	const onQuestionTypeChange = (questionType: {
		value: string;
		label: string;
		icon: string;
	}) => {
		setQuestionType(questionType.value);
	};

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
					<FormLabel>{__('Question Type', 'masteriyo')}</FormLabel>
					<Controller
						defaultValue={getQuestionTypeDefaultValue()}
						render={({ field: { onChange, value } }) => (
							<Select
								value={value}
								options={questionType}
								onChange={(data: any) => {
									onChange(data);
									onQuestionTypeChange(data);
								}}
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
