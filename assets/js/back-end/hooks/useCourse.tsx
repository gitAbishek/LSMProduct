import { useToast } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import { useContext } from 'react';
import { useMutation, useQueryClient } from 'react-query';
import urls from '../constants/urls';
import { MasteriyoContext } from '../context/MasteriyoProvider';
import { CourseSchema } from '../schemas';
import API from '../utils/api';
import { deepMerge } from '../utils/utils';

const useCourse = () => {
	const { mtoOptions, setMtoOptions } = useContext(MasteriyoContext);
	const toast = useToast();
	const queryClient = useQueryClient();
	const courseId = (mtoOptions && mtoOptions?.course?.id) || false;
	const courseName = (mtoOptions && mtoOptions?.course?.name) || false;
	const previewUrl = (mtoOptions && mtoOptions?.course?.previewUrl) || false;
	const courseAPI = new API(urls.courses);

	const setCourseId = (id: number) => {
		setMtoOptions(deepMerge(mtoOptions, { course: { id: id } }));
	};

	const setCourseName = (name: string) => {
		setMtoOptions(deepMerge(mtoOptions, { course: { name: name } }));
	};

	const setPreviewUrl = (previewUrl: string) => {
		setMtoOptions(
			deepMerge(mtoOptions, { course: { previewUrl: previewUrl } })
		);
	};

	const updateCourseMutation = useMutation(
		({ id, data }: { id: number; data: CourseSchema }) =>
			courseAPI.update(id, data)
	);

	const updateCourse = (id: number, data: any) => {
		updateCourseMutation.mutate(
			{ id, data },
			{
				onSuccess: (res: CourseSchema) => {
					toast({
						title: res.name + __(' is Published successfully.', 'masteriyo'),
						description: __('You can keep editing it', 'masteriyo'),
						status: 'success',
						isClosable: true,
					});
					queryClient.invalidateQueries(`course${res.id}`);
				},
			}
		);
	};

	return {
		courseId,
		setCourseId,
		courseName,
		setCourseName,
		previewUrl,
		setPreviewUrl,
		updateCourse,
	};
};

export default useCourse;
