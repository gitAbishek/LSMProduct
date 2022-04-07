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
import { useForm } from 'react-hook-form';
import { BiDotsVerticalRounded } from 'react-icons/bi';
import { useQuery } from 'react-query';
import { useOnType } from 'use-ontype';
import urls from '../../../constants/urls';
import { CourseCategoriesResponse } from '../../../types/course';
import API from '../../../utils/api';
import { makeCategoriesHierarchy } from '../../../utils/categories';
import { deepClean, deepMerge } from '../../../utils/utils';

const courseStatusList = [
	{
		label: __('All', 'masteriyo'),
		value: '',
	},
	{
		label: __('Published', 'masteriyo'),
		value: 'publish',
	},
	{
		label: __('Draft', 'masteriyo'),
		value: 'draft',
	},
	{
		label: __('Trash', 'masteriyo'),
		value: 'trash',
	},
];

interface FilterParams {
	category?: string | number;
	search?: string;
	status?: string;
	price_type?: string;
}

interface Props {
	setFilterParams: any;
	filterParams: FilterParams;
}

const CourseFilter: React.FC<Props> = (props) => {
	const { setFilterParams, filterParams } = props;
	const categoryAPI = new API(urls.categories);
	const categoriesQuery = useQuery<CourseCategoriesResponse>(
		'categoryLists',
		() => categoryAPI.list({ per_page: 100 }),
		{
			retry: false,
		}
	);

	const categories = categoriesQuery.isSuccess
		? makeCategoriesHierarchy(categoriesQuery.data.data)
		: [];

	const { handleSubmit, register } = useForm();
	const [isMobile] = useMediaQuery('(min-width: 48em)');
	const onSearchInput = useOnType(
		{
			onTypeFinish: (val: string) => {
				setFilterParams({
					search: val,
					category: filterParams.category,
					status: filterParams.status,
					price_type: filterParams.price_type,
				});
			},
		},
		800
	);
	const [isOpen, setIsOpen] = useState(isMobile);

	const onChange = (data: FilterParams) => {
		setFilterParams(
			deepClean(deepMerge(data, { search: filterParams.search }))
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
							placeholder={__('Search courses', 'masteriyo')}
							{...onSearchInput}
						/>
						<Select {...register('category')}>
							<option value="">{__('All Categories', 'masteriyo')}</option>
							{categoriesQuery.isSuccess
								? categories.map((category) => (
										<option key={category.id} value={category.id}>
											{'— '.repeat(category.depth) + category.name}
										</option>
								  ))
								: null}
						</Select>

						<Select {...register('status')}>
							{courseStatusList.map((option: any) => (
								<option key={option.value} value={option.value}>
									{option.label}
								</option>
							))}
						</Select>

						<Select {...register('price_type')}>
							<option value="">{__('Pricing', 'masteriyo')}</option>
							<option value="free">{__('Free', 'masteriyo')}</option>
							<option value="paid">{__('Paid', 'masteriyo')}</option>
						</Select>
					</Stack>
				</form>
			</Collapse>
		</Box>
	);
};

export default CourseFilter;
