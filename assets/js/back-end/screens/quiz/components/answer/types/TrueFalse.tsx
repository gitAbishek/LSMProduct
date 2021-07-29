import {
	Alert,
	AlertDescription,
	AlertIcon,
	AlertTitle,
	Box,
	Button,
	ButtonGroup,
	Editable,
	EditableInput,
	EditablePreview,
	Flex,
	Heading,
	Icon,
	IconButton,
	Input,
	Radio,
	Stack,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useEffect, useState } from 'react';
import { useFormContext } from 'react-hook-form';
import { BiPlus, BiTrash } from 'react-icons/bi';
import { Sortable } from '../../../../../assets/icons';
import { sectionHeaderStyles } from '../../../../../config/styles';
import { duplicateObject, isEmpty } from '../../../../../utils/utils';

interface Props {
	answersData?: any;
	setIsQuestionDisabled?: any;
}

const TrueFalse: React.FC<Props> = (props) => {
	const { answersData, setIsQuestionDisabled } = props;
	const { register, setValue } = useFormContext();
	const [answers, setAnswers] = useState<any>(
		answersData || [
			{ name: 'True', correct: true },
			{ name: 'False', correct: false },
		]
	);
	const iconStyles = {
		fontSize: 'x-large',
		color: 'gray.500',
		minW: 'auto',
		_hover: { color: 'blue.500' },
	};

	const onAddNewAnswerPress = () => {
		var newAnswers = [...answers];
		newAnswers.length < 2 &&
			setAnswers([
				...newAnswers,
				{ name: 'new answer ' + (answers.length + 1), correct: false },
			]);
	};

	const onDeletePress = (id: any) => {
		var newAnswers = [...answers];
		newAnswers.splice(id, 1);
		setAnswers(newAnswers);
	};

	const onCheckPress = (id: any, correct: boolean) => {
		var newAnswers = [...answers];

		// uncheck other checkbox
		for (var key in newAnswers) {
			newAnswers[key] = { ...newAnswers[key], correct: false };
		}

		newAnswers.splice(id, 1, { ...newAnswers[id], correct: correct });
		setAnswers(newAnswers);
	};

	const onUpdate = (id: any, value: string, olderValue: string) => {
		var newAnswers = [...answers];
		let modifiedValue = isEmpty(value) ? olderValue : value;
		newAnswers.splice(id, 1, { ...newAnswers[id], name: modifiedValue });
		if (duplicateObject('name', newAnswers)) {
			setIsQuestionDisabled(true);
		}
		setAnswers(newAnswers);
	};

	useEffect(() => {
		setValue('answers', answers);
	}, [answers, setValue, setIsQuestionDisabled]);

	return (
		<Stack direction="column" spacing="6">
			<Flex sx={sectionHeaderStyles}>
				<Heading fontSize="lg" fontWeight="semibold">
					{__('Answers', 'masteriyo')}
				</Heading>
			</Flex>
			<Input type="hidden" {...register('answers')} />
			<Box>
				{duplicateObject('name', answers) && (
					<Alert status="error" mb="4" fontSize="xs" p="2">
						<AlertIcon />
						<AlertTitle mr={2}>{__('Duplicate Names', 'masteriyo')}</AlertTitle>
						<AlertDescription>
							{__('Answer can not be duplicate', 'masteriyo')}
						</AlertDescription>
					</Alert>
				)}
				{answers &&
					answers.map(
						(answer: { name: string; correct: boolean }, index: number) => (
							<Flex
								key={index}
								border="1px"
								borderColor={answer?.correct ? 'green.200' : 'gray.100'}
								rounded="sm"
								mb="4"
								align="center"
								justify="space-between"
								px="2"
								py="1">
								<Stack direction="row" spacing="2" align="center" flex="1">
									<Icon as={Sortable} fontSize="lg" color="gray.500" />
									<Editable
										defaultValue={answer?.name}
										onSubmit={(value) => onUpdate(index, value, answer.name)}>
										<EditablePreview minW="sm" />
										<EditableInput />
									</Editable>
								</Stack>

								<Stack direction="row" spacing="4">
									<Radio
										colorScheme="green"
										isChecked={answer?.correct}
										onChange={(e) => onCheckPress(index, e.target.checked)}
									/>
									<Stack direction="row" spacing="2">
										<IconButton
											variant="unstyled"
											sx={iconStyles}
											aria-label={__('Delete', 'masteriyo')}
											icon={<BiTrash />}
											minW="auto"
											onClick={() => onDeletePress(index)}
										/>
									</Stack>
								</Stack>
							</Flex>
						)
					)}
			</Box>
			<ButtonGroup>
				<Button
					leftIcon={<Icon as={BiPlus} fontSize="xl" />}
					variant="link"
					color="gray.900"
					isDisabled={answers.length > 1}
					onClick={onAddNewAnswerPress}>
					{__('Add New Answer', 'masteriyo')}
				</Button>
			</ButtonGroup>
		</Stack>
	);
};

export default TrueFalse;
