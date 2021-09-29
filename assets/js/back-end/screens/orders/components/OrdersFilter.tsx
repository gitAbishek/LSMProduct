import {
	Box,
	Collapse,
	Flex,
	IconButton,
	Select,
	Stack,
	useMediaQuery,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useEffect, useState } from 'react';
import { useForm } from 'react-hook-form';
import { BiDotsVerticalRounded } from 'react-icons/bi';
import AsyncSelect from 'react-select/async';
import DesktopHidden from '../../../components/common/DesktopHidden';
import MobileHidden from '../../../components/common/MobileHidden';
import { reactSelectStyles } from '../../../config/styles';
import urls from '../../../constants/urls';
import API from '../../../utils/api';
import { deepClean } from '../../../utils/utils';

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
		label: __('Processing', 'masteriyo'),
		value: 'processing',
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
	after?: string;
	before?: string;
}

interface Props {
	setFilterParams: any;
}

const OrdersFilter: React.FC<Props> = (props) => {
	const { setFilterParams } = props;
	const { handleSubmit, register, setValue } = useForm();
	const [isMobile] = useMediaQuery('(min-width: 48em)');
	const [isOpen, setIsOpen] = useState(isMobile);

	const onChange = (data: FilterParams) => {
		setFilterParams(deepClean(data));
	};

	useEffect(() => {
		setIsOpen(isMobile);
	}, [isMobile]);

	const usersAPI = new API(urls.users);
	const orderFilterForm = (
		<form onChange={handleSubmit(onChange)}>
			<Stack direction={['column', null, 'row']} spacing="4" mt={[6, null, 0]}>
				<AsyncSelect
					styles={reactSelectStyles}
					cacheOptions={true}
					loadingMessage={() => __('Searching...', 'masteriyo')}
					noOptionsMessage={() =>
						__('Please enter 3 or more characters', 'masteriyo')
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
								data.map((user: any) => {
									return {
										value: user.id,
										label: `${user.display_name} (#${user.id} – ${user.email})`,
									};
								})
							);
						});
					}}
				/>
				<Select {...register('status')}>
					{courseStatusList.map((option: any) => (
						<option key={option.value} value={option.value}>
							{option.label}
						</option>
					))}
				</Select>
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
						aria-label={__('toggle filter')}
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
