import { useMutation } from 'react-query';
import urls from '../constants/urls';
import { CourseSchema } from '../schemas';
import API from '../utils/api';

const useCourse = () => {
	const courseAPI = new API(urls.courses);
	const updateCourse = useMutation(
		({ id, data }: { id: number; data: CourseSchema | any }) =>
			courseAPI.update(id, data)
	);

	const draftCourse = useMutation((id: number) =>
		courseAPI.update(id, { status: 'draft' })
	);

	const publishCourse = useMutation((id: number) =>
		courseAPI.update(id, { status: 'publish' })
	);

	return {
		updateCourse,
		draftCourse,
		publishCourse,
	};
};

export default useCourse;
