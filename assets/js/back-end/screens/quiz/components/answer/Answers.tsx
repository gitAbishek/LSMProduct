import {
	Button,
	ButtonGroup,
	Checkbox,
	Editable,
	EditableInput,
	EditablePreview,
	Flex,
	Heading,
	Icon,
	IconButton,
	Input,
	Stack,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import { borderedBoxStyles, sectionHeaderStyles } from 'Config/styles';
import React, { useEffect, useState } from 'react';
import { useFormContext } from 'react-hook-form';
import { BiCopy, BiPlus, BiTrash } from 'react-icons/bi';

import { Sortable } from '../../../../assets/icons';

interface Props {
	questionData: any;
	questionType: string;
}

const Answers: React.FC<Props> = (props) => {
	const { questionData, questionType } = props;
	const { register, setValue } = useFormContext();
	const [answers, setAnswers] = useState<any>(questionData.answers);

	const onAddNewAnswerPress = () => {
		setAnswers([
			...answers,
			{
				name: 'This is name',
				answer: false,
			},
		]);
	};

	const iconStyles = {
		fontSize: 'x-large',
		color: 'gray.500',
		minW: 'auto',
		_hover: { color: 'blue.500' },
	};

	const onDeletePress = (id: any) => {
		var newAnswers = [...answers];
		newAnswers.splice(id, 1);
		setAnswers(newAnswers);
	};

	useEffect(() => {
		setValue('answers', answers);
	}, [answers]);

	return (
		<Stack direction="column" spacing="6">
			<Flex sx={sectionHeaderStyles}>
				<Heading fontSize="lg" fontWeight="semibold">
					{__('Answers', 'masteriyo')}
				</Heading>
			</Flex>
			<Input type="hidden" {...register('answers')} />

			{answers.length !== 0 &&
				answers.map((answer: any, index: any) => (
					<Flex sx={borderedBoxStyles} key={index}>
						<Stack direction="row" spacing="2" align="center" flex="1">
							<Icon as={Sortable} fontSize="lg" color="gray.500" />
							<Editable defaultValue={answer.name}>
								<EditablePreview />
								<EditableInput />
							</Editable>
						</Stack>
						<Stack direction="row" spacing="4">
							<Checkbox defaultChecked={answer.answer} />
							<Stack direction="row" spacing="2">
								<IconButton
									variant="unstyled"
									sx={iconStyles}
									aria-label={__('Duplicate', 'masteriyo')}
									icon={<BiCopy />}
								/>
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
				))}
			<ButtonGroup>
				<Button
					leftIcon={<Icon as={BiPlus} fontSize="xl" />}
					variant="link"
					color="gray.900"
					onClick={onAddNewAnswerPress}>
					Add New Answer
				</Button>
			</ButtonGroup>
		</Stack>
	);
};

export default Answers;
