import { FormControl, FormErrorMessage, FormLabel } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useState } from 'react';
import { Controller, useFormContext } from 'react-hook-form';
import { useQuery } from 'react-query';
import Select from 'react-select';
import { reactSelectStyles } from '../../../config/styles';
import urls from '../../../constants/urls';
import { CourseCategoriesResponse } from '../../../types/course';
import API from '../../../utils/api';
import { makeCategoriesHierarchy } from '../../../utils/categories';
import { isArray } from '../../../utils/utils';

interface CategoryOption {
	value: string | number;
	label: string;
}

interface Props {
	defaultValue?: string | number;
}

const ParentCategory: React.FC<Props> = (props) => {
	const { defaultValue } = props;
	const {
		formState: { errors },
		control,
	} = useFormContext();
	const categoryAPI = new API(urls.categories);
	const [categories, setCategories] = useState<CategoryOption[]>([]);

	const categoriesQuery = useQuery<CourseCategoriesResponse>(
		'categoryLists',
		() => categoryAPI.list({ per_page: 100 }),
		{
			onSuccess: (response) => {
				if (isArray(response?.data)) {
					setCategories(
						makeCategoriesHierarchy(response.data)?.map((cat) => ({
							value: cat?.id,
							label: '— '.repeat(cat?.depth) + cat?.name,
						}))
					);
				}
			},
		}
	);

	if (!categoriesQuery.isSuccess) {
		return null;
	}

	return (
		<FormControl isInvalid={!!errors?.parentId}>
			<FormLabel>{__('Parent Category', 'masteriyo')}</FormLabel>
			<Controller
				name="parentId"
				control={control}
				defaultValue={categories?.find((cat) => defaultValue === cat?.value)}
				render={({ field: { onChange, value } }) => (
					<Select
						onChange={onChange}
						value={value}
						styles={reactSelectStyles}
						options={categories}
					/>
				)}
			/>
			<FormErrorMessage>
				{errors?.parentId && errors?.parentId?.message}
			</FormErrorMessage>
		</FormControl>
	);
};

export default ParentCategory;
