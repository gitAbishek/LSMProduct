import {
	Button,
	ButtonGroup,
	FormControl,
	FormLabel,
	Icon,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useContext, useState } from 'react';
import { Controller, useFormContext } from 'react-hook-form';
import { BiPlus } from 'react-icons/bi';
import { useInfiniteQuery } from 'react-query';
import Select from 'react-select';
import ReactSelectOptions from '../../../components/common/ReactSelectOptions';
import { reactSelectStyles } from '../../../config/styles';
import urls from '../../../constants/urls';
import { CreateCatModal } from '../../../context/CreateCatProvider';
import { CourseCategorySchema } from '../../../schemas';
import { CourseCategoriesResponse } from '../../../types/course';
import API from '../../../utils/api';
import { makeCategoriesHierarchy } from '../../../utils/categories';

interface Props {
	defaultValue?: any;
}
interface SelectOption {
	value: number;
	label: string;
}

const Categories: React.FC<Props> = (props) => {
	const { defaultValue } = props;
	const { setIsCreateCatModalOpen } = useContext(CreateCatModal);
	const [categoriesList, setCategoriesList] = useState<SelectOption[]>([]);

	const categoryAPI = new API(urls.categories);

	const categoriesQuery = useInfiniteQuery<CourseCategoriesResponse>(
		'categoryLists',
		({ pageParam = 1 }) => categoryAPI.list({ per_page: 10, page: pageParam }),
		{
			retry: false,
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
						label: '— '.repeat(category.depth) + category.name,
					}))
				);
			},
			getNextPageParam: (lastResponse) =>
				lastResponse.meta.current_page >= lastResponse.meta.pages
					? undefined
					: lastResponse.meta.current_page + 1,
		}
	);
	const { hasNextPage, fetchNextPage, isFetchingNextPage } = categoriesQuery;

	const { control } = useFormContext();

	if (categoriesQuery.isSuccess) {
		return (
			<FormControl>
				<FormLabel>{__('Categories', 'masteriyo')}</FormLabel>
				<Controller
					name="categories"
					control={control}
					defaultValue={
						defaultValue &&
						defaultValue?.map((category: any) => {
							return {
								value: category.id,
								label: category.name,
							};
						})
					}
					render={({ field: { onChange, value } }) => (
						<Select
							onChange={onChange}
							value={value}
							styles={reactSelectStyles}
							closeMenuOnSelect={false}
							isMulti
							isFetchingNextPage={isFetchingNextPage}
							options={categoriesList}
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
				<ButtonGroup mt="4">
					<Button
						variant="link"
						leftIcon={<Icon fontSize="xl" as={BiPlus} />}
						onClick={() => setIsCreateCatModalOpen(true)}
						_hover={{ color: 'blue.500' }}>
						{__('Add New Category', 'masteriyo')}
					</Button>
				</ButtonGroup>
			</FormControl>
		);
	}

	return <></>;
};

export default Categories;
