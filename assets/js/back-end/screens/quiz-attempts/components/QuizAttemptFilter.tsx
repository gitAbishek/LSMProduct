import {
	Box,
	Collapse,
	Flex,
	IconButton,
	Stack,
	useMediaQuery,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useEffect, useState } from 'react';
import 'react-datepicker/dist/react-datepicker.css';
import { useForm } from 'react-hook-form';
import { BiDotsVerticalRounded } from 'react-icons/bi';
import { useQuery } from 'react-query';
import AsyncSelect from 'react-select/async';
import DesktopHidden from '../../../components/common/DesktopHidden';
import MobileHidden from '../../../components/common/MobileHidden';
import { reactSelectStyles } from '../../../config/styles';
import urls from '../../../constants/urls';
import { UsersApiResponse } from '../../../types/users';
import API from '../../../utils/api';
import { deepClean, isEmpty } from '../../../utils/utils';

interface Props {
	setFilterParams: any;
}

const QuizAttemptFilter: React.FC<Props> = (props) => {
	const { setFilterParams } = props;
	const { handleSubmit, setValue } = useForm();
	const [isMobile] = useMediaQuery('(min-width: 48em)');
	const [isOpen, setIsOpen] = useState(isMobile);

	const onChange = (data: any) => {
		setFilterParams(deepClean(data));
	};

	useEffect(() => {
		setIsOpen(isMobile);
	}, [isMobile]);

	const usersAPI = new API(urls.users);
	const quizAPI = new API(urls.quizes);

	const usersQuery = useQuery<UsersApiResponse>('users', () =>
		usersAPI.list({
			orderby: 'display_name',
			order: 'asc',
			per_page: 10,
		})
	);

	const quizzesQuery = useQuery('quizzes', () =>
		quizAPI.list({
			per_page: 10,
		})
	);

	const QuizAttemptFilterForm = (
		<form onChange={handleSubmit(onChange)}>
			<Stack direction={['column', null, 'row']} spacing="4" mt={[6, null, 0]}>
				<Box flex="1">
					<AsyncSelect
						styles={reactSelectStyles}
						cacheOptions={true}
						loadingMessage={() => __('Searching...', 'masteriyo')}
						noOptionsMessage={({ inputValue }) =>
							!isEmpty(inputValue)
								? __('Users not found.', 'masteriyo')
								: usersQuery.isLoading
								? __('Loading...', 'masteriyo')
								: __('Please enter one or more characters.', 'masteriyo')
						}
						isClearable={true}
						placeholder={__('Search by username or email', 'masteriyo')}
						onChange={(selectedOption: any) => {
							setValue('user_id', selectedOption?.value);
							handleSubmit(onChange)();
						}}
						defaultOptions={
							usersQuery.isSuccess
								? usersQuery.data?.data?.map((user) => {
										return {
											value: user.id,
											label: `${user.display_name} (#${user.id} - ${user.email})`,
											avatar_url: user.avatar_url,
										};
								  })
								: []
						}
						loadOptions={(searchValue, callback) => {
							if (isEmpty(searchValue)) {
								return callback([]);
							}
							usersAPI.list({ search: searchValue }).then((data) => {
								callback(
									data.data.map((user: any) => {
										return {
											value: user.id,
											label: `${user.display_name} (#${user.id} - ${user.email})`,
										};
									})
								);
							});
						}}
					/>
				</Box>

				<Box flex="1">
					<AsyncSelect
						styles={reactSelectStyles}
						cacheOptions={true}
						loadingMessage={() => __('Searching...', 'masteriyo')}
						noOptionsMessage={({ inputValue }) =>
							!isEmpty(inputValue)
								? __('Quiz not found.', 'masteriyo')
								: quizzesQuery.isLoading
								? __('Loading...', 'masteriyo')
								: __('Please enter one or more characters.', 'masteriyo')
						}
						isClearable={true}
						placeholder={__('Search by Quiz', 'masteriyo')}
						onChange={(selectedOption: any) => {
							setValue('quiz_id', selectedOption?.value);
							handleSubmit(onChange)();
						}}
						defaultOptions={
							quizzesQuery.isSuccess
								? quizzesQuery.data?.map((quiz: any) => {
										return {
											value: quiz.id,
											label: quiz.name,
										};
								  })
								: []
						}
						loadOptions={(searchValue, callback) => {
							if (isEmpty(searchValue)) {
								return callback([]);
							}
							quizAPI.list({ search: searchValue }).then((data) => {
								callback(
									data.map((quiz: any) => {
										return {
											value: quiz.id,
											label: quiz.name,
										};
									})
								);
							});
						}}
					/>
				</Box>
			</Stack>
		</form>
	);

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
			<DesktopHidden>
				<Collapse in={isOpen}>{QuizAttemptFilterForm}</Collapse>
			</DesktopHidden>
			<MobileHidden>{QuizAttemptFilterForm}</MobileHidden>
		</Box>
	);
};

export default QuizAttemptFilter;
