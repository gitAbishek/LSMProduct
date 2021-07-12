import {
	Box,
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
import { nanoid } from 'nanoid';
import React, { useEffect, useState } from 'react';
import { useFormContext } from 'react-hook-form';
import { BiCopy, BiPlus, BiTrash } from 'react-icons/bi';
import { Sortable } from '../../../../../assets/icons';

interface Props {
	answersData?: any;
}

const TrueFalse: React.FC<Props> = (props) => {
	const { answersData } = props;
	const { register, setValue } = useFormContext();
	const [answers, setAnswers] = useState<any>({});
	const [checkedItem, setCheckedItem] = React.useState<any>(null);
	const nanoId = nanoid();

	const onAddNewAnswerPress = () => {
		setAnswers({
			...answers,
			[nanoId]: {
				name: 'New Answer',
				right: false,
				checked: false,
			},
		});
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

	const onCheckPress = (id: any, checked: boolean) => {
		var newAnswers = answers;

		// uncheck other checkbox
		for (var key in newAnswers) {
			if (newAnswers.hasOwnProperty(key)) {
				newAnswers[key] = { ...answers[key], checked: false };
			}
		}

		setAnswers({
			...newAnswers,
			[id]: {
				name: 'New Answer',
				right: false,
				checked: checked,
			},
		});
	};

	useEffect(() => {
		setValue('answers', answers);
	}, [answers, setValue]);

	console.log(answers);
	return (
		<Stack direction="column" spacing="6">
			<Flex sx={sectionHeaderStyles}>
				<Heading fontSize="lg" fontWeight="semibold">
					{__('Answers', 'masteriyo')}
				</Heading>
			</Flex>
			<Input type="hidden" {...register('answers')} />
			<Box>
				{Object.entries(answers).map(([id, answer]: any) => (
					<Flex sx={borderedBoxStyles} key={id}>
						<Stack direction="row" spacing="2" align="center" flex="1">
							<Icon as={Sortable} fontSize="lg" color="gray.500" />
							<Editable defaultValue={answer?.name}>
								<EditablePreview />
								<EditableInput />
							</Editable>
						</Stack>
						<Stack direction="row" spacing="4">
							<Checkbox
								isChecked={answer?.checked}
								onChange={(e) => onCheckPress(id, e.target.checked)}
							/>
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
									onClick={() => onDeletePress(id)}
								/>
							</Stack>
						</Stack>
					</Flex>
				))}
			</Box>
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

export default TrueFalse;
