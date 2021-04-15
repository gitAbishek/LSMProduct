import {
	AccordionButton,
	AccordionItem,
	AccordionPanel,
	Flex,
	Icon,
	IconButton,
	Stack,
	background,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useState } from 'react';
import { useForm } from 'react-hook-form';
import { BiCopy, BiTrash } from 'react-icons/bi';
import { useMutation } from 'react-query';
import { useToasts } from 'react-toast-notifications';

import { Sortable } from '../../../assets/icons';
import urls from '../../../constants/urls';
import API from '../../../utils/api';

interface Props {
	questionData: any;
}

const Question: React.FC<Props> = (props) => {
	const { questionData } = props;
	const { addToast } = useToasts();
	const { register, handleSubmit } = useForm();
	const questionAPI = new API(urls.questions);

	const updateQuestion = useMutation(
		(data: object) => questionAPI.update(questionData.id, data),
		{
			onSuccess: (data: any) => {
				addToast(data?.name + __(' has been updated successfully'), {
					appearance: 'success',
					autoDismiss: true,
				});
			},
		}
	);
	const onSubmit = (data: object) => {
		updateQuestion.mutate(data);
	};

	return (
		<AccordionItem
			borderWidth="1px"
			borderColor="gray.100"
			rounded="sm"
			_expanded={{ shadow: 'box' }}
			mb="4"
			py="1"
			px="2">
			<Flex align="center" justify="space-between">
				<Stack direction="row" spacing="2" align="center" flex="1">
					<Icon as={Sortable} fontSize="lg" color="gray.500" />
					<AccordionButton _hover={{ background: 'transparent' }} px="0">
						{questionData.name}
					</AccordionButton>
				</Stack>
				<Stack direction="row" spacing="2">
					<IconButton
						variant="unstyled"
						fontSize="x-large"
						color="gray.500"
						_hover={{ color: 'blue.500' }}
						aria-label={__('Duplicate', 'masteriyo')}
						icon={<BiCopy />}
						textAlign="right"
						minW="auto"
					/>
					<IconButton
						variant="unstyled"
						fontSize="x-large"
						color="gray.500"
						_hover={{ color: 'blue.500' }}
						aria-label={__('Duplicate', 'masteriyo')}
						icon={<BiTrash />}
						textAlign="right"
						minW="auto"
					/>
				</Stack>
			</Flex>
			<AccordionPanel>This is content</AccordionPanel>
		</AccordionItem>
	);
};

export default Question;
