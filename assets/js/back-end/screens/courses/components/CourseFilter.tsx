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
import { Controller, useForm } from 'react-hook-form';
import { BiDotsVerticalRounded } from 'react-icons/bi';
import { useInfiniteQuery } from 'react-query';
import ReactSelect from 'react-select';
import { useOnType } from 'use-ontype';
import ReactSelectOptions from '../../../components/common/ReactSelectOptions';
import { reactSelectStyles } from '../../../config/styles';
import urls from '../../../constants/urls';
import { CourseCategorySchema } from '../../../schemas';
import { CourseCategoriesResponse } from '../../../types/course';
import API from '../../../utils/api';
import { makeCategoriesHierarchy } from '../../../utils/categories';
import { deepClean, deepMerge } from '../../../utils/utils';

const pricingOptions = [
	{
		label: __('Free', 'masteriyo'),
		value: 'free',
	},
	{
		label: __('Paid', 'masteriyo'),
		value: 'paid',
	},
];

interface FilterParams {
	category?: string | number;
	search?: string;
	status?: string;
	priceType?: string;
}

interface Props {
	setFilterParams: any;
	filterParams: FilterParams;
	courseStatus: string;
}
interface SelectOption {
	value: number;
	label: string;
}

const CourseFilter: React.FC<Props> = (props) => {
	const [categoriesList, setCategoriesList] = useState<SelectOption[]>([]);
	const { setFilterParams, filterParams, courseStatus } = props;
	const categoryAPI = new API(urls.categories);
	const categoriesQuery = useInfiniteQuery<CourseCategoriesResponse>(
		'categoryLists',
		({ pageParam = 1 }) => categoryAPI.list({ per_page: 10, page: pageParam }),
		{
			retry: false,
			getNextPageParam: (lastResponse) =>
				lastResponse.meta.current_page >= lastResponse.meta.pages
					? undefined
					: lastResponse.meta.current_page + 1,
			onSuccess: (response) => {
				let categories: CourseCategorySchema[] = [];

				response?.pages?.forEach((page) => {
					page.data.forEach((category) => {
						categories.push(category);
					});
				});

				setCategoriesList(
					makeCategoriesHierarchy(categories).map((category) => ({
						value: category.id,
						label: '??? '.repeat(category.depth) + category.name,
					}))
				);
			},
		}
	);
	const { hasNextPage, fetchNextPage, isFetchingNextPage } = categoriesQuery;

	const { handleSubmit, control } = useForm();

	const [isMobile] = useMediaQuery('(min-width: 48em)');
	const onSearchInput = useOnType(
		{
			onTypeFinish: (val: string) => {
				setFilterParams({
					search: val,
					category: filterParams.category,
					status: courseStatus,
					price_type: filterParams.priceType,
				});
			},
		},
		800
	);
	const [isOpen, setIsOpen] = useState(isMobile);

	const onChange = (data: any) => {
		setFilterParams(
			deepClean(
				deepMerge(data, {
					search: filterParams.search,
					category: data.category?.value,
					status: courseStatus,
					price_type: data.price_type?.value,
				})
			)
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
						<Controller
							name="category"
							control={control}
							render={({ field: { onChange: onChangeValue, value } }) => (
								<ReactSelect
									placeholder={__('All Categories', 'masteriyo')}
									onChange={(...args: any[]) => {
										onChangeValue(...args);
										handleSubmit(onChange)();
									}}
									value={value}
									styles={reactSelectStyles}
									closeMenuOnSelect={true}
									isClearable={true}
									options={categoriesList}
									isFetchingNextPage={isFetchingNextPage}
									onMenuScrollToBottom={() => {
										if (hasNextPage) {
											fetchNextPage();
										}
									}}
									components={{ MenuList: ReactSelectOptions }}
									noOptionsMessage={({ inputValue }) => {
										if (inputValue.length > 0) {
											return __('No categories found.', 'masteriyo');
										}
										return __('No categories.', 'masteriyo');
									}}
								/>
							)}
						/>

						<Controller
							name="price_type"
							control={control}
							render={({ field: { onChange: onChangeValue, value } }) => (
								<ReactSelect
									onChange={(...args: any[]) => {
										onChangeValue(...args);
										handleSubmit(onChange)();
									}}
									value={value}
									styles={reactSelectStyles}
									options={pricingOptions}
									placeholder={__('Pricing', 'masteriyo')}
									isClearable
									isSearchable={false}
								/>
							)}
						/>
					</Stack>
				</form>
			</Collapse>
		</Box>
	);
};

export default CourseFilter;
