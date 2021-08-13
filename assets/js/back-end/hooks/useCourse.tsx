import { useContext } from 'react';
import { MasteriyoContext } from '../context/MasteriyoProvider';
import { deepMerge } from '../utils/utils';

const useCourse = () => {
	const { mtoOptions, setMtoOptions } = useContext(MasteriyoContext);

	const courseId = (mtoOptions && mtoOptions?.course?.id) || false;
	const courseName = (mtoOptions && mtoOptions?.course?.name) || false;

	const setCourseId = (id: number) => {
		setMtoOptions(deepMerge(mtoOptions, { course: { id: id } }));
	};

	const setCourseName = (name: string) => {
		setMtoOptions(deepMerge(mtoOptions, { course: { name: name } }));
	};

	return { courseId, setCourseId, courseName, setCourseName };
};

export default useCourse;
