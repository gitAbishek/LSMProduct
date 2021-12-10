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
import AsyncSelect from 'react-select/async';
import DesktopHidden from '../../../components/common/DesktopHidden';
import MobileHidden from '../../../components/common/MobileHidden';
import { reactSelectStyles } from '../../../config/styles';
import urls from '../../../constants/urls';
import API from '../../../utils/api';
import { deepClean } from '../../../utils/utils';

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

	const QuizAttemptFilterForm = (
		<form onChange={handleSubmit(onChange)}>
			<Stack direction={['column', null, 'row']} spacing="4" mt={[6, null, 0]}>
				<Box flex="1">
					<AsyncSelect
						styles={reactSelectStyles}
						cacheOptions={true}
						loadingMessage={() => __('Searching...', 'masteriyo')}
						noOptionsMessage={() =>
							__('Please enter 3 or more characters', 'masteriyo')
						}
						isClearable={true}
						placeholder={__('Search by username or email', 'masteriyo')}
						onChange={(selectedOption: any) => {
							setValue('user_id', selectedOption?.value);
							handleSubmit(onChange)();
						}}
						loadOptions={(searchValue, callback) => {
							if (searchValue.length < 3) {
								return callback([]);
							}
							usersAPI.list({ search: searchValue }).then((data) => {
								callback(
									data.data.map((user: any) => {
										return {
											value: user.id,
											label: `${user.display_name} (#${user.id} – ${user.email})`,
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
						noOptionsMessage={() =>
							__('Please enter 3 or more characters', 'masteriyo')
						}
						isClearable={true}
						placeholder={__('Search by Quiz', 'masteriyo')}
						onChange={(selectedOption: any) => {
							setValue('quiz_id', selectedOption?.value);
							handleSubmit(onChange)();
						}}
						loadOptions={(searchValue, callback) => {
							if (searchValue.length < 3) {
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
