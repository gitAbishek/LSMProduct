import { FormErrorMessage, FormLabel } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { useFormContext } from 'react-hook-form';
import { useQuery } from 'react-query';
import AsyncSelect from 'react-select/async';
import FormControlTwoCol from '../../../components/common/FormControlTwoCol';
import { reactSelectStyles } from '../../../config/styles';
import urls from '../../../constants/urls';
import { CoursesResponse } from '../../../types/course';
import API from '../../../utils/api';
import { isEmpty } from '../../../utils/utils';

type Nullable<T> = T | null;
interface DefaultValue {
	label: string;
	value: number;
}
interface Props {
	defaultValue: Nullable<DefaultValue>;
}

const Courses: React.FC<Props> = (props) => {
	const { defaultValue } = props;
	const coursesAPI = new API(urls.courses);

	const {
		register,
		formState: { errors },
		setValue,
	} = useFormContext();

	const courseQuery = useQuery<CoursesResponse>('courseList', () =>
		coursesAPI.list({
			order_by: 'name',
			order: 'asc',
			per_page: 5,
		})
	);

	return (
		<FormControlTwoCol isInvalid={!!errors?.course_id} py="3">
			<FormLabel>{__('Course', 'masteriyo')}</FormLabel>
			<AsyncSelect
				{...register('course_id', {
					required: __('Course must be selected', 'masteriyo'),
				})}
				placeholder={__('Select Course', 'masteriyo')}
				defaultValue={defaultValue}
				isClearable={true}
				styles={reactSelectStyles}
				cacheOptions={true}
				loadingMessage={() => __('Searching course...', 'masteriyo')}
				noOptionsMessage={({ inputValue }) =>
					inputValue.length > 0
						? __('Course not found.', 'masteriyo')
						: courseQuery.isLoading
						? __('Loading...', 'masteriyo')
						: __('Please enter 1 or more characters.', 'masteriyo')
				}
				onChange={(selectedOption: any) => {
					setValue('course_id', selectedOption?.value.toString());
				}}
				defaultOptions={
					courseQuery.isSuccess
						? courseQuery?.data?.data?.map((course) => {
								return {
									value: course.id,
									label: `${course.name} (#${course.id} - ${course.name})`,
								};
						  })
						: []
				}
				loadOptions={(searchValue, callback) => {
					if (isEmpty(searchValue)) {
						return callback([]);
					}
					coursesAPI.list({ search: searchValue }).then((data) => {
						callback(
							data?.data?.map((course: any) => {
								return {
									value: course.id,
									label: `#${course.id} ${course.name}`,
								};
							})
						);
					});
				}}
			/>
			<FormErrorMessage>
				{errors?.course_id && errors?.course_id?.message}
			</FormErrorMessage>
		</FormControlTwoCol>
	);
};

Courses.defaultProps = {
	defaultValue: null,
};

export default Courses;
