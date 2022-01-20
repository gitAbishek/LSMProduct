import {
	Box,
	Collapse,
	Flex,
	IconButton,
	Input,
	Select,
	Stack,
	useMediaQuery,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useEffect, useState } from 'react';
import ReactDatePicker from 'react-datepicker';
import 'react-datepicker/dist/react-datepicker.css';
import { Controller, useForm } from 'react-hook-form';
import { BiDotsVerticalRounded } from 'react-icons/bi';
import AsyncSelect from 'react-select/async';
import DesktopHidden from '../../../components/common/DesktopHidden';
import MobileHidden from '../../../components/common/MobileHidden';
import { reactSelectStyles } from '../../../config/styles';
import urls from '../../../constants/urls';
import API from '../../../utils/api';
import { deepClean, deepMerge } from '../../../utils/utils';

const courseStatusList = [
	{
		label: __('All Status', 'masteriyo'),
		value: '',
	},
	{
		label: __('Pending', 'masteriyo'),
		value: 'pending',
	},
	{
		label: __('On-hold', 'masteriyo'),
		value: 'on-hold',
	},
	{
		label: __('Completed', 'masteriyo'),
		value: 'completed',
	},
	{
		label: __('Cancelled', 'masteriyo'),
		value: 'cancelled',
	},
	{
		label: __('Refunded', 'masteriyo'),
		value: 'refunded',
	},
	{
		label: __('Failed', 'masteriyo'),
		value: 'failed',
	},
];

interface FilterParams {
	status?: string;
	after?: Date;
	before?: Date;
}

interface Props {
	setFilterParams: any;
}

const OrdersFilter: React.FC<Props> = (props) => {
	const { setFilterParams } = props;
	const { handleSubmit, register, setValue, control } = useForm();
	const [isMobile] = useMediaQuery('(min-width: 48em)');
	const [isOpen, setIsOpen] = useState(isMobile);

	const onChange = (data: FilterParams) => {
		const formattedDate = {
			before: data?.before?.toISOString(),
			after: data?.after?.toISOString(),
		};
		setFilterParams(deepClean(deepMerge(data, formattedDate)));
	};

	useEffect(() => {
		setIsOpen(isMobile);
	}, [isMobile]);

	const usersAPI = new API(urls.users);

	const orderFilterForm = (
		<form onChange={handleSubmit(onChange)}>
			<Stack direction={['column', null, 'row']} spacing="4" mt={[6, null, 0]}>
				<Box>
					<Controller
						control={control}
						name="after"
						render={({ field: { onChange: onDateChange, value } }) => (
							<ReactDatePicker
								dateFormat="yyyy-MM-dd"
								onChange={(value: Date) => {
									onDateChange(value);
									handleSubmit(onChange)();
								}}
								selected={value as unknown as Date}
								customInput={<Input />}
								placeholderText={__('From', 'masteriyo')}
								autoComplete="off"
							/>
						)}
					/>
				</Box>
				<Box>
					<Controller
						control={control}
						name="before"
						render={({ field: { onChange: onDateChange, value } }) => (
							<ReactDatePicker
								dateFormat="yyyy-MM-dd"
								onChange={(value: Date) => {
									onDateChange(value);
									handleSubmit(onChange)();
								}}
								selected={value as unknown as Date}
								customInput={<Input />}
								placeholderText={__('To', 'masteriyo')}
								autoComplete="off"
							/>
						)}
					/>
				</Box>
				<AsyncSelect
					styles={reactSelectStyles}
					cacheOptions={true}
					loadingMessage={() => __('Searching...', 'masteriyo')}
					noOptionsMessage={() =>
						__('Please enter 3 or more characters.', 'masteriyo')
					}
					isClearable={true}
					placeholder={__('Search by customer', 'masteriyo')}
					onChange={(selectedOption: any) => {
						setValue('customer', selectedOption?.value);
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
				<Box flex="1">
					<Select {...register('status')}>
						{courseStatusList.map((option: any) => (
							<option key={option.value} value={option.value}>
								{option.label}
							</option>
						))}
					</Select>
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
				<Collapse in={isOpen}>{orderFilterForm}</Collapse>
			</DesktopHidden>
			<MobileHidden>{orderFilterForm}</MobileHidden>
		</Box>
	);
};

export default OrdersFilter;
