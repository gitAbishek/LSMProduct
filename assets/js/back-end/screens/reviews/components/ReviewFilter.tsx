import {
	Box,
	Collapse,
	Flex,
	IconButton,
	Input,
	Stack,
	useMediaQuery,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useEffect, useState } from 'react';
import { useForm } from 'react-hook-form';
import { BiDotsVerticalRounded } from 'react-icons/bi';
import { useQuery } from 'react-query';
import AsyncSelect from 'react-select/async';
import { useOnType } from 'use-ontype';
import { reactSelectStyles } from '../../../config/styles';
import urls from '../../../constants/urls';
import { CoursesResponse } from '../../../types/course';
import { UsersApiResponse } from '../../../types/users';
import API from '../../../utils/api';
import { deepClean, deepMerge } from '../../../utils/utils';

interface FilterParams {
	course?: string | number;
	user?: string | number;
	search?: string;
}

interface Props {
	setFilterParams: any;
	filterParams: FilterParams;
}

const ReviewFilter: React.FC<Props> = ({ filterParams, setFilterParams }) => {
	const [isMobile] = useMediaQuery('(min-width: 48em)');
	const [isOpen, setIsOpen] = useState(false);
	const courseAPI = new API(urls.courses);
	const userAPI = new API(urls.users);

	const courseQueries = useQuery<CoursesResponse>('courseList', () =>
		courseAPI.list({
			order_by: 'name',
			order: 'asc',
			per_page: 5,
		})
	);

	const userQueries = useQuery<UsersApiResponse>('userList', () =>
		userAPI.list({
			order_by: 'name',
			order: 'asc',
			per_page: 5,
		})
	);

	const { handleSubmit, register, setValue } = useForm();

	const onSearchInput = useOnType(
		{
			onTypeFinish: (val: string) => {
				setFilterParams({
					parent: 0,
					user: filterParams.user,
					course: filterParams.course,
					search: val,
				});
			},
		},
		800
	);

	const onChange = (data: FilterParams) => {
		setFilterParams(
			deepClean(deepMerge(data, { search: filterParams.search, parent: 0 }))
		);
	};

	useEffect(() => {
		setIsOpen(isMobile);
	}, [isMobile]);

	return (
		<Box px={{ base: 6, md: 12 }}>
			<Flex justify="end">
				{!isMobile && (
					<IconButton
						icon={<BiDotsVerticalRounded />}
						variant="outline"
						rounded="sm"
						fontSize="large"
						aria-label={__('toggle filter', 'masteriyo')}
						onClick={() => setIsOpen(!isOpen)}
					/>
				)}
			</Flex>
			<Collapse in={isOpen}>
				<form onChange={handleSubmit(onChange)}>
					<Stack
						direction={['column', null, 'row']}
						spacing="4"
						mt={[6, null, 0]}>
						<Input
							placeholder={__('Search reviews', 'masteriyo')}
							{...onSearchInput}
							height="40px"
						/>
						<AsyncSelect
							{...register('course')}
							onChange={(selectedOption: any) => {
								setValue('course', selectedOption?.value.toString());
								handleSubmit(onChange)();
							}}
							placeholder={__('Filter by Course', 'masteriyo')}
							isClearable={true}
							styles={reactSelectStyles}
							cacheOptions={true}
							loadingMessage={() => __('Searching course...', 'masteriyo')}
							noOptionsMessage={({ inputValue }) =>
								inputValue.length > 0
									? __('Course not found.', 'masteriyo')
									: courseQueries.isLoading
									? __('Loading...', 'masteriyo')
									: __('Please enter 1 or more characters.', 'masteriyo')
							}
							defaultOptions={
								courseQueries.isSuccess
									? courseQueries?.data?.data?.map((course) => {
											return {
												value: course.id,
												label: `(#${course.id} - ${course.name})`,
											};
									  })
									: []
							}
							loadOptions={(searchValue, callback) => {
								if (searchValue.length < 0) {
									return callback([]);
								}
								courseAPI.list({ search: searchValue }).then((data) => {
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

						<AsyncSelect
							{...register('user')}
							onChange={(selectedOption: any) => {
								setValue('user', selectedOption?.value.toString());
								handleSubmit(onChange)();
							}}
							placeholder={__('Filter by Author', 'masteriyo')}
							isClearable={true}
							styles={reactSelectStyles}
							cacheOptions={true}
							loadingMessage={() => __('Searching author...', 'masteriyo')}
							noOptionsMessage={({ inputValue }) =>
								inputValue.length > 0
									? __('Author not found.', 'masteriyo')
									: userQueries.isLoading
									? __('Loading...', 'masteriyo')
									: __('Please enter 1 or more characters.', 'masteriyo')
							}
							defaultOptions={
								userQueries.isSuccess
									? userQueries?.data?.data?.map((author) => {
											return {
												value: author.id,
												label: `${author.username} (#${author.id} - ${author.email})`,
											};
									  })
									: []
							}
							loadOptions={(searchValue, callback) => {
								if (searchValue.length < 0) {
									return callback([]);
								}
								userAPI.list({ search: searchValue }).then((data) => {
									callback(
										data?.data?.map((author: any) => {
											return {
												value: author.id,
												label: `${author.username} (#${author.id} - ${author.email})`,
											};
										})
									);
								});
							}}
						/>
					</Stack>
				</form>
			</Collapse>
		</Box>
	);
};

export default ReviewFilter;
