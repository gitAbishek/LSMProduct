import { Center, FormControl, FormLabel, Spinner } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import Select from 'Components/common/Select';
import React from 'react';
import { Controller, useFormContext } from 'react-hook-form';
import { useQuery } from 'react-query';

import urls from '../../../constants/urls';
import API from '../../../utils/api';

const Categories = () => {
	const categoryAPI = new API(urls.categories);
	const categoryQuery = useQuery('categoryLists', () => categoryAPI.list());
	const { control } = useFormContext();
	const categoriesOption = categoryQuery?.data?.map((category: any) => {
		return {
			value: category.id,
			label: category.name,
		};
	});

	return (
		<>
			{categoryQuery.isLoading ? (
				<Center h="12">
					<Spinner />
				</Center>
			) : (
				<FormControl>
					<FormLabel>{__('Categories', 'masteriyo')}</FormLabel>
					<Controller
						render={({ field }) => (
							<Select
								{...field}
								closeMenuOnSelect={false}
								isMulti
								options={categoriesOption}
							/>
						)}
						control={control}
						name="categories"
					/>
				</FormControl>
			)}
		</>
	);
};

export default Categories;
