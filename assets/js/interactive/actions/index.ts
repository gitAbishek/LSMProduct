import { GlobalState } from 'little-state-machine';

export function updateQuizProgress(
	state: GlobalState,
	payload: {
		quizProgress: {};
	}
): GlobalState {
	return {
		...state,
		...payload,
	};
}
