import {
	FormControl,
	FormErrorMessage,
	FormLabel,
	Input,
	Stack,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { useFormContext } from 'react-hook-form';

import { CourseDataMap } from '../../../types/course';

interface Props {
	courseData: CourseDataMap | any;
}
const CourseSetting: React.FC<Props> = (props) => {
	const { courseData } = props;

	const {
		register,
		formState: { errors },
	} = useFormContext();
	return (
		<form>
			<Stack direction="row" spacing="8">
				<FormControl isInvalid={!!errors?.name}>
					<FormLabel>{__('Course Name', 'masteriyo')}</FormLabel>
					<Input
						defaultValue={courseData.difficulties}
						placeholder={__('Your Course Name', 'masteriyo')}
						{...register('name', {
							required: __('You must provide name for the course', 'masteriyo'),
						})}
					/>
					<FormErrorMessage>
						{errors?.name && errors?.name?.message}
					</FormErrorMessage>
				</FormControl>
			</Stack>
		</form>
	);
};

export default CourseSetting;
