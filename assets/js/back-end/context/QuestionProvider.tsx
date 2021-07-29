import React, { createContext, useMemo, useState } from 'react';

export const QuestionContext = createContext<{
	submitQuestion: boolean;
	setSubmitQuestion?: any;
}>({ submitQuestion: false });

const QuestionProvider: React.FC = ({ children }) => {
	const [submitQuestion, setSubmitQuestion] = useState(false);
	const providerValue = useMemo(
		() => ({
			submitQuestion: submitQuestion,
			setSubmitQuestion: setSubmitQuestion,
		}),
		[submitQuestion, setSubmitQuestion]
	);

	return (
		<QuestionContext.Provider value={providerValue}>
			{children}
		</QuestionContext.Provider>
	);
};

export default QuestionProvider;
