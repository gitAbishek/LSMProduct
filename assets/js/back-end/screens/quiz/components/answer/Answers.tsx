import { Spinner } from '@chakra-ui/react';
import React from 'react';
import MultipleChoice from './types/MultipleChoice';
import ShortAnswer from './types/ShortAnswer';
import SingleChoice from './types/SingleChoice';
import TrueFalse from './types/TrueFalse';

interface Props {
	answers?: any;
	questionType: string;
	setIsQuestionDisabled?: any;
}

const Answers: React.FC<Props> = (props) => {
	const { answers, questionType, setIsQuestionDisabled } = props;

	if (questionType === 'true-false') {
		return (
			<TrueFalse
				answersData={answers}
				setIsQuestionDisabled={setIsQuestionDisabled}
			/>
		);
	} else if (questionType === 'single-choice') {
		return <SingleChoice answersData={answers} />;
	} else if (questionType === 'multiple-choice') {
		return <MultipleChoice answersData={answers} />;
	} else if (questionType === 'short-answer') {
		return <ShortAnswer answersData={answers} />;
	}
	return (
		<>
			<Spinner />
		</>
	);
};

export default Answers;
