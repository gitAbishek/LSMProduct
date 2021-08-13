import { useContext } from 'react';
import { MasteriyoContext } from '../context/MasteriyoProvider';
import { deepMerge } from '../utils/utils';

const useCourse = () => {
	const { mtoOptions, setMtoOptions } = useContext(MasteriyoContext);

	const courseId = (mtoOptions && mtoOptions?.course?.id) || false;
	const setCourseId = (id: number) => {
		setMtoOptions(deepMerge(mtoOptions, { course: { id: id } }));
	};

	return { courseId, setCourseId };
};

export default useCourse;
