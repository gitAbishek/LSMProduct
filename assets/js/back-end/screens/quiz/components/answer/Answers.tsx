import React from 'react';
import SingleChoice from './types/SingleChoice';
import TrueFalse from './types/TrueFalse';

interface Props {
	answers?: any;
	questionType: string;
}

const Answers: React.FC<Props> = (props) => {
	const { answers, questionType } = props;

	if (questionType === 'true-false') {
		return <TrueFalse answersData={answers} />;
	} else if (questionType === 'single-choice') {
		return <SingleChoice answersData={answers} />;
	}
	return <p>Empty Answer</p>;
};

export default Answers;
