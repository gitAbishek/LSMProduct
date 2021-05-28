import {
	Flex,
	Stack,
	Icon,
	Editable,
	EditablePreview,
	EditableInput,
	Checkbox,
	IconButton,
	Box,
	Button,
	ButtonGroup,
	Heading,
	Input,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import { borderedBoxStyles, sectionHeaderStyles } from 'Config/styles';
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
	const [answers, setAnswers] = useState<any>(answersData);

	const onAddNewAnswerPress = () => {
		setAnswers([
			...answers,
			{
				name: 'New Answer',
				right: false,
				checked: false,
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
			<Box>
				{answers.length !== 0 &&
					answers.map((answer: any, index: any) => (
						<Flex sx={borderedBoxStyles} key={index}>
							<Stack direction="row" spacing="2" align="center" flex="1">
								<Icon as={Sortable} fontSize="lg" color="gray.500" />
								<Editable defaultValue={answer?.name}>
									<EditablePreview />
									<EditableInput />
								</Editable>
							</Stack>
							<Stack direction="row" spacing="4">
								<Checkbox defaultChecked={answer?.checked} />
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
