import {
	AccordionButton,
	Button,
	ButtonGroup,
	Checkbox,
	Flex,
	Heading,
	Icon,
	IconButton,
	Menu,
	MenuButton,
	MenuItem,
	MenuList,
	Stack,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useState } from 'react';
import { BiCopy, BiDotsVerticalRounded, BiPlus, BiTrash } from 'react-icons/bi';

import { Sortable } from '../../../../assets/icons';

interface Props {
	questionData: any;
}

const AnswerBuilder: React.FC<Props> = (props) => {
	const { questionData } = props;
	const [answers, setAnswers] = useState<any>(questionData?.answers);

	const onAddNewAnswerPress = () => {
		setAnswers([
			...answers,
			{
				label: 'True',
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

	return (
		<Stack direction="column" spacing="6">
			<Flex
				align="center"
				justify="space-between"
				borderBottom="1px"
				borderColor="gray.100"
				pb="3">
				<Heading fontSize="lg" fontWeight="semibold">
					{__('Answers', 'masteriyo')}
				</Heading>
				<Menu placement="bottom-end">
					<MenuButton
						as={IconButton}
						icon={<BiDotsVerticalRounded />}
						variant="outline"
						rounded="xs"
						fontSize="large"
						size="sm"
					/>
					<MenuList>
						<MenuItem icon={<BiTrash />}>{__('Delete', 'masteriyo')}</MenuItem>
					</MenuList>
				</Menu>
			</Flex>
			{answers.map((answer: any, index: any) => (
				<Flex
					key="index"
					borderWidth="1px"
					borderColor="gray.100"
					rounded="sm"
					mb="4"
					alignItems="center"
					justify="space-between"
					px="2"
					py="1">
					<Stack direction="row" spacing="2" align="center" flex="1">
						<Icon as={Sortable} fontSize="lg" color="gray.500" />
						<AccordionButton _hover={{ background: 'transparent' }} px="0">
							{answer.label}
						</AccordionButton>
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

export default AnswerBuilder;
