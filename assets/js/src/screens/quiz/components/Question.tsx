import {
	AccordionButton,
	AccordionItem,
	AccordionPanel,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import Button from 'Components/common/Button';
import FormGroup from 'Components/common/FormGroup';
import Input from 'Components/common/Input';
import Label from 'Components/common/Label';
import OptionButton from 'Components/common/OptionButton';
import Select from 'Components/common/Select';
import Textarea from 'Components/common/Textarea';
import React, { useState } from 'react';
import { useForm } from 'react-hook-form';
import { useMutation } from 'react-query';
import { useToasts } from 'react-toast-notifications';

import AnswerMultiChoice from './AnswerMultiChoice';
import AnswerTrueFalse from './AnswerTrueFalse';

interface Props {
	questionData: any;
}

const Question: React.FC<Props> = (props) => {
	const { questionData } = props;
	const { addToast } = useToasts();
	const { register, handleSubmit } = useForm();
	const [questionType, setQuestionType] = useState<string>();

	const updateQuestionMutation = useMutation(
		(data: object) => updateQuestion(questionData.id, data),
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
		updateQuestionMutation.mutate(data);
	};

	return (
		<AccordionItem>
			<AccordionButton>{questionData.name}</AccordionButton>
			<AccordionPanel>This is content</AccordionPanel>
		</AccordionItem>
	);
};

export default Question;
