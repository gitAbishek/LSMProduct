import { Avatar, Box, FormControl, FormLabel, HStack } from '@chakra-ui/react';
import { useSelect } from '@wordpress/data';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { useFormContext } from 'react-hook-form';
import { useQuery } from 'react-query';
import { components, ControlProps, OptionProps } from 'react-select';
import AsyncSelect from 'react-select/async';
import { reactSelectStyles } from '../../../config/styles';
import urls from '../../../constants/urls';
import { CourseDataMap } from '../../../types/course';
import { UsersApiResponse } from '../../../types/users';
import API from '../../../utils/api';
import { isEmpty } from '../../../utils/utils';

interface Props {
	courseData?: CourseDataMap | any;
}

interface AsyncSelectOption {
	value: string | number;
	label: string;
	avatar_url?: string;
}

const Control: React.FC<ControlProps<AsyncSelectOption, false>> = ({
	children,
	...rest
}) => {
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

	const usersQuery = useQuery<UsersApiResponse>('users', () =>
		usersAPI.list({
			roles: 'administrator,masteriyo_instructor',
			orderby: 'display_name',
			order: 'asc',
			per_page: 10,
		})
	);

	if (!usersQuery.isLoading && canEditUsers === true && defaultInstructor) {
		return (
			<FormControl>
				<FormLabel>{__('Instructor', 'masteriyo')}</FormLabel>
				<AsyncSelect
					components={{ Control, Option }}
					styles={reactSelectStyles}
					cacheOptions={true}
					loadingMessage={() => __('Searching...', 'masteriyo')}
					noOptionsMessage={({ inputValue }) =>
						!isEmpty(inputValue)
							? __('Users not found.', 'masteriyo')
							: __('Please enter one or more characters.', 'masteriyo')
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
					defaultOptions={
						usersQuery.isSuccess
							? usersQuery.data?.data?.map((user) => {
									return {
										value: user.id,
										label: user.display_name,
										avatar_url: user.avatar_url,
									};
							  })
							: []
					}
					loadOptions={(searchValue, callback) => {
						if (isEmpty(searchValue)) {
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
