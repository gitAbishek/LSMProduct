import {
	Box,
	FormControl,
	FormErrorMessage,
	FormLabel,
	Select,
	Stack,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { useFormContext } from 'react-hook-form';

import { CourseDataMap } from '../../../types/course';

interface Props {
	courseData?: CourseDataMap | any;
}
const CourseSetting: React.FC<Props> = (props) => {
	const { courseData } = props;

	const {
		register,
		formState: { errors },
	} = useFormContext();
	return (
		<Box bg="white" p="10" shadow="box">
			<form>
				<Stack direction="row" spacing="8">
					<FormControl isInvalid={errors?.difficulty} maxW="xs">
						<FormLabel>{__('Difficulty', 'masteriyo')}</FormLabel>
						<Select
							defaultValue={courseData?.difficulty}
							placeholder={__('Choose Course Level', 'masteriyo')}
							{...register('difficulty')}>
							<option value="beginner">{__('Beginner', 'masteriyo')}</option>
							<option value="intermediate">
								{__('Intermediate', 'masteriyo')}
							</option>
							<option value="advanced">{__('Advanced', 'masteriyo')}</option>
						</Select>

						<FormErrorMessage>
							{errors?.difficulty && errors?.difficulty?.message}
						</FormErrorMessage>
					</FormControl>
				</Stack>
			</form>
		</Box>
	);
};

export default CourseSetting;
