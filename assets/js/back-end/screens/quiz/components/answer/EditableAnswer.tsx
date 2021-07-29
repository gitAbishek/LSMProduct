import { Editable, EditableInput, EditablePreview } from '@chakra-ui/react';
import React, { useContext, useState } from 'react';
import { QuestionContext } from '../../../../context/QuestionProvider';
import { duplicateObject, isEmpty } from '../../../../utils/utils';

interface Props {
	defaultValue: string;
	setAnswers: any;
	answers: any;
	index: number;
}

const EditableAnswer: React.FC<Props> = (props) => {
	const { defaultValue, setAnswers, answers, index } = props;
	const [editableValue, setEditableValue] = useState(defaultValue);
	const { submitQuestionDisabled, setSubmitQuestionDisabled } =
		useContext(QuestionContext);
	const onSubmit = (index: number, value: string) => {
		var newAnswers = [...answers];

		if (duplicateObject('name', newAnswers)) {
			setSubmitQuestionDisabled(true);
		} else {
			setSubmitQuestionDisabled(false);
		}

		if (isEmpty(value)) {
			newAnswers.splice(index, 1, {
				...newAnswers[index],
				name: defaultValue,
			});
			setEditableValue(defaultValue);
		} else {
			newAnswers.splice(index, 1, { ...newAnswers[index], name: value });
			setEditableValue(value);
		}

		setAnswers(newAnswers);
	};

	console.log(submitQuestionDisabled);

	const onChange = (value: string) => {
		setEditableValue(value);
	};

	return (
		<Editable
			defaultValue={defaultValue}
			value={editableValue}
			onChange={(value) => onChange(value)}
			onSubmit={(value) => onSubmit(index, value)}>
			<EditablePreview minW="sm" />
			<EditableInput />
		</Editable>
	);
};

export default EditableAnswer;
