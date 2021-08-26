import React, { createContext, useContext, useMemo, useReducer } from 'react';

const ACTIONS = {
	SHOW_CATEGORY: 'showCategory',
	SET_COURSE_PUBLISHED: 'isCoursePublished',
};

type ReducerProps = {
	showCatPopup?: boolean;
	isCoursePublished?: boolean;
};

const Reducer = (
	prevState: ReducerProps,
	action: { type: string; payload: any }
) => {
	switch (action.type) {
		case ACTIONS.SHOW_CATEGORY:
			return {
				...prevState,
				showCatPopup: action.payload,
			};
		case ACTIONS.SET_COURSE_PUBLISHED:
			return {
				...prevState,
				isCoursePublished: action.payload,
			};
		default:
			return prevState;
	}
};

const initialState: ReducerProps = {
	showCatPopup: false,
	isCoursePublished: false,
};

export const MasteriyoContext = createContext<ReducerProps | any>(initialState);

const MasteriyoProvider: React.FC = ({ children }) => {
	const [state, dispatch] = useReducer(Reducer, initialState);

	const providerValue = useMemo(() => {
		return [state, dispatch];
	}, [state]);

	return (
		<MasteriyoContext.Provider value={providerValue}>
			{children}
		</MasteriyoContext.Provider>
	);
};

export const useOptions = () => useContext(MasteriyoContext);

export default MasteriyoProvider;
