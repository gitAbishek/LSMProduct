import { Center, FormControl, FormLabel, Spinner } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import Select from 'Components/common/Select';
import React, { useState } from 'react';
import { Controller, useFormContext } from 'react-hook-form';
import { useQuery } from 'react-query';

import urls from '../../../constants/urls';
import API from '../../../utils/api';

interface Props {
	defaultValue?: any;
}

const Categories: React.FC<Props> = (props) => {
	const { defaultValue } = props;
	const [categoriesList, setCategoriesList] = useState<any>(null);
	const categoryAPI = new API(urls.categories);
	const categoryQuery = useQuery('categoryLists', () => categoryAPI.list(), {
		retry: false,
		onSuccess: (data) => {
			setCategoriesList(
				data.map((category: any) => {
					return {
						value: category.id,
						label: category.name,
					};
				})
			);
		},
	});
	const { control } = useFormContext();

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
						defaultValue={
							defaultValue &&
							defaultValue?.map((category: any) => {
								return {
									value: category.id,
									label: category.name,
								};
							})
						}
						render={({ field }) => (
							<Select
								{...field}
								closeMenuOnSelect={false}
								isMulti
								options={categoriesList}
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
