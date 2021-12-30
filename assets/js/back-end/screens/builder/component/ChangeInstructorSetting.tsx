import { Avatar, Box, FormControl, FormLabel, HStack } from '@chakra-ui/react';
import { useSelect } from '@wordpress/data';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { useFormContext } from 'react-hook-form';
import { components, ControlProps, OptionProps } from 'react-select';
import AsyncSelect from 'react-select/async';
import { reactSelectStyles } from '../../../config/styles';
import urls from '../../../constants/urls';
import { CourseDataMap } from '../../../types/course';
import API from '../../../utils/api';
import { isEmpty } from '../../../utils/utils';

interface Props {
	courseData?: CourseDataMap | any;
}

const Control: React.FC<
	ControlProps<
		{
			value: any;
			label: any;
			avatar_url?: string;
		},
		false
	>
> = ({ children, ...rest }) => {
	return (
		<components.Control {...rest}>
			<Avatar marginLeft="2" src={rest.getValue()?.[0]?.avatar_url} size="xs" />
			{children}
		</components.Control>
	);
};

const Option: React.FC<
	OptionProps<
		{
			value: any;
			label: any;
			avatar_url?: string;
		},
		false
	>
> = ({ children, ...rest }) => {
	return (
		<components.Option {...rest}>
			<HStack alignItems="center">
				<Avatar src={rest.data?.avatar_url} size="xs" />
				<Box>{children}</Box>
			</HStack>
		</components.Option>
	);
};

const ChangeInstructorSetting: React.FC<Props> = (props) => {
	const canEditUsers = useSelect(
		(select: any) => select('core').canUser('create', 'users'),
		[]
	);
	const currentUser = useSelect(
		(select: any) => select('core').getCurrentUser(),
		[]
	);
	const defaultInstructor = isEmpty(currentUser)
		? null
		: {
				value: currentUser?.id,
				label: currentUser?.name,
				avatar_url: currentUser?.avatar_urls['24'],
		  };

	const { courseData } = props;
	const { setValue } = useFormContext();

	const usersAPI = new API(urls.users);

	if (canEditUsers === true && defaultInstructor) {
		return (
			<FormControl>
				<FormLabel>{__('Instructor', 'masteriyo')}</FormLabel>
				<AsyncSelect
					components={{ Control, Option }}
					styles={reactSelectStyles}
					cacheOptions={true}
					loadingMessage={() => __('Searching...', 'masteriyo')}
					noOptionsMessage={() =>
						__('Please enter 3 or more characters', 'masteriyo')
					}
					isClearable={true}
					placeholder={__('Search by username or email', 'masteriyo')}
					defaultValue={
						courseData
							? {
									value: courseData.author.id,
									label: courseData.author.display_name,
									avatar_url: courseData.author.avatar_url,
							  }
							: defaultInstructor
					}
					onChange={(selectedOption: any) => {
						setValue('author_id', selectedOption?.value);
					}}
					loadOptions={(searchValue, callback) => {
						if (searchValue.length < 3) {
							return callback([]);
						}
						usersAPI
							.list({
								search: searchValue,
								roles: 'administrator,masteriyo_instructor',
							})
							.then((data) => {
								callback(
									data.data.map((user: any) => {
										return {
											value: user.id,
											label: user.display_name,
											avatar_url: user.avatar_url,
										};
									})
								);
							});
					}}
				/>
			</FormControl>
		);
	}
	return null;
};

export default ChangeInstructorSetting;
