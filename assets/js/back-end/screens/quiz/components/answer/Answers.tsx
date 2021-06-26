import React from 'react';
import TrueFalse from './types/TrueFalse';

interface Props {
	answers?: any;
	questionType: string;
}

const Answers: React.FC<Props> = (props) => {
	const { answers, questionType } = props;

	if (questionType === 'true-false') {
		return <TrueFalse answersData={answers} />;
	}
	return <p>Empty Answer</p>;
};

export default Answers;
